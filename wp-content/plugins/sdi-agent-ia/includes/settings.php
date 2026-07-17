<?php
/**
 * Admin settings page for SDi Agent IA.
 *
 * @package SDi_Agent_IA
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Register the settings menu.
 */
function sdi_ai_admin_menu() {
	add_options_page(
		__( 'SDi Agent IA', 'sdi-agent-ia' ),
		__( 'SDi Agent IA', 'sdi-agent-ia' ),
		'manage_options',
		'sdi-agent-ia',
		'sdi_ai_settings_page'
	);
}
add_action( 'admin_menu', 'sdi_ai_admin_menu' );

/**
 * Register the setting + sanitizer.
 */
function sdi_ai_register_settings() {
	register_setting( 'sdi_ai_group', SDI_AI_OPTION, 'sdi_ai_sanitize' );
}
add_action( 'admin_init', 'sdi_ai_register_settings' );

/**
 * Sanitize settings on save.
 *
 * @param array $input Raw input.
 * @return array
 */
function sdi_ai_sanitize( $input ) {
	$out = sdi_ai_defaults();
	$in  = (array) $input;

	$out['enabled']       = empty( $in['enabled'] ) ? 0 : 1;
	$out['collect_email'] = empty( $in['collect_email'] ) ? 0 : 1;
	$out['agent_name']    = isset( $in['agent_name'] ) ? sanitize_text_field( $in['agent_name'] ) : $out['agent_name'];
	$out['button_label']  = isset( $in['button_label'] ) ? sanitize_text_field( $in['button_label'] ) : $out['button_label'];
	$out['welcome']       = isset( $in['welcome'] ) ? sanitize_textarea_field( $in['welcome'] ) : $out['welcome'];
	$out['provider']      = ( isset( $in['provider'] ) && in_array( $in['provider'], array( 'mistral', 'openai' ), true ) ) ? $in['provider'] : 'mistral';
	$out['model']         = isset( $in['model'] ) ? sanitize_text_field( $in['model'] ) : $out['model'];
	$out['temperature']   = isset( $in['temperature'] ) ? (string) floatval( $in['temperature'] ) : $out['temperature'];
	$out['system_prompt'] = isset( $in['system_prompt'] ) ? sanitize_textarea_field( $in['system_prompt'] ) : $out['system_prompt'];
	$out['notify_email']  = ( isset( $in['notify_email'] ) && is_email( $in['notify_email'] ) ) ? sanitize_email( $in['notify_email'] ) : $out['notify_email'];
	$out['accent']        = isset( $in['accent'] ) ? sanitize_hex_color( $in['accent'] ) : $out['accent'];
	if ( ! $out['accent'] ) {
		$out['accent'] = '#1D6EFF';
	}

	// API key: keep existing if the field is left blank (so it isn't wiped).
	$existing = (array) get_option( SDI_AI_OPTION, array() );
	if ( isset( $in['api_key'] ) && '' !== trim( $in['api_key'] ) ) {
		$out['api_key'] = sanitize_text_field( trim( $in['api_key'] ) );
	} else {
		$out['api_key'] = isset( $existing['api_key'] ) ? $existing['api_key'] : '';
	}

	return $out;
}

/**
 * Render the settings page.
 */
