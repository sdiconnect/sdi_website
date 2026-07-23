<?php
/**
 * Footer: site footer, mobile action bar, optional chatbot, closing tags.
 *
 * @package SDi
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

$sdi_logo_white = SDI_URI . '/assets/img/sdi-logo-white.png';

$sdi_footer_cols = array(
	array(
		'title' => 'Services',
		'links' => array(
			array( 'Création de site internet', sdi_page_url( 'creation-site-internet' ) ),
			array( 'Site e-commerce', sdi_page_url( 'site-e-commerce' ) ),
			array( 'Référencement SEO', sdi_page_url( 'referencement-seo' ) ),
			array( 'Google Ads', sdi_page_url( 'google-ads' ) ),
			array( 'SaaS sur-mesure', sdi_page_url( 'saas-sur-mesure' ) ),
		),
	),
	array(
		'title' => 'Agents IA & agence',
		'links' => array(
			array( 'Agents IA sur-mesure', sdi_page_url( 'agents-ia-sur-mesure' ) ),
			array( 'Automatisation IA', sdi_page_url( 'automatisation-ia' ) ),
			array( 'Réalisations', sdi_page_url( 'realisations' ) ),
			array( "L'agence", sdi_page_url( 'agence' ) ),
			array( 'Contact', sdi_page_url( 'contact' ) ),
		),
	),
	array(
		'title' => 'Zones',
		'links' => array(
			array( 'Agence web Dijon', sdi_page_url( 'agence-web-dijon' ) ),
			array( "SEO Dijon & Côte-d'Or", sdi_page_url( 'seo-dijon-cote-dor' ) ),
			array( 'Agence web Paris', sdi_page_url( 'agence-web-paris' ) ),
			array( 'SEO national', sdi_page_url( 'seo-national' ) ),
			array( 'France entière', sdi_page_url( 'france-entiere' ) ),
		),
	),
);
?>
	</main><!-- #sdi-content -->

	<footer class="sdi-footer">
		<div class="sdi-footer__inner">
			<div class="sdi-footer__cols">
				<div>
					<img src="<?php echo esc_url( $sdi_logo_white ); ?>" alt="SDi — Agence web &amp; IA" style="height:40px;width:auto;display:block;">
					<p class="sdi-footer__lede">Agence web &amp; IA ancrée à Dijon, siège à Paris. Nous concevons sites, plateformes SaaS et agents IA sur-mesure pour les entreprises, partout en France.</p>
					<div class="sdi-footer__socials">
						<a class="sdi-social" href="<?php echo esc_url( sdi_social_url( 'linkedin' ) ); ?>" aria-label="LinkedIn"><?php sdi_the_icon( 'linkedin', 18 ); ?></a>
						<a class="sdi-social" href="<?php echo esc_url( sdi_social_url( 'instagram' ) ); ?>" aria-label="Instagram"><?php sdi_the_icon( 'instagram', 18 ); ?></a>
						<a class="sdi-social" href="<?php echo esc_url( sdi_social_url( 'facebook' ) ); ?>" aria-label="Facebook"><?php sdi_the_icon( 'facebook', 18 ); ?></a>
					</div>
				</div>
				<?php foreach ( $sdi_footer_cols as $col ) : ?>
					<div>
						<h2 class="sdi-footer__coltitle"><?php echo esc_html( $col['title'] ); ?></h2>
						<div class="sdi-footer__links">
							<?php foreach ( $col['links'] as $link ) : ?>
								<a class="sdi-flink" href="<?php echo esc_url( $link[1] ); ?>"><?php echo esc_html( $link[0] ); ?></a>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="sdi-footer__legal">
				<p>© <?php echo esc_html( gmdate( 'Y' ) ); ?> Solutions Digitales Intégrées (SAS) · Siège : 60 rue François 1er, 75008 Paris · RCS Paris 827 966 946</p>
				<div class="sdi-footer__legal-links">
					<?php
					$sdi_legal = array(
						'mentions-legales' => 'Mentions légales',
						'confidentialite'  => 'Confidentialité',
					);
					foreach ( $sdi_legal as $sdi_slug => $sdi_label ) :
						$sdi_legal_page = get_page_by_path( $sdi_slug );
						$sdi_href       = $sdi_legal_page ? get_permalink( $sdi_legal_page ) : '#';
						?>
						<a href="<?php echo esc_url( $sdi_href ); ?>"><?php echo esc_html( $sdi_label ); ?></a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</footer>

	<!-- Mobile fixed action bar : un seul CTA vers l'agent IA (notre outil fort).
	     data-sdi-bot-open ouvre le chat quand l'extension SDi Agent IA est active ;
	     sinon le lien mène à la page Contact (repli gracieux). -->
	<div class="sdi-mobilebar">
		<a class="sdi-mobilebar__agent" href="<?php echo esc_url( sdi_page_url( 'contact' ) ); ?>" data-sdi-bot-open aria-label="Parler à un conseiller">
			<span class="sdi-mobilebar__agent-ic"><?php sdi_the_icon( 'sparkles', 19 ); ?></span>
			<span class="sdi-mobilebar__agent-txt">Parler à un conseiller</span>
			<span class="sdi-mobilebar__agent-dot" aria-hidden="true"></span>
		</a>
	</div>

	<?php
	/*
	 * The live AI chat widget is provided by the companion plugin « SDi Agent IA »
	 * (site-wide). The former theme demo chatbot has been removed to avoid a
	 * duplicate widget. The homepage "Tester l'assistant" button still works: the
	 * plugin binds to any [data-sdi-bot-open] trigger.
	 */
	?>
</div><!-- .sdi-root -->

<?php wp_footer(); ?>
</body>
</html>
