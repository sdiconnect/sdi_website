<?php
/**
 * Contact form handling (admin-post) with nonce + honeypot, native wp_mail.
 *
 * @package SDi
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Where to send contact requests.
 *
 * @return string
 */
function sdi_contact_recipient() {
	/**
	 * Filter the contact form recipient. Defaults to SDi's public inbox;
	 * override with the filter or set the option 'sdi_contact_email'.
	 *
	 * @param string $email Recipient email.
	 */
	$default = get_option( 'sdi_contact_email' );
	if ( ! $default || ! is_email( $default ) ) {
		$default = 'contact@sdi-connect.com';
	}
	return apply_filters( 'sdi_contact_recipient', $default );
}

/**
 * Best-effort client IP (behind Cloudflare / reverse proxy) for rate limiting.
 *
 * @return string
 */
function sdi_client_ip() {
	foreach ( array( 'HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR' ) as $k ) {
		if ( ! empty( $_SERVER[ $k ] ) ) {
			$ip = trim( explode( ',', wp_unslash( $_SERVER[ $k ] ) )[0] );
			return filter_var( $ip, FILTER_VALIDATE_IP ) ? $ip : '';
		}
	}
	return '';
}

/**
 * Heuristic spam detection for the contact form. Returns a short reason code
 * when the submission looks like spam, or '' when it looks legitimate.
 *
 * @param string $name    Submitted name.
 * @param string $message Submitted message.
 * @return string
 */
