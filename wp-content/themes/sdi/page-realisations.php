<?php
/**
 * Template for the Réalisations listing page (slug: realisations).
 *
 * @package SDi
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

sdi_set_seo(
	array(
		'title'       => 'Réalisations & études de cas clients | Agence web SDi — Dijon & Paris',
		'description' => "Découvrez les réalisations de SDi : sites internet, e-commerce, plateformes SaaS et agents IA livrés pour Orvitis, Ghitti Immobilier, Tereos, Krys et d'autres. Des projets concrets avec des résultats mesurables, à Dijon et partout en France.",
		'keywords'    => 'réalisations agence web, études de cas, portfolio agence Dijon, projets web, cas client SaaS',
		'breadcrumb'  => array( 'Accueil' => home_url( '/' ), 'Réalisations' => get_permalink() ),
	)
);

$stats = array(
	array( '+100', 'projets livrés', 'var(--blue-400)' ),
	array( '2017', 'depuis', 'var(--cyan-400)' ),
	array( '4,9/5', '· 109 avis Google', 'var(--magenta-400)' ),
);

$works = sdi_get_realisations( 0 );

get_header();
?>

<!-- HERO -->
<section style="position:relative;background:var(--gradient-navy);color:var(--white);overflow:hidden;">
	<div style="position:absolute;inset:0;background:var(--gradient-halo);pointer-events:none;"></div>
	<div style="position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,0.035) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.035) 1px,transparent 1px);background-size:64px 64px;mask-image:radial-gradient(ellipse 80% 70% at 50% 15%,#000 0%,transparent 75%);-webkit-mask-image:radial-gradient(ellipse 80% 70% at 50% 15%,#000 0%,transparent 75%);pointer-events:none;"></div>
	<div style="position:relative;max-width:1240px;margin:0 auto;padding:clamp(44px,6vw,80px) clamp(20px,5vw,32px) clamp(52px,6vw,88px);">
		<?php echo sdi_breadcrumb( array( array( 'label' => 'Accueil', 'href' => home_url( '/' ) ), array( 'label' => 'Réalisations' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<div class="sdi-reveal sdi-eyebrow sdi-eyebrow--pill" data-reveal style="margin-top:22px;color:var(--cyan-400);font-weight:500;">// Études de cas</div>
		<h1 class="sdi-reveal" data-reveal data-reveal-delay="80" style="margin-top:22px;max-width:880px;font-family:var(--font-display);font-weight:700;font-size:clamp(34px,5vw,60px);line-height:1.05;letter-spacing:-0.03em;color:#fff;text-wrap:balance;">Des projets concrets, des résultats mesurables.</h1>
		<p class="sdi-reveal" data-reveal data-reveal-delay="160" style="margin-top:22px;max-width:620px;font-size:clamp(17px,1.4vw,20px);line-height:1.6;color:var(--navy-300);">Plus de 100 projets livrés depuis 2017, pour des clients locaux comme nationaux. Voici une sélection, avec les résultats obtenus.</p>
		<div class="sdi-reveal" data-reveal data-reveal-delay="240" style="margin-top:30px;display:flex;flex-wrap:wrap;gap:10px 26px;">
			<?php foreach ( $stats as $st ) : ?>
				<div style="display:flex;align-items:baseline;gap:8px;"><span style="font-family:var(--font-display);font-weight:700;font-size:26px;color:<?php echo esc_attr( $st[2] ); ?>;letter-spacing:-0.02em;"><?php echo esc_html( $st[0] ); ?></span><span style="font-size:14px;color:var(--navy-300);"><?php echo esc_html( $st[1] ); ?></span></div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- GRID -->
<section style="background:var(--surface-page);">
	<div style="max-width:1240px;margin:0 auto;padding:clamp(52px,6vw,84px) clamp(20px,5vw,32px);">
		<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:24px;">
			<?php
			$delay = 0;
			foreach ( $works as $w ) :
				$is_study = ( 'study' === $w['tag_style'] );
				$tag_bg   = $is_study ? 'rgba(29,110,255,0.85)' : 'rgba(255,255,255,0.14)';
				$tag_col  = '#fff';
				$cta      = $is_study ? "Lire l'étude" : 'Voir le projet';
				?>
				<a href="<?php echo esc_url( $w['href'] ); ?>" class="sdi-reveal sdi-card" data-reveal data-reveal-delay="<?php echo esc_attr( $delay ); ?>" style="display:block;border-radius:var(--radius-2xl);overflow:hidden;background:var(--surface-card);border:1px solid var(--border-subtle);box-shadow:var(--shadow-md);">
					<div style="height:190px;background:<?php echo esc_attr( $w['cover'] ); ?>;position:relative;display:flex;align-items:flex-end;padding:18px;">
						<div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(9,14,27,0.8),rgba(9,14,27,0.1) 58%,transparent);"></div>
						<span style="position:relative;font-family:var(--font-display);font-weight:700;font-size:21px;color:#fff;letter-spacing:0.02em;text-shadow:0 1px 10px rgba(0,0,0,0.45);"><?php echo esc_html( $w['client'] ); ?></span>
						<span style="position:absolute;top:14px;right:14px;font-family:var(--font-mono);font-size:10px;letter-spacing:0.1em;text-transform:uppercase;font-weight:600;padding:5px 10px;border-radius:999px;background:<?php echo esc_attr( $tag_bg ); ?>;color:<?php echo esc_attr( $tag_col ); ?>;backdrop-filter:blur(6px);"><?php echo esc_html( $w['tag_label'] ); ?></span>
					</div>
					<div style="padding:24px;">
						<span style="font-family:var(--font-mono);font-size:10px;letter-spacing:0.1em;text-transform:uppercase;color:var(--text-muted);"><?php echo esc_html( $w['sector'] ); ?></span>
						<h2 style="margin-top:10px;font-family:var(--font-display);font-weight:600;font-size:19px;color:var(--text-strong);line-height:1.25;"><?php echo esc_html( $w['title'] ); ?></h2>
						<div style="margin-top:18px;padding-top:16px;border-top:1px solid var(--navy-100);display:flex;align-items:center;justify-content:space-between;">
							<div style="display:flex;align-items:baseline;gap:8px;"><span style="font-family:var(--font-display);font-weight:700;font-size:26px;color:var(--brand-primary);letter-spacing:-0.02em;"><?php echo esc_html( $w['metric'] ); ?></span><span style="font-size:13px;color:var(--text-muted);"><?php echo esc_html( $w['metric_label'] ); ?></span></div>
							<span style="display:inline-flex;align-items:center;gap:5px;font-size:13px;font-weight:600;color:var(--brand-primary);"><?php echo esc_html( $cta ); ?> <?php sdi_the_icon( 'arrow-right', 15 ); ?></span>
						</div>
					</div>
				</a>
			<?php $delay += 60; endforeach; ?>
		</div>
	</div>
</section>

<!-- CTA -->
<section style="position:relative;background:var(--navy-950);color:var(--white);overflow:hidden;">
	<div style="position:absolute;top:-20%;left:50%;transform:translateX(-50%);width:900px;height:560px;background:radial-gradient(ellipse at center,rgba(29,110,255,0.18) 0%,rgba(29,110,255,0) 64%);pointer-events:none;"></div>
	<div style="position:relative;max-width:1240px;margin:0 auto;padding:clamp(52px,6vw,84px) clamp(20px,5vw,32px);text-align:center;">
		<h2 class="sdi-reveal" data-reveal style="font-family:var(--font-display);font-weight:700;font-size:clamp(28px,3.8vw,46px);line-height:1.06;letter-spacing:-0.025em;color:#fff;text-wrap:balance;max-width:720px;margin:0 auto;">Votre projet mérite la même exigence.</h2>
		<p class="sdi-reveal" data-reveal data-reveal-delay="80" style="margin:16px auto 0;max-width:520px;font-size:17px;line-height:1.6;color:var(--navy-300);">Racontez-nous votre besoin : on vous propose une direction claire et chiffrée.</p>
		<div class="sdi-reveal" data-reveal data-reveal-delay="160" style="margin-top:28px;display:flex;justify-content:center;gap:14px;flex-wrap:wrap;">
			<?php echo sdi_button( array( 'label' => 'Nous contacter', 'variant' => 'primary', 'size' => 'lg', 'href' => sdi_page_url( 'contact' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	</div>
</section>

<?php
get_footer();
