<?php
/**
 * Plugin Name:       SDi Agent IA
 * Plugin URI:        https://sdi-connect.com
 * Description:        Agent conversationnel IA (chatbot) pour le site SDi : widget flottant « Parler à un agent commercial IA », propulsé par Mistral, avec export de la discussion par e-mail. Paramétrable depuis Réglages → SDi Agent IA.
 * Version:           1.0.4
 * Author:            SDi — Solutions Digitales Intégrées
 * Author URI:        https://sdi-connect.com
 * License:           GPL-2.0-or-later
 * Text Domain:       sdi-agent-ia
 *
 * @package SDi_Agent_IA
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'SDI_AI_VERSION', '1.0.4' );
define( 'SDI_AI_FILE', __FILE__ );
define( 'SDI_AI_URL', plugin_dir_url( __FILE__ ) );
define( 'SDI_AI_DIR', plugin_dir_path( __FILE__ ) );
define( 'SDI_AI_OPTION', 'sdi_ai_settings' );

/**
 * Default settings.
 *
 * @return array
 */
function sdi_ai_defaults() {
	return array(
		'enabled'       => 1,
		'agent_name'    => 'Conseiller IA SDi',
		'avatar'        => '',
		'button_label'  => 'Parler à un agent commercial IA',
		'welcome'       => "Bonjour 👋 Je suis le conseiller IA de SDi. Posez-moi vos questions sur nos services (sites web, e-commerce, SEO, agents IA…) ou décrivez votre projet — je vous oriente tout de suite.",
		'provider'      => 'mistral',
		'model'         => 'mistral-small-latest',
		'api_key'       => '',
		'temperature'   => '0.4',
		'system_prompt' => sdi_ai_default_prompt(),
		'notify_email'  => 'contact@sdi-connect.com',
		'accent'        => '#1D6EFF',
		'collect_email' => 1,
	);
}

/**
 * Default « education » prompt for the agent.
 *
 * @return string
 */
function sdi_ai_default_prompt() {
	return "Tu es le conseiller commercial IA de SDi (Solutions Digitales Intégrées), une agence web & IA basée à Dijon avec un siège à Paris, qui intervient partout en France.\n\n"
		. "SERVICES DE SDi : création de site internet, site e-commerce, référencement SEO (local Dijon/Côte-d'Or et national), Google Ads, développement de SaaS et d'applications sur-mesure, agents IA sur-mesure et automatisation IA, hébergement, branding.\n"
		. "PREUVES : plus de 100 projets livrés depuis 2017, note 4,9/5 sur 109 avis Google.\n"
		. "COORDONNÉES : téléphone 09 80 80 62 96, e-mail contact@sdi-connect.com, siège 60 rue François 1er, 75008 Paris.\n\n"
		. "TON : professionnel, chaleureux, clair et concis. Tutoie jamais le visiteur, vouvoie-le. Réponds en français.\n\n"
		. "OBJECTIF : aider le visiteur, comprendre son besoin et l'orienter vers une prise de contact ou un devis gratuit. Propose naturellement de laisser ses coordonnées pour être recontacté sous 24h.\n\n"
		. "RÈGLES : ne donne jamais de prix précis (chaque projet est sur-mesure : propose un devis gratuit). N'invente pas d'informations sur SDi. Si tu ne sais pas, propose de mettre le visiteur en relation avec l'équipe. Réponses courtes (2 à 5 phrases).";
}

/**
 * Read a setting.
 *
 * @param string $key Key.
 * @return mixed
 */
function sdi_ai_get( $key ) {
	$opts = wp_parse_args( (array) get_option( SDI_AI_OPTION, array() ), sdi_ai_defaults() );
	return isset( $opts[ $key ] ) ? $opts[ $key ] : '';
}

require_once SDI_AI_DIR . 'includes/settings.php';
require_once SDI_AI_DIR . 'includes/ajax.php';

/**
 * Enqueue front-end assets and print the widget (only when enabled & configured to show).
 */
function sdi_ai_enqueue() {
	if ( is_admin() || ! sdi_ai_get( 'enabled' ) ) {
		return;
	}

	wp_enqueue_style( 'sdi-ai', SDI_AI_URL . 'assets/css/agent.css', array(), SDI_AI_VERSION );
	wp_enqueue_script( 'sdi-ai', SDI_AI_URL . 'assets/js/agent.js', array(), SDI_AI_VERSION, true );

	wp_localize_script(
		'sdi-ai',
		'SDI_AI',
		array(
			'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
			'nonce'        => wp_create_nonce( 'sdi_ai' ),
			'agentName'    => sdi_ai_get( 'agent_name' ),
			'avatar'       => sdi_ai_get( 'avatar' ),
			'buttonLabel'  => sdi_ai_get( 'button_label' ),
			'welcome'      => sdi_ai_get( 'welcome' ),
			'accent'       => sdi_ai_get( 'accent' ),
			'collectEmail' => (int) sdi_ai_get( 'collect_email' ),
			'hasKey'       => sdi_ai_get( 'api_key' ) ? 1 : 0,
		)
	);
}
add_action( 'wp_enqueue_scripts', 'sdi_ai_enqueue' );

/**
 * Activation: seed default options.
 */
function sdi_ai_activate() {
	if ( false === get_option( SDI_AI_OPTION ) ) {
		add_option( SDI_AI_OPTION, sdi_ai_defaults() );
	}
}
register_activation_hook( __FILE__, 'sdi_ai_activate' );