function sdi_contact_is_spam( $name, $message ) {
	$haystack = $name . ' ' . $message;

	// Second honeypot (a tempting "url" field) — real users never fill it.
	if ( ! empty( $_POST['sdi_url'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		return 'hp2';
	}

	// Time trap: a human can't read + fill the form in under ~3s; bots do.
	$ts = isset( $_POST['sdi_ts'] ) ? (int) $_POST['sdi_ts'] : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing
	if ( $ts > 0 && ( time() - $ts ) < 3 ) {
		return 'too_fast';
	}

	// Non-Latin scripts (Cyrillic, CJK, Arabic, Hebrew, Thai, Hangul, Kana):
	// SDi's audience writes in French/Latin — this alone kills most bot spam.
	if ( preg_match( '/[\x{0400}-\x{052F}\x{0590}-\x{05FF}\x{0600}-\x{06FF}\x{0E00}-\x{0E7F}\x{3040}-\x{30FF}\x{4E00}-\x{9FFF}\x{AC00}-\x{D7AF}]/u', $haystack ) ) {
		return 'script';
	}

	// A name is never a URL; messages rarely carry links (spam usually does).
	if ( preg_match( '#https?://|www\.|\[url#i', $name ) ) {
		return 'name_url';
	}
	if ( preg_match_all( '#https?://|www\.#i', $message ) >= 2 ) {
		return 'links';
	}
	if ( preg_match( '#\[url|\[link|xn--|viagra|casino|crypto|bitcoin|порн|секс#iu', $message ) ) {
		return 'keyword';
	}

	return '';
}

/**
 * Process the contact form submission.
 */
function sdi_handle_contact() {
	$referer = wp_get_referer() ? wp_get_referer() : home_url( '/' );

	// Nonce.
	if ( ! isset( $_POST['sdi_contact_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sdi_contact_nonce'] ) ), 'sdi_contact' ) ) {
		wp_safe_redirect( add_query_arg( 'contact', 'error', $referer ) );
		exit;
	}

	// Honeypot: real users leave it empty.
	if ( ! empty( $_POST['sdi_website_hp'] ) ) {
		wp_safe_redirect( add_query_arg( 'contact', 'sent', $referer ) ); // silently accept, discard.
		exit;
	}

	// Per-IP rate limit: max 5 submissions / 15 min (stops bulk flooding).
	$ip = sdi_client_ip();
	if ( $ip ) {
		$rl_key = 'sdi_cf_' . md5( $ip );
		$count  = (int) get_transient( $rl_key );
		if ( $count >= 5 ) {
			wp_safe_redirect( add_query_arg( 'contact', 'sent', $referer ) . '#contact' ); // silently drop.
			exit;
		}
		set_transient( $rl_key, $count + 1, 15 * MINUTE_IN_SECONDS );
	}

	$name    = isset( $_POST['sdi_name'] ) ? sanitize_text_field( wp_unslash( $_POST['sdi_name'] ) ) : '';
	$email   = isset( $_POST['sdi_email'] ) ? sanitize_email( wp_unslash( $_POST['sdi_email'] ) ) : '';
	$phone   = isset( $_POST['sdi_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['sdi_phone'] ) ) : '';
	$message = isset( $_POST['sdi_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['sdi_message'] ) ) : '';
	$source  = isset( $_POST['sdi_source'] ) ? sanitize_text_field( wp_unslash( $_POST['sdi_source'] ) ) : '';

	// Spam heuristics: silently discard (don't tip off the bot) and pretend it worked.
	if ( '' !== sdi_contact_is_spam( $name, $message ) ) {
		wp_safe_redirect( add_query_arg( 'contact', 'sent', $referer ) . '#contact' );
		exit;
	}

	if ( '' === $name || ! is_email( $email ) || '' === $message ) {
		$back = add_query_arg( 'contact', 'error', $referer );
		wp_safe_redirect( remove_query_arg( 'contact', $back ) . ( strpos( $back, '?' ) !== false ? '&' : '?' ) . 'contact=error#contact' );
		exit;
	}

	$to      = sdi_contact_recipient();
	$subject = sprintf( '[SDi] Nouvelle demande de %s', $name );
	if ( $source ) {
		$subject .= ' — ' . $source;
	}
	$body    = "Nouvelle demande via le site sdi-connect.com\n\n"
		. "Nom & entreprise : {$name}\n"
		. "Email : {$email}\n"
		. "Téléphone : {$phone}\n"
		. ( $source ? "Source : {$source}\n" : '' )
		. "\nBesoin :\n{$message}\n";

	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		'Reply-To: ' . $name . ' <' . $email . '>',
	);

	wp_mail( $to, $subject, $body, $headers );

	wp_safe_redirect( add_query_arg( 'contact', 'sent', $referer ) . '#contact' );
	exit;
}
add_action( 'admin_post_nopriv_sdi_contact', 'sdi_handle_contact' );
add_action( 'admin_post_sdi_contact', 'sdi_handle_contact' );

/**
 * Whether the current view just had a successful submission.
 *
 * @return bool
 */
function sdi_contact_sent() {
	return isset( $_GET['contact'] ) && 'sent' === $_GET['contact']; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only display flag.
}

/**
 * Whether the current view had a submission error.
 *
 * @return bool
 */
function sdi_contact_error() {
	return isset( $_GET['contact'] ) && 'error' === $_GET['contact']; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only display flag.
}

/**
 * Render the contact form markup (shared by front-page and contact page).
 *
 * @param string $heading Card heading text.
 */
function sdi_contact_form( $heading = 'Nous contacter', $source = '' ) {
	if ( sdi_contact_sent() ) {
		?>
		<div style="text-align:center;padding:44px 12px;">
			<div style="width:64px;height:64px;border-radius:50%;background:var(--blue-50);color:var(--brand-primary);display:flex;align-items:center;justify-content:center;margin:0 auto;"><?php sdi_the_icon( 'check', 30 ); ?></div>
			<h3 style="margin-top:20px;font-family:var(--font-display);font-weight:700;font-size:22px;color:var(--text-strong);">Merci, c'est noté&nbsp;!</h3>
			<p style="margin-top:10px;font-size:15px;color:var(--text-muted);">Notre équipe vous recontacte sous 24h ouvrées.</p>
		</div>
		<?php
		return;
	}
	?>
	<h3 style="font-family:var(--font-display);font-weight:700;font-size:22px;color:var(--text-strong);letter-spacing:-0.01em;"><?php echo esc_html( $heading ); ?></h3>
	<?php if ( sdi_contact_error() ) : ?>
		<p role="alert" style="margin-top:12px;font-size:14px;color:#B4231F;background:#FDECEA;border:1px solid #F5C6C2;padding:10px 12px;border-radius:10px;">Merci de vérifier votre nom, un email valide et votre message.</p>
	<?php endif; ?>
	<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" style="margin-top:20px;display:flex;flex-direction:column;gap:16px;">
		<input type="hidden" name="action" value="sdi_contact">
		<?php if ( $source ) : ?><input type="hidden" name="sdi_source" value="<?php echo esc_attr( $source ); ?>"><?php endif; ?>
		<?php wp_nonce_field( 'sdi_contact', 'sdi_contact_nonce' ); ?>
		<input type="hidden" name="sdi_ts" value="<?php echo esc_attr( time() ); ?>">
		<div style="position:absolute;left:-9999px;" aria-hidden="true">
			<label>Ne pas remplir<input type="text" name="sdi_website_hp" tabindex="-1" autocomplete="off"></label>
			<label>Site web<input type="text" name="sdi_url" tabindex="-1" autocomplete="off"></label>
		</div>
		<?php
		echo sdi_input( array( 'label' => 'Nom & entreprise', 'name' => 'sdi_name', 'placeholder' => 'Marie Durand · Domaine Durand', 'required' => true ) ); // phpcs:ignore WordPress.Security.EscapeOutput
		echo sdi_input( array( 'label' => 'Email professionnel', 'name' => 'sdi_email', 'type' => 'email', 'icon' => 'mail', 'placeholder' => 'vous@entreprise.fr', 'required' => true ) ); // phpcs:ignore WordPress.Security.EscapeOutput
		echo sdi_input( array( 'label' => 'Téléphone', 'name' => 'sdi_phone', 'type' => 'tel', 'icon' => 'phone', 'placeholder' => '06 12 34 56 78' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
		?>
		<div>
			<label class="sdi-field__label" for="sdi-message">Votre besoin</label>
			<textarea class="sdi-textarea" id="sdi-message" name="sdi_message" rows="4" placeholder="Décrivez votre projet en quelques mots…" required></textarea>
		</div>
		<?php echo sdi_button( array( 'label' => 'Envoyer ma demande', 'variant' => 'primary', 'size' => 'lg', 'full' => true, 'type' => 'submit' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<p style="font-size:12px;color:var(--text-subtle);line-height:1.45;text-align:center;">En envoyant ce formulaire, vous acceptez d'être recontacté par SDi. Vos données ne sont jamais revendues.</p>
	</form>
	<?php
}
