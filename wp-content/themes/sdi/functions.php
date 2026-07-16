<?php
/**
 * SDi theme bootstrap.
 *
 * @package SDi
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'SDI_VERSION', '1.0.0' );
define( 'SDI_DIR', get_template_directory() );
define( 'SDI_URI', get_template_directory_uri() );

require_once SDI_DIR . '/inc/icons.php';
require_once SDI_DIR . '/inc/components.php';
require_once SDI_DIR . '/inc/cpt.php';
require_once SDI_DIR . '/inc/seo.php';
require_once SDI_DIR . '/inc/contact.php';

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
}
add_action( 'customize_register', 'sdi_customize' );

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
