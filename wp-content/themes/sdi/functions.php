<?php
/**
 * SDi theme bootstrap.
 *
 * @package SDi
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'SDI_VERSION', '1.0.2' );
define( 'SDI_DIR', get_template_directory() );
define( 'SDI_URI', get_template_directory_uri() );

require_once SDI_DIR . '/inc/icons.php';
require_once SDI_DIR . '/inc/components.php';
require_once SDI_DIR . '/inc/cpt.php';
require_once SDI_DIR . '/inc/seo.php';
require_once SDI_DIR . '/inc/contact.php';
require_once SDI_DIR . '/inc/landing.php';
require_once SDI_DIR . '/inc/landing-data.php';

/**
 * Theme setup.
 */
function sdi_setup() {
	load_theme_textdomain( 'sdi', SDI_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'custom-logo', array( 'height' => 52, 'flex-width' => true ) );

	// Let pages carry a hand-written meta description via the excerpt box.
	add_post_type_support( 'page', 'excerpt' );

	add_image_size( 'sdi-cover', 720, 480, true );

	register_nav_menus(
		array(
			'primary' => __( 'Navigation principale', 'sdi' ),
		)
	);
}
add_action( 'after_setup_theme', 'sdi_setup' );

/**
 * Enqueue styles and scripts.
 */
function sdi_assets() {
	// Google Fonts: Roboto + Roboto Mono.
	wp_enqueue_style(
		'sdi-fonts',
		'https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700&family=Roboto+Mono:wght@400;500;600&display=swap',
		array(),
		null
	);

	wp_enqueue_style( 'sdi-tokens', SDI_URI . '/assets/css/tokens.css', array(), filemtime( SDI_DIR . '/assets/css/tokens.css' ) );
	wp_enqueue_style( 'sdi-main', SDI_URI . '/assets/css/main.css', array( 'sdi-tokens' ), filemtime( SDI_DIR . '/assets/css/main.css' ) );

	wp_enqueue_script( 'sdi-main', SDI_URI . '/assets/js/main.js', array(), filemtime( SDI_DIR . '/assets/js/main.js' ), true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'sdi_assets' );

/**
 * Preconnect to Google Fonts for faster loads.
 *
 * @param array  $urls          URLs to print.
 * @param string $relation_type Relation.
 * @return array
 */
function sdi_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array( 'href' => 'https://fonts.googleapis.com' );
		$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' => '' );
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'sdi_resource_hints', 10, 2 );

/**
 * Add defer to the main script.
 *
 * @param string $tag    Script tag.
 * @param string $handle Handle.
 * @return string
 */
