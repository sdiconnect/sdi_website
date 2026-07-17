<?php
/**
 * AJAX handlers: chat completion (Mistral/OpenAI) and transcript e-mail.
 *
 * @package SDi_Agent_IA
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Read + sanitize the posted conversation history.
 *
 * @return array List of ['role'=>'user'|'assistant', 'content'=>string].
 */
function sdi_ai_read_history() {
	$raw = isset( $_POST['history'] ) ? wp_unslash( $_POST['history'] ) : '[]'; // phpcs:ignore WordPress.Security.NonceVerification, WordPress.Security.ValidatedSanitizedInput
	$arr = json_decode( (string) $raw, true );
	if ( ! is_array( $arr ) ) {
		return array();
	}
	$out = array();
	foreach ( array_slice( $arr, -16 ) as $m ) {
		if ( ! isset( $m['role'], $m['content'] ) ) {
			continue;
		}
		$role = ( 'assistant' === $m['role'] ) ? 'assistant' : 'user';
		$out[] = array(
			'role'    => $role,
			'content' => mb_substr( sanitize_textarea_field( $m['content'] ), 0, 2000 ),
		);
	}
	return $out;
}

/**
 * Chat endpoint: returns the assistant's reply.
 */
function sdi_ai_ajax_chat() {
	check_ajax_referer( 'sdi_ai', 'nonce' );

	$message = isset( $_POST['message'] ) ? mb_substr( sanitize_textarea_field( wp_unslash( $_POST['message'] ) ), 0, 2000 ) : '';
	if ( '' === trim( $message ) ) {
		wp_send_json_error( array( 'message' => 'Message vide.' ), 400 );
	}

	$history = sdi_ai_read_history();
	$api_key = sdi_ai_get( 'api_key' );

	// No key configured → graceful lead-capture fallback (no error).
	if ( ! $api_key ) {
		wp_send_json_success(
			array(
				'reply'    => "Merci pour votre message ! Notre conseiller IA sera bientôt disponible. En attendant, laissez-nous votre e-mail via le bouton « Envoyer par e-mail » ci-dessous, ou appelez-nous au 09 80 80 62 96 — on vous répond sous 24h.",
				'fallback' => true,
			)
		);
	}

	$messages = array(
		array( 'role' => 'system', 'content' => sdi_ai_get( 'system_prompt' ) ),
	);
	foreach ( $history as $m ) {
		$messages[] = $m;
	}
	$messages[] = array( 'role' => 'user', 'content' => $message );

	$result = sdi_ai_call( $messages );

	if ( empty( $result['ok'] ) ) {
		wp_send_json_success(
			array(
				'reply'    => "Désolé, je rencontre un souci technique momentané. Réessayez dans un instant, ou laissez-nous vos coordonnées et l'équipe SDi vous répond sous 24h.",
				'fallback' => true,
				'debug'    => current_user_can( 'manage_options' ) ? $result['error'] : null,
			)
		);
	}

	wp_send_json_success( array( 'reply' => $result['reply'] ) );
}
add_action( 'wp_ajax_sdi_ai_chat', 'sdi_ai_ajax_chat' );
add_action( 'wp_ajax_nopriv_sdi_ai_chat', 'sdi_ai_ajax_chat' );

/**
 * Call the configured LLM provider (OpenAI-compatible chat completions).
 *
 * @param array $messages Chat messages.
 * @return array ['ok'=>bool, 'reply'=>string, 'error'=>string]
 */
function sdi_ai_call( $messages ) {
	$provider = sdi_ai_get( 'provider' );
	$key      = sdi_ai_get( 'api_key' );
	$model    = sdi_ai_get( 'model' );
	$temp     = (float) sdi_ai_get( 'temperature' );

	$endpoints = array(
		'mistral' => 'https://api.mistral.ai/v1/chat/completions',
		'openai'  => 'https://api.openai.com/v1/chat/completions',
	);
	$endpoint = isset( $endpoints[ $provider ] ) ? $endpoints[ $provider ] : $endpoints['mistral'];

	$body = array(
		'model'       => $model ? $model : 'mistral-small-latest',
		'messages'    => $messages,
		'temperature' => $temp,
		'max_tokens'  => 600,
	);

	$response = wp_remote_post(
		$endpoint,
		array(
			'timeout' => 45,
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bearer ' . $key,
			),
			'body'    => wp_json_encode( $body ),
		)
	);

	if ( is_wp_error( $response ) ) {
		return array( 'ok' => false, 'error' => $response->get_error_message() );
	}

	$code = wp_remote_retrieve_response_code( $response );
	$data = json_decode( wp_remote_retrieve_body( $response ), true );

	if ( 200 !== (int) $code ) {
		$err = isset( $data['message'] ) ? $data['message'] : ( isset( $data['error']['message'] ) ? $data['error']['message'] : 'HTTP ' . $code );
		return array( 'ok' => false, 'error' => $err );
	}

	$reply = isset( $data['choices'][0]['message']['content'] ) ? trim( $data['choices'][0]['message']['content'] ) : '';
	if ( '' === $reply ) {
		return array( 'ok' => false, 'error' => 'Réponse vide du fournisseur.' );
	}

	return array( 'ok' => true, 'reply' => $reply );
}

/**
 * Transcript endpoint: e-mail the conversation to the configured address.
 */
function sdi_ai_ajax_transcript() {
	check_ajax_referer( 'sdi_ai', 'nonce' );

	$history = sdi_ai_read_history();
	if ( empty( $history ) ) {
		wp_send_json_error( array( 'message' => 'Conversation vide.' ), 400 );
	}

	$visitor_email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$visitor_name  = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$page_url      = isset( $_POST['page'] ) ? esc_url_raw( wp_unslash( $_POST['page'] ) ) : '';

	$lines = array();
	foreach ( $history as $m ) {
		$who     = ( 'assistant' === $m['role'] ) ? 'Agent IA' : 'Visiteur';
		$lines[] = $who . ' : ' . $m['content'];
	}
	$transcript = implode( "\n\n", $lines );

	$to      = sdi_ai_get( 'notify_email' );
	if ( ! is_email( $to ) ) {
		$to = get_option( 'admin_email' );
	}
	$subject = '[SDi Agent IA] Conversation' . ( $visitor_name ? ' — ' . $visitor_name : '' );
	$body    = "Nouvelle conversation depuis l'agent IA du site\n\n"
		. ( $visitor_name ? "Nom : {$visitor_name}\n" : '' )
		. ( $visitor_email ? "E-mail : {$visitor_email}\n" : '' )
		. ( $page_url ? "Page : {$page_url}\n" : '' )
		. "\n----- Conversation -----\n\n"
		. $transcript . "\n";

	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	if ( $visitor_email && is_email( $visitor_email ) ) {
		$headers[] = 'Reply-To: ' . ( $visitor_name ? $visitor_name . ' ' : '' ) . '<' . $visitor_email . '>';
	}

	$sent = wp_mail( $to, $subject, $body, $headers );

	if ( ! $sent ) {
		wp_send_json_error( array( 'message' => "L'envoi a échoué. Réessayez ou contactez-nous directement." ), 500 );
	}

	wp_send_json_success( array( 'message' => 'Conversation envoyée. Merci, nous vous recontactons vite !' ) );
}
add_action( 'wp_ajax_sdi_ai_transcript', 'sdi_ai_ajax_transcript' );
add_action( 'wp_ajax_nopriv_sdi_ai_transcript', 'sdi_ai_ajax_transcript' );
