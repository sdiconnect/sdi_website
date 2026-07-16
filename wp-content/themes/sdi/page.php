<?php
/**
 * Generic page template (fallback for pages without a dedicated template,
 * e.g. Mentions légales, Confidentialité).
 *
 * @package SDi
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

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