function sdi_defer_main( $tag, $handle ) {
	if ( 'sdi-main' === $handle ) {
		return str_replace( ' src', ' defer src', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'sdi_defer_main', 10, 2 );

/* ============================================================
   Navigation model (mirrors the design's fixed nav)
   ============================================================ */

/**
 * Return a page permalink by slug/path, falling back to home.
 *
 * @param string $path Page path/slug.
 * @return string
 */
function sdi_page_url( $path ) {
	$page = get_page_by_path( $path );
	return $page ? get_permalink( $page ) : home_url( '/' . ltrim( $path, '/' ) );
}

/**
 * Primary nav items (fixed, per the SDi design).
 *
 * @return array
 */
function sdi_nav_items() {
	return array(
		array( 'key' => 'services',     'label' => 'Services',     'url' => sdi_page_url( 'services' ) ),
		array( 'key' => 'ia',           'label' => 'Agents IA',    'url' => sdi_page_url( 'agents-ia' ), 'dot' => true ),
		array( 'key' => 'realisations', 'label' => 'Réalisations', 'url' => sdi_page_url( 'realisations' ) ),
		array( 'key' => 'agence',       'label' => 'Agence',       'url' => sdi_page_url( 'agence' ) ),
	);
}

/**
 * Active nav key for the current request (templates can also force it via $GLOBALS['sdi_active']).
 *
 * @return string
 */
function sdi_active_nav() {
	if ( ! empty( $GLOBALS['sdi_active'] ) ) {
		return $GLOBALS['sdi_active'];
	}
	if ( is_singular( 'realisation' ) ) {
		return 'realisations';
	}
	$obj = get_queried_object();
	if ( $obj instanceof WP_Post ) {
		$map = array(
			'services'     => 'services',
			'agents-ia'    => 'ia',
			'realisations' => 'realisations',
			'agence'       => 'agence',
		);
		if ( isset( $map[ $obj->post_name ] ) ) {
			return $map[ $obj->post_name ];
		}
	}
	return '';
}

/* ============================================================
   Customizer: social links
   ============================================================ */

/**
 * Register social link settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer.
 */
function sdi_customize( $wp_customize ) {
	$wp_customize->add_section( 'sdi_social', array( 'title' => __( 'SDi — Réseaux sociaux', 'sdi' ), 'priority' => 40 ) );
	foreach ( array( 'linkedin' => 'LinkedIn', 'instagram' => 'Instagram', 'facebook' => 'Facebook' ) as $k => $label ) {
		$wp_customize->add_setting( 'sdi_' . $k, array( 'default' => '#', 'sanitize_callback' => 'esc_url_raw' ) );
		$wp_customize->add_control( 'sdi_' . $k, array( 'label' => $label, 'section' => 'sdi_social', 'type' => 'url' ) );
	}

	/* ---- Avis clients ---- */
	$wp_customize->add_section( 'sdi_reviews', array( 'title' => __( 'SDi — Avis clients', 'sdi' ), 'priority' => 41 ) );

	$wp_customize->add_setting( 'sdi_rating_value', array( 'default' => '4,9', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'sdi_rating_value', array( 'label' => __( 'Note moyenne (ex. 4,9)', 'sdi' ), 'section' => 'sdi_reviews', 'type' => 'text' ) );

	$wp_customize->add_setting( 'sdi_rating_count', array( 'default' => '109', 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'sdi_rating_count', array( 'label' => __( "Nombre d'avis Google", 'sdi' ), 'section' => 'sdi_reviews', 'type' => 'number' ) );

	$defaults = sdi_default_reviews();
	for ( $i = 1; $i <= 3; $i++ ) {
		$d = $defaults[ $i - 1 ];
		$wp_customize->add_setting( "sdi_review{$i}_name", array( 'default' => $d['name'], 'sanitize_callback' => 'sanitize_text_field' ) );
		$wp_customize->add_control( "sdi_review{$i}_name", array( 'label' => sprintf( __( 'Avis %d — Nom', 'sdi' ), $i ), 'section' => 'sdi_reviews', 'type' => 'text' ) );

		$wp_customize->add_setting( "sdi_review{$i}_meta", array( 'default' => $d['meta'], 'sanitize_callback' => 'sanitize_text_field' ) );
		$wp_customize->add_control( "sdi_review{$i}_meta", array( 'label' => sprintf( __( 'Avis %d — Fonction / ville', 'sdi' ), $i ), 'section' => 'sdi_reviews', 'type' => 'text' ) );

		$wp_customize->add_setting( "sdi_review{$i}_quote", array( 'default' => $d['quote'], 'sanitize_callback' => 'sanitize_textarea_field' ) );
		$wp_customize->add_control( "sdi_review{$i}_quote", array( 'label' => sprintf( __( 'Avis %d — Texte', 'sdi' ), $i ), 'section' => 'sdi_reviews', 'type' => 'textarea' ) );

		$wp_customize->add_setting( "sdi_review{$i}_rating", array( 'default' => 5, 'sanitize_callback' => 'absint' ) );
		$wp_customize->add_control( "sdi_review{$i}_rating", array( 'label' => sprintf( __( 'Avis %d — Étoiles (1-5)', 'sdi' ), $i ), 'section' => 'sdi_reviews', 'type' => 'number', 'input_attrs' => array( 'min' => 1, 'max' => 5 ) ) );
	}
}
add_action( 'customize_register', 'sdi_customize' );

/**
 * Default reviews (also the Customizer defaults).
 *
 * @return array
 */
function sdi_default_reviews() {
	return array(
		array( 'name' => 'Julien M.', 'meta' => 'Directeur, PME industrielle · Dijon', 'quote' => 'Une équipe technique qui comprend vraiment les enjeux métier. Notre nouvel outil interne nous fait gagner un temps considérable.', 'rating' => 5 ),
		array( 'name' => 'Sophie R.', 'meta' => 'Gérante, commerce · Beaune', 'quote' => 'Réactifs, pédagogues et de très bon conseil sur le SEO local. On est enfin visibles sur Google dans notre secteur.', 'rating' => 5 ),
		array( 'name' => 'Thomas L.', 'meta' => 'Fondateur, domaine viticole · Côte-d\'Or', 'quote' => "Ils nous ont accompagnés de A à Z, du branding au site. Le chatbot IA qu'ils ont intégré capte des demandes toute la journée.", 'rating' => 5 ),
	);
}

/**
 * Reviews for display, read from the Customizer with sensible fallbacks.
 *
 * @return array Each: name, meta, quote, rating, initials, delay.
 */
function sdi_get_reviews() {
	$defaults = sdi_default_reviews();
	$out      = array();
	for ( $i = 1; $i <= 3; $i++ ) {
		$d       = $defaults[ $i - 1 ];
		$name    = get_theme_mod( "sdi_review{$i}_name", $d['name'] );
		$quote   = get_theme_mod( "sdi_review{$i}_quote", $d['quote'] );
		if ( '' === trim( (string) $quote ) ) {
			continue;
		}
		$parts    = preg_split( '/\s+/', trim( $name ) );
		$initials = strtoupper( mb_substr( $parts[0], 0, 1 ) . ( isset( $parts[1] ) ? mb_substr( $parts[1], 0, 1 ) : '' ) );
		$out[]    = array(
			'name'     => $name,
			'meta'     => get_theme_mod( "sdi_review{$i}_meta", $d['meta'] ),
			'quote'    => $quote,
			'rating'   => (int) get_theme_mod( "sdi_review{$i}_rating", $d['rating'] ),
			'initials' => $initials,
			'delay'    => ( $i - 1 ) * 80,
		);
	}
	return $out;
}

/**
 * Google rating value as displayed (e.g. "4,9").
 *
 * @return string
 */
function sdi_rating_value() {
	return get_theme_mod( 'sdi_rating_value', '4,9' );
}

/**
 * Google reviews count.
 *
 * @return int
 */
function sdi_rating_count() {
	return (int) get_theme_mod( 'sdi_rating_count', 109 );
}

/**
 * Social link helper.
 *
 * @param string $network linkedin|instagram|facebook.
 * @return string
 */
function sdi_social_url( $network ) {
	return get_theme_mod( 'sdi_' . $network, '#' );
}

/* ============================================================
   First-run scaffolding: pages, front page, menu, réalisations
   ============================================================ */

/**
 * Create the site's pages and seed content on theme activation.
 */
function sdi_scaffold_site() {
	if ( get_option( 'sdi_scaffolded' ) ) {
		return;
	}

	$pages = array(
		'accueil'      => 'Accueil',
		'services'     => 'Services',
		'agents-ia'    => 'Agents IA',
		'realisations' => 'Réalisations',
		'agence'       => 'Agence',
		'contact'      => 'Contact',
	);

	$ids = array();
	foreach ( $pages as $slug => $title ) {
		$existing = get_page_by_path( $slug );
		if ( $existing ) {
			$ids[ $slug ] = $existing->ID;
			continue;
		}
		$ids[ $slug ] = wp_insert_post(
			array(
				'post_title'   => $title,
				'post_name'    => $slug,
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_content' => '',
			)
		);
	}

	// Static front page = Accueil.
	if ( ! empty( $ids['accueil'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $ids['accueil'] );
	}

	// Site identity defaults.
	if ( 'My Site' === get_bloginfo( 'name' ) || '' === get_bloginfo( 'description' ) ) {
		update_option( 'blogdescription', 'Agence web & IA à Dijon et Paris — sites, SaaS & agents IA sur-mesure.' );
	}

	sdi_seed_realisations();

	update_option( 'sdi_scaffolded', 1 );
}
add_action( 'after_switch_theme', 'sdi_scaffold_site' );

/**
 * Seed the designed réalisations into the CMS (idempotent).
 */
function sdi_seed_realisations() {
	$existing = get_posts( array( 'post_type' => 'realisation', 'numberposts' => 1, 'fields' => 'ids', 'post_status' => 'any' ) );
	if ( ! empty( $existing ) ) {
		return;
	}

	foreach ( sdi_default_realisations() as $r ) {
		$post_id = wp_insert_post(
			array(
				'post_title'   => $r['title'],
				'post_name'    => $r['slug'],
				'post_status'  => 'publish',
				'post_type'    => 'realisation',
				'menu_order'   => isset( $r['menu_order'] ) ? $r['menu_order'] : 0,
				'post_content' => isset( $r['content'] ) ? $r['content'] : '',
			)
		);
		if ( is_wp_error( $post_id ) || ! $post_id ) {
			continue;
		}

		$meta = array(
			'_sdi_client'       => isset( $r['client'] ) ? $r['client'] : '',
			'_sdi_sector'       => isset( $r['sector'] ) ? $r['sector'] : '',
			'_sdi_tag_label'    => isset( $r['tag_label'] ) ? $r['tag_label'] : 'Projet livré',
			'_sdi_tag_style'    => isset( $r['tag_style'] ) ? $r['tag_style'] : 'done',
			'_sdi_metric'       => isset( $r['metric'] ) ? $r['metric'] : '',
			'_sdi_metric_label' => isset( $r['metric_label'] ) ? $r['metric_label'] : '',
			'_sdi_accent'       => isset( $r['cover'] ) ? $r['cover'] : '',
			'_sdi_perimetre'    => isset( $r['perimetre'] ) ? $r['perimetre'] : '',
			'_sdi_annee'        => isset( $r['annee'] ) ? $r['annee'] : '',
			'_sdi_tags'         => isset( $r['tags'] ) ? implode( ', ', (array) $r['tags'] ) : '',
			'_sdi_quote'        => isset( $r['quote'] ) ? $r['quote'] : '',
			'_sdi_quote_author' => isset( $r['quote_author'] ) ? $r['quote_author'] : '',
			'_sdi_quote_role'   => isset( $r['quote_role'] ) ? $r['quote_role'] : '',
		);
		if ( ! empty( $r['metrics'] ) ) {
			$lines = array();
			foreach ( $r['metrics'] as $m ) {
				$lines[] = $m['value'] . '|' . $m['label'];
			}
			$meta['_sdi_metrics'] = implode( "\n", $lines );
		}
		foreach ( $meta as $k => $v ) {
			update_post_meta( $post_id, $k, $v );
		}
	}

	flush_rewrite_rules();
}

/**
 * Query réalisations, falling back to the designed defaults when empty.
 *
 * @param int $limit Max items (0 = all).
 * @return array Array of display arrays (see sdi_realisation_data()).
 */
function sdi_get_realisations( $limit = 0 ) {
	$q = get_posts(
		array(
			'post_type'      => 'realisation',
			'post_status'    => 'publish',
			'numberposts'    => $limit > 0 ? $limit : -1,
			'orderby'        => 'menu_order date',
			'order'          => 'ASC',
		)
	);

	if ( empty( $q ) ) {
		$defaults = array_map( 'sdi_realisation_data', sdi_default_realisations() );
		return $limit > 0 ? array_slice( $defaults, 0, $limit ) : $defaults;
	}

	return array_map( 'sdi_realisation_data', $q );
}

/* ============================================================
   Migrations (run on activation and on version change)
   ============================================================ */

/**
 * Run version-gated migrations. Cheap no-op once up to date.
 */
function sdi_run_migrations() {
	if ( get_option( 'sdi_theme_version' ) === SDI_VERSION ) {
		return;
	}
	// v1.0.1: create the marketing landing pages + write their SEO meta.
	sdi_create_landing_pages();
	// v1.0.2: create the legal pages (Confidentialité, Mentions légales).
	sdi_create_legal_pages();
	flush_rewrite_rules();
	update_option( 'sdi_theme_version', SDI_VERSION );
}
add_action( 'after_switch_theme', 'sdi_run_migrations', 20 );
add_action( 'admin_init', 'sdi_run_migrations' );

/**
 * Create (idempotent) the legal pages with default French content. Never
 * overwrites an existing page (so client edits are preserved).
 */
function sdi_create_legal_pages() {
	$today = date_i18n( 'F Y' );

	$confidentialite = '<p><em>Dernière mise à jour : ' . esc_html( $today ) . '</em></p>'
		. '<p>La présente politique de confidentialité décrit la manière dont Solutions Digitales Intégrées (SAS), ci-après « SDi », collecte, utilise et protège les données personnelles des visiteurs du site sdi-connect.com, conformément au Règlement Général sur la Protection des Données (RGPD) et à la loi Informatique et Libertés.</p>'
		. '<h2>Responsable du traitement</h2>'
		. '<p>Le responsable du traitement est Solutions Digitales Intégrées (SAS), 60 rue François 1er, 75008 Paris. Pour toute question relative à vos données : <a href="mailto:contact@sdi-connect.com">contact@sdi-connect.com</a>.</p>'
		. '<h2>Données collectées</h2>'
		. '<p>Nous collectons uniquement les données que vous nous transmettez volontairement :</p>'
		. '<ul><li>via le formulaire de contact (nom, entreprise, e-mail, téléphone, message) ;</li><li>via l\'assistant conversationnel (contenu de la discussion et, le cas échéant, votre e-mail) ;</li><li>des données de navigation anonymisées à des fins de mesure d\'audience.</li></ul>'
		. '<h2>Finalités</h2>'
		. '<p>Vos données sont utilisées pour répondre à vos demandes, établir un devis, assurer le suivi de la relation commerciale et améliorer nos services. Elles ne sont jamais revendues à des tiers.</p>'
		. '<h2>Base légale</h2>'
		. '<p>Le traitement repose sur votre consentement (formulaires, chat) et sur l\'intérêt légitime de SDi à répondre à ses prospects et clients.</p>'
		. '<h2>Durée de conservation</h2>'
		. '<p>Les données de prospection sont conservées au maximum 3 ans à compter du dernier contact. Les données liées à une relation contractuelle sont conservées pendant la durée légale applicable.</p>'
		. '<h2>Hébergement</h2>'
		. '<p>Le site est hébergé par Hostinger International Ltd, 61 Lordou Vironos Street, 6023 Larnaca, Chypre. Les données sont traitées au sein de l\'Union européenne.</p>'
		. '<h2>Cookies</h2>'
		. '<p>Le site peut déposer des cookies de mesure d\'audience et de fonctionnement. Vous pouvez configurer votre navigateur pour les refuser. Les cookies non essentiels ne sont déposés qu\'avec votre consentement.</p>'
		. '<h2>Vos droits</h2>'
		. '<p>Vous disposez d\'un droit d\'accès, de rectification, d\'effacement, de limitation, d\'opposition et de portabilité de vos données. Pour les exercer, écrivez à <a href="mailto:contact@sdi-connect.com">contact@sdi-connect.com</a>. Vous pouvez également introduire une réclamation auprès de la CNIL (<a href="https://www.cnil.fr" rel="nofollow">www.cnil.fr</a>).</p>';

	$mentions = '<h2>Éditeur du site</h2>'
		. '<p>Le site sdi-connect.com est édité par :<br>Solutions Digitales Intégrées (SAS)<br>Siège social : 60 rue François 1er, 75008 Paris<br>RCS Paris 827 966 946<br>Téléphone : 09 80 80 62 96<br>E-mail : <a href="mailto:contact@sdi-connect.com">contact@sdi-connect.com</a></p>'
		. '<h2>Directeur de la publication</h2>'
		. '<p>Le représentant légal de Solutions Digitales Intégrées (SAS).</p>'
		. '<h2>Hébergeur</h2>'
		. '<p>Hostinger International Ltd<br>61 Lordou Vironos Street, 6023 Larnaca, Chypre</p>'
		. '<h2>Propriété intellectuelle</h2>'
		. '<p>L\'ensemble des contenus présents sur le site (textes, visuels, logos, code) est la propriété de SDi ou de ses partenaires et est protégé par le droit de la propriété intellectuelle. Toute reproduction sans autorisation est interdite.</p>'
		. '<h2>Responsabilité</h2>'
		. '<p>SDi s\'efforce d\'assurer l\'exactitude des informations diffusées sur ce site mais ne saurait être tenue responsable des erreurs, omissions ou indisponibilités.</p>'
		. '<h2>Données personnelles</h2>'
		. '<p>Le traitement de vos données personnelles est détaillé dans notre <a href="' . esc_url( sdi_page_url( 'confidentialite' ) ) . '">politique de confidentialité</a>.</p>';

	$legal_pages = array(
		'confidentialite'  => array( 'title' => 'Politique de confidentialité', 'content' => $confidentialite, 'excerpt' => 'Politique de confidentialité de SDi : collecte, utilisation et protection de vos données personnelles (RGPD).' ),
		'mentions-legales' => array( 'title' => 'Mentions légales', 'content' => $mentions, 'excerpt' => 'Mentions légales du site sdi-connect.com édité par Solutions Digitales Intégrées (SAS).' ),
	);

	foreach ( $legal_pages as $slug => $data ) {
		if ( get_page_by_path( $slug ) ) {
			continue;
		}
		wp_insert_post(
			array(
				'post_title'   => $data['title'],
				'post_name'    => $slug,
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_content' => $data['content'],
				'post_excerpt' => $data['excerpt'],
			)
		);
	}
}

/**
 * Create (idempotent) the landing pages from inc/landing-data.php and write
 * their Yoast SEO meta + excerpt. Never overwrites an existing page's content.
 */
function sdi_create_landing_pages() {
	if ( ! function_exists( 'sdi_landing_pages' ) ) {
		return;
	}
	foreach ( sdi_landing_pages() as $slug => $cfg ) {
		$existing = get_page_by_path( $slug );
		if ( $existing ) {
			$page_id = $existing->ID;
		} else {
			$page_id = wp_insert_post(
				array(
					'post_title'   => $cfg['title'],
					'post_name'    => $slug,
					'post_status'  => 'publish',
					'post_type'    => 'page',
					'post_content' => '',
					'post_excerpt' => isset( $cfg['seo']['desc'] ) ? $cfg['seo']['desc'] : '',
					'menu_order'   => isset( $cfg['menu_order'] ) ? $cfg['menu_order'] : 0,
				)
			);
			if ( is_wp_error( $page_id ) || ! $page_id ) {
				continue;
			}
		}

		// Yoast / Rank Math meta (harmless if the plugin isn't installed).
		if ( ! empty( $cfg['seo'] ) ) {
			update_post_meta( $page_id, '_yoast_wpseo_title', $cfg['seo']['title'] );
			update_post_meta( $page_id, '_yoast_wpseo_metadesc', $cfg['seo']['desc'] );
			if ( ! empty( $cfg['seo']['focus'] ) ) {
				update_post_meta( $page_id, '_yoast_wpseo_focuskw', $cfg['seo']['focus'] );
			}
			update_post_meta( $page_id, 'rank_math_title', $cfg['seo']['title'] );
			update_post_meta( $page_id, 'rank_math_description', $cfg['seo']['desc'] );
			if ( ! empty( $cfg['seo']['focus'] ) ) {
				update_post_meta( $page_id, 'rank_math_focus_keyword', $cfg['seo']['focus'] );
			}
		}
	}
}
