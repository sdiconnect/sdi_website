<?php
/**
 * Custom post type "Réalisation" (case studies) + native meta box.
 *
 * Kept ACF-free on purpose so the theme is self-contained.
 *
 * @package SDi
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Register the "realisation" post type.
 */
function sdi_register_realisation_cpt() {
	$labels = array(
		'name'               => __( 'Réalisations', 'sdi' ),
		'singular_name'      => __( 'Réalisation', 'sdi' ),
		'add_new'            => __( 'Ajouter', 'sdi' ),
		'add_new_item'       => __( 'Ajouter une réalisation', 'sdi' ),
		'edit_item'          => __( 'Modifier la réalisation', 'sdi' ),
		'new_item'           => __( 'Nouvelle réalisation', 'sdi' ),
		'view_item'          => __( 'Voir la réalisation', 'sdi' ),
		'search_items'       => __( 'Rechercher', 'sdi' ),
		'not_found'          => __( 'Aucune réalisation', 'sdi' ),
		'all_items'          => __( 'Toutes les réalisations', 'sdi' ),
		'menu_name'          => __( 'Réalisations', 'sdi' ),
	);

	register_post_type(
		'realisation',
		array(
			'labels'       => $labels,
			'public'       => true,
			'has_archive'  => false,
			'menu_icon'    => 'dashicons-portfolio',
			'menu_position' => 22,
			'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
			'rewrite'      => array( 'slug' => 'realisations', 'with_front' => false ),
			'show_in_rest' => true,
		)
	);
}
add_action( 'init', 'sdi_register_realisation_cpt' );

/**
 * Meta fields definition for a réalisation.
 *
 * @return array key => label.
 */
function sdi_realisation_fields() {
	return array(
		'_sdi_client'       => 'Nom du client (ex. ORVITIS)',
		'_sdi_sector'       => 'Secteur / ligne (ex. Habitat · Site & portail)',
		'_sdi_tag_label'    => 'Badge carte (ex. Étude de cas / Projet livré)',
		'_sdi_tag_style'    => 'Style du badge : "study" (bleu) ou "done" (translucide)',
		'_sdi_metric'       => 'Métrique carte (ex. +65%)',
		'_sdi_metric_label' => 'Libellé métrique (ex. de demandes en ligne)',
		'_sdi_accent'       => 'Fond de couverture si pas d\'image (CSS, ex. linear-gradient(135deg,#0F172A,#1D6EFF))',
		'_sdi_perimetre'    => 'Périmètre (fiche mission)',
		'_sdi_annee'        => 'Année (fiche mission)',
		'_sdi_tags'         => 'Tags (séparés par des virgules)',
		'_sdi_quote'        => 'Témoignage (citation)',
		'_sdi_quote_author' => 'Témoignage — auteur',
		'_sdi_quote_role'   => 'Témoignage — rôle / société',
		'_sdi_metrics'      => 'Métriques d\'en-tête (4 max, format: valeur|libellé par ligne)',
	);
}

/**
 * Register the meta box.
 */
function sdi_add_realisation_metabox() {
	add_meta_box(
		'sdi_realisation_details',
		__( 'Détails de la réalisation', 'sdi' ),
		'sdi_render_realisation_metabox',
		'realisation',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'sdi_add_realisation_metabox' );

/**
 * Render the meta box.
 *
 * @param WP_Post $post Current post.
 */
function sdi_render_realisation_metabox( $post ) {
	wp_nonce_field( 'sdi_realisation_save', 'sdi_realisation_nonce' );
	$fields = sdi_realisation_fields();
	echo '<style>.sdi-mb label{display:block;font-weight:600;margin:14px 0 4px;} .sdi-mb input,.sdi-mb textarea{width:100%;} .sdi-mb p.desc{color:#666;font-size:12px;margin:2px 0 0;}</style>';
	echo '<div class="sdi-mb">';
	foreach ( $fields as $key => $label ) {
		$val      = get_post_meta( $post->ID, $key, true );
		$multi    = in_array( $key, array( '_sdi_metrics', '_sdi_quote' ), true );
		echo '<label for="' . esc_attr( $key ) . '">' . esc_html( $label ) . '</label>';
		if ( $multi ) {
			echo '<textarea id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" rows="4">' . esc_textarea( $val ) . '</textarea>';
		} else {
			echo '<input type="text" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '">';
		}
	}
	echo '<p class="desc">Le titre du post = titre du projet. Le contenu (éditeur) = récit Contexte / Réponse / Résultats. L\'image mise en avant = couverture.</p>';
	echo '</div>';
}

/**
 * Save meta box values.
 *
 * @param int $post_id Post ID.
 */
function sdi_save_realisation_meta( $post_id ) {
	if ( ! isset( $_POST['sdi_realisation_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sdi_realisation_nonce'] ) ), 'sdi_realisation_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
	if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }

	foreach ( array_keys( sdi_realisation_fields() ) as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			$raw = wp_unslash( $_POST[ $key ] );
			if ( in_array( $key, array( '_sdi_metrics', '_sdi_quote' ), true ) ) {
				update_post_meta( $post_id, $key, sanitize_textarea_field( $raw ) );
			} else {
				update_post_meta( $post_id, $key, sanitize_text_field( $raw ) );
			}
		}
	}
}
add_action( 'save_post_realisation', 'sdi_save_realisation_meta' );

