<?php
/**
 * SEO: per-page title/description/keywords, Open Graph, JSON-LD structured data.
 *
 * Templates call sdi_set_seo() before get_header() to override values.
 *
 * @package SDi
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Set SEO values for the current request.
 *
 * @param array $args title|description|keywords|breadcrumb (array of label=>url).
 */
function sdi_set_seo( $args ) {
	$GLOBALS['sdi_seo'] = wp_parse_args( $args, isset( $GLOBALS['sdi_seo'] ) ? $GLOBALS['sdi_seo'] : array() );
}

/**
 * Company / NAP constants used across SEO output.
 */
function sdi_company() {
	return array(
		'name'      => 'SDi — Solutions Digitales Intégrées',
		'legal'     => 'Solutions Digitales Intégrées (SAS)',
		'phone'     => '+33980806296',
		'phone_fr'  => '09 80 80 62 96',
		'email'     => 'contact@sdi-connect.com',
		'street'    => '60 rue François 1er',
		'postal'    => '75008',
		'city'      => 'Paris',
		'rating'    => '4.9',
		'reviews'   => '109',
		'founded'   => '2017',
	);
}

/**
 * Override the document title when a template set one.
 *
 * @param string $title Original.
 * @return string
 */
function sdi_filter_document_title( $title ) {
	if ( ! empty( $GLOBALS['sdi_seo']['title'] ) ) {
		return $GLOBALS['sdi_seo']['title'];
	}
	return $title;
}
add_filter( 'pre_get_document_title', 'sdi_filter_document_title', 20 );

/**
 * Resolve the effective meta description for this request.
 *
 * @return string
 */
function sdi_meta_description() {
	if ( ! empty( $GLOBALS['sdi_seo']['description'] ) ) {
		return $GLOBALS['sdi_seo']['description'];
	}
	if ( is_singular() ) {
		$excerpt = get_the_excerpt();
		if ( $excerpt ) {
			return wp_strip_all_tags( $excerpt );
		}
	}
	return get_bloginfo( 'description' );
}

/**
 * Output meta description, keywords, and Open Graph / Twitter tags.
 */
function sdi_head_meta() {
	$desc = sdi_meta_description();
	$logo = get_template_directory_uri() . '/assets/img/sdi-logo-color.png';
	$url  = ( is_front_page() ) ? home_url( '/' ) : ( is_singular() ? get_permalink() : home_url( add_query_arg( array(), $GLOBALS['wp']->request ) ) );
	$title = wp_get_document_title();

	if ( $desc ) {
		echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
	}
	if ( ! empty( $GLOBALS['sdi_seo']['keywords'] ) ) {
		echo '<meta name="keywords" content="' . esc_attr( $GLOBALS['sdi_seo']['keywords'] ) . '">' . "\n";
	}
	echo '<meta property="og:type" content="website">' . "\n";
	echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	if ( $desc ) {
		echo '<meta property="og:description" content="' . esc_attr( $desc ) . '">' . "\n";
	}
	echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
	echo '<meta property="og:locale" content="fr_FR">' . "\n";

	$og_image = ( is_singular() && has_post_thumbnail() ) ? get_the_post_thumbnail_url( null, 'large' ) : $logo;
	echo '<meta property="og:image" content="' . esc_url( $og_image ) . '">' . "\n";
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
	if ( $desc ) {
		echo '<meta name="twitter:description" content="' . esc_attr( $desc ) . '">' . "\n";
	}
	echo '<meta name="twitter:image" content="' . esc_url( $og_image ) . '">' . "\n";
}
add_action( 'wp_head', 'sdi_head_meta', 2 );

/**
 * Output JSON-LD structured data (LocalBusiness + AggregateRating, and
 * BreadcrumbList when a template provided a breadcrumb).
 */
function sdi_head_jsonld() {
	$c = sdi_company();

	$business = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'ProfessionalService',
		'@id'         => home_url( '/#organization' ),
		'name'        => 'SDi',
		'legalName'   => $c['legal'],
		'url'         => home_url( '/' ),
		'telephone'   => $c['phone'],
		'email'       => $c['email'],
		'logo'        => get_template_directory_uri() . '/assets/img/sdi-logo-color.png',
		'image'       => get_template_directory_uri() . '/assets/img/sdi-logo-color.png',
		'foundingDate' => $c['founded'],
		'priceRange'  => '€€',
		'description' => 'Agence web & IA à Dijon et Paris : sites internet, e-commerce, SEO, applications SaaS et agents IA sur-mesure, partout en France.',
		'address'     => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => $c['street'],
			'postalCode'      => $c['postal'],
			'addressLocality' => $c['city'],
			'addressCountry'  => 'FR',
		),
		'areaServed'  => array(
			array( '@type' => 'City', 'name' => 'Dijon' ),
			array( '@type' => 'AdministrativeArea', 'name' => 'Côte-d\'Or' ),
			array( '@type' => 'City', 'name' => 'Paris' ),
			array( '@type' => 'Country', 'name' => 'France' ),
		),
		'sameAs'      => array_values( array_filter( array(
			get_theme_mod( 'sdi_linkedin', '' ),
			get_theme_mod( 'sdi_instagram', '' ),
			get_theme_mod( 'sdi_facebook', '' ),
		) ) ),
		'aggregateRating' => array(
			'@type'       => 'AggregateRating',
			'ratingValue' => $c['rating'],
			'reviewCount' => $c['reviews'],
			'bestRating'  => '5',
		),
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $business, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";

	if ( ! empty( $GLOBALS['sdi_seo']['breadcrumb'] ) && is_array( $GLOBALS['sdi_seo']['breadcrumb'] ) ) {
		$items = array();
		$pos   = 1;
		foreach ( $GLOBALS['sdi_seo']['breadcrumb'] as $label => $u ) {
			$items[] = array(
				'@type'    => 'ListItem',
				'position' => $pos++,
				'name'     => $label,
				'item'     => $u,
			);
		}
		$crumb = array(
			'@context'        => 'https://schema.org',
			'@type'           => 'BreadcrumbList',
			'itemListElement' => $items,
		);
		echo '<script type="application/ld+json">' . wp_json_encode( $crumb, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
	}
}
add_action( 'wp_head', 'sdi_head_jsonld', 3 );
