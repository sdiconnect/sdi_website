<?php
/**
 * Single case study (realisation) template.
 *
 * @package SDi
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

// SEO must be set before get_header() (wp_head fires there).
$sdi_post = get_queried_object();
if ( $sdi_post instanceof WP_Post ) {
	$sdi_client = get_post_meta( $sdi_post->ID, '_sdi_client', true );
	$sdi_desc   = has_excerpt( $sdi_post ) ? wp_strip_all_tags( get_the_excerpt( $sdi_post ) ) : wp_trim_words( wp_strip_all_tags( $sdi_post->post_content ), 32 );
	sdi_set_seo(
		array(
			'title'       => sprintf( 'Étude de cas %s — %s | SDi', $sdi_client ? $sdi_client : $sdi_post->post_title, $sdi_post->post_title ),
			'description' => $sdi_desc,
			'breadcrumb'  => array(
				'Accueil'      => home_url( '/' ),
				'Réalisations' => sdi_page_url( 'realisations' ),
				( $sdi_client ? $sdi_client : $sdi_post->post_title ) => get_permalink( $sdi_post ),
			),
		)
	);
}

get_header();

while ( have_posts() ) :
	the_post();
	$r = sdi_realisation_data( get_post() );

	$facts = array_filter(
		array(
			array( 'Client', $r['client'] ),
			array( 'Secteur', $r['sector'] ),
			array( 'Périmètre', $r['perimetre'] ),
			array( 'Année', $r['annee'] ),
		),
		function ( $f ) { return '' !== $f[1]; }
	);
	?>

	<!-- HERO -->
	<section style="position:relative;background:var(--gradient-navy);color:var(--white);overflow:hidden;">
		<div style="position:absolute;inset:0;background:var(--gradient-halo);pointer-events:none;"></div>
		<div style="position:relative;max-width:1240px;margin:0 auto;padding:clamp(40px,5vw,68px) clamp(20px,5vw,32px) clamp(48px,6vw,80px);">
			<?php
			echo sdi_breadcrumb( // phpcs:ignore WordPress.Security.EscapeOutput
				array(
					array( 'label' => 'Accueil', 'href' => home_url( '/' ) ),
					array( 'label' => 'Réalisations', 'href' => sdi_page_url( 'realisations' ) ),
					array( 'label' => $r['client'] ? $r['client'] : get_the_title() ),
				)
			);
			?>
			<div data-split-900 style="margin-top:24px;display:grid;grid-template-columns:1fr 0.9fr;gap:clamp(32px,4vw,56px);align-items:center;">
				<div>
					<?php if ( $r['sector'] ) : ?>
						<div class="sdi-reveal sdi-eyebrow" data-reveal style="color:var(--cyan-400);font-weight:500;">// <?php echo esc_html( $r['sector'] ); ?></div>
					<?php endif; ?>
					<h1 class="sdi-reveal" data-reveal data-reveal-delay="80" style="margin-top:16px;font-family:var(--font-display);font-weight:700;font-size:clamp(30px,4.4vw,52px);line-height:1.05;letter-spacing:-0.03em;color:#fff;text-wrap:balance;"><?php the_title(); ?></h1>
					<?php if ( has_excerpt() ) : ?>
						<p class="sdi-reveal" data-reveal data-reveal-delay="160" style="margin-top:20px;font-size:clamp(16px,1.3vw,19px);line-height:1.6;color:var(--navy-300);max-width:520px;"><?php echo esc_html( get_the_excerpt() ); ?></p>
					<?php endif; ?>
				</div>
				<?php if ( $r['image'] ) : ?>
					<div class="sdi-reveal" data-reveal data-reveal-delay="200" style="border-radius:20px;overflow:hidden;border:1px solid var(--border-inverse);box-shadow:0 30px 70px rgba(7,11,22,0.5);">
						<img src="<?php echo esc_url( $r['image'] ); ?>" alt="<?php echo esc_attr( sprintf( 'Aperçu du projet %s réalisé par SDi', $r['client'] ) ); ?>" style="display:block;width:100%;height:auto;">
					</div>
				<?php endif; ?>
			</div>
			<?php if ( ! empty( $r['metrics'] ) ) : ?>
				<div class="sdi-reveal" data-reveal data-reveal-delay="260" style="margin-top:36px;display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:20px;border-top:1px solid var(--border-inverse);padding-top:28px;">
					<?php foreach ( $r['metrics'] as $m ) : ?>
						<div><div style="font-family:var(--font-display);font-weight:700;font-size:clamp(30px,3.4vw,40px);line-height:1;letter-spacing:-0.02em;color:<?php echo esc_attr( $m['color'] ); ?>;"><?php echo esc_html( $m['value'] ); ?></div><div style="margin-top:8px;font-size:14px;color:var(--navy-300);"><?php echo esc_html( $m['label'] ); ?></div></div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<!-- FICHE + NARRATIF -->
	<section style="background:var(--surface-page);">
		<div style="max-width:1240px;margin:0 auto;padding:clamp(52px,6vw,84px) clamp(20px,5vw,32px);">
			<div data-split-900 style="display:grid;grid-template-columns:0.34fr 0.66fr;gap:clamp(32px,4vw,56px);align-items:start;">
				<aside class="sdi-reveal" data-reveal style="position:sticky;top:96px;background:var(--white);border:1px solid var(--border-subtle);border-radius:var(--radius-2xl);box-shadow:var(--shadow-sm);padding:26px;">
					<h2 style="font-family:var(--font-mono);font-size:11px;letter-spacing:0.12em;text-transform:uppercase;color:var(--text-muted);font-weight:600;">La mission</h2>
					<div style="margin-top:16px;display:flex;flex-direction:column;gap:14px;">
						<?php foreach ( $facts as $f ) : ?>
							<div><div style="font-size:12px;color:var(--text-subtle);"><?php echo esc_html( $f[0] ); ?></div><div style="margin-top:2px;font-size:15px;font-weight:600;color:var(--text-strong);"><?php echo esc_html( $f[1] ); ?></div></div>
						<?php endforeach; ?>
					</div>
					<?php if ( ! empty( $r['tags'] ) ) : ?>
						<div style="margin-top:20px;padding-top:18px;border-top:1px solid var(--navy-100);display:flex;flex-wrap:wrap;gap:8px;">
							<?php foreach ( $r['tags'] as $t ) : ?>
								<span style="font-size:12px;font-weight:500;padding:6px 12px;border-radius:999px;background:var(--blue-50);color:var(--brand-primary);"><?php echo esc_html( $t ); ?></span>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</aside>
				<div style="display:flex;flex-direction:column;gap:clamp(32px,4vw,48px);">
					<div class="sdi-reveal sdi-prose" data-reveal>
						<?php the_content(); ?>
					</div>
					<?php if ( $r['quote'] ) : ?>
						<div class="sdi-reveal" data-reveal style="background:var(--white);border:1px solid var(--border-subtle);border-radius:var(--radius-2xl);box-shadow:var(--shadow-md);padding:clamp(24px,3vw,36px);">
							<?php echo sdi_stars( 5, 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<p style="margin-top:16px;font-family:var(--font-display);font-weight:500;font-size:clamp(18px,2vw,23px);line-height:1.45;color:var(--text-strong);letter-spacing:-0.01em;">«&nbsp;<?php echo esc_html( $r['quote'] ); ?>&nbsp;»</p>
							<?php if ( $r['quote_author'] ) : ?>
								<div style="margin-top:20px;display:flex;align-items:center;gap:12px;"><div style="width:44px;height:44px;border-radius:50%;background:var(--gradient-brand);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;"><?php echo esc_html( mb_substr( $r['client'] ? $r['client'] : $r['quote_author'], 0, 1 ) ); ?></div><div><div style="font-weight:600;color:var(--text-strong);font-size:15px;"><?php echo esc_html( $r['quote_author'] ); ?></div><div style="font-size:13px;color:var(--text-muted);"><?php echo esc_html( $r['quote_role'] ); ?></div></div></div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>

	<!-- CTA -->
	<section style="position:relative;background:var(--navy-950);color:var(--white);overflow:hidden;">
		<div style="position:absolute;top:-20%;left:50%;transform:translateX(-50%);width:900px;height:560px;background:radial-gradient(ellipse at center,rgba(29,110,255,0.18) 0%,rgba(29,110,255,0) 64%);pointer-events:none;"></div>
		<div style="position:relative;max-width:1240px;margin:0 auto;padding:clamp(52px,6vw,84px) clamp(20px,5vw,32px);text-align:center;">
			<h2 class="sdi-reveal" data-reveal style="font-family:var(--font-display);font-weight:700;font-size:clamp(26px,3.6vw,44px);line-height:1.08;letter-spacing:-0.025em;color:#fff;text-wrap:balance;max-width:700px;margin:0 auto;">Un projet similaire ? Écrivons la prochaine étude de cas.</h2>
			<div class="sdi-reveal" data-reveal data-reveal-delay="120" style="margin-top:26px;display:flex;justify-content:center;gap:14px;flex-wrap:wrap;">
				<?php echo sdi_button( array( 'label' => 'Nous contacter', 'variant' => 'primary', 'size' => 'lg', 'href' => sdi_page_url( 'contact' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<a href="<?php echo esc_url( sdi_page_url( 'realisations' ) ); ?>" class="sdi-ghost-dark">Toutes les réalisations</a>
			</div>
		</div>
	</section>

<?php endwhile; ?>

<?php
get_footer();
