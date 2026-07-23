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
 * Mint a fresh nonce.
 *
 * Page caches (LiteSpeed, WP Rocket…) serve HTML whose embedded nonce expires
 * after ~24h, which would make every chat fail. The widget calls this
 * uncached endpoint on load to always hold a valid nonce.
 */
function sdi_ai_ajax_nonce() {
	nocache_headers();
	wp_send_json_success( array( 'nonce' => wp_create_nonce( 'sdi_ai' ) ) );
}
add_action( 'wp_ajax_sdi_ai_nonce', 'sdi_ai_ajax_nonce' );
add_action( 'wp_ajax_nopriv_sdi_ai_nonce', 'sdi_ai_ajax_nonce' );

/**
 * Chat endpoint: returns the assistant's reply.
 */
function sdi_ai_ajax_chat() {
	// Soft nonce check: on failure return clean JSON so the widget can fetch a
	// fresh nonce and retry (instead of check_ajax_referer dying with -1, which
	// the front-end can only surface as a generic error).
	if ( ! check_ajax_referer( 'sdi_ai', 'nonce', false ) ) {
		wp_send_json_error( array( 'code' => 'bad_nonce', 'message' => 'Session expirée.' ), 403 );
	}

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
	if ( ! check_ajax_referer( 'sdi_ai', 'nonce', false ) ) {
		wp_send_json_error( array( 'code' => 'bad_nonce', 'message' => 'Session expirée.' ), 403 );
	}

	$history = sdi_ai_read_history();
	if ( empty( $history ) ) {
		wp_send_json_error( array( 'message' => 'Conversation vide.' ), 400 );
	}

	$visitor_email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$visitor_name  = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$page_url      = isset( $_POST['page'] ) ? esc_url_raw( wp_unslash( $_POST['page'] ) ) : '';

	// Raw transcript (kept, but shown discreetly at the bottom for reference).
	$lines = array();
	$user_text = '';
	foreach ( $history as $m ) {
		$who      = ( 'assistant' === $m['role'] ) ? 'Agent IA' : 'Visiteur';
		$lines[]  = $who . ' : ' . $m['content'];
		if ( 'user' === $m['role'] ) {
			$user_text .= $m['content'] . "\n";
		}
	}
	$transcript = implode( "\n\n", $lines );

	// Detect a phone number typed in the conversation.
	$visitor_phone = '';
	if ( preg_match( '/(?:(?:\+|00)\d{2}[\s.\-]?|0)[1-9](?:[\s.\-]?\d{2}){4}/', $user_text, $pm ) ) {
		$visitor_phone = trim( $pm[0] );
	}

	// Synthesized report (AI when a key is configured, heuristic otherwise).
	$summary = sdi_ai_summarize( $transcript );
	if ( '' === $summary ) {
		$asks    = array_filter( array_map( 'trim', explode( "\n", $user_text ) ) );
		$summary = $asks ? "Demande(s) du visiteur :\n- " . implode( "\n- ", $asks ) : 'Le visiteur a démarré une conversation.';
	}

	$to = sdi_ai_get( 'notify_email' );
	if ( ! is_email( $to ) ) {
		$to = get_option( 'admin_email' );
	}
	$subject = '[Demande] ' . ( $visitor_name ? $visitor_name : 'Nouveau contact via l\'assistant IA' );

	// HTML e-mail: synthesized report + contact details in bold.
	$contact_rows = '';
	$contact_rows .= '<tr><td style="padding:2px 0;color:#64748B;">Nom</td><td style="padding:2px 0 2px 12px;"><strong>' . esc_html( $visitor_name ? $visitor_name : 'Non communiqué' ) . '</strong></td></tr>';
	$contact_rows .= '<tr><td style="padding:2px 0;color:#64748B;">E-mail</td><td style="padding:2px 0 2px 12px;"><strong>' . ( $visitor_email ? '<a href="mailto:' . esc_attr( $visitor_email ) . '" style="color:#1D6EFF;">' . esc_html( $visitor_email ) . '</a>' : 'Non communiqué' ) . '</strong></td></tr>';
	$contact_rows .= '<tr><td style="padding:2px 0;color:#64748B;">Téléphone</td><td style="padding:2px 0 2px 12px;"><strong>' . esc_html( $visitor_phone ? $visitor_phone : 'Non communiqué' ) . '</strong></td></tr>';

	$body  = '<div style="font-family:Arial,Helvetica,sans-serif;font-size:15px;color:#1F2937;line-height:1.55;max-width:640px;">';
	$body .= '<h2 style="margin:0 0 4px;font-size:19px;color:#0F172A;">Nouvelle demande via l\'assistant IA</h2>';
	if ( $page_url ) {
		$body .= '<p style="margin:0 0 16px;font-size:13px;color:#64748B;">Depuis : <a href="' . esc_url( $page_url ) . '" style="color:#1D6EFF;">' . esc_html( $page_url ) . '</a></p>';
	}
	$body .= '<table style="border-collapse:collapse;margin:0 0 18px;font-size:15px;">' . $contact_rows . '</table>';
	$body .= '<div style="background:#F4F7FB;border:1px solid #E6ECF4;border-radius:10px;padding:14px 16px;margin:0 0 20px;">';
	$body .= '<div style="font-size:12px;text-transform:uppercase;letter-spacing:.08em;color:#64748B;font-weight:bold;margin-bottom:6px;">Compte-rendu de la demande</div>';
	$body .= '<div>' . nl2br( esc_html( $summary ) ) . '</div>';
	$body .= '</div>';
	$body .= '<details style="margin-top:8px;"><summary style="cursor:pointer;color:#94A3B8;font-size:13px;">Voir la conversation complète</summary>';
	$body .= '<div style="margin-top:10px;padding:12px 14px;background:#fff;border:1px solid #EEF2F7;border-radius:8px;font-size:13px;color:#475569;white-space:pre-wrap;">' . esc_html( $transcript ) . '</div>';
	$body .= '</details>';
	$body .= '</div>';

	$headers = array( 'Content-Type: text/html; charset=UTF-8' );
	if ( $visitor_email && is_email( $visitor_email ) ) {
		$headers[] = 'Reply-To: ' . ( $visitor_name ? $visitor_name . ' ' : '' ) . '<' . $visitor_email . '>';
	}

	$sent = wp_mail( $to, $subject, $body, $headers );

	if ( ! $sent ) {
		wp_send_json_error( array( 'message' => "L'envoi a échoué. Réessayez ou contactez-nous directement." ), 500 );
	}

	wp_send_json_success( array( 'message' => 'Merci, votre demande a bien été transmise. Nous vous recontactons vite !' ) );
}

