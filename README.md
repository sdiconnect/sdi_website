# sdi_website

Site WordPress de **SDi — Solutions Digitales Intégrées** ([sdi-connect.com](https://sdi-connect.com)),
agence web & IA à Dijon et Paris.

Ce dépôt contient le **thème sur-mesure** issu de la refonte design, prêt à être
déployé dans une installation WordPress.

## Thème

➡️ [`wp-content/themes/sdi/`](wp-content/themes/sdi/) — voir son
[README](wp-content/themes/sdi/README.md) pour l'installation, la structure des
templates, la gestion des réalisations et le formulaire de contact.

Points clés :

- Recréation haute-fidélité du design system SDi (navy / bleu électrique / cyan).
- WordPress natif, sans page builder ni dépendance obligatoire.
- Icônes Lucide inlinées, animations respectueuses de `prefers-reduced-motion`.
- SEO prioritaire : titres/meta uniques, Open Graph, JSON-LD (LocalBusiness,
  AggregateRating, Breadcrumb), ancrage local Dijon/Côte-d'Or + national.
- CPT « Réalisations » pour gérer les études de cas depuis l'admin.

## Déploiement

Copier `wp-content/themes/sdi/` dans le `wp-content/themes/` de l'installation
WordPress cible, puis activer le thème. Les pages et le contenu de démonstration
sont créés automatiquement à l'activation.