/**
 * Normalize a réalisation into a display array (from a post or a raw default).
 *
 * @param WP_Post|array $post Post object or default array.
 * @return array
 */
function sdi_realisation_data( $post ) {
	if ( is_array( $post ) ) {
		return wp_parse_args( $post, sdi_realisation_defaults_shape() );
	}

	$id     = $post->ID;
	$thumb  = get_the_post_thumbnail_url( $id, 'large' );
	$accent = get_post_meta( $id, '_sdi_accent', true );

	if ( $thumb ) {
		$cover = "url('" . esc_url( $thumb ) . "') center top / cover";
		$img   = $thumb;
	} else {
		$cover = $accent ?: 'linear-gradient(135deg,#0F172A,#1D6EFF)';
		// Derive a hero image from an image-based accent cover, e.g. url('…/foo.png') … .
		$img = '';
		if ( $accent && preg_match( "/url\\((['\"]?)(.*?)\\1\\)/", $accent, $m ) ) {
			$img = $m[2];
		}
	}

	return array(
		'title'        => get_the_title( $id ),
		'client'       => get_post_meta( $id, '_sdi_client', true ),
		'sector'       => get_post_meta( $id, '_sdi_sector', true ),
		'tag_label'    => get_post_meta( $id, '_sdi_tag_label', true ) ?: 'Projet livré',
		'tag_style'    => get_post_meta( $id, '_sdi_tag_style', true ) ?: 'done',
		'metric'       => get_post_meta( $id, '_sdi_metric', true ),
		'metric_label' => get_post_meta( $id, '_sdi_metric_label', true ),
		'cover'        => $cover,
		'href'         => get_permalink( $id ),
		'image'        => $img,
		'perimetre'    => get_post_meta( $id, '_sdi_perimetre', true ),
		'annee'        => get_post_meta( $id, '_sdi_annee', true ),
		'tags'         => array_filter( array_map( 'trim', explode( ',', (string) get_post_meta( $id, '_sdi_tags', true ) ) ) ),
		'metrics'      => sdi_parse_metrics( get_post_meta( $id, '_sdi_metrics', true ) ),
		'quote'        => get_post_meta( $id, '_sdi_quote', true ),
		'quote_author' => get_post_meta( $id, '_sdi_quote_author', true ),
		'quote_role'   => get_post_meta( $id, '_sdi_quote_role', true ),
		'content'      => apply_filters( 'the_content', get_post_field( 'post_content', $id ) ),
	);
}

/**
 * Default shape so array-based defaults don't warn on missing keys.
 */
function sdi_realisation_defaults_shape() {
	return array(
		'title' => '', 'client' => '', 'sector' => '', 'tag_label' => 'Projet livré', 'tag_style' => 'done',
		'metric' => '', 'metric_label' => '', 'cover' => 'linear-gradient(135deg,#0F172A,#1D6EFF)', 'href' => '',
		'image' => '', 'perimetre' => '', 'annee' => '', 'tags' => array(), 'metrics' => array(),
		'quote' => '', 'quote_author' => '', 'quote_role' => '', 'content' => '',
	);
}

/**
 * Parse "value|label" per-line metric strings into arrays with alternating colors.
 *
 * @param string $raw Raw textarea content.
 * @return array
 */
function sdi_parse_metrics( $raw ) {
	$out    = array();
	$colors = array( 'var(--blue-400)', 'var(--cyan-400)', 'var(--blue-400)', 'var(--magenta-400)' );
	$lines  = array_filter( array_map( 'trim', explode( "\n", (string) $raw ) ) );
	foreach ( array_values( $lines ) as $i => $line ) {
		$parts = array_map( 'trim', explode( '|', $line, 2 ) );
		if ( '' === $parts[0] ) { continue; }
		$out[] = array(
			'value' => $parts[0],
			'label' => isset( $parts[1] ) ? $parts[1] : '',
			'color' => $colors[ $i % count( $colors ) ],
		);
	}
	return $out;
}

/**
 * The designed réalisations, used both to seed the CMS on activation and as a
 * fallback so the site is complete out of the box.
 *
 * @return array
 */
