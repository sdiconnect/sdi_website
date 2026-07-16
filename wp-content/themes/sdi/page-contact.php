<?php
/**
 * Template for the Contact page (slug: contact).
 *
 * @package SDi
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

sdi_set_seo(
	array(
		'title'       => 'Nous contacter — Agence web & IA à Dijon et Paris | SDi',
		'description' => "Contactez SDi pour votre projet de site internet, e-commerce, SEO, application SaaS ou agent IA. Siège au 60 rue François 1er, 75008 Paris — intervention à Dijon, en Côte-d'Or et partout en France. Réponse sous 24h.",
		'keywords'    => 'contact agence web Dijon, contact agence IA Paris, devis site internet, SDi contact',
		'breadcrumb'  => array( 'Accueil' => home_url( '/' ), 'Contact' => get_permalink() ),
	)
);

get_header();
?>

<section id="contact" style="position:relative;background:var(--navy-950);color:var(--white);overflow:hidden;scroll-margin-top:80px;">
	<div style="position:absolute;top:-20%;left:50%;transform:translateX(-50%);width:1000px;height:640px;background:radial-gradient(ellipse at center,rgba(29,110,255,0.2) 0%,rgba(29,110,255,0) 62%);pointer-events:none;"></div>
	<div style="position:absolute;bottom:-15%;right:0;width:520px;height:520px;background:radial-gradient(circle at center,rgba(218,38,134,0.1) 0%,rgba(218,38,134,0) 68%);pointer-events:none;"></div>
	<div style="position:relative;max-width:1240px;margin:0 auto;padding:clamp(44px,6vw,80px) clamp(20px,5vw,32px) clamp(56px,7vw,96px);">
		<?php echo sdi_breadcrumb( array( array( 'label' => 'Accueil', 'href' => home_url( '/' ) ), array( 'label' => 'Contact' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		<div data-split style="margin-top:24px;display:grid;grid-template-columns:1fr 1fr;gap:clamp(36px,5vw,64px);align-items:start;">
			<div>
				<div class="sdi-reveal sdi-eyebrow" data-reveal style="color:var(--cyan-400);font-weight:500;">// Parlons de votre projet</div>
				<h1 class="sdi-reveal" data-reveal data-reveal-delay="80" style="margin-top:18px;font-family:var(--font-display);font-weight:700;font-size:clamp(32px,4.4vw,54px);line-height:1.04;letter-spacing:-0.03em;color:#fff;text-wrap:balance;">Discutons de votre projet digital.</h1>
				<p class="sdi-reveal" data-reveal data-reveal-delay="160" style="margin-top:18px;font-size:clamp(16px,1.3vw,19px);line-height:1.6;color:var(--navy-300);max-width:460px;">Site, SEO, application ou agent IA : dites-nous où vous en êtes, on vous répond sous 24h avec une première direction.</p>
				<div class="sdi-reveal" data-reveal data-reveal-delay="220" style="margin-top:32px;display:flex;flex-direction:column;gap:16px;">
					<a href="tel:+33980806296" style="display:inline-flex;align-items:center;gap:12px;color:#fff;font-size:17px;font-weight:600;"><span style="width:44px;height:44px;border-radius:12px;background:rgba(255,255,255,0.06);border:1px solid var(--border-inverse);display:flex;align-items:center;justify-content:center;color:var(--cyan-400);"><?php sdi_the_icon( 'phone', 19 ); ?></span>09 80 80 62 96</a>
					<a href="mailto:contact@sdi-connect.com" style="display:inline-flex;align-items:center;gap:12px;color:#fff;font-size:17px;font-weight:600;"><span style="width:44px;height:44px;border-radius:12px;background:rgba(255,255,255,0.06);border:1px solid var(--border-inverse);display:flex;align-items:center;justify-content:center;color:var(--cyan-400);"><?php sdi_the_icon( 'mail', 19 ); ?></span>contact@sdi-connect.com</a>
					<div style="display:inline-flex;align-items:flex-start;gap:12px;color:#fff;font-size:16px;font-weight:500;"><span style="width:44px;height:44px;flex-shrink:0;border-radius:12px;background:rgba(255,255,255,0.06);border:1px solid var(--border-inverse);display:flex;align-items:center;justify-content:center;color:var(--cyan-400);"><?php sdi_the_icon( 'map-pin', 19 ); ?></span><span>Siège : 60 rue François 1er, 75008 Paris<br><span style="color:var(--navy-300);font-weight:400;font-size:14px;">Intervention à Dijon, en Côte-d'Or &amp; partout en France</span></span></div>
				</div>
			</div>
			<div class="sdi-reveal" data-reveal data-reveal-delay="120">
				<div style="background:var(--white);border-radius:var(--radius-3xl);padding:clamp(24px,3vw,36px);box-shadow:0 40px 90px rgba(7,11,22,0.5);">
					<?php sdi_contact_form( 'Écrivez-nous' ); ?>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
