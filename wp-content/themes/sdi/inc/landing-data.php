<?php
/**
 * Content + SEO config for the v1.0.1 marketing landing pages.
 *
 * Single source of truth: rendered by page.php via sdi_landing(), and used to
 * write Yoast meta + create pages on upgrade (functions.php migration) and to
 * generate the WXR import file.
 *
 * @package SDi
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Shared hero CTAs.
 *
 * @param string $primary Primary label.
 * @return array
 */
function sdi_landing_cta( $primary = 'Demander un devis' ) {
	return array(
		array( 'label' => $primary, 'href' => sdi_page_url( 'contact' ), 'style' => 'primary' ),
		array( 'label' => 'Voir nos réalisations', 'href' => sdi_page_url( 'realisations' ), 'style' => 'ghost' ),
	);
}

/**
 * Breadcrumb trail builder.
 *
 * @param string $label   Current page label.
 * @param array  $middle  Optional extra [label=>href] steps.
 * @return array
 */
function sdi_landing_crumbs( $label, $middle = array() ) {
	$trail = array( array( 'label' => 'Accueil', 'href' => home_url( '/' ) ) );
	foreach ( $middle as $l => $h ) {
		$trail[] = array( 'label' => $l, 'href' => $h );
	}
	$trail[] = array( 'label' => $label );
	return $trail;
}

/**
 * All landing pages keyed by slug.
 *
 * @return array
 */
