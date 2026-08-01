<?php
/**
 * Front page (Accueil).
 *
 * @package SDi
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

sdi_set_seo(
	array(
		'title'       => 'SDi — Agence web & IA à Dijon et Paris | Sites, SaaS & agents IA',
		'description' => 'SDi, agence web & IA ancrée à Dijon avec un siège à Paris : création de site internet, e-commerce, référencement SEO, applications SaaS et agents IA sur-mesure. Plus de 100 projets livrés depuis 2017, partout en France. 4,9/5 sur 109 avis.',
		'keywords'    => 'agence web Dijon, agence IA, création site internet Dijon, référencement SEO Dijon, agence web Paris, agents IA, développement SaaS',
	)
);

$client_logos = sdi_get_client_logos();

$services = array(
	array( 'icon' => 'layout-template', 'title' => 'Création de site internet', 'desc' => 'Sites vitrines sur-mesure, rapides et pensés pour convertir vos visiteurs en clients.', 'href' => sdi_page_url( 'creation-site-internet' ), 'delay' => 0, 'border' => 'var(--border-subtle)', 'iconBg' => 'var(--blue-50)', 'iconColor' => 'var(--brand-primary)' ),
	array( 'icon' => 'shopping-bag', 'title' => 'Site e-commerce', 'desc' => 'Boutiques en ligne performantes, du catalogue au paiement, prêtes à vendre.', 'href' => sdi_page_url( 'site-e-commerce' ), 'delay' => 60, 'border' => 'var(--border-subtle)', 'iconBg' => 'var(--blue-50)', 'iconColor' => 'var(--brand-primary)' ),
	array( 'icon' => 'search', 'title' => 'Référencement SEO', 'desc' => 'Une visibilité durable sur Google, avec une priorité forte au SEO local dijonnais.', 'href' => sdi_page_url( 'referencement-seo' ), 'delay' => 120, 'border' => 'var(--border-subtle)', 'iconBg' => 'var(--blue-50)', 'iconColor' => 'var(--brand-primary)' ),
	array( 'icon' => 'target', 'title' => 'Google Ads & Webmarketing', 'desc' => 'Des campagnes rentables, pilotées à la donnée, pour générer des leads qualifiés.', 'href' => sdi_page_url( 'google-ads' ), 'delay' => 0, 'border' => 'var(--border-subtle)', 'iconBg' => 'var(--blue-50)', 'iconColor' => 'var(--brand-primary)' ),
	array( 'icon' => 'boxes', 'title' => 'SaaS & applications sur-mesure', 'desc' => 'Nous développons vos outils métier et plateformes SaaS, de la conception au déploiement.', 'href' => sdi_page_url( 'saas-sur-mesure' ), 'delay' => 60, 'tag' => 'Produit', 'tagColor' => 'var(--brand-primary)', 'tagBg' => 'var(--blue-50)', 'border' => 'var(--blue-200)', 'iconBg' => 'var(--gradient-brand)', 'iconColor' => '#fff' ),
	array( 'icon' => 'bot', 'title' => 'Agents IA sur-mesure', 'desc' => 'Des assistants intelligents connectés à vos outils, qui automatisent votre quotidien.', 'href' => sdi_page_url( 'agents-ia-sur-mesure' ), 'delay' => 120, 'tag' => 'IA', 'tagColor' => 'var(--cyan-500)', 'tagBg' => 'rgba(0,165,228,0.1)', 'border' => 'rgba(0,165,228,0.4)', 'iconBg' => 'linear-gradient(135deg,var(--cyan-500),var(--blue-500))', 'iconColor' => '#fff' ),
	array( 'icon' => 'server', 'title' => 'Hébergement & infogérance', 'desc' => 'Infrastructure sécurisée, supervisée et maintenue en continu par nos équipes.', 'href' => sdi_page_url( 'services' ), 'delay' => 0, 'border' => 'var(--border-subtle)', 'iconBg' => 'var(--blue-50)', 'iconColor' => 'var(--brand-primary)' ),
	array( 'icon' => 'palette', 'title' => 'Communication & branding', 'desc' => 'Identité de marque, direction artistique et supports qui vous démarquent vraiment.', 'href' => sdi_page_url( 'services' ), 'delay' => 60, 'border' => 'var(--border-subtle)', 'iconBg' => 'var(--blue-50)', 'iconColor' => 'var(--brand-primary)' ),
);

$ia_caps = array(
	array( 'headphones', 'Support client 24/7', 'Répond aux questions, qualifie et oriente vos prospects, jour et nuit.', 0 ),
	array( 'workflow', 'Automatisation', 'Élimine les tâches répétitives en se branchant sur vos outils existants.', 60 ),
	array( 'chart-column', 'Analyse de données', 'Transforme vos données en décisions, en langage clair et actionnable.', 120 ),
	array( 'pen-tool', 'Génération de contenu', 'Produit devis, e-mails et contenus, dans votre ton, en quelques secondes.', 180 ),
);

$diffs = array(
	array( 'map-pin', 'Local & national', 'Ancrés à Dijon, à vos côtés, mais capables de livrer partout en France.', 0 ),
	array( 'code', 'Expertise produit', 'SaaS et agents IA : nous maîtrisons la technique, de la conception au déploiement.', 60 ),
	array( 'user-check', 'Interlocuteur unique', 'Du branding au déploiement, une seule équipe pilote votre projet.', 120 ),
	array( 'trending-up', 'Résultats mesurables', 'Chaque projet est cadré par des objectifs concrets et des chiffres.', 180 ),
);

$counters = array(
	array( 'to' => '100', 'dec' => '0', 'prefix' => '+', 'suffix' => '', 'display' => '+100', 'color' => 'var(--blue-400)', 'label' => 'Projets livrés', 'delay' => 0 ),
	array( 'to' => '9', 'dec' => '0', 'prefix' => '', 'suffix' => ' ans', 'display' => '9 ans', 'color' => 'var(--cyan-400)', 'label' => "D'expérience, depuis 2017", 'delay' => 80 ),
	array( 'to' => '100', 'dec' => '0', 'prefix' => '+', 'suffix' => '', 'display' => '+100', 'color' => 'var(--blue-400)', 'label' => 'Clients accompagnés', 'delay' => 160 ),
	array( 'to' => str_replace( ',', '.', sdi_rating_value() ), 'dec' => '1', 'prefix' => '', 'suffix' => '', 'display' => sdi_rating_value(), 'color' => 'var(--magenta-400)', 'label' => 'Note Google · ' . sdi_rating_count() . ' avis', 'delay' => 240 ),
);

$steps = array(
	array( '01', 'search-check', 'Écoute & audit', 'On analyse votre situation, vos objectifs et vos concurrents.', 0 ),
	array( '02', 'route', 'Stratégie', 'On définit ensemble un plan clair, priorisé et chiffré.', 60 ),
	array( '03', 'layers', 'Production', 'Design, développement et intégration par une équipe dédiée.', 120 ),
	array( '04', 'chart-line', 'Suivi & optimisation', 'On mesure, on ajuste et on fait grandir vos résultats dans la durée.', 180 ),
);

$reviews       = sdi_get_reviews();
$rating_value  = sdi_rating_value();
$rating_count  = sdi_rating_count();

$cities = array( 'Dijon', 'Beaune', 'Chénove', 'Quetigny', 'Talant', 'Dijon Métropole', 'Paris', 'Île-de-France', 'France entière' );

$works = sdi_get_realisations( 3 );

get_header();
?>

<!-- ============ 1. HERO ============ -->
<?php
$hero_img = get_template_directory_uri() . '/assets/img/hero';
$hero_shots = array(
	'tereos'   => array( 'label' => 'Tereos · SaaS industriel', 'alt' => 'Plateforme de gestion des équipements Tereos développée par SDi' ),
	'audialys' => array( 'label' => 'Audialys · SaaS IA', 'alt' => 'Application Audialys, transcription et génération de documents par IA' ),
	'orvitis'  => array( 'label' => 'Orvitis · Site & portail', 'alt' => 'Site et portail locataires Orvitis réalisé par SDi' ),
);
?>
<section class="sdi-hero">
	<div class="sdi-hero__grid" aria-hidden="true"></div>
	<div class="sdi-hero__glow" aria-hidden="true"></div>

	<div class="sdi-hero__inner">

		<div class="sdi-hero__copy">
			<p class="sdi-hero__eyebrow">Agence web &amp; IA · Paris · Dijon</p>

			<h1 class="sdi-hero__title">Sites web, SaaS et agents IA, conçus pour <em>faire grandir</em> votre entreprise.</h1>

			<p class="sdi-hero__lead">Votre agence digitale, ancrée à Dijon et pilotée depuis Paris, qui allie la proximité d'un partenaire local à la puissance technique d'un studio produit. Depuis 2017, plus de 100 projets livrés partout en France.</p>

			<div class="sdi-hero__ctas">
				<a class="sdi-hero__cta sdi-hero__cta--primary" href="#contact" data-scroll-to="#contact">Demander un devis</a>
				<a class="sdi-hero__cta sdi-hero__cta--ghost" href="#realisations">Voir nos réalisations <span class="sdi-hero__cta-arrow" aria-hidden="true">→</span></a>
			</div>

			<ul class="sdi-hero__trust">
				<li>Dijon &amp; Paris</li>
				<li>Depuis 2017</li>
				<li>+100 projets livrés</li>
				<li class="is-rating"><?php echo esc_html( sdi_rating_value() ); ?>/5 · <?php echo esc_html( sdi_rating_count() ); ?> avis Google</li>
			</ul>
		</div>

		<div class="sdi-hero__collage">
			<?php
			$i = 0;
			foreach ( $hero_shots as $slug => $shot ) :
				$eager = ( 'audialys' === $slug );
				?>
			<figure class="sdi-shot sdi-shot--<?php echo esc_attr( $slug ); ?>">
				<div class="sdi-shot__frame">
					<img
						src="<?php echo esc_url( "$hero_img/hero-$slug-1024.webp" ); ?>"
						srcset="<?php echo esc_url( "$hero_img/hero-$slug-512.webp" ); ?> 512w, <?php echo esc_url( "$hero_img/hero-$slug-1024.webp" ); ?> 1024w"
						sizes="(max-width: 640px) 60vw, 430px"
						width="1024" height="683"
						alt="<?php echo esc_attr( $shot['alt'] ); ?>"
						loading="eager" decoding="async"<?php echo $eager ? ' fetchpriority="high"' : ''; ?> />
					<span class="sdi-shot__veil" aria-hidden="true"></span>
					<figcaption class="sdi-shot__label"><?php echo esc_html( $shot['label'] ); ?></figcaption>
				</div>
			</figure>
			<?php $i++; endforeach; ?>

			<div class="sdi-badge sdi-badge--stat">
				<span class="sdi-badge__k">Leads / mois</span>
				<span class="sdi-badge__v">+38<span>%</span></span>
			</div>

			<div class="sdi-badge sdi-badge--rating">
				<span class="sdi-badge__star" aria-hidden="true">★</span>
				<span class="sdi-badge__txt">
					<strong><?php echo esc_html( sdi_rating_value() ); ?>/5</strong>
					<small><?php echo esc_html( sdi_rating_count() ); ?> avis Google</small>
				</span>
			</div>

			<div class="sdi-hero__collage-glow" aria-hidden="true"></div>
		</div>

	</div>
</section>

<!-- ============ 2. LOGOS CLIENTS (marquee pleine largeur) ============ -->
<?php if ( ! empty( $client_logos ) ) : ?>
<section style="background:var(--navy-950);border-top:1px solid rgba(255,255,255,0.06);border-bottom:1px solid rgba(255,255,255,0.06);overflow:hidden;">
	<div style="padding:clamp(34px,4vw,48px) 0;">
		<p style="text-align:center;font-family:var(--font-mono);text-transform:uppercase;letter-spacing:var(--tracking-caps);font-size:12px;color:var(--navy-400);font-weight:500;padding:0 24px;">Ils nous font confiance, du local au national</p>
		<div data-marquee style="margin-top:28px;position:relative;width:100%;-webkit-mask-image:linear-gradient(90deg,transparent,#000 6%,#000 94%,transparent);mask-image:linear-gradient(90deg,transparent,#000 6%,#000 94%,transparent);">
			<div data-marquee-track style="display:flex;width:max-content;align-items:center;">
				<?php
				for ( $dup = 0; $dup < 2; $dup++ ) {
					foreach ( $client_logos as $logo ) {
						$hidden = $dup ? ' aria-hidden="true"' : '';
						$alt    = $dup ? '' : $logo['alt'];
						echo '<span' . $hidden . ' style="flex:0 0 auto;display:inline-flex;align-items:center;justify-content:center;height:46px;padding:0 clamp(30px,3.8vw,60px);">';
						echo '<img src="' . esc_url( $logo['url'] ) . '" alt="' . esc_attr( $alt ) . '" class="sdi-client-logo" loading="lazy" style="height:100%;max-height:46px;width:auto;object-fit:contain;">';
						echo '</span>';
					}
				}
				?>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>

<!-- ============ 3. SERVICES ============ -->
<section id="services" style="background:var(--surface-page);scroll-margin-top:80px;">
	<div style="max-width:1240px;margin:0 auto;padding:clamp(50px,6vw,84px) clamp(20px,5vw,32px);">
		<div class="sdi-reveal" data-reveal style="max-width:720px;">
			<span class="sdi-eyebrow" style="color:var(--brand-primary);">// Nos expertises</span>
			<h2 class="sdi-h2" style="margin-top:16px;">Une seule équipe, tout le spectre digital.</h2>
			<p class="sdi-lead" style="margin-top:18px;">Du site vitrine à la plateforme SaaS, de la visibilité Google aux agents IA : nous couvrons chaque étape de votre présence numérique, de la stratégie à la mise en ligne.</p>
		</div>
		<div style="margin-top:36px;display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:20px;">
			<?php foreach ( $services as $s ) : ?>
				<div class="sdi-reveal sdi-card" data-reveal data-reveal-delay="<?php echo esc_attr( $s['delay'] ); ?>" style="position:relative;background:var(--surface-card);border:1px solid <?php echo esc_attr( $s['border'] ); ?>;border-radius:var(--radius-2xl);box-shadow:var(--shadow-md);padding:28px;">
					<?php if ( ! empty( $s['tag'] ) ) : ?>
						<span style="position:absolute;top:22px;right:22px;font-family:var(--font-mono);font-size:10px;letter-spacing:0.1em;text-transform:uppercase;font-weight:600;padding:5px 10px;border-radius:999px;color:<?php echo esc_attr( $s['tagColor'] ); ?>;background:<?php echo esc_attr( $s['tagBg'] ); ?>;"><?php echo esc_html( $s['tag'] ); ?></span>
					<?php endif; ?>
					<div class="sdi-tile3d<?php echo ( 'bot' === $s['icon'] ) ? ' sdi-tile3d--cyan' : ''; ?>"><?php sdi_the_icon( $s['icon'], 24 ); ?></div>
					<h3 style="margin-top:20px;font-family:var(--font-display);font-weight:600;font-size:20px;letter-spacing:-0.01em;color:var(--text-strong);"><?php echo esc_html( $s['title'] ); ?></h3>
					<p style="margin-top:9px;font-size:15px;line-height:1.55;color:var(--text-muted);"><?php echo esc_html( $s['desc'] ); ?></p>
					<a href="<?php echo esc_url( $s['href'] ); ?>" style="margin-top:16px;display:inline-flex;align-items:center;gap:6px;font-size:14px;font-weight:600;color:var(--brand-primary);">En savoir plus <?php sdi_the_icon( 'arrow-right', 15 ); ?></a>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- ============ 4. FOCUS AGENTS IA ============ -->
<section id="ia" style="position:relative;background:var(--navy-950);color:var(--white);overflow:hidden;scroll-margin-top:70px;">
	<div style="position:absolute;top:-15%;left:50%;transform:translateX(-50%);width:900px;height:600px;background:radial-gradient(ellipse at center,rgba(0,165,228,0.18) 0%,rgba(0,165,228,0) 65%);pointer-events:none;"></div>
	<div style="position:absolute;bottom:-10%;right:-5%;width:520px;height:520px;background:radial-gradient(circle at center,rgba(29,110,255,0.16) 0%,rgba(29,110,255,0) 68%);pointer-events:none;"></div>
	<div style="position:relative;max-width:1240px;margin:0 auto;padding:clamp(50px,6vw,84px) clamp(20px,5vw,32px);">
		<div data-split style="display:grid;grid-template-columns:0.95fr 1.05fr;gap:clamp(40px,5vw,72px);align-items:center;">
			<div class="sdi-reveal" data-reveal>
				<span class="sdi-eyebrow" style="display:inline-flex;align-items:center;gap:8px;color:var(--cyan-400);padding:7px 14px;border:1px solid var(--border-inverse);border-radius:999px;background:rgba(0,165,228,0.08);"><span style="width:6px;height:6px;border-radius:50%;background:var(--cyan-400);"></span>// Branche IA</span>
				<h2 class="sdi-h2 sdi-h2--dark" style="margin-top:20px;">Ce qu'un agent IA peut faire pour votre entreprise.</h2>
				<p style="margin-top:18px;font-size:18px;line-height:1.6;color:var(--navy-300);max-width:520px;">Nous concevons des agents IA sur-mesure, connectés à vos outils, qui travaillent pour vous en continu. Pas de la théorie : nous les utilisons d'abord sur nos propres process.</p>
				<div style="margin-top:32px;display:flex;flex-wrap:wrap;gap:14px;">
					<a href="<?php echo esc_url( sdi_page_url( 'agents-ia' ) ); ?>" class="sdi-btn-gradient">Découvrir nos agents IA <?php sdi_the_icon( 'arrow-right', 18 ); ?></a>
					<button type="button" class="sdi-ghost-dark sdi-ghost-dark--sm" data-sdi-bot-open><?php sdi_the_icon( 'message-circle', 18 ); ?>Tester l'assistant</button>
				</div>
			</div>
			<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
				<?php foreach ( $ia_caps as $cap ) : ?>
					<div class="sdi-reveal" data-reveal data-reveal-delay="<?php echo esc_attr( $cap[3] ); ?>" style="border:1px solid var(--border-inverse);border-radius:var(--radius-xl);padding:22px;background:linear-gradient(155deg,rgba(255,255,255,0.05),rgba(255,255,255,0.01));backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px);">
						<div class="sdi-tile3d sdi-tile3d--cyan"><?php sdi_the_icon( $cap[0], 22 ); ?></div>
						<h3 style="margin-top:16px;font-size:17px;font-weight:600;color:var(--white);"><?php echo esc_html( $cap[1] ); ?></h3>
						<p style="margin-top:7px;font-size:14px;line-height:1.5;color:var(--navy-300);"><?php echo esc_html( $cap[2] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>

<!-- ============ 5. POURQUOI SDI ============ -->
<section id="agence" style="background:var(--white);scroll-margin-top:80px;">
	<div style="max-width:1240px;margin:0 auto;padding:clamp(50px,6vw,84px) clamp(20px,5vw,32px);">
		<div class="sdi-reveal" data-reveal style="max-width:720px;">
			<span class="sdi-eyebrow" style="color:var(--brand-primary);">// Pourquoi SDi</span>
			<h2 class="sdi-h2" style="margin-top:16px;">Une méthode d'ingénieur, une exigence de designer.</h2>
		</div>
		<div style="margin-top:36px;display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:20px;">
			<?php foreach ( $diffs as $d ) : ?>
				<div class="sdi-reveal" data-reveal data-reveal-delay="<?php echo esc_attr( $d[3] ); ?>" style="padding:30px 26px;border-radius:var(--radius-2xl);background:var(--surface-page);border:1px solid var(--border-subtle);">
					<div class="sdi-tile3d"><?php sdi_the_icon( $d[0], 24 ); ?></div>
					<h3 style="margin-top:18px;font-family:var(--font-display);font-weight:600;font-size:19px;color:var(--text-strong);letter-spacing:-0.01em;"><?php echo esc_html( $d[1] ); ?></h3>
					<p style="margin-top:9px;font-size:15px;line-height:1.55;color:var(--text-muted);"><?php echo esc_html( $d[2] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- ============ 6. CHIFFRES CLÉS ============ -->
<section style="position:relative;background:var(--gradient-navy);color:var(--white);overflow:hidden;">
	<div style="position:absolute;inset:0;background:var(--gradient-halo);pointer-events:none;"></div>
	<div style="position:relative;max-width:1240px;margin:0 auto;padding:clamp(44px,5vw,68px) clamp(20px,5vw,32px);">
		<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:32px 24px;">
			<?php foreach ( $counters as $k ) : ?>
				<div class="sdi-reveal" data-reveal data-reveal-delay="<?php echo esc_attr( $k['delay'] ); ?>" style="text-align:center;">
					<div style="font-family:var(--font-display);font-weight:700;font-size:clamp(44px,5.5vw,64px);line-height:1;letter-spacing:-0.03em;color:<?php echo esc_attr( $k['color'] ); ?>;"><span data-count-to="<?php echo esc_attr( $k['to'] ); ?>" data-count-dec="<?php echo esc_attr( $k['dec'] ); ?>" data-count-prefix="<?php echo esc_attr( $k['prefix'] ); ?>" data-count-suffix="<?php echo esc_attr( $k['suffix'] ); ?>"><?php echo esc_html( $k['display'] ); ?></span></div>
					<div style="margin-top:12px;font-size:15px;color:var(--navy-300);font-weight:500;"><?php echo esc_html( $k['label'] ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- ============ 7. RÉALISATIONS ============ -->
<section id="realisations" style="background:var(--surface-page);scroll-margin-top:80px;">
	<div style="max-width:1240px;margin:0 auto;padding:clamp(50px,6vw,84px) clamp(20px,5vw,32px);">
		<div class="sdi-reveal" data-reveal style="max-width:640px;">
			<span class="sdi-eyebrow" style="color:var(--brand-primary);">// Réalisations</span>
			<h2 class="sdi-h2" style="margin-top:16px;">Des projets concrets, des résultats mesurables.</h2>
		</div>
		<div style="margin-top:44px;display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:22px;">
			<?php $wd = 0; foreach ( $works as $w ) : ?>
				<a href="<?php echo esc_url( $w['href'] ); ?>" class="sdi-reveal sdi-card" data-reveal data-reveal-delay="<?php echo esc_attr( $wd ); ?>" style="display:block;border-radius:var(--radius-2xl);overflow:hidden;background:var(--surface-card);border:1px solid var(--border-subtle);box-shadow:var(--shadow-md);">
					<div style="height:172px;background:<?php echo esc_attr( $w['cover'] ); ?>;position:relative;display:flex;align-items:flex-end;padding:18px;">
						<div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(9,14,27,0.78),rgba(9,14,27,0.08) 58%,transparent);"></div>
						<span style="position:relative;font-family:var(--font-display);font-weight:700;font-size:20px;color:#fff;letter-spacing:0.02em;text-shadow:0 1px 10px rgba(0,0,0,0.45);"><?php echo esc_html( $w['client'] ); ?></span>
					</div>
					<div style="padding:22px;">
						<span style="font-family:var(--font-mono);font-size:10px;letter-spacing:0.1em;text-transform:uppercase;color:var(--text-muted);"><?php echo esc_html( $w['sector'] ); ?></span>
						<h3 style="margin-top:10px;font-family:var(--font-display);font-weight:600;font-size:18px;color:var(--text-strong);line-height:1.25;"><?php echo esc_html( $w['title'] ); ?></h3>
						<div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--navy-100);display:flex;align-items:baseline;gap:8px;">
							<span style="font-family:var(--font-display);font-weight:700;font-size:26px;color:var(--brand-primary);letter-spacing:-0.02em;"><?php echo esc_html( $w['metric'] ); ?></span>
							<span style="font-size:13px;color:var(--text-muted);"><?php echo esc_html( $w['metric_label'] ); ?></span>
						</div>
					</div>
				</a>
			<?php $wd += 60; endforeach; ?>
		</div>
		<div class="sdi-reveal" data-reveal style="margin-top:40px;display:flex;justify-content:center;">
			<a href="<?php echo esc_url( sdi_page_url( 'realisations' ) ); ?>" class="sdi-btn sdi-btn--primary sdi-btn--lg">Voir toutes nos réalisations <?php sdi_the_icon( 'arrow-right', 18 ); ?></a>
		</div>
	</div>
</section>

<!-- ============ 8. MÉTHODE ============ -->
<section style="background:var(--white);">
	<div style="max-width:1240px;margin:0 auto;padding:clamp(50px,6vw,84px) clamp(20px,5vw,32px);">
		<div class="sdi-reveal" data-reveal style="max-width:720px;">
			<span class="sdi-eyebrow" style="color:var(--brand-primary);">// Notre méthode</span>
			<h2 class="sdi-h2" style="margin-top:16px;">Un cadre clair, du premier échange au suivi.</h2>
		</div>
		<div style="margin-top:36px;display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));gap:20px;">
			<?php foreach ( $steps as $st ) : ?>
				<div class="sdi-reveal" data-reveal data-reveal-delay="<?php echo esc_attr( $st[4] ); ?>" style="position:relative;padding:26px 22px;border-radius:var(--radius-2xl);border:1px solid var(--border-subtle);background:var(--surface-page);">
					<div style="display:flex;align-items:center;justify-content:space-between;">
						<div class="sdi-tile3d"><?php sdi_the_icon( $st[1], 24 ); ?></div>
						<span style="font-family:var(--font-mono);font-weight:700;font-size:14px;color:var(--navy-300);letter-spacing:0.06em;"><?php echo esc_html( $st[0] ); ?></span>
					</div>
					<h3 style="margin-top:16px;font-family:var(--font-display);font-weight:600;font-size:19px;color:var(--text-strong);letter-spacing:-0.01em;"><?php echo esc_html( $st[2] ); ?></h3>
					<p style="margin-top:9px;font-size:14px;line-height:1.55;color:var(--text-muted);"><?php echo esc_html( $st[3] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- ============ 9. TÉMOIGNAGES ============ -->
<section style="background:var(--surface-page);">
	<div style="max-width:1240px;margin:0 auto;padding:clamp(50px,6vw,84px) clamp(20px,5vw,32px);">
		<div class="sdi-reveal" data-reveal style="display:flex;flex-wrap:wrap;align-items:center;gap:16px;justify-content:space-between;">
			<div>
				<span class="sdi-eyebrow" style="color:var(--brand-primary);">// Avis clients</span>
				<h2 class="sdi-h2" style="margin-top:16px;">Ce que disent nos clients.</h2>
			</div>
			<div style="display:flex;align-items:center;gap:12px;padding:14px 20px;border-radius:var(--radius-xl);background:var(--white);border:1px solid var(--border-subtle);box-shadow:var(--shadow-sm);">
				<div style="font-family:var(--font-display);font-weight:700;font-size:34px;color:var(--text-strong);line-height:1;"><?php echo esc_html( $rating_value ); ?></div>
				<div><?php echo sdi_stars( 5, 15 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><div style="font-size:12px;color:var(--text-muted);margin-top:3px;"><?php echo esc_html( $rating_count ); ?> avis Google</div></div>
			</div>
		</div>
		<div style="margin-top:44px;display:grid;grid-template-columns:repeat(auto-fit,minmax(290px,1fr));gap:22px;">
			<?php foreach ( $reviews as $r ) : ?>
				<div class="sdi-reveal" data-reveal data-reveal-delay="<?php echo esc_attr( $r['delay'] ); ?>" style="padding:30px 28px;border-radius:var(--radius-2xl);background:var(--white);border:1px solid var(--border-subtle);box-shadow:var(--shadow-md);display:flex;flex-direction:column;">
					<?php echo sdi_stars( $r['rating'], 17 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<p style="margin-top:18px;font-size:16px;line-height:1.6;color:var(--text-body);flex:1;"><?php echo esc_html( $r['quote'] ); ?></p>
					<div style="margin-top:22px;display:flex;align-items:center;gap:12px;">
						<div style="width:44px;height:44px;border-radius:50%;background:var(--gradient-brand);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:16px;"><?php echo esc_html( $r['initials'] ); ?></div>
						<div><div style="font-weight:600;color:var(--text-strong);font-size:15px;"><?php echo esc_html( $r['name'] ); ?></div><div style="font-size:13px;color:var(--text-muted);"><?php echo esc_html( $r['meta'] ); ?></div></div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- ============ 10. ANCRAGE LOCAL SEO ============ -->
<section style="background:var(--white);border-top:1px solid var(--border-subtle);">
	<div style="max-width:1240px;margin:0 auto;padding:clamp(50px,6vw,80px) clamp(20px,5vw,32px);">
		<div data-split style="display:grid;grid-template-columns:1fr 1fr;gap:clamp(36px,5vw,64px);align-items:center;">
			<div class="sdi-reveal" data-reveal>
				<span class="sdi-eyebrow" style="display:inline-flex;align-items:center;gap:8px;color:var(--brand-cyan);"><?php sdi_the_icon( 'map-pin', 15 ); ?>// Présence locale &amp; nationale</span>
				<h2 style="margin-top:16px;font-family:var(--font-display);font-weight:700;font-size:clamp(28px,3.6vw,44px);line-height:1.08;letter-spacing:-0.025em;color:var(--text-strong);text-wrap:balance;">Ancrés à Dijon, un siège à Paris, présents partout en France.</h2>
				<p style="margin-top:16px;font-size:17px;line-height:1.6;color:var(--text-muted);max-width:500px;">Nous accompagnons de près les entreprises de Dijon et de Côte-d'Or, avec une vraie connaissance du tissu local. Depuis notre siège parisien, nous menons aussi des projets d'envergure nationale.</p>
				<div style="margin-top:24px;display:flex;flex-wrap:wrap;gap:10px;">
					<?php foreach ( $cities as $city ) : ?>
						<a href="<?php echo esc_url( sdi_page_url( 'contact' ) ); ?>" class="sdi-chip-city" style="padding:9px 16px;border-radius:999px;border:1px solid var(--border-subtle);background:var(--surface-page);font-size:14px;font-weight:500;color:var(--text-body);transition:all .18s;"><?php echo esc_html( $city ); ?></a>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="sdi-reveal" data-reveal data-reveal-delay="120" style="border-radius:var(--radius-3xl);overflow:hidden;border:1px solid var(--border-subtle);background:var(--gradient-navy);box-shadow:var(--shadow-lg);padding:36px;position:relative;min-height:280px;display:flex;flex-direction:column;justify-content:space-between;">
				<div style="position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,0.05) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.05) 1px,transparent 1px);background-size:40px 40px;mask-image:radial-gradient(ellipse at 70% 30%,#000,transparent 75%);-webkit-mask-image:radial-gradient(ellipse at 70% 30%,#000,transparent 75%);"></div>
				<div style="position:absolute;top:30%;left:62%;width:14px;height:14px;border-radius:50%;background:var(--brand-primary);box-shadow:0 0 0 6px rgba(29,110,255,0.22),0 0 26px rgba(29,110,255,0.6);"></div>
				<div style="position:absolute;top:52%;left:40%;width:14px;height:14px;border-radius:50%;background:var(--cyan-400);box-shadow:0 0 0 6px rgba(0,165,228,0.22),0 0 26px rgba(0,165,228,0.6);"></div>
				<div style="position:relative;font-family:var(--font-mono);font-size:11px;letter-spacing:0.12em;text-transform:uppercase;color:var(--navy-400);">// Paris · Dijon · France</div>
				<div style="position:relative;display:flex;flex-direction:column;gap:18px;">
					<div style="display:flex;align-items:flex-start;gap:12px;">
						<span style="margin-top:3px;width:9px;height:9px;border-radius:50%;background:var(--brand-primary);flex-shrink:0;box-shadow:0 0 12px rgba(29,110,255,0.7);"></span>
						<div><div style="font-family:var(--font-mono);font-size:10px;letter-spacing:0.12em;text-transform:uppercase;color:var(--brand-primary);font-weight:600;">Siège social</div><div style="font-family:var(--font-display);font-weight:700;font-size:24px;color:#fff;letter-spacing:-0.02em;line-height:1.1;">Paris</div><div style="margin-top:4px;font-size:14px;color:var(--navy-300);">60 rue François 1er, 75008 Paris</div></div>
					</div>
					<div style="display:flex;align-items:flex-start;gap:12px;">
						<span style="margin-top:3px;width:9px;height:9px;border-radius:50%;background:var(--cyan-400);flex-shrink:0;box-shadow:0 0 12px rgba(0,165,228,0.7);"></span>
						<div><div style="font-family:var(--font-mono);font-size:10px;letter-spacing:0.12em;text-transform:uppercase;color:var(--cyan-400);font-weight:600;">Zone d'intervention</div><div style="font-family:var(--font-display);font-weight:700;font-size:24px;color:#fff;letter-spacing:-0.02em;line-height:1.1;">Dijon &amp; Côte-d'Or</div><div style="margin-top:4px;font-size:14px;color:var(--navy-300);">Et projets menés partout en France</div></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- ============ 11. CTA / CONTACT ============ -->
<section id="contact" style="position:relative;background:var(--navy-950);color:var(--white);overflow:hidden;scroll-margin-top:70px;">
	<div style="position:absolute;top:-20%;left:50%;transform:translateX(-50%);width:1000px;height:700px;background:radial-gradient(ellipse at center,rgba(29,110,255,0.2) 0%,rgba(29,110,255,0) 62%);pointer-events:none;"></div>
	<div style="position:absolute;bottom:-15%;right:0;width:520px;height:520px;background:radial-gradient(circle at center,rgba(218,38,134,0.1) 0%,rgba(218,38,134,0) 68%);pointer-events:none;"></div>
	<div style="position:relative;max-width:1240px;margin:0 auto;padding:clamp(50px,6vw,84px) clamp(20px,5vw,32px);">
		<div data-split style="display:grid;grid-template-columns:1fr 1fr;gap:clamp(40px,5vw,72px);align-items:center;">
			<div class="sdi-reveal" data-reveal>
				<span class="sdi-eyebrow" style="color:var(--cyan-400);">// Parlons de votre projet</span>
				<h2 style="margin-top:18px;font-family:var(--font-display);font-weight:700;font-size:clamp(32px,4.4vw,56px);line-height:1.04;letter-spacing:-0.03em;color:#fff;text-wrap:balance;">Discutons de votre prochain projet.</h2>
				<p style="margin-top:18px;font-size:18px;line-height:1.6;color:var(--navy-300);max-width:460px;">Site, SEO, ou opportunité IA : on analyse votre situation et on vous dit concrètement quoi faire. Sans engagement.</p>
				<div style="margin-top:32px;display:flex;flex-direction:column;gap:16px;">
					<a href="tel:+33980806296" style="display:inline-flex;align-items:center;gap:12px;color:#fff;font-size:17px;font-weight:600;"><span style="width:44px;height:44px;border-radius:12px;background:rgba(255,255,255,0.06);border:1px solid var(--border-inverse);display:flex;align-items:center;justify-content:center;color:var(--cyan-400);"><?php sdi_the_icon( 'phone', 19 ); ?></span>09 80 80 62 96</a>
					<a href="mailto:contact@sdi-connect.com" style="display:inline-flex;align-items:center;gap:12px;color:#fff;font-size:17px;font-weight:600;"><span style="width:44px;height:44px;border-radius:12px;background:rgba(255,255,255,0.06);border:1px solid var(--border-inverse);display:flex;align-items:center;justify-content:center;color:var(--cyan-400);"><?php sdi_the_icon( 'mail', 19 ); ?></span>contact@sdi-connect.com</a>
				</div>
				<div style="margin-top:28px;display:flex;flex-wrap:wrap;gap:8px 20px;">
					<span style="display:inline-flex;align-items:center;gap:7px;font-size:14px;color:var(--navy-300);"><span style="color:var(--green-400);display:inline-flex;"><?php sdi_the_icon( 'check-circle-2', 16 ); ?></span>Réponse sous 24h</span>
					<span style="display:inline-flex;align-items:center;gap:7px;font-size:14px;color:var(--navy-300);"><span style="color:var(--green-400);display:inline-flex;"><?php sdi_the_icon( 'check-circle-2', 16 ); ?></span>Échange sans engagement</span>
				</div>
			</div>
			<div class="sdi-reveal" data-reveal data-reveal-delay="120">
				<div style="background:var(--white);border-radius:var(--radius-3xl);padding:clamp(24px,3vw,36px);box-shadow:0 40px 90px rgba(7,11,22,0.5);">
					<?php sdi_contact_form( 'Nous contacter' ); ?>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
