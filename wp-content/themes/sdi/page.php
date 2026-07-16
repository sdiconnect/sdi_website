<?php
/**
 * Page template.
 *
 * Renders a designed landing page when the slug matches a landing config
 * (see inc/landing-data.php); otherwise falls back to generic prose
 * (Mentions légales, Confidentialité, etc.).
 *
 * @package SDi
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$sdi_qobj    = get_queried_object();
$sdi_landing = sdi_landing_for_post( $sdi_qobj );

if ( $sdi_landing ) {
	// Active nav group by slug family.
	$sdi_services = array( 'creation-site-internet', 'site-e-commerce', 'referencement-seo', 'google-ads', 'saas-sur-mesure' );
	$sdi_ia       = array( 'agents-ia-sur-mesure', 'automatisation-ia' );
	if ( in_array( $sdi_qobj->post_name, $sdi_services, true ) ) {
		$GLOBALS['sdi_active'] = 'services';
	} elseif ( in_array( $sdi_qobj->post_name, $sdi_ia, true ) ) {
		$GLOBALS['sdi_active'] = 'ia';
	}

	// SEO (used when no SEO plugin is active; Yoast/Rank Math override via postmeta).
	if ( ! empty( $sdi_landing['seo'] ) ) {
		$crumb_map = array();
		foreach ( (array) ( $sdi_landing['crumbs'] ?? array() ) as $step ) {
			$crumb_map[ $step['label'] ] = isset( $step['href'] ) ? $step['href'] : get_permalink( $sdi_qobj );
		}
		sdi_set_seo(
			array(
				'title'       => $sdi_landing['seo']['title'],
				'description' => $sdi_landing['seo']['desc'],
				'keywords'    => $sdi_landing['seo']['focus'] ?? '',
				'breadcrumb'  => $crumb_map,
			)
		);
	}

	get_header();
	sdi_landing( $sdi_landing );
	get_footer();
	return;
}

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<section style="position:relative;background:var(--gradient-navy);color:var(--white);overflow:hidden;">
		<div style="position:absolute;inset:0;background:var(--gradient-halo);pointer-events:none;"></div>
		<div style="position:relative;max-width:1240px;margin:0 auto;padding:clamp(44px,6vw,72px) clamp(20px,5vw,32px);">
			<?php echo sdi_breadcrumb( array( array( 'label' => 'Accueil', 'href' => home_url( '/' ) ), array( 'label' => get_the_title() ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<h1 style="margin-top:20px;font-family:var(--font-display);font-weight:700;font-size:clamp(30px,4.4vw,52px);line-height:1.05;letter-spacing:-0.03em;color:#fff;text-wrap:balance;"><?php the_title(); ?></h1>
		</div>
	</section>

	<section style="background:var(--surface-page);">
		<div style="max-width:820px;margin:0 auto;padding:clamp(48px,6vw,80px) clamp(20px,5vw,32px);">
			<div class="sdi-prose"><?php the_content(); ?></div>
			<?php
			wp_link_pages(
				array(
					'before' => '<div style="margin-top:24px;">',
					'after'  => '</div>',
				)
			);
			?>
		</div>
	</section>
	<?php
endwhile;

get_footer();