function sdi_landing_pages() {
	$contact = sdi_page_url( 'contact' );
	$services = sdi_page_url( 'services' );

	$pages = array();

	/* ============================ SERVICES ============================ */

	$pages['creation-site-internet'] = array(
		'title'      => 'Création de site internet',
		'menu_order' => 10,
		'seo'        => array(
			'title' => 'Création de site internet à Dijon & Paris | Agence SDi',
			'desc'  => 'Création de site internet sur-mesure par SDi : design, performance et SEO pensés pour convertir. Agence web à Dijon et Paris, partout en France. Devis sous 24h.',
			'focus' => 'création de site internet',
		),
		'eyebrow'    => '// Création de site internet',
		'h1'         => 'Création de site internet sur-mesure, à Dijon et partout en France.',
		'intro'      => 'Nous concevons des sites vitrines rapides, élégants et pensés pour transformer vos visiteurs en clients. Un site à votre image, optimisé pour le mobile et pour Google, que vous pilotez ensuite en toute autonomie.',
		'crumbs'     => sdi_landing_crumbs( 'Création de site internet', array( 'Services' => $services ) ),
		'cta'        => sdi_landing_cta(),
		'sections'   => array(
			array(
				'type'    => 'features',
				'eyebrow' => '// Ce qui fait la différence',
				'title'   => 'Un site qui inspire confiance et convertit.',
				'lead'    => 'Chaque site est construit sur-mesure autour de vos objectifs business, pas d\'un template générique.',
				'items'   => array(
					array( 'icon' => 'layout-template', 'title' => 'Design sur-mesure', 'desc' => 'Une interface unique, alignée sur votre marque et vos parcours clients.', 'points' => array( 'Maquettes validées avant développement', 'Identité et charte respectées', 'Zéro thème générique' ) ),
					array( 'icon' => 'trending-up', 'title' => 'Pensé pour convertir', 'desc' => 'Structure, appels à l\'action et contenus orientés résultats.', 'points' => array( 'Parcours de conversion optimisés', 'Formulaires et prises de contact', 'Preuves & réassurance' ) ),
					array( 'icon' => 'search', 'title' => 'Optimisé pour Google', 'desc' => 'Un socle technique SEO propre, prêt à se positionner.', 'points' => array( 'Core Web Vitals & vitesse', 'Balisage et données structurées', 'SEO local Dijon inclus' ) ),
				),
			),
			array(
				'type'       => 'checklist',
				'eyebrow'    => '// Inclus dans chaque projet',
				'title'      => 'Un site complet, prêt à travailler pour vous.',
				'body'       => array( 'De la conception au lancement, nous couvrons chaque étape pour vous livrer un site fiable, autonome et évolutif.' ),
				'card_title' => 'Ce que vous obtenez',
				'points'     => array(
					'Design responsive (mobile, tablette, desktop)',
					'CMS pour gérer vos contenus sans technicien',
					'Hébergement, nom de domaine et mise en ligne',
					'Optimisation des performances et du SEO',
					'Formation à la prise en main',
					'Maintenance et évolutions à la demande',
				),
			),
			array(
				'type'    => 'steps',
				'eyebrow' => '// Notre méthode',
				'title'   => 'Du brief à la mise en ligne.',
				'items'   => array(
					array( 'title' => 'Écoute & cadrage', 'desc' => 'On analyse vos objectifs, votre marché et vos concurrents.' ),
					array( 'title' => 'Design', 'desc' => 'Maquettes sur-mesure validées avant toute ligne de code.' ),
					array( 'title' => 'Développement', 'desc' => 'Intégration, CMS, SEO technique et tests multi-supports.' ),
					array( 'title' => 'Lancement & suivi', 'desc' => 'Mise en ligne, formation, mesure et optimisation continue.' ),
				),
			),
			array(
				'type'  => 'richtext',
				'title' => 'Une agence de création de site internet à Dijon et Paris',
				'html'  => '<p>SDi accompagne les entreprises, commerces, industriels et institutions dans la <strong>création de leur site internet</strong>, à Dijon, en Côte-d\'Or et partout en France. Chaque projet est conçu sur-mesure : nous ne partons jamais d\'un template, mais de vos objectifs.</p><p>Un site vitrine performant doit être rapide, lisible sur mobile, bien référencé et simple à mettre à jour. C\'est exactement ce que nous livrons, avec un <strong>interlocuteur unique</strong> qui pilote votre projet de la première maquette à la mise en ligne. Découvrez aussi notre <a href="' . esc_url( sdi_page_url( 'referencement-seo' ) ) . '">référencement SEO</a> et nos <a href="' . esc_url( sdi_page_url( 'site-e-commerce' ) ) . '">sites e-commerce</a>.</p>',
			),
			array(
				'type'    => 'faq',
				'title'   => 'Vos questions sur la création de site',
				'items'   => array(
					array( 'q' => 'Combien coûte la création d\'un site internet ?', 'a' => 'Le budget dépend du nombre de pages, des fonctionnalités et du niveau de sur-mesure. Après un premier échange, nous vous remettons un devis clair et détaillé, sans engagement.' ),
					array( 'q' => 'Combien de temps faut-il pour créer un site ?', 'a' => 'Un site vitrine se livre généralement en 4 à 8 semaines selon le périmètre et la disponibilité des contenus. Nous fixons un planning précis dès le cadrage.' ),
					array( 'q' => 'Pourrai-je modifier mon site moi-même ?', 'a' => 'Oui. Nous intégrons un CMS simple et nous vous formons à la prise en main pour que vous gériez vos contenus en toute autonomie.' ),
					array( 'q' => 'Intervenez-vous en dehors de Dijon ?', 'a' => 'Oui, nous travaillons partout en France, à distance comme sur site. Notre siège est à Paris et notre ancrage à Dijon.' ),
				),
			),
		),
		'final_cta'  => array( 'heading' => 'Prêt à lancer votre nouveau site ?', 'text' => 'Racontez-nous votre projet : on vous propose une direction claire et un devis sous 24h.' ),
	);

	$pages['site-e-commerce'] = array(
		'title'      => 'Site e-commerce',
		'menu_order' => 11,
		'seo'        => array(
			'title' => 'Création de site e-commerce | Agence e-commerce SDi Dijon',
			'desc'  => 'SDi crée votre boutique en ligne performante : catalogue, paiement sécurisé, tunnel optimisé. Agence e-commerce à Dijon et Paris. Vendez plus, partout en France.',
			'focus' => 'site e-commerce',
		),
		'eyebrow'    => '// Site e-commerce',
		'h1'         => 'Des boutiques en ligne performantes, prêtes à vendre.',
		'intro'      => 'Du catalogue au paiement, nous créons des sites e-commerce rapides et optimisés pour la conversion. Une boutique que vos clients trouvent simple, et que vous pilotez sans dépendre de personne.',
		'crumbs'     => sdi_landing_crumbs( 'Site e-commerce', array( 'Services' => $services ) ),
		'cta'        => sdi_landing_cta(),
		'sections'   => array(
			array(
				'type'    => 'features',
				'eyebrow' => '// Une boutique qui vend',
				'title'   => 'Chaque détail au service de la conversion.',
				'items'   => array(
					array( 'icon' => 'shopping-bag', 'title' => 'Tunnel optimisé', 'desc' => 'Un parcours d\'achat fluide, du produit au paiement, sans friction.', 'points' => array( 'Fiche produit convaincante', 'Panier & checkout simplifiés', 'Relance de panier abandonné' ) ),
					array( 'icon' => 'target', 'title' => 'Paiement & livraison', 'desc' => 'Paiement sécurisé et options de livraison adaptées à votre activité.', 'points' => array( 'Paiement CB & multi-moyens', 'Multi-transporteurs', 'Gestion des taxes & promos' ) ),
					array( 'icon' => 'boxes', 'title' => 'Catalogue autonome', 'desc' => 'Vous gérez produits, stocks et commandes en toute autonomie.', 'points' => array( 'Back-office simple', 'Import/export catalogue', 'Suivi des commandes' ) ),
				),
			),
			array(
				'type'       => 'checklist',
				'eyebrow'    => '// Inclus',
				'title'      => 'Tout pour lancer et faire grandir votre boutique.',
				'body'       => array( 'Nous connectons votre e-commerce à vos outils (paiement, logistique, analytics) et l\'optimisons pour Google, afin d\'attirer un trafic qui achète.' ),
				'card_title' => 'Ce que vous obtenez',
				'points'     => array(
					'Boutique responsive et rapide',
					'Paiement sécurisé (CB, portefeuilles, x3…)',
					'Gestion catalogue, stocks et commandes',
					'SEO produit & catégories',
					'Suivi analytics et conversions',
					'Maintenance et évolutions',
				),
			),
			array(
				'type'  => 'richtext',
				'title' => 'Agence e-commerce à Dijon et Paris',
				'html'  => '<p>Vous vendez déjà en boutique ou vous lancez votre activité en ligne ? SDi conçoit des <strong>sites e-commerce</strong> sur-mesure, pensés pour la performance et la conversion, à Dijon comme partout en France.</p><p>Nous vous accompagnons du choix de la solution à la mise en ligne, puis dans la durée pour faire progresser votre chiffre d\'affaires : <a href="' . esc_url( sdi_page_url( 'referencement-seo' ) ) . '">SEO</a>, <a href="' . esc_url( sdi_page_url( 'google-ads' ) ) . '">Google Ads</a> et même des <a href="' . esc_url( sdi_page_url( 'agents-ia-sur-mesure' ) ) . '">agents IA</a> de conseil produit.</p>',
			),
			array(
				'type'    => 'faq',
				'title'   => 'Vos questions sur l\'e-commerce',
				'items'   => array(
					array( 'q' => 'Sur quelle solution développez-vous ?', 'a' => 'Nous choisissons la solution la plus adaptée à votre activité (WooCommerce, Shopify, ou développement sur-mesure) plutôt qu\'une réponse unique. L\'objectif : la bonne technologie pour vos besoins et votre budget.' ),
					array( 'q' => 'Peut-on connecter mon e-commerce à mes outils ?', 'a' => 'Oui : caisse, ERP, logistique, comptabilité, e-mailing… Nous connectons votre boutique à votre écosystème pour automatiser un maximum de tâches.' ),
					array( 'q' => 'Gérez-vous aussi l\'acquisition de trafic ?', 'a' => 'Oui, nous pilotons votre visibilité via le SEO et Google Ads pour attirer un trafic qualifié qui achète.' ),
				),
			),
		),
		'final_cta'  => array( 'heading' => 'Envie de vendre en ligne ?', 'text' => 'Parlons de votre projet e-commerce : on vous propose une solution claire et chiffrée.' ),
	);

	$pages['referencement-seo'] = array(
		'title'      => 'Référencement SEO',
		'menu_order' => 12,
		'seo'        => array(
			'title' => 'Référencement SEO | Agence SEO à Dijon & Paris — SDi',
			'desc'  => 'Agence SEO SDi : audit, SEO technique, contenu et netlinking pour un référencement durable sur Google. SEO local Dijon & national. Plus de trafic qualifié.',
			'focus' => 'référencement SEO',
		),
		'eyebrow'    => '// Référencement naturel (SEO)',
		'h1'         => 'Un référencement durable sur Google, à Dijon comme au national.',
		'intro'      => 'Nous construisons votre visibilité sur Google dans le temps : SEO technique, contenu et autorité. Une priorité forte au référencement local dijonnais, sans jamais négliger le national.',
		'crumbs'     => sdi_landing_crumbs( 'Référencement SEO', array( 'Services' => $services ) ),
		'cta'        => sdi_landing_cta( 'Demander un audit SEO' ),
		'sections'   => array(
			array(
				'type'    => 'features',
				'eyebrow' => '// Les 3 piliers du SEO',
				'title'   => 'Une méthode complète, pas de recette magique.',
				'items'   => array(
					array( 'icon' => 'search-check', 'title' => 'SEO technique', 'desc' => 'Un site sain que Google explore et comprend facilement.', 'points' => array( 'Audit technique complet', 'Vitesse & Core Web Vitals', 'Indexation & données structurées' ) ),
					array( 'icon' => 'pen-tool', 'title' => 'Contenu & sémantique', 'desc' => 'Des contenus qui répondent aux intentions de recherche.', 'points' => array( 'Recherche de mots-clés', 'Contenus optimisés', 'Maillage interne' ) ),
					array( 'icon' => 'trending-up', 'title' => 'Autorité & netlinking', 'desc' => 'Des liens de qualité pour renforcer votre crédibilité.', 'points' => array( 'Stratégie de liens', 'Citations locales', 'Google Business Profile' ) ),
				),
			),
			array(
				'type'    => 'steps',
				'eyebrow' => '// Comment on procède',
				'title'   => 'Un SEO piloté par la donnée.',
				'items'   => array(
					array( 'title' => 'Audit', 'desc' => 'État des lieux technique, sémantique et concurrentiel.' ),
					array( 'title' => 'Stratégie', 'desc' => 'Plan d\'action priorisé sur vos mots-clés à fort potentiel.' ),
					array( 'title' => 'Optimisation', 'desc' => 'Technique, contenus et netlinking déployés dans la durée.' ),
					array( 'title' => 'Reporting', 'desc' => 'Suivi des positions, du trafic et des conversions chaque mois.' ),
				),
			),
			array(
				'type'  => 'richtext',
				'title' => 'Agence SEO à Dijon, en Côte-d\'Or et partout en France',
				'html'  => '<p>Le <strong>référencement naturel</strong> est le levier le plus rentable sur le long terme : un trafic régulier, qualifié et gratuit une fois les positions acquises. SDi met l\'accent sur le <a href="' . esc_url( sdi_page_url( 'seo-dijon-cote-dor' ) ) . '">SEO local à Dijon et en Côte-d\'Or</a>, tout en menant des stratégies de <a href="' . esc_url( sdi_page_url( 'seo-national' ) ) . '">SEO national</a>.</p><p>Besoin de résultats rapides en parallèle ? Nous combinons souvent le SEO avec <a href="' . esc_url( sdi_page_url( 'google-ads' ) ) . '">Google Ads</a> pour capter de la demande immédiatement pendant que le référencement monte en puissance.</p>',
			),
			array(
				'type'    => 'faq',
				'title'   => 'Vos questions sur le SEO',
				'items'   => array(
					array( 'q' => 'En combien de temps voit-on des résultats en SEO ?', 'a' => 'Les premiers effets apparaissent généralement entre 3 et 6 mois. Le SEO est un investissement de fond : les positions gagnées durent et rapportent dans le temps.' ),
					array( 'q' => 'Faites-vous du SEO local ?', 'a' => 'Oui, c\'est même notre priorité pour les entreprises de Dijon et de Côte-d\'Or : Google Business Profile, citations locales et contenus géolocalisés.' ),
					array( 'q' => 'Garantissez-vous la 1re position ?', 'a' => 'Aucune agence sérieuse ne peut garantir une position exacte (Google ne le permet pas). Nous garantissons une méthode rigoureuse, transparente et pilotée par la donnée.' ),
				),
			),
		),
		'final_cta'  => array( 'heading' => 'Envie d\'être enfin visible sur Google ?', 'text' => 'Demandez votre audit SEO : on vous montre concrètement où vous en êtes et quoi améliorer.' ),
	);

	$pages['google-ads'] = array(
		'title'      => 'Google Ads',
		'menu_order' => 13,
		'seo'        => array(
			'title' => 'Agence Google Ads (SEA) à Dijon & Paris | SDi',
			'desc'  => 'Campagnes Google Ads rentables pilotées à la donnée : Search, Shopping, Display. Agence SEA SDi à Dijon et Paris. Générez des leads qualifiés dès le 1er mois.',
			'focus' => 'Google Ads',
		),
		'eyebrow'    => '// Google Ads & webmarketing',
		'h1'         => 'Des campagnes Google Ads rentables, pilotées à la donnée.',
		'intro'      => 'Search, Shopping, Display : nous créons et optimisons vos campagnes pour générer des leads et des ventes, en maîtrisant votre coût d\'acquisition. Des résultats mesurables, dès le premier mois.',
		'crumbs'     => sdi_landing_crumbs( 'Google Ads', array( 'Services' => $services ) ),
		'cta'        => sdi_landing_cta(),
		'sections'   => array(
			array(
				'type'    => 'features',
				'eyebrow' => '// Ce que nous pilotons',
				'title'   => 'Le bon message, à la bonne personne, au bon moment.',
				'items'   => array(
					array( 'icon' => 'search', 'title' => 'Search', 'desc' => 'Captez la demande active de ceux qui cherchent déjà vos services.', 'points' => array( 'Mots-clés & enchères', 'Annonces qui convertissent', 'Extensions & ciblage local' ) ),
					array( 'icon' => 'shopping-bag', 'title' => 'Shopping', 'desc' => 'Mettez vos produits en avant directement dans Google.', 'points' => array( 'Flux produit optimisé', 'Performance Max', 'ROAS piloté' ) ),
					array( 'icon' => 'target', 'title' => 'Display & Retargeting', 'desc' => 'Restez présent auprès des visiteurs qui n\'ont pas converti.', 'points' => array( 'Bannières & audiences', 'Retargeting', 'Notoriété locale' ) ),
				),
			),
			array(
				'type'       => 'checklist',
				'eyebrow'    => '// Notre engagement',
				'title'      => 'De la transparence et des résultats.',
				'body'       => array( 'Vous gardez la propriété de votre compte et de vos données. Nous pilotons vos campagnes vers un objectif clair : le meilleur retour sur investissement.' ),
				'card_title' => 'Notre approche',
				'points'     => array(
					'Suivi des conversions fiable',
					'Optimisation continue des enchères',
					'Compte et données 100% à vous',
					'Reporting clair chaque mois',
					'Pas d\'engagement de longue durée',
					'Coordination avec votre SEO',
				),
			),
			array(
				'type'  => 'richtext',
				'title' => 'Agence Google Ads à Dijon et Paris',
				'html'  => '<p><strong>Google Ads</strong> (SEA) est le levier idéal pour générer des résultats rapides pendant que votre <a href="' . esc_url( sdi_page_url( 'referencement-seo' ) ) . '">SEO</a> monte en puissance. SDi crée, pilote et optimise vos campagnes pour capter une demande qualifiée et rentabiliser chaque euro investi.</p><p>Nous travaillons aussi bien pour des commerces locaux à Dijon que pour des enseignes à ambition nationale, en coordination avec votre <a href="' . esc_url( sdi_page_url( 'site-e-commerce' ) ) . '">site e-commerce</a> ou vitrine.</p>',
			),
			array(
				'type'    => 'faq',
				'title'   => 'Vos questions sur Google Ads',
				'items'   => array(
					array( 'q' => 'Quel budget prévoir pour Google Ads ?', 'a' => 'Le budget média dépend de votre marché et de vos objectifs. Nous définissons ensemble un budget de départ maîtrisé, puis nous l\'ajustons selon les performances.' ),
					array( 'q' => 'À qui appartient le compte Google Ads ?', 'a' => 'À vous. Nous travaillons sur votre propre compte, vous gardez la main et l\'historique, même si vous changez de prestataire.' ),
					array( 'q' => 'SEO ou Google Ads ?', 'a' => 'Les deux sont complémentaires : Google Ads apporte des résultats immédiats, le SEO construit une visibilité durable. Nous combinons souvent les deux.' ),
				),
			),
		),
		'final_cta'  => array( 'heading' => 'Envie de générer des leads dès maintenant ?', 'text' => 'On analyse votre marché et on vous propose une stratégie Google Ads chiffrée, sans engagement.' ),
	);

	$pages['saas-sur-mesure'] = array(
		'title'      => 'SaaS sur-mesure',
		'menu_order' => 14,
		'seo'        => array(
			'title' => 'Développement SaaS & application sur-mesure | SDi',
			'desc'  => 'SDi développe vos plateformes SaaS et applications métier sur-mesure : cadrage, dev full-stack, maintenance. Agence produit à Dijon et Paris. De l\'idée au déploiement.',
			'focus' => 'SaaS sur-mesure',
		),
		'eyebrow'    => '// SaaS & applications sur-mesure',
		'h1'         => 'Vos outils métier et plateformes SaaS, conçus de bout en bout.',
		'intro'      => 'Quand les logiciels du marché ne suffisent plus, nous développons l\'outil qui colle exactement à vos process. Du cadrage produit au déploiement, avec une vraie exigence technique.',
		'crumbs'     => sdi_landing_crumbs( 'SaaS sur-mesure', array( 'Services' => $services ) ),
		'cta'        => sdi_landing_cta( 'Parler de mon projet' ),
		'sections'   => array(
			array(
				'type'    => 'features',
				'eyebrow' => '// Ce que nous construisons',
				'title'   => 'Des produits fiables, évolutifs, adoptés.',
				'items'   => array(
					array( 'icon' => 'boxes', 'title' => 'Plateformes SaaS', 'desc' => 'Des applications web multi-utilisateurs, sécurisées et scalables.', 'points' => array( 'Architecture solide', 'Gestion des rôles & droits', 'Prêt à monter en charge' ) ),
					array( 'icon' => 'workflow', 'title' => 'Outils métier', 'desc' => 'Digitalisez vos process spécifiques et gagnez en productivité.', 'points' => array( 'Automatisation des tâches', 'Tableaux de bord', 'Connexion à vos données' ) ),
					array( 'icon' => 'server', 'title' => 'Intégrations & API', 'desc' => 'Connectez votre outil à votre écosystème existant.', 'points' => array( 'API & webhooks', 'Synchronisation de données', 'Sécurité & hébergement' ) ),
				),
			),
			array(
				'type'    => 'steps',
				'eyebrow' => '// De l\'idée au produit',
				'title'   => 'Une démarche produit, pas juste du code.',
				'items'   => array(
					array( 'title' => 'Cadrage produit', 'desc' => 'On clarifie le besoin, les utilisateurs et le périmètre.' ),
					array( 'title' => 'Conception & UX', 'desc' => 'Prototype et parcours pensés pour l\'adoption.' ),
					array( 'title' => 'Développement', 'desc' => 'Dev full-stack itératif, testé et documenté.' ),
					array( 'title' => 'Déploiement & suivi', 'desc' => 'Mise en production, maintenance et évolutions.' ),
				),
			),
			array(
				'type'  => 'richtext',
				'title' => 'Agence de développement SaaS à Dijon et Paris',
				'html'  => '<p>Un logiciel <strong>SaaS sur-mesure</strong> vous fait gagner du temps, réduit les erreurs et crée un avantage concurrentiel durable. SDi maîtrise la conception produit de bout en bout : cadrage, UX, développement full-stack, maintenance et évolutions.</p><p>Nous concevons aussi des <a href="' . esc_url( sdi_page_url( 'agents-ia-sur-mesure' ) ) . '">agents IA</a> et des briques d\'<a href="' . esc_url( sdi_page_url( 'automatisation-ia' ) ) . '">automatisation</a> que nous intégrons directement à vos plateformes. Voir nos <a href="' . esc_url( sdi_page_url( 'realisations' ) ) . '">réalisations</a>.</p>',
			),
			array(
				'type'    => 'faq',
				'title'   => 'Vos questions sur le SaaS sur-mesure',
				'items'   => array(
					array( 'q' => 'Pourquoi développer sur-mesure plutôt qu\'acheter un logiciel ?', 'a' => 'Quand vos process sont spécifiques, un outil sur-mesure épouse exactement votre fonctionnement, sans compromis ni abonnements par utilisateur qui explosent. Il devient un actif qui vous appartient.' ),
					array( 'q' => 'Comment se déroule un projet SaaS ?', 'a' => 'De façon itérative : nous livrons rapidement une première version utilisable, puis nous l\'enrichissons selon les retours réels des utilisateurs.' ),
					array( 'q' => 'Assurez-vous la maintenance ?', 'a' => 'Oui, nous assurons la maintenance, la sécurité et les évolutions de votre plateforme dans la durée.' ),
				),
			),
		),
		'final_cta'  => array( 'heading' => 'Une idée de produit ou d\'outil métier ?', 'text' => 'Parlons-en : on cadre votre besoin et on vous propose une première direction concrète.' ),
	);

	/* ============================ IA ============================ */

	$pages['agents-ia-sur-mesure'] = array(
		'title'      => 'Agents IA sur-mesure',
		'menu_order' => 20,
		'seo'        => array(
			'title' => 'Agents IA sur-mesure pour entreprise | Agence IA SDi',
			'desc'  => 'SDi conçoit des agents IA sur-mesure connectés à vos outils : support 24/7, qualification de leads, chatbots. Agence IA à Dijon et Paris. Passez à l\'IA utile.',
			'focus' => 'agents IA sur-mesure',
		),
		'eyebrow'    => '// Agents IA sur-mesure',
		'h1'         => 'Des agents IA qui travaillent pour votre entreprise, en continu.',
		'intro'      => 'Nous concevons des assistants intelligents connectés à vos outils : ils répondent à vos clients, qualifient vos leads et automatisent vos tâches, 24h/24. Une IA concrète, au service de résultats mesurables.',
		'crumbs'     => sdi_landing_crumbs( 'Agents IA sur-mesure', array( 'Agents IA' => sdi_page_url( 'agents-ia' ) ) ),
		'cta'        => sdi_landing_cta( 'Parler de mon projet IA' ),
		'sections'   => array(
			array(
				'type'    => 'features',
				'eyebrow' => '// Ce que fait un agent IA',
				'title'   => 'Un collaborateur digital disponible en permanence.',
				'items'   => array(
					array( 'icon' => 'headphones', 'title' => 'Support & vente 24/7', 'desc' => 'Répond, qualifie et oriente vos prospects, jour et nuit, dans votre ton.', 'points' => array( 'Chatbot sur votre site', 'Prise de rendez-vous', 'Réponses à vos FAQ' ) ),
					array( 'icon' => 'workflow', 'title' => 'Automatisation', 'desc' => 'Élimine les tâches répétitives en se branchant sur vos outils.', 'points' => array( 'Traitement des demandes', 'Génération de documents', 'Connexion à vos données' ) ),
					array( 'icon' => 'chart-column', 'title' => 'Analyse & décision', 'desc' => 'Transforme vos données en réponses claires et actionnables.', 'points' => array( 'Recherche dans vos documents', 'Synthèses automatiques', 'Aide à la décision' ) ),
				),
			),
			array(
				'type'    => 'steps',
				'eyebrow' => '// Comment on procède',
				'title'   => 'De l\'idée à l\'agent en production.',
				'items'   => array(
					array( 'title' => 'Cadrage', 'desc' => 'On identifie le cas d\'usage à plus fort impact.' ),
					array( 'title' => 'Connexion', 'desc' => 'On branche l\'agent sur vos données et vos outils.' ),
					array( 'title' => 'Entraînement', 'desc' => 'On l\'ajuste à votre ton et à vos règles métier.' ),
					array( 'title' => 'Mise en production', 'desc' => 'On déploie, on mesure et on améliore en continu.' ),
				),
			),
			array(
				'type'  => 'richtext',
				'title' => 'Agence spécialisée en agents IA à Dijon et Paris',
				'html'  => '<p>L\'intelligence artificielle n\'a de valeur que lorsqu\'elle sert un résultat concret. SDi conçoit des <strong>agents IA sur-mesure</strong> pour les entreprises : nous les utilisons d\'abord sur nos propres process avant de les déployer chez nos clients.</p><p>Un agent IA se connecte à votre site, votre CRM ou vos documents pour automatiser ce qui vous fait perdre du temps. Découvrez aussi l\'<a href="' . esc_url( sdi_page_url( 'automatisation-ia' ) ) . '">automatisation IA</a> et notre approche sur la page <a href="' . esc_url( sdi_page_url( 'agents-ia' ) ) . '">Agents IA</a>.</p>',
			),
			array(
				'type'    => 'faq',
				'title'   => 'Vos questions sur les agents IA',
				'items'   => array(
					array( 'q' => 'Un agent IA est-il fiable pour parler à mes clients ?', 'a' => 'Oui, à condition d\'être bien cadré. Nous l\'entraînons sur vos contenus, fixons des garde-fous et prévoyons un relais humain quand c\'est nécessaire.' ),
					array( 'q' => 'À quels outils peut-on le connecter ?', 'a' => 'À la plupart : site web, CRM, messagerie, base documentaire, e-commerce… L\'objectif est qu\'il travaille dans votre écosystème existant.' ),
					array( 'q' => 'Mes données sont-elles protégées ?', 'a' => 'La confidentialité est au cœur de nos projets. Nous choisissons des solutions respectueuses de vos données et cadrons précisément ce qui est utilisé.' ),
				),
			),
		),
		'final_cta'  => array( 'heading' => 'Curieux de ce que l\'IA peut faire chez vous ?', 'text' => 'On identifie ensemble une première automatisation à fort impact, et on vous en montre la valeur.', 'secondary' => array( 'type' => 'link', 'label' => 'Voir les réalisations', 'href' => sdi_page_url( 'realisations' ) ) ),
	);

	$pages['automatisation-ia'] = array(
		'title'      => 'Automatisation IA',
		'menu_order' => 21,
		'seo'        => array(
			'title' => 'Automatisation IA des process | Agence IA SDi Dijon & Paris',
			'desc'  => 'Automatisez vos tâches répétitives avec l\'IA : devis, e-mails, reporting, workflows connectés à vos outils. SDi, agence d\'automatisation IA à Dijon et Paris.',
			'focus' => 'automatisation IA',
		),
		'eyebrow'    => '// Automatisation IA',
		'h1'         => 'Automatisez vos tâches répétitives grâce à l\'IA.',
		'intro'      => 'Devis, e-mails, saisies, reporting, relances : nous identifions ce qui vous fait perdre du temps et le confions à l\'IA. Vos équipes se concentrent sur ce qui a vraiment de la valeur.',
		'crumbs'     => sdi_landing_crumbs( 'Automatisation IA', array( 'Agents IA' => sdi_page_url( 'agents-ia' ) ) ),
		'cta'        => sdi_landing_cta( 'Automatiser mes process' ),
		'sections'   => array(
			array(
				'type'    => 'features',
				'eyebrow' => '// Ce que l\'on automatise',
				'title'   => 'Moins de tâches manuelles, moins d\'erreurs.',
				'items'   => array(
					array( 'icon' => 'pen-tool', 'title' => 'Documents & e-mails', 'desc' => 'Génération de devis, comptes-rendus, réponses et contenus.', 'points' => array( 'Devis & documents', 'Réponses e-mail', 'Contenus récurrents' ) ),
					array( 'icon' => 'workflow', 'title' => 'Workflows connectés', 'desc' => 'Des enchaînements automatiques entre vos outils.', 'points' => array( 'Synchronisation de données', 'Notifications & relances', 'Traitement des demandes' ) ),
					array( 'icon' => 'chart-column', 'title' => 'Reporting & analyse', 'desc' => 'Des synthèses et tableaux de bord générés pour vous.', 'points' => array( 'Rapports automatiques', 'Extraction de données', 'Alertes' ) ),
				),
			),
			array(
				'type'       => 'checklist',
				'eyebrow'    => '// Notre démarche',
				'title'      => 'On commence petit, on prouve la valeur, on étend.',
				'body'       => array( 'Plutôt qu\'un grand projet risqué, nous identifions une première automatisation à fort impact, nous la déployons vite, puis nous l\'élargissons.' ),
				'card_title' => 'Les bénéfices',
				'points'     => array(
					'Du temps gagné sur les tâches à faible valeur',
					'Moins d\'erreurs de saisie',
					'Des délais de traitement réduits',
					'Des équipes recentrées sur l\'essentiel',
					'Une automatisation branchée à vos outils',
					'Un déploiement progressif et maîtrisé',
				),
			),
			array(
				'type'  => 'richtext',
				'title' => 'Agence d\'automatisation IA à Dijon et Paris',
				'html'  => '<p>L\'<strong>automatisation IA</strong> ne consiste pas à remplacer vos équipes, mais à les libérer des tâches répétitives. SDi identifie les process qui vous coûtent du temps et les automatise avec des <a href="' . esc_url( sdi_page_url( 'agents-ia-sur-mesure' ) ) . '">agents IA</a> connectés à vos outils.</p><p>Cette brique s\'intègre naturellement à un <a href="' . esc_url( sdi_page_url( 'saas-sur-mesure' ) ) . '">outil métier sur-mesure</a> ou à votre écosystème existant.</p>',
			),
			array(
				'type'    => 'faq',
				'title'   => 'Vos questions sur l\'automatisation IA',
				'items'   => array(
					array( 'q' => 'Par où commencer ?', 'a' => 'Par un cas d\'usage simple et à fort impact. Nous auditons vos tâches répétitives et priorisons celle qui rapportera le plus, le plus vite.' ),
					array( 'q' => 'L\'IA va-t-elle remplacer mes salariés ?', 'a' => 'Non : elle prend en charge les tâches sans valeur ajoutée pour que vos équipes se concentrent sur le relationnel, le conseil et la décision.' ),
					array( 'q' => 'Est-ce réservé aux grandes entreprises ?', 'a' => 'Pas du tout. Les TPE et PME sont souvent celles qui gagnent le plus, car chaque heure automatisée compte davantage.' ),
				),
			),
		),
		'final_cta'  => array( 'heading' => 'Quelles tâches aimeriez-vous ne plus faire à la main ?', 'text' => 'On identifie ensemble votre première automatisation à fort impact.' ),
	);

	/* ============================ ZONES ============================ */

	$service_links = array(
		array( 'label' => 'Création de site internet', 'href' => sdi_page_url( 'creation-site-internet' ), 'desc' => 'Sites vitrines sur-mesure qui convertissent.' ),
		array( 'label' => 'Site e-commerce', 'href' => sdi_page_url( 'site-e-commerce' ), 'desc' => 'Boutiques en ligne performantes.' ),
		array( 'label' => 'Référencement SEO', 'href' => sdi_page_url( 'referencement-seo' ), 'desc' => 'Visibilité durable sur Google.' ),
		array( 'label' => 'Google Ads', 'href' => sdi_page_url( 'google-ads' ), 'desc' => 'Des leads qualifiés, dès le 1er mois.' ),
		array( 'label' => 'SaaS sur-mesure', 'href' => sdi_page_url( 'saas-sur-mesure' ), 'desc' => 'Vos outils métier et plateformes.' ),
		array( 'label' => 'Agents IA sur-mesure', 'href' => sdi_page_url( 'agents-ia-sur-mesure' ), 'desc' => 'L\'IA au service de vos résultats.' ),
	);

	$pages['agence-web-dijon'] = array(
		'title'      => 'Agence web Dijon',
		'menu_order' => 30,
		'seo'        => array(
			'title' => 'Agence web à Dijon | Création de site & SEO — SDi',
			'desc'  => 'SDi, agence web à Dijon : création de site internet, e-commerce, SEO local et agents IA. Un partenaire de proximité en Côte-d\'Or. Depuis 2017, +100 projets.',
			'focus' => 'agence web Dijon',
		),
		'eyebrow'    => '// Agence web · Dijon',
		'h1'         => 'Votre agence web à Dijon, ancrée en Côte-d\'Or.',
		'intro'      => 'De la création de site au référencement, en passant par les agents IA, SDi accompagne de près les entreprises dijonnaises. La proximité d\'un partenaire local, la puissance technique d\'un studio produit.',
		'crumbs'     => sdi_landing_crumbs( 'Agence web Dijon' ),
		'cta'        => sdi_landing_cta(),
		'sections'   => array(
			array(
				'type'  => 'stats',
				'items' => array(
					array( 'value' => '+100', 'label' => 'projets livrés depuis 2017' ),
					array( 'value' => 'Dijon', 'label' => 'et toute la Côte-d\'Or' ),
					array( 'value' => '4,9/5', 'label' => 'sur 109 avis Google' ),
				),
			),
			array(
				'type'       => 'checklist',
				'eyebrow'    => '// Pourquoi SDi à Dijon',
				'title'      => 'Un partenaire digital de proximité.',
				'body'       => array( 'Nous connaissons le tissu économique local et nous nous déplaçons pour vous rencontrer. Un interlocuteur unique, joignable, qui parle votre langage.' ),
				'card_title' => 'Nos atouts locaux',
				'points'     => array(
					'Des rendez-vous en présentiel à Dijon',
					'Une vraie connaissance du marché local',
					'Un SEO local pensé pour la Côte-d\'Or',
					'Un interlocuteur unique et réactif',
					'Une équipe qui maîtrise toute la chaîne',
					'Des références locales et nationales',
				),
			),
			array(
				'type'    => 'links',
				'eyebrow' => '// Nos services à Dijon',
				'title'   => 'Tout le digital, au même endroit.',
				'lead'    => 'Une seule équipe pour votre site, votre visibilité et vos outils.',
				'items'   => $service_links,
			),
			array(
				'type'  => 'richtext',
				'title' => 'Agence web et IA à Dijon',
				'html'  => '<p>Basée à Dijon et pilotée depuis Paris, SDi est l\'<strong>agence web dijonnaise</strong> qui allie proximité et exigence technique. Nous aidons commerces, PME, industriels et institutions de Dijon Métropole et de Côte-d\'Or à réussir en ligne depuis 2017.</p><p>Vous cherchez à améliorer votre visibilité locale ? Découvrez notre offre dédiée au <a href="' . esc_url( sdi_page_url( 'seo-dijon-cote-dor' ) ) . '">SEO à Dijon et en Côte-d\'Or</a>. Un projet à Paris ou ailleurs ? Voir aussi <a href="' . esc_url( sdi_page_url( 'agence-web-paris' ) ) . '">agence web Paris</a>.</p>',
			),
			array(
				'type'    => 'faq',
				'title'   => 'Agence web à Dijon : vos questions',
				'items'   => array(
					array( 'q' => 'Peut-on se rencontrer à Dijon ?', 'a' => 'Bien sûr. Nous privilégions un premier échange pour comprendre votre projet, en présentiel à Dijon ou en visio selon votre préférence.' ),
					array( 'q' => 'Travaillez-vous avec des petites structures ?', 'a' => 'Oui : commerces, artisans, TPE, PME, associations… Nous adaptons le périmètre et le budget à votre réalité.' ),
					array( 'q' => 'Intervenez-vous ailleurs qu\'à Dijon ?', 'a' => 'Oui, dans toute la Côte-d\'Or (Beaune, Chenôve, Quetigny, Talant…) et partout en France.' ),
				),
			),
		),
		'final_cta'  => array( 'heading' => 'Un projet digital à Dijon ?', 'text' => 'Parlons-en autour d\'un café ou en visio : on vous propose une direction claire, sans engagement.' ),
	);

	$pages['seo-dijon-cote-dor'] = array(
		'title'      => 'SEO Dijon & Côte-d\'Or',
		'menu_order' => 31,
		'seo'        => array(
			'title' => 'Référencement SEO à Dijon & en Côte-d\'Or | Agence SDi',
			'desc'  => 'Agence SEO à Dijon : référencement local Côte-d\'Or, Google Business Profile, contenu et netlinking. Soyez trouvé par vos clients dijonnais. Audit SEO offert.',
			'focus' => 'SEO Dijon',
		),
		'eyebrow'    => '// SEO local · Dijon & Côte-d\'Or',
		'h1'         => 'Soyez trouvé par vos clients à Dijon et en Côte-d\'Or.',
		'intro'      => 'Le référencement local est décisif : 8 recherches sur 10 aboutissent à un contact quand elles sont locales. Nous vous positionnons sur les recherches de vos futurs clients dijonnais.',
		'crumbs'     => sdi_landing_crumbs( 'SEO Dijon & Côte-d\'Or' ),
		'cta'        => sdi_landing_cta( 'Demander un audit SEO local' ),
		'sections'   => array(
			array(
				'type'    => 'features',
				'eyebrow' => '// Les leviers du SEO local',
				'title'   => 'Dominez les recherches locales.',
				'items'   => array(
					array( 'icon' => 'map-pin', 'title' => 'Google Business Profile', 'desc' => 'Un profil optimisé pour apparaître dans le pack local et sur Maps.', 'points' => array( 'Optimisation de la fiche', 'Gestion des avis', 'Photos & posts' ) ),
					array( 'icon' => 'search-check', 'title' => 'SEO géolocalisé', 'desc' => 'Des pages et contenus pensés pour Dijon et la Côte-d\'Or.', 'points' => array( 'Pages locales', 'Mots-clés géolocalisés', 'Données structurées' ) ),
					array( 'icon' => 'trending-up', 'title' => 'Citations & avis', 'desc' => 'De la cohérence et de la crédibilité pour rassurer Google.', 'points' => array( 'Annuaires locaux', 'Cohérence NAP', 'Stratégie d\'avis' ) ),
				),
			),
			array(
				'type'  => 'richtext',
				'title' => 'Agence SEO local à Dijon',
				'html'  => '<p>Le <strong>SEO local à Dijon</strong> est la priorité pour tout commerce, artisan ou entreprise de services qui cible une clientèle de proximité. Bien positionné dans le pack local Google et sur Maps, vous captez des clients au moment précis où ils cherchent vos services en Côte-d\'Or.</p><p>Le SEO local s\'intègre à une <a href="' . esc_url( sdi_page_url( 'referencement-seo' ) ) . '">stratégie SEO globale</a> et se combine efficacement avec <a href="' . esc_url( sdi_page_url( 'google-ads' ) ) . '">Google Ads</a>. Vous cherchez plus large ? Voir notre offre <a href="' . esc_url( sdi_page_url( 'agence-web-dijon' ) ) . '">agence web Dijon</a>.</p>',
			),
			array(
				'type'    => 'faq',
				'title'   => 'SEO à Dijon : vos questions',
				'items'   => array(
					array( 'q' => 'Qu\'est-ce que le SEO local ?', 'a' => 'C\'est l\'optimisation de votre présence pour les recherches géolocalisées (ex. « plombier Dijon »). Il repose beaucoup sur votre fiche Google Business Profile, vos avis et des contenus locaux.' ),
					array( 'q' => 'Combien de temps pour ranker à Dijon ?', 'a' => 'Le SEO local donne souvent des résultats plus rapides que le SEO national : quelques semaines à quelques mois selon la concurrence de votre secteur.' ),
					array( 'q' => 'Gérez-vous mes avis Google ?', 'a' => 'Oui, nous vous aidons à mettre en place une stratégie d\'avis et à optimiser votre fiche pour améliorer votre visibilité locale.' ),
				),
			),
		),
		'final_cta'  => array( 'heading' => 'Envie d\'être n°1 sur Google à Dijon ?', 'text' => 'Demandez votre audit SEO local : on vous montre votre positionnement et vos opportunités.' ),
	);

	$pages['agence-web-paris'] = array(
		'title'      => 'Agence web Paris',
		'menu_order' => 32,
		'seo'        => array(
			'title' => 'Agence web à Paris | Sites, SaaS & IA — SDi',
			'desc'  => 'SDi, agence web à Paris (siège 75008) : création de site, e-commerce, SaaS et agents IA pour des projets d\'envergure nationale. Expertise produit, résultats mesurables.',
			'focus' => 'agence web Paris',
		),
		'eyebrow'    => '// Agence web · Paris',
		'h1'         => 'Votre agence web à Paris, pour des projets ambitieux.',
		'intro'      => 'Depuis notre siège parisien (75008), nous menons des projets web, SaaS et IA d\'envergure nationale. La rigueur d\'un studio produit, avec un interlocuteur unique qui pilote tout.',
		'crumbs'     => sdi_landing_crumbs( 'Agence web Paris' ),
		'cta'        => sdi_landing_cta(),
		'sections'   => array(
			array(
				'type'  => 'stats',
				'items' => array(
					array( 'value' => 'Paris', 'label' => 'siège au 60 rue François 1er, 75008' ),
					array( 'value' => '+100', 'label' => 'projets livrés' ),
					array( 'value' => '9 ans', 'label' => 'd\'expérience, depuis 2017' ),
				),
			),
			array(
				'type'    => 'links',
				'eyebrow' => '// Nos services à Paris',
				'title'   => 'Sites, visibilité, produit & IA.',
				'lead'    => 'Un partenaire unique pour toute votre présence digitale.',
				'items'   => $service_links,
			),
			array(
				'type'  => 'richtext',
				'title' => 'Agence web et IA à Paris',
				'html'  => '<p>SDi est une <strong>agence web parisienne</strong> qui conçoit des sites, des plateformes SaaS et des agents IA pour des entreprises à ambition nationale. Notre siège se situe au 60 rue François 1er, 75008 Paris.</p><p>Nous combinons une exigence produit forte et un pilotage clair, avec un interlocuteur unique du cadrage au déploiement. Ancrés aussi à <a href="' . esc_url( sdi_page_url( 'agence-web-dijon' ) ) . '">Dijon</a>, nous intervenons <a href="' . esc_url( sdi_page_url( 'france-entiere' ) ) . '">partout en France</a>.</p>',
			),
			array(
				'type'    => 'faq',
				'title'   => 'Agence web à Paris : vos questions',
				'items'   => array(
					array( 'q' => 'Peut-on vous rencontrer à Paris ?', 'a' => 'Oui, notre siège est dans le 8e arrondissement. Nous organisons volontiers un rendez-vous sur place ou en visio.' ),
					array( 'q' => 'Gérez-vous des projets d\'envergure nationale ?', 'a' => 'Oui : sites multi-sites, plateformes SaaS, refontes ambitieuses, projets IA… Nous avons l\'exigence et la méthode pour les mener.' ),
					array( 'q' => 'Travaillez-vous à distance ?', 'a' => 'Oui, une grande partie de nos projets se pilote à distance, avec des points réguliers et des outils de suivi partagés.' ),
				),
			),
		),
		'final_cta'  => array( 'heading' => 'Un projet web ou produit à Paris ?', 'text' => 'Parlons-en : on cadre votre besoin et on vous propose une direction claire et chiffrée.' ),
	);

	$pages['seo-national'] = array(
		'title'      => 'SEO national',
		'menu_order' => 33,
		'seo'        => array(
			'title' => 'Référencement SEO national | Agence SEO France — SDi',
			'desc'  => 'Stratégie SEO nationale par SDi : visibilité Google sur toute la France, SEO technique, contenu et autorité. Faites décoller votre trafic organique partout.',
			'focus' => 'SEO national',
		),
		'eyebrow'    => '// SEO national',
		'h1'         => 'Une visibilité Google à l\'échelle nationale.',
		'intro'      => 'Vous visez toute la France ? Nous construisons une stratégie SEO ambitieuse : socle technique solide, contenus experts et autorité, pour vous positionner sur des requêtes concurrentielles et générer du trafic partout.',
		'crumbs'     => sdi_landing_crumbs( 'SEO national' ),
		'cta'        => sdi_landing_cta( 'Demander un audit SEO' ),
		'sections'   => array(
			array(
				'type'    => 'features',
				'eyebrow' => '// Une stratégie complète',
				'title'   => 'Ce qui fait la différence au national.',
				'items'   => array(
					array( 'icon' => 'search-check', 'title' => 'Socle technique', 'desc' => 'Un site rapide, propre et parfaitement explorable à grande échelle.', 'points' => array( 'Architecture & crawl', 'Performance', 'Données structurées' ) ),
					array( 'icon' => 'pen-tool', 'title' => 'Contenu d\'autorité', 'desc' => 'Des contenus experts qui répondent aux intentions de recherche.', 'points' => array( 'Cocons sémantiques', 'Contenus experts', 'Maillage interne' ) ),
					array( 'icon' => 'trending-up', 'title' => 'Netlinking', 'desc' => 'Des liens de qualité pour rivaliser sur les requêtes concurrentielles.', 'points' => array( 'Stratégie de liens', 'Relations presse & digital PR', 'Suivi d\'autorité' ) ),
				),
			),
			array(
				'type'  => 'richtext',
				'title' => 'Agence SEO nationale',
				'html'  => '<p>Le <strong>SEO national</strong> demande plus d\'ampleur que le local : concurrence forte, volume de contenus, autorité à construire. SDi met en place une stratégie structurée et pilotée par la donnée pour vous positionner durablement sur toute la France.</p><p>Le SEO se combine idéalement avec <a href="' . esc_url( sdi_page_url( 'google-ads' ) ) . '">Google Ads</a> pour des résultats immédiats. Un ancrage local en Bourgogne ? Voir aussi <a href="' . esc_url( sdi_page_url( 'seo-dijon-cote-dor' ) ) . '">SEO Dijon & Côte-d\'Or</a>.</p>',
			),
			array(
				'type'    => 'faq',
				'title'   => 'SEO national : vos questions',
				'items'   => array(
					array( 'q' => 'Quelle différence avec le SEO local ?', 'a' => 'Le SEO local cible une zone géographique et s\'appuie beaucoup sur Google Business Profile. Le SEO national vise des requêtes plus larges et concurrentielles, avec un fort besoin de contenu et d\'autorité.' ),
					array( 'q' => 'Combien de temps pour des résultats ?', 'a' => 'Comptez 6 à 12 mois pour des positions solides sur des requêtes concurrentielles. C\'est un investissement de fond, très rentable dans la durée.' ),
					array( 'q' => 'Faut-il produire beaucoup de contenu ?', 'a' => 'Oui, le contenu est central au national. Nous définissons une stratégie éditoriale priorisée sur vos meilleures opportunités de trafic.' ),
				),
			),
		),
		'final_cta'  => array( 'heading' => 'Envie de rayonner sur toute la France ?', 'text' => 'Demandez votre audit SEO : on vous propose une feuille de route claire et priorisée.' ),
	);

	$pages['france-entiere'] = array(
		'title'      => 'France entière',
		'menu_order' => 34,
		'seo'        => array(
			'title' => 'Agence web & IA partout en France | SDi',
			'desc'  => 'SDi intervient partout en France : sites, e-commerce, SaaS et agents IA. Ancrés à Dijon, siège à Paris, nous menons vos projets à distance, où que vous soyez.',
			'focus' => 'agence web France',
		),
		'eyebrow'    => '// Partout en France',
		'h1'         => 'Une agence web & IA qui intervient partout en France.',
		'intro'      => 'Ancrés à Dijon, siège à Paris, nous menons des projets partout en France, à distance comme sur site. Où que vous soyez, vous bénéficiez de la même exigence et d\'un interlocuteur unique.',
		'crumbs'     => sdi_landing_crumbs( 'France entière' ),
		'cta'        => sdi_landing_cta(),
		'sections'   => array(
			array(
				'type'       => 'checklist',
				'eyebrow'    => '// Travailler à distance avec SDi',
				'title'      => 'La proximité, sans la contrainte géographique.',
				'body'       => array( 'Nos méthodes et nos outils de suivi nous permettent de collaborer efficacement avec des clients dans toute la France, avec des points réguliers et une totale transparence.' ),
				'card_title' => 'Comment on collabore',
				'points'     => array(
					'Réunions en visio et points d\'avancement réguliers',
					'Outils de suivi partagés et transparents',
					'Un interlocuteur unique dédié à votre projet',
					'Des livrables clairs à chaque étape',
					'Des déplacements possibles selon le projet',
					'La même exigence, où que vous soyez',
				),
			),
			array(
				'type'    => 'links',
				'eyebrow' => '// Nos services',
				'title'   => 'Tout le digital, partout en France.',
				'items'   => $service_links,
			),
			array(
				'type'  => 'richtext',
				'title' => 'Agence web et IA partout en France',
				'html'  => '<p>Vous n\'êtes ni à Dijon ni à Paris ? Aucun problème. SDi mène des projets <strong>partout en France</strong>, à distance, avec la même rigueur et le même niveau d\'exigence.</p><p>Que vous ayez besoin d\'un <a href="' . esc_url( sdi_page_url( 'creation-site-internet' ) ) . '">site internet</a>, d\'un <a href="' . esc_url( sdi_page_url( 'site-e-commerce' ) ) . '">e-commerce</a>, d\'une stratégie <a href="' . esc_url( sdi_page_url( 'referencement-seo' ) ) . '">SEO</a> ou d\'<a href="' . esc_url( sdi_page_url( 'agents-ia-sur-mesure' ) ) . '">agents IA</a>, nous vous accompagnons de bout en bout.</p>',
			),
			array(
				'type'    => 'faq',
				'title'   => 'Travailler à distance : vos questions',
				'items'   => array(
					array( 'q' => 'Peut-on vraiment mener un projet à distance ?', 'a' => 'Oui, c\'est le quotidien de beaucoup de nos projets. Des points réguliers, des outils partagés et une communication claire garantissent le même résultat qu\'en présentiel.' ),
					array( 'q' => 'Vous déplacez-vous ?', 'a' => 'Selon le projet et son ampleur, nous pouvons nous déplacer pour les moments clés. La majorité des échanges se fait toutefois en visio, plus souple pour tous.' ),
					array( 'q' => 'Où êtes-vous basés ?', 'a' => 'Notre siège est à Paris et notre ancrage historique à Dijon, mais nos clients sont partout en France.' ),
				),
			),
		),
		'final_cta'  => array( 'heading' => 'Un projet, où que vous soyez en France ?', 'text' => 'Parlons-en en visio : on vous propose une direction claire et un devis sous 24h.' ),
	);

	return $pages;
}

/**
 * Look up the landing config for a queried page, if any.
 *
 * @param WP_Post|null $post Post object.
 * @return array|null
 */
function sdi_landing_for_post( $post ) {
	if ( ! ( $post instanceof WP_Post ) || 'page' !== $post->post_type ) {
		return null;
	}
	$pages = sdi_landing_pages();
	return isset( $pages[ $post->post_name ] ) ? $pages[ $post->post_name ] : null;
}