/**
 * Produce a short, actionable summary (CR) of the conversation using the LLM.
 * Returns '' if no API key is configured or the call fails (caller falls back).
 *
 * @param string $transcript Full conversation text.
 * @return string
 */
function sdi_ai_summarize( $transcript ) {
	if ( ! sdi_ai_get( 'api_key' ) || '' === trim( $transcript ) ) {
		return '';
	}
	$messages = array(
		array(
			'role'    => 'system',
			'content' => "Tu rédiges des comptes-rendus commerciaux ultra-synthétiques en français à partir de conversations entre un visiteur et l'assistant d'un site. Sois factuel, bref et actionnable. N'invente rien.",
		),
		array(
			'role'    => 'user',
			'content' => "Voici la conversation :\n\n" . $transcript . "\n\nRédige un compte-rendu court pour l'équipe commerciale, en 3 sections :\n- Besoin : (1 à 2 phrases)\n- Points clés : (puces : type de projet, budget/délai/secteur si mentionnés)\n- Prochaine action : (1 phrase)\nPas de formule de politesse, pas d'introduction.",
		),
	);
	$res = sdi_ai_call( $messages );
	return ! empty( $res['ok'] ) ? trim( $res['reply'] ) : '';
}
add_action( 'wp_ajax_sdi_ai_transcript', 'sdi_ai_ajax_transcript' );
add_action( 'wp_ajax_nopriv_sdi_ai_transcript', 'sdi_ai_ajax_transcript' );
