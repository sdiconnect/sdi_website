<?php
/**
 * Template for the Agents IA page (slug: agents-ia).
 *
 * @package SDi
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

sdi_set_seo(
	array(
		'title'       => 'Agents IA sur-mesure & automatisation | Agence IA Dijon & Paris — SDi',
		'description' => "SDi conçoit des agents IA sur-mesure pour les entreprises : chatbots, automatisation des process, support client 24/7 et analyse de données. Une agence IA à Dijon et Paris qui déploie l'intelligence artificielle sur vos propres outils.",
		'keywords'    => 'agents IA, agence IA Dijon, chatbot entreprise, automatisation IA, intelligence artificielle entreprise, IA Paris',
		'breadcrumb'  => array( 'Accueil' => home_url( '/' ), 'Agents IA' => get_permalink() ),
	)
);

$caps = array(
	array( 'headphones', 'Support client 24/7', 'Répond, qualifie et oriente vos prospects, jour et nuit, dans votre ton.', 0 ),
	array( 'workflow', 'Automatisation', 'Élimine les tâches répétitives en se branchant sur vos outils existants.', 60 ),
	array( 'chart-column', 'Analyse de données', 'Transforme vos données en décisions claires et actionnables.', 120 ),
	array( 'pen-tool', 'Génération de contenu', 'Produit devis, e-mails et contenus en quelques secondes.', 180 ),
);

$use_cases = array(
	array( 'store', 'Commerce & retail', 'Conseil produit, disponibilité, prise de commande automatisée.', 0 ),
	array( 'grape', 'Domaines viticoles', 'Réponses œnotourisme, réservations et ventes en ligne.', 60 ),
	array( 'factory', 'Industrie & PME', 'Support technique interne et automatisation documentaire.', 120 ),
	array( 'building-2', 'Immobilier', 'Qualification des acquéreurs et préparation des visites.', 160 ),
	array( 'landmark', 'Institutions', 'Renseignement citoyen et orientation vers les bons services.', 200 ),
	array( 'briefcase', 'Services B2B', 'Qualification de leads et prise de rendez-vous commerciale.', 240 ),
);

$steps = array(
	array( '01', 'Cadrage', "On identifie le cas d'usage à plus fort impact.", 0 ),
	array( '02', 'Connexion', "On branche l'agent sur vos données et vos outils.", 60 ),
	array( '03', 'Entraînement', "On l'ajuste à votre ton et à vos règles métier.", 120 ),
	array( '04', 'Mise en production', 'On déploie, on mesure et on améliore en continu.', 180 ),
);

get_header();
?>

<!-- HERO -->
<section style="position:relative;background:var(--navy-950);color:var(--white);overflow:hidden;">
	<div style="position:absolute;top:-15%;left:50%;transform:translateX(-50%);width:1000px;height:640px;background:radial-gradient(ellipse at center,rgba(0,165,228,0.2) 0%,rgba(0,165,228,0) 62%);pointer-events:none;"></div>
	<div style="position:absolute;bottom:-10%;right:-5%;width:520px;height:520px;background:radial-gradient(circle at center,rgba(29,110,255,0.16) 0%,rgba(29,110,255,0) 68%);pointer-events:none;"></div>
	<div style="position:relative;max-width:1240px;margin:0 auto;padding:clamp(44px,6vw,80px) clamp(20px,5vw,32px) clamp(52px,6vw,88px);">
		<?php echo sdi_breadcrumb( array( array( 'label' => 'Accueil', 'href' => home_url( '/' ) ), array( 'label' => 'Agents IA' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<div data-split style="margin-top:22px;display:grid;grid-template-columns:1.05fr 0.95fr;gap:clamp(36px,5vw,64px);align-items:center;">
			<div>
				<div class="sdi-reveal sdi-eyebrow sdi-eyebrow--pill" data-reveal style="color:var(--cyan-400);font-weight:500;background:rgba(0,165,228,0.08);"><span style="width:6px;height:6px;border-radius:50%;background:var(--cyan-400);"></span>// Branche IA</div>
				<h1 class="sdi-reveal" data-reveal data-reveal-delay="80" style="margin-top:20px;font-family:var(--font-display);font-weight:700;font-size:clamp(34px,5vw,60px);line-height:1.04;letter-spacing:-0.03em;color:#fff;text-wrap:balance;">Des agents IA qui travaillent pour vous, en continu.</h1>
				<p class="sdi-reveal" data-reveal data-reveal-delay="160" style="margin-top:22px;font-size:clamp(17px,1.4vw,20px);line-height:1.6;color:var(--navy-300);max-width:540px;">Nous concevons des assistants intelligents, connectés à vos outils, pour automatiser vos tâches, qualifier vos leads et répondre à vos clients. Pas de la théorie : nous les utilisons d'abord sur nos propres process.</p>
				<div class="sdi-reveal" data-reveal data-reveal-delay="240" style="margin-top:32px;display:flex;flex-wrap:wrap;gap:14px;">
					<a href="<?php echo esc_url( sdi_page_url( 'contact' ) ); ?>" class="sdi-btn-gradient">Parler de mon projet IA <?php sdi_the_icon( 'arrow-right', 18 ); ?></a>
				</div>
			</div>
			<!-- chat mock -->
			<div class="sdi-reveal" data-reveal data-reveal-delay="200" style="border-radius:24px;border:1px solid var(--border-inverse);background:var(--glass-bg-dark);backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);overflow:hidden;box-shadow:0 40px 90px rgba(7,11,22,0.55);">
				<div style="padding:16px 18px;display:flex;align-items:center;gap:12px;border-bottom:1px solid var(--border-inverse);">
					<span style="width:38px;height:38px;border-radius:11px;background:var(--gradient-brand);display:flex;align-items:center;justify-content:center;color:#fff;"><?php sdi_the_icon( 'sparkles', 19 ); ?></span>
					<div><div style="color:#fff;font-weight:600;font-size:14px;">Assistant SDi</div><div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--navy-400);"><span style="width:7px;height:7px;border-radius:50%;background:var(--green-400);"></span>En ligne</div></div>
				</div>
				<div style="padding:20px;display:flex;flex-direction:column;gap:12px;">
					<div style="align-self:flex-start;max-width:84%;background:rgba(255,255,255,0.06);border:1px solid var(--border-inverse);border-radius:14px 14px 14px 4px;padding:11px 14px;font-size:14px;line-height:1.5;color:var(--navy-200);">Bonjour ! Je qualifie vos demandes et je prends rendez-vous 24h/24. Que puis-je faire pour vous ?</div>
					<div style="align-self:flex-end;max-width:84%;background:var(--brand-primary);color:#fff;border-radius:14px 14px 4px 14px;padding:11px 14px;font-size:14px;line-height:1.5;">Je veux automatiser mes devis.</div>
					<div style="align-self:flex-start;display:flex;gap:5px;padding:11px 14px;background:rgba(255,255,255,0.06);border:1px solid var(--border-inverse);border-radius:14px;width:fit-content;">
						<span style="width:7px;height:7px;border-radius:50%;background:var(--navy-400);animation:sdiType 1.2s infinite;"></span>
						<span style="width:7px;height:7px;border-radius:50%;background:var(--navy-400);animation:sdiType 1.2s .2s infinite;"></span>
						<span style="width:7px;height:7px;border-radius:50%;background:var(--navy-400);animation:sdiType 1.2s .4s infinite;"></span>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- CAPACITÉS -->
<section style="background:var(--surface-page);">
	<div style="max-width:1240px;margin:0 auto;padding:clamp(52px,6vw,84px) clamp(20px,5vw,32px);">
		<div class="sdi-reveal" data-reveal style="max-width:700px;">
			<span class="sdi-eyebrow" style="color:var(--brand-cyan);">// Ce que fait un agent IA</span>
			<h2 class="sdi-h2" style="margin-top:16px;font-size:clamp(28px,3.8vw,46px);">Quatre façons de gagner du temps, dès maintenant.</h2>
		</div>
		<div style="margin-top:32px;display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:20px;">
			<?php foreach ( $caps as $c ) : ?>
				<div class="sdi-reveal" data-reveal data-reveal-delay="<?php echo esc_attr( $c[3] ); ?>" style="background:var(--surface-card);border:1px solid var(--border-subtle);border-radius:var(--radius-2xl);box-shadow:var(--shadow-md);padding:26px;">
					<div style="width:50px;height:50px;border-radius:14px;background:rgba(0,165,228,0.1);color:var(--cyan-500);display:flex;align-items:center;justify-content:center;"><?php sdi_the_icon( $c[0], 24 ); ?></div>
					<h3 style="margin-top:16px;font-family:var(--font-display);font-weight:600;font-size:19px;color:var(--text-strong);letter-spacing:-0.01em;"><?php echo esc_html( $c[1] ); ?></h3>
					<p style="margin-top:9px;font-size:15px;line-height:1.55;color:var(--text-muted);"><?php echo esc_html( $c[2] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- CAS D'USAGE -->
<section style="background:var(--white);border-top:1px solid var(--border-subtle);">
	<div style="max-width:1240px;margin:0 auto;padding:clamp(52px,6vw,84px) clamp(20px,5vw,32px);">
		<div class="sdi-reveal" data-reveal style="max-width:700px;">
			<span class="sdi-eyebrow" style="color:var(--brand-primary);">// Cas d'usage</span>
			<h2 class="sdi-h2" style="margin-top:16px;font-size:clamp(28px,3.8vw,46px);">Un agent adapté à votre métier.</h2>
		</div>
		<div style="margin-top:32px;display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px;">
			<?php foreach ( $use_cases as $u ) : ?>
				<div class="sdi-reveal" data-reveal data-reveal-delay="<?php echo esc_attr( $u[3] ); ?>" style="display:flex;align-items:flex-start;gap:14px;padding:22px;border-radius:var(--radius-xl);border:1px solid var(--border-subtle);background:var(--surface-page);">
					<span style="width:42px;height:42px;flex-shrink:0;border-radius:12px;background:var(--blue-50);color:var(--brand-primary);display:flex;align-items:center;justify-content:center;"><?php sdi_the_icon( $u[0], 20 ); ?></span>
					<div><h3 style="font-size:16px;font-weight:600;color:var(--text-strong);"><?php echo esc_html( $u[1] ); ?></h3><p style="margin-top:5px;font-size:14px;line-height:1.5;color:var(--text-muted);"><?php echo esc_html( $u[2] ); ?></p></div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- PROCESS -->
<section style="position:relative;background:var(--navy-950);color:var(--white);overflow:hidden;">
	<div style="position:absolute;top:-20%;right:-5%;width:700px;height:520px;background:radial-gradient(ellipse at center,rgba(0,165,228,0.15) 0%,rgba(0,165,228,0) 65%);pointer-events:none;"></div>
	<div style="position:relative;max-width:1240px;margin:0 auto;padding:clamp(52px,6vw,84px) clamp(20px,5vw,32px);">
		<div class="sdi-reveal" data-reveal style="max-width:700px;">
			<span class="sdi-eyebrow" style="color:var(--cyan-400);">// Comment on procède</span>
			<h2 class="sdi-h2 sdi-h2--dark" style="margin-top:16px;font-size:clamp(28px,3.8vw,46px);">De l'idée à l'agent en production.</h2>
		</div>
		<div style="margin-top:34px;display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:18px;">
			<?php foreach ( $steps as $st ) : ?>
				<div class="sdi-reveal" data-reveal data-reveal-delay="<?php echo esc_attr( $st[3] ); ?>" style="border:1px solid var(--border-inverse);border-radius:var(--radius-xl);padding:24px;background:linear-gradient(155deg,rgba(255,255,255,0.05),rgba(255,255,255,0.01));">
					<span style="font-family:var(--font-mono);font-weight:700;font-size:14px;color:var(--cyan-400);letter-spacing:0.06em;"><?php echo esc_html( $st[0] ); ?></span>
					<h3 style="margin-top:12px;font-size:17px;font-weight:600;color:#fff;"><?php echo esc_html( $st[1] ); ?></h3>
					<p style="margin-top:7px;font-size:14px;line-height:1.5;color:var(--navy-300);"><?php echo esc_html( $st[2] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php
sdi_cta_split(
	"Curieux de ce que l'IA peut faire chez vous ?",
	'On identifie ensemble une première automatisation à fort impact, et on vous en montre la valeur.',
	array( 'secondary' => array( 'type' => 'link', 'label' => 'Voir les réalisations', 'href' => sdi_page_url( 'realisations' ) ) )
);
get_footer();
