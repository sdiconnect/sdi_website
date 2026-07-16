<?php
/**
 * 404 template.
 *
 * @package SDi
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();
?>
<section style="position:relative;background:var(--gradient-navy);color:var(--white);overflow:hidden;">
	<div style="position:absolute;inset:0;background:var(--gradient-halo);pointer-events:none;"></div>
	<div style="position:relative;max-width:1240px;margin:0 auto;padding:clamp(64px,9vw,120px) clamp(20px,5vw,32px);text-align:center;">
		<div class="sdi-eyebrow" style="color:var(--cyan-400);">// Erreur 404</div>
		<h1 style="margin-top:16px;font-family:var(--font-display);font-weight:700;font-size:clamp(34px,5vw,60px);line-height:1.05;letter-spacing:-0.03em;color:#fff;">Cette page est introuvable.</h1>
		<p style="margin:18px auto 0;max-width:520px;font-size:18px;line-height:1.6;color:var(--navy-300);">La page que vous cherchez a peut-être été déplacée. Revenez à l'accueil ou contactez-nous.</p>
		<div style="margin-top:30px;display:flex;justify-content:center;gap:14px;flex-wrap:wrap;">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="sdi-btn sdi-btn--primary sdi-btn--lg">Retour à l'accueil</a>
			<a href="<?php echo esc_url( sdi_page_url( 'contact' ) ); ?>" class="sdi-ghost-dark">Nous contacter</a>
		</div>
	</div>
</section>
<?php
get_footer();