function sdi_ai_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$o        = wp_parse_args( (array) get_option( SDI_AI_OPTION, array() ), sdi_ai_defaults() );
	$has_key  = ! empty( $o['api_key'] );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'SDi Agent IA — chatbot', 'sdi-agent-ia' ); ?></h1>
		<p style="max-width:760px;">Configurez votre agent conversationnel IA. Il s'affiche en bas de votre site et discute avec vos visiteurs. À la fin de l'échange, la conversation peut être envoyée par e-mail à votre adresse.</p>

		<?php if ( ! $has_key ) : ?>
			<div class="notice notice-warning"><p><strong>Clé API manquante.</strong> L'agent affichera un message d'attente tant qu'aucune clé n'est renseignée. Créez une clé sur <a href="https://console.mistral.ai/" target="_blank" rel="noopener">console.mistral.ai</a>.</p></div>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'sdi_ai_group' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row">Activer l'agent</th>
					<td><label><input type="checkbox" name="<?php echo esc_attr( SDI_AI_OPTION ); ?>[enabled]" value="1" <?php checked( $o['enabled'], 1 ); ?>> Afficher le widget sur le site</label></td>
				</tr>
				<tr>
					<th scope="row"><label for="sdi_ai_button_label">Texte du bouton</label></th>
					<td>
						<input type="text" id="sdi_ai_button_label" class="regular-text" name="<?php echo esc_attr( SDI_AI_OPTION ); ?>[button_label]" value="<?php echo esc_attr( $o['button_label'] ); ?>">
						<p class="description">Ex. « Parler à un agent commercial IA », « Discuter avec un conseiller », « Poser une question »…</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sdi_ai_agent_name">Nom de l'agent</label></th>
					<td><input type="text" id="sdi_ai_agent_name" class="regular-text" name="<?php echo esc_attr( SDI_AI_OPTION ); ?>[agent_name]" value="<?php echo esc_attr( $o['agent_name'] ); ?>"></td>
				</tr>
				<tr>
					<th scope="row"><label for="sdi_ai_welcome">Message d'accueil</label></th>
					<td><textarea id="sdi_ai_welcome" class="large-text" rows="3" name="<?php echo esc_attr( SDI_AI_OPTION ); ?>[welcome]"><?php echo esc_textarea( $o['welcome'] ); ?></textarea></td>
				</tr>

				<tr><th colspan="2"><hr><h2 style="margin:.4em 0;">Intelligence artificielle</h2></th></tr>
				<tr>
					<th scope="row"><label for="sdi_ai_provider">Fournisseur IA</label></th>
					<td>
						<select id="sdi_ai_provider" name="<?php echo esc_attr( SDI_AI_OPTION ); ?>[provider]">
							<option value="mistral" <?php selected( $o['provider'], 'mistral' ); ?>>Mistral AI</option>
							<option value="openai" <?php selected( $o['provider'], 'openai' ); ?>>OpenAI (compatible)</option>
						</select>
						<p class="description">Par défaut : Mistral AI (recommandé).</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sdi_ai_model">Modèle</label></th>
					<td>
						<input type="text" id="sdi_ai_model" class="regular-text" name="<?php echo esc_attr( SDI_AI_OPTION ); ?>[model]" value="<?php echo esc_attr( $o['model'] ); ?>">
						<p class="description">Mistral : <code>mistral-small-latest</code>, <code>mistral-large-latest</code>. OpenAI : <code>gpt-4o-mini</code>…</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sdi_ai_api_key">Clé API</label></th>
					<td>
						<input type="password" id="sdi_ai_api_key" class="regular-text" name="<?php echo esc_attr( SDI_AI_OPTION ); ?>[api_key]" value="" autocomplete="off" placeholder="<?php echo $has_key ? '•••••••••• (déjà enregistrée — laisser vide pour conserver)' : 'Collez votre clé API'; ?>">
						<p class="description">La clé reste côté serveur, elle n'est jamais exposée aux visiteurs. Laissez vide pour conserver la clé actuelle.</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sdi_ai_temperature">Créativité (température)</label></th>
					<td><input type="number" step="0.1" min="0" max="1" id="sdi_ai_temperature" name="<?php echo esc_attr( SDI_AI_OPTION ); ?>[temperature]" value="<?php echo esc_attr( $o['temperature'] ); ?>" class="small-text"> <span class="description">0 = factuel, 1 = créatif. Recommandé : 0,4.</span></td>
				</tr>
				<tr>
					<th scope="row"><label for="sdi_ai_system_prompt">Éducation de l'agent (prompt système)</label></th>
					<td>
						<textarea id="sdi_ai_system_prompt" class="large-text code" rows="12" name="<?php echo esc_attr( SDI_AI_OPTION ); ?>[system_prompt]"><?php echo esc_textarea( $o['system_prompt'] ); ?></textarea>
						<p class="description">Décrivez à l'agent votre entreprise, vos services, votre ton et vos règles, pour qu'il réponde au mieux à vos visiteurs.</p>
					</td>
				</tr>

				<tr><th colspan="2"><hr><h2 style="margin:.4em 0;">Fin de conversation</h2></th></tr>
				<tr>
					<th scope="row">Coordonnées visiteur</th>
					<td><label><input type="checkbox" name="<?php echo esc_attr( SDI_AI_OPTION ); ?>[collect_email]" value="1" <?php checked( $o['collect_email'], 1 ); ?>> Proposer au visiteur de laisser son e-mail dans le chat</label></td>
				</tr>
				<tr>
					<th scope="row"><label for="sdi_ai_notify_email">E-mail de réception</label></th>
					<td>
						<input type="email" id="sdi_ai_notify_email" class="regular-text" name="<?php echo esc_attr( SDI_AI_OPTION ); ?>[notify_email]" value="<?php echo esc_attr( $o['notify_email'] ); ?>">
						<p class="description">La conversation vous est envoyée à cette adresse quand le visiteur clique sur « Envoyer par e-mail ».</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="sdi_ai_accent">Couleur d'accent</label></th>
					<td><input type="text" id="sdi_ai_accent" name="<?php echo esc_attr( SDI_AI_OPTION ); ?>[accent]" value="<?php echo esc_attr( $o['accent'] ); ?>" class="regular-text" placeholder="#1D6EFF"> <span class="description">Format hexadécimal, ex. #1D6EFF.</span></td>
				</tr>
			</table>
			<?php submit_button( 'Enregistrer les réglages' ); ?>
		</form>
	</div>
	<?php
}