function sdi_default_realisations() {
	$img = get_template_directory_uri() . '/assets/work/';
	return array(
		array(
			'slug'         => 'orvitis',
			'title'        => 'Refonte du site institutionnel et recherche de logement en ligne',
			'client'       => 'ORVITIS',
			'sector'       => 'Habitat · Site & portail',
			'tag_label'    => 'Étude de cas',
			'tag_style'    => 'study',
			'metric'       => '+65%',
			'metric_label' => 'de demandes en ligne',
			'cover'        => "url('" . $img . "orvitis-site.png') center top / cover",
			'image'        => $img . 'orvitis-site.png',
			'perimetre'    => 'Site institutionnel + portail',
			'annee'        => '2024',
			'tags'         => array( 'UX/UI', 'Développement', 'SEO local', 'Accessibilité', 'CMS' ),
			'metrics'      => array(
				array( 'value' => '+65%', 'label' => 'de demandes de logement en ligne', 'color' => 'var(--blue-400)' ),
				array( 'value' => '−30%', 'label' => "d'appels au standard", 'color' => 'var(--cyan-400)' ),
				array( 'value' => '×2', 'label' => 'de pages vues par visite', 'color' => 'var(--blue-400)' ),
				array( 'value' => 'AA', 'label' => "niveau d'accessibilité RGAA", 'color' => 'var(--magenta-400)' ),
			),
			'quote'        => "Un site enfin à la hauteur de notre mission de service public, et une recherche de logement que nos usagers trouvent simple.",
			'quote_author' => 'Direction communication',
			'quote_role'   => 'Orvitis · Côte-d\'Or',
			'content'      => "<h2>Le contexte</h2>\n<p>L'ancien site d'Orvitis ne reflétait plus la mission de l'organisme et rendait la recherche de logement complexe. Les équipes ne pouvaient pas gérer facilement leurs contenus, et le trafic mobile était mal servi.</p>\n<h2>Notre réponse</h2>\n<p>Nous avons repensé l'architecture, le design et le moteur de recherche de logement, tout en donnant aux équipes un CMS simple pour piloter leurs contenus au quotidien.</p>\n<ul><li>Recherche de logement guidée et filtrable</li><li>Espace locataire modernisé</li><li>Design responsive et accessible (RGAA)</li><li>SEO local pour capter les recherches en Côte-d'Or</li></ul>\n<h2>Les résultats</h2>\n<p>En quelques mois, les demandes de logement en ligne ont progressé de 65% et la pression sur le standard a nettement baissé. Les équipes publient désormais leurs actualités sans dépendre d'un prestataire.</p>",
			'menu_order'   => 0,
		),
		array(
			'slug'         => 'ghitti-immobilier',
			'title'        => 'Site premium avec catalogue de programmes et prise de contact',
			'client'       => 'GHITTI IMMOBILIER',
			'sector'       => 'Immobilier · Site sur-mesure',
			'tag_label'    => 'Projet livré',
			'tag_style'    => 'done',
			'metric'       => '+52%',
			'metric_label' => 'de leads acheteurs',
			'cover'        => "url('" . $img . "ghitti-site.png') center top / cover",
			'image'        => $img . 'ghitti-site.png',
			'menu_order'   => 1,
		),
		array(
			'slug'         => 'tereos',
			'title'        => 'Plateforme métier de suivi de production sur-mesure',
			'client'       => 'TEREOS',
			'sector'       => 'Agro-industrie · SaaS',
			'tag_label'    => 'Projet livré',
			'tag_style'    => 'done',
			'metric'       => '−40%',
			'metric_label' => 'de saisie manuelle',
			'cover'        => 'linear-gradient(135deg,#0F172A,#1D6EFF)',
			'menu_order'   => 2,
		),
		array(
			'slug'         => 'krys',
			'title'        => 'Campagnes SEA locales multi-magasins pilotées à la donnée',
			'client'       => 'KRYS',
			'sector'       => 'Retail · Google Ads',
			'tag_label'    => 'Projet livré',
			'tag_style'    => 'done',
			'metric'       => '+38%',
			'metric_label' => 'de conversions',
			'cover'        => 'linear-gradient(135deg,#0F172A,#334155)',
			'menu_order'   => 3,
		),
		array(
			'slug'         => 'dijon-cereales',
			'title'        => 'Site institutionnel et espace adhérents connecté',
			'client'       => 'DIJON CÉRÉALES',
			'sector'       => 'Coopérative · Web',
			'tag_label'    => 'Projet livré',
			'tag_style'    => 'done',
			'metric'       => '×2',
			'metric_label' => 'de trafic organique',
			'cover'        => 'linear-gradient(135deg,#16203A,#00A5E4)',
			'menu_order'   => 4,
		),
		array(
			'slug'         => 'nature-decouvertes',
			'title'        => 'Agent IA de conseil produit intégré au site',
			'client'       => 'NATURE & DÉCOUVERTES',
			'sector'       => 'Retail · IA',
			'tag_label'    => 'Projet livré',
			'tag_style'    => 'done',
			'metric'       => '×3',
			'metric_label' => 'de leads qualifiés',
			'cover'        => 'linear-gradient(135deg,#16203A,#1D6EFF)',
			'menu_order'   => 5,
		),
	);
}
