<?php
/**
 * Template for the Agence page (slug: agence).
 *
 * @package SDi
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

sdi_set_seo(
	array(
		'title'       => "L'agence — Studio digital & IA à Dijon et Paris | SDi",
		'description' => 'SDi est une agence digitale et IA fondée en 2017, ancrée à Dijon avec un siège à Paris. Découvrez notre méthode d\'ingénieur, nos valeurs et notre équipe qui conçoit sites, SaaS et agents IA partout en France.',
		'keywords'    => 'agence digitale Dijon, studio web Paris, agence IA, à propos SDi, équipe agence web',
		'breadcrumb'  => array( 'Accueil' => home_url( '/' ), 'Agence' => get_permalink() ),
	)
);

$counters = array(
	array( 'to' => '100', 'dec' => '0', 'prefix' => '+', 'suffix' => '', 'display' => '+100', 'color' => 'var(--brand-primary)', 'label' => 'projets livrés', 'delay' => 0 ),
	array( 'to' => '9', 'dec' => '0', 'prefix' => '', 'suffix' => ' ans', 'display' => '9 ans', 'color' => 'var(--cyan-500)', 'label' => "d'expérience, depuis 2017", 'delay' => 80 ),
	array( 'to' => '100', 'dec' => '0', 'prefix' => '+', 'suffix' => '', 'display' => '+100', 'color' => 'var(--brand-primary)', 'label' => 'clients accompagnés', 'delay' => 160 ),
	array( 'to' => '4.9', 'dec' => '1', 'prefix' => '', 'suffix' => '/5', 'display' => '4,9/5', 'color' => 'var(--magenta-500)', 'label' => '· 109 avis Google', 'delay' => 240 ),
);

$values = array(
	array( 'user-check', 'Interlocuteur unique', 'Une seule équipe pilote votre projet, du début à la fin.', 0 ),
	array( 'code', 'Maîtrise technique', 'Nous codons nos produits en interne, sans sous-traitance.', 60 ),
	array( 'heart-handshake', 'Relation durable', 'La majorité de nos clients nous restent fidèles dans le temps.', 120 ),
	array( 'sparkles', 'Innovation utile', "L'IA et la tech au service d'un résultat concret, jamais du gadget.", 180 ),
);

get_header();
?>

<!-- HERO -->
<section style="position:relative;background:var(--gradient-navy);color:var(--white);overflow:hidden;">
	<div style="position:absolute;inset:0;background:var(--gradient-halo);pointer-events:none;"></div>
	<div style="position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,0.035) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.035) 1px,transparent 1px);background-size:64px 64px;mask-image:radial-gradient(ellipse 80% 70% at 50% 15%,#000 0%,transparent 75%);-webkit-mask-image:radial-gradient(ellipse 80% 70% at 50% 15%,#000 0%,transparent 75%);pointer-events:none;"></div>
	<div style="position:relative;max-width:1240px;margin:0 auto;padding:clamp(44px,6vw,80px) clamp(20px,5vw,32px) clamp(52px,6vw,88px);">
		<?php echo sdi_breadcrumb( array( array( 'label' => 'Accueil', 'href' => home_url( '/' ) ), array( 'label' => 'Agence' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<div class="sdi-reveal sdi-eyebrow sdi-eyebrow--pill" data-reveal style="margin-top:22px;color:var(--cyan-400);font-weight:500;">// L'agence</div>
		<h1 class="sdi-reveal" data-reveal data-reveal-delay="80" style="margin-top:22px;max-width:900px;font-family:var(--font-display);font-weight:700;font-size:clamp(34px,5vw,60px);line-height:1.05;letter-spacing:-0.03em;color:#fff;text-wrap:balance;">Un studio digital &amp; IA, à taille humaine, exigeant sur les résultats.</h1>
		<p class="sdi-reveal" data-reveal data-reveal-delay="160" style="margin-top:22px;max-width:640px;font-size:clamp(17px,1.4vw,20px);line-height:1.6;color:var(--navy-300);">Depuis 2017, nous accompagnons entreprises, institutions et industriels dans leurs projets numériques. Ancrés à Dijon, siège à Paris, nous mêlons technologie, design et innovation.</p>
	</div>
</section>

<!-- STATS -->
<section style="background:var(--white);border-bottom:1px solid var(--border-subtle);">
	<div style="max-width:1240px;margin:0 auto;padding:clamp(40px,5vw,60px) clamp(20px,5vw,32px);">
		<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:28px 24px;">
			<?php foreach ( $counters as $k ) : ?>
				<div class="sdi-reveal" data-reveal data-reveal-delay="<?php echo esc_attr( $k['delay'] ); ?>" style="text-align:center;">
					<div style="font-family:var(--font-display);font-weight:700;font-size:clamp(38px,4.6vw,54px);line-height:1;letter-spacing:-0.03em;color:<?php echo esc_attr( $k['color'] ); ?>;"><span data-count-to="<?php echo esc_attr( $k['to'] ); ?>" data-count-dec="<?php echo esc_attr( $k['dec'] ); ?>" data-count-prefix="<?php echo esc_attr( $k['prefix'] ); ?>" data-count-suffix="<?php echo esc_attr( $k['suffix'] ); ?>"><?php echo esc_html( $k['display'] ); ?></span></div>
					<div style="margin-top:10px;font-size:15px;color:var(--text-muted);font-weight:500;"><?php echo esc_html( $k['label'] ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- HISTOIRE -->
<section style="background:var(--surface-page);">
	<div style="max-width:1240px;margin:0 auto;padding:clamp(52px,6vw,84px) clamp(20px,5vw,32px);">
		<div data-split-900 style="display:grid;grid-template-columns:0.42fr 0.58fr;gap:clamp(32px,4vw,56px);align-items:start;">
			<div class="sdi-reveal" data-reveal>
				<span class="sdi-eyebrow" style="color:var(--brand-primary);font-weight:600;">// Notre histoire</span>
				<h2 style="margin-top:14px;font-family:var(--font-display);font-weight:700;font-size:clamp(26px,3.2vw,40px);line-height:1.08;letter-spacing:-0.025em;color:var(--text-strong);text-wrap:balance;">Nés à Dijon, tournés vers la France entière.</h2>
			</div>
			<div class="sdi-reveal" data-reveal data-reveal-delay="80" style="display:flex;flex-direction:column;gap:16px;font-size:16px;line-height:1.65;color:var(--text-muted);">
				<p>SDi est née d'une conviction : les entreprises méritent un partenaire digital qui comprenne à la fois la technique et les enjeux business. Pas une agence de communication de plus, mais un vrai studio produit.</p>
				<p>Au fil des années, nous avons élargi notre spectre — du site vitrine aux plateformes SaaS, jusqu'aux agents IA que nous concevons aujourd'hui. Toujours en interne, toujours avec la même exigence.</p>
				<p>Aujourd'hui, notre ancrage dijonnais nous garde proches du tissu local, tandis que notre siège parisien nous permet de mener des projets d'envergure nationale.</p>
			</div>
		</div>
	</div>
</section>

<!-- VALEURS -->
<section style="background:var(--white);border-top:1px solid var(--border-subtle);">
	<div style="max-width:1240px;margin:0 auto;padding:clamp(52px,6vw,84px) clamp(20px,5vw,32px);">
		<div class="sdi-reveal" data-reveal style="max-width:700px;">
			<span class="sdi-eyebrow" style="color:var(--brand-primary);font-weight:600;">// Nos valeurs</span>
			<h2 class="sdi-h2" style="margin-top:14px;font-size:clamp(26px,3.4vw,42px);">Ce qui guide chacun de nos projets.</h2>
		</div>
		<div style="margin-top:32px;display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:20px;">
			<?php foreach ( $values as $v ) : ?>
				<div class="sdi-reveal" data-reveal data-reveal-delay="<?php echo esc_attr( $v[3] ); ?>" style="padding:28px 24px;border-radius:var(--radius-2xl);border:1px solid var(--border-subtle);background:var(--surface-page);">
					<div style="width:50px;height:50px;border-radius:14px;background:var(--blue-50);color:var(--brand-primary);display:flex;align-items:center;justify-content:center;"><?php sdi_the_icon( $v[0], 24 ); ?></div>
					<h3 style="margin-top:16px;font-family:var(--font-display);font-weight:600;font-size:19px;color:var(--text-strong);letter-spacing:-0.01em;"><?php echo esc_html( $v[1] ); ?></h3>
					<p style="margin-top:9px;font-size:15px;line-height:1.55;color:var(--text-muted);"><?php echo esc_html( $v[2] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- IMPLANTATIONS -->
<section style="position:relative;background:var(--navy-950);color:var(--white);overflow:hidden;">
	<div style="position:absolute;top:-20%;right:-5%;width:700px;height:520px;background:radial-gradient(ellipse at center,rgba(0,165,228,0.14) 0%,rgba(0,165,228,0) 65%);pointer-events:none;"></div>
	<div style="position:relative;max-width:1240px;margin:0 auto;padding:clamp(52px,6vw,84px) clamp(20px,5vw,32px);">
		<div class="sdi-reveal" data-reveal style="max-width:700px;">
			<span class="sdi-eyebrow" style="color:var(--cyan-400);">// Où nous trouver</span>
			<h2 class="sdi-h2 sdi-h2--dark" style="margin-top:16px;font-size:clamp(26px,3.4vw,42px);">Deux ancrages, une seule équipe.</h2>
		</div>
		<div style="margin-top:32px;display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:18px;">
			<div class="sdi-reveal" data-reveal style="border:1px solid var(--border-inverse);border-radius:var(--radius-2xl);padding:28px;background:linear-gradient(155deg,rgba(255,255,255,0.05),rgba(255,255,255,0.01));">
				<div style="display:flex;align-items:center;gap:10px;"><span style="width:10px;height:10px;border-radius:50%;background:var(--brand-primary);box-shadow:0 0 12px rgba(29,110,255,0.7);"></span><span style="font-family:var(--font-mono);font-size:11px;letter-spacing:0.12em;text-transform:uppercase;color:var(--brand-primary);font-weight:600;">Siège social</span></div>
				<h3 style="margin-top:12px;font-family:var(--font-display);font-weight:700;font-size:26px;color:#fff;letter-spacing:-0.02em;">Paris</h3>
				<p style="margin-top:6px;font-size:15px;color:var(--navy-300);">60 rue François 1er, 75008 Paris</p>
			</div>
			<div class="sdi-reveal" data-reveal data-reveal-delay="80" style="border:1px solid var(--border-inverse);border-radius:var(--radius-2xl);padding:28px;background:linear-gradient(155deg,rgba(255,255,255,0.05),rgba(255,255,255,0.01));">
				<div style="display:flex;align-items:center;gap:10px;"><span style="width:10px;height:10px;border-radius:50%;background:var(--cyan-400);box-shadow:0 0 12px rgba(0,165,228,0.7);"></span><span style="font-family:var(--font-mono);font-size:11px;letter-spacing:0.12em;text-transform:uppercase;color:var(--cyan-400);font-weight:600;">Zone d'intervention</span></div>
				<h3 style="margin-top:12px;font-family:var(--font-display);font-weight:700;font-size:26px;color:#fff;letter-spacing:-0.02em;">Dijon &amp; Côte-d'Or</h3>
				<p style="margin-top:6px;font-size:15px;color:var(--navy-300);">Et projets menés partout en France</p>
			</div>
		</div>
	</div>
</section>

<?php
sdi_cta_split(
	'Envie de travailler avec nous ?',
	'Parlons de votre projet, ou de rejoindre l\'équipe.',
	array( 'secondary' => array( 'type' => 'none' ) )
);
get_footer();
