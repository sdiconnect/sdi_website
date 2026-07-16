<?php
/**
 * Template for the Services page (slug: services).
 *
 * @package SDi
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

sdi_set_seo(
	array(
		'title'       => 'Services — Création de site, SEO, SaaS & IA | Agence web Dijon & Paris — SDi',
		'description' => 'SDi, agence web & IA à Dijon et Paris : création de site internet, e-commerce, référencement SEO, Google Ads, applications SaaS sur-mesure, agents IA et hébergement. Un partenaire unique pour toute votre présence digitale.',
		'keywords'    => 'agence web Dijon, création site internet Dijon, référencement SEO Dijon, agence web Paris, développement SaaS, agents IA, Google Ads',
		'breadcrumb'  => array( 'Accueil' => home_url( '/' ), 'Services' => get_permalink() ),
	)
);

$tile_blue = array( 'iconBg' => 'var(--blue-50)', 'iconColor' => 'var(--brand-primary)' );

$groups = array(
	array(
		'eyebrow' => '// Web & e-commerce', 'accent' => 'var(--brand-primary)', 'title' => 'Des sites qui inspirent confiance et convertissent.', 'mt' => '0px',
		'services' => array(
			array_merge( array( 'icon' => 'layout-template', 'title' => 'Création de site internet', 'desc' => 'Sites vitrines sur-mesure, rapides et pensés pour transformer vos visiteurs en clients.', 'points' => array( 'Design sur-mesure, zéro template', 'Optimisé mobile & Core Web Vitals', 'CMS pour gérer vos contenus' ) ), $tile_blue ),
			array_merge( array( 'icon' => 'shopping-bag', 'title' => 'Site e-commerce', 'desc' => 'Boutiques en ligne performantes, du catalogue au paiement, prêtes à vendre.', 'points' => array( 'Tunnel de commande optimisé', 'Paiement sécurisé & multi-livraison', 'Gestion catalogue autonome' ) ), $tile_blue ),
		),
	),
	array(
		'eyebrow' => '// Visibilité', 'accent' => 'var(--brand-cyan)', 'title' => 'Être trouvé, à Dijon comme partout en France.', 'mt' => 'clamp(44px,5vw,64px)',
		'services' => array(
			array( 'icon' => 'search', 'title' => 'Référencement SEO', 'desc' => 'Une visibilité durable sur Google, avec une forte priorité au SEO local dijonnais.', 'points' => array( 'Audit technique & sémantique', "SEO local Dijon & Côte-d'Or", 'Contenu & netlinking' ), 'iconBg' => 'rgba(0,165,228,0.12)', 'iconColor' => 'var(--cyan-500)' ),
			array( 'icon' => 'target', 'title' => 'Google Ads & webmarketing', 'desc' => 'Des campagnes rentables, pilotées à la donnée, pour générer des leads qualifiés.', 'points' => array( 'Search, Display & Shopping', 'Suivi des conversions', 'Reporting clair chaque mois' ), 'iconBg' => 'rgba(0,165,228,0.12)', 'iconColor' => 'var(--cyan-500)' ),
		),
	),
	array(
		'eyebrow' => '// Produit & IA', 'accent' => 'var(--brand-primary)', 'title' => 'Vos outils métier et vos agents IA, conçus et pilotés de bout en bout.', 'mt' => 'clamp(44px,5vw,64px)',
		'services' => array(
			array( 'icon' => 'boxes', 'title' => 'SaaS & applications sur-mesure', 'desc' => 'Nous développons vos plateformes métier, de la conception au déploiement.', 'points' => array( 'Cadrage produit & UX', 'Développement full-stack', 'Maintenance & évolutions' ), 'iconBg' => 'var(--gradient-brand)', 'iconColor' => '#fff' ),
			array( 'icon' => 'bot', 'title' => 'Agents IA sur-mesure', 'desc' => 'Des assistants intelligents connectés à vos outils, qui automatisent votre quotidien.', 'points' => array( 'Chatbots & support 24/7', 'Automatisation des process', 'Connexion à vos données' ), 'iconBg' => 'linear-gradient(135deg,var(--cyan-500),var(--blue-500))', 'iconColor' => '#fff' ),
		),
	),
	array(
		'eyebrow' => '// Support & marque', 'accent' => 'var(--brand-primary)', 'title' => 'Une présence fiable et cohérente dans la durée.', 'mt' => 'clamp(44px,5vw,64px)',
		'services' => array(
			array_merge( array( 'icon' => 'server', 'title' => 'Hébergement & infogérance', 'desc' => 'Infrastructure sécurisée, supervisée et maintenue en continu par nos équipes.', 'points' => array( 'Hébergement haute dispo', 'Sauvegardes & sécurité', 'Supervision proactive' ) ), $tile_blue ),
			array_merge( array( 'icon' => 'palette', 'title' => 'Communication & branding', 'desc' => 'Identité de marque, direction artistique et supports qui vous démarquent.', 'points' => array( 'Logo & charte graphique', 'Direction artistique', 'Supports print & digital' ) ), $tile_blue ),
		),
	),
);

$pillars = array(
	array( 'user-check', 'Interlocuteur unique', 'Une seule équipe, du branding au déploiement.', 0 ),
	array( 'code', 'Expertise technique', 'Une maîtrise produit de la conception au déploiement.', 60 ),
	array( 'map-pin', 'Local & national', 'Proximité dijonnaise, siège à Paris, livraison partout.', 120 ),
	array( 'trending-up', 'Résultats mesurables', 'Chaque projet est cadré par des objectifs concrets.', 180 ),
);

get_header();
?>

<!-- HERO -->
<section style="position:relative;background:var(--gradient-navy);color:var(--white);overflow:hidden;">
	<div style="position:absolute;inset:0;background:var(--gradient-halo);pointer-events:none;"></div>
	<div style="position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,0.035) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.035) 1px,transparent 1px);background-size:64px 64px;mask-image:radial-gradient(ellipse 80% 70% at 50% 15%,#000 0%,transparent 75%);-webkit-mask-image:radial-gradient(ellipse 80% 70% at 50% 15%,#000 0%,transparent 75%);pointer-events:none;"></div>
	<div style="position:relative;max-width:1240px;margin:0 auto;padding:clamp(44px,6vw,80px) clamp(20px,5vw,32px) clamp(52px,6vw,88px);">
		<?php echo sdi_breadcrumb( array( array( 'label' => 'Accueil', 'href' => home_url( '/' ) ), array( 'label' => 'Services' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<div class="sdi-reveal sdi-eyebrow sdi-eyebrow--pill" data-reveal style="margin-top:22px;color:var(--cyan-400);font-weight:500;">// Nos expertises</div>
		<h1 class="sdi-reveal" data-reveal data-reveal-delay="80" style="margin-top:22px;max-width:900px;font-family:var(--font-display);font-weight:700;font-size:clamp(34px,5vw,60px);line-height:1.05;letter-spacing:-0.03em;color:var(--white);text-wrap:balance;">Tout le spectre digital, porté par une seule équipe.</h1>
		<p class="sdi-reveal" data-reveal data-reveal-delay="160" style="margin-top:22px;max-width:640px;font-size:clamp(17px,1.4vw,20px);line-height:1.6;color:var(--navy-300);">Sites internet, e-commerce, référencement, applications métier, agents IA : nous couvrons chaque étape de votre présence numérique, de la stratégie à la mise en ligne, à Dijon comme partout en France.</p>
		<div class="sdi-reveal" data-reveal data-reveal-delay="240" style="margin-top:32px;display:flex;flex-wrap:wrap;gap:14px;">
			<a href="<?php echo esc_url( sdi_page_url( 'contact' ) ); ?>" class="sdi-btn sdi-btn--primary sdi-btn--lg">Discuter de mon projet</a>
			<a href="<?php echo esc_url( sdi_page_url( 'realisations' ) ); ?>" class="sdi-ghost-dark">Voir nos réalisations</a>
		</div>
	</div>
</section>

<!-- EXPERTISES -->
<section style="background:var(--surface-page);">
	<div style="max-width:1240px;margin:0 auto;padding:clamp(52px,6vw,84px) clamp(20px,5vw,32px);">
		<?php foreach ( $groups as $g ) : ?>
			<div class="sdi-reveal" data-reveal style="margin-top:<?php echo esc_attr( $g['mt'] ); ?>;">
				<div style="display:flex;align-items:center;gap:12px;">
					<span class="sdi-eyebrow" style="color:<?php echo esc_attr( $g['accent'] ); ?>;font-weight:600;"><?php echo esc_html( $g['eyebrow'] ); ?></span>
					<span style="flex:1;height:1px;background:var(--border-subtle);"></span>
				</div>
				<h2 style="margin-top:14px;font-family:var(--font-display);font-weight:700;font-size:clamp(24px,3vw,34px);line-height:1.1;letter-spacing:-0.02em;color:var(--text-strong);"><?php echo esc_html( $g['title'] ); ?></h2>
				<div style="margin-top:26px;display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:20px;">
					<?php foreach ( $g['services'] as $s ) : ?>
						<div class="sdi-card sdi-card--sm" style="position:relative;background:var(--surface-card);border:1px solid var(--border-subtle);border-radius:var(--radius-2xl);box-shadow:var(--shadow-md);padding:28px;">
							<div style="width:52px;height:52px;border-radius:15px;background:<?php echo esc_attr( $s['iconBg'] ); ?>;color:<?php echo esc_attr( $s['iconColor'] ); ?>;display:flex;align-items:center;justify-content:center;"><?php sdi_the_icon( $s['icon'], 24 ); ?></div>
							<h3 style="margin-top:18px;font-family:var(--font-display);font-weight:600;font-size:21px;letter-spacing:-0.01em;color:var(--text-strong);"><?php echo esc_html( $s['title'] ); ?></h3>
							<p style="margin-top:9px;font-size:15px;line-height:1.55;color:var(--text-muted);"><?php echo esc_html( $s['desc'] ); ?></p>
							<div style="margin-top:18px;display:flex;flex-direction:column;gap:9px;">
								<?php foreach ( $s['points'] as $pt ) : ?>
									<div style="display:flex;align-items:flex-start;gap:9px;font-size:14px;color:var(--text-body);"><span style="margin-top:1px;color:var(--brand-primary);flex-shrink:0;display:inline-flex;"><?php sdi_the_icon( 'check', 16 ); ?></span><?php echo esc_html( $pt ); ?></div>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</section>

<!-- APPROCHE -->
<section style="position:relative;background:var(--navy-950);color:var(--white);overflow:hidden;">
	<div style="position:absolute;top:-20%;left:50%;transform:translateX(-50%);width:900px;height:560px;background:radial-gradient(ellipse at center,rgba(29,110,255,0.16) 0%,rgba(29,110,255,0) 65%);pointer-events:none;"></div>
	<div style="position:relative;max-width:1240px;margin:0 auto;padding:clamp(52px,6vw,84px) clamp(20px,5vw,32px);">
		<div class="sdi-reveal" data-reveal style="max-width:720px;">
			<span class="sdi-eyebrow" style="color:var(--cyan-400);">// Notre approche</span>
			<h2 class="sdi-h2 sdi-h2--dark" style="margin-top:16px;font-size:clamp(28px,3.8vw,46px);">Une méthode d'ingénieur, une exigence de designer.</h2>
		</div>
		<div style="margin-top:34px;display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:18px;">
			<?php foreach ( $pillars as $p ) : ?>
				<div class="sdi-reveal" data-reveal data-reveal-delay="<?php echo esc_attr( $p[3] ); ?>" style="border:1px solid var(--border-inverse);border-radius:var(--radius-xl);padding:24px;background:linear-gradient(155deg,rgba(255,255,255,0.05),rgba(255,255,255,0.01));">
					<div style="width:46px;height:46px;border-radius:13px;background:rgba(0,165,228,0.14);color:var(--cyan-400);display:flex;align-items:center;justify-content:center;"><?php sdi_the_icon( $p[0], 22 ); ?></div>
					<h3 style="margin-top:15px;font-size:17px;font-weight:600;color:#fff;"><?php echo esc_html( $p[1] ); ?></h3>
					<p style="margin-top:7px;font-size:14px;line-height:1.5;color:var(--navy-300);"><?php echo esc_html( $p[2] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php
sdi_cta_split(
	'Un projet en tête ? Parlons-en simplement.',
	'On analyse votre besoin et on vous propose une direction claire, chiffrée et sans engagement.'
);
get_footer();
