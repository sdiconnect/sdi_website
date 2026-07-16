# Thème WordPress SDi

Thème sur-mesure pour **SDi — Solutions Digitales Intégrées** (sdi-connect.com),
agence web & IA à Dijon et Paris. Recréation haute-fidélité de la refonte « Claude
Design » dans un thème WordPress natif, sans page builder ni dépendance à un plugin.

## Pile & principes

- WordPress natif (thème classique PHP), **aucune dépendance externe obligatoire**.
- Design system SDi reconstruit en CSS (`assets/css/tokens.css`) : navy `#0F172A`,
  bleu électrique `#1D6EFF`, cyan `#00A5E4`, magenta ponctuel, Roboto + Roboto Mono.
- Icônes **Lucide** inlinées en SVG (`inc/icons.php`) — 2px, `currentColor`, zéro JS.
- Interactions : reveal au scroll, compteurs animés, menu mobile, chatbot démo,
  respect de `prefers-reduced-motion` (`assets/js/main.js`).
- SEO prioritaire : `<title>`/meta uniques par page, Open Graph/Twitter, JSON-LD
  `ProfessionalService` + `AggregateRating` (4,9/109) + `BreadcrumbList` (`inc/seo.php`).

## Installation

1. Copier le dossier `sdi/` dans `wp-content/themes/`.
2. Dans l'admin WordPress : **Apparence → Thèmes → Activer « SDi — Digital & IA »**.
3. À l'activation, le thème crée automatiquement (idempotent) :
   - les pages **Accueil, Services, Agents IA, Réalisations, Agence, Contact** (aux
     bons slugs, avec la page d'accueil définie comme page statique) ;
   - le CPT **Réalisations** pré-rempli avec les 6 études de cas du design
     (Orvitis complet, Ghitti, Tereos, Krys, Dijon Céréales, Nature & Découvertes).
4. **Réglages → Permaliens** : choisir « Titre de la publication » (`/%postname%/`)
   puis enregistrer, pour de belles URLs et les études de cas.

## Structure des templates

| Fichier | Rôle |
|---|---|
| `front-page.php` | Accueil (13 sections + formulaire + chatbot) |
| `page-services.php` | Services (4 blocs / 8 expertises) |
| `page-agents-ia.php` | Agents IA |
| `page-realisations.php` | Grille des réalisations (dynamique, CPT) |
| `single-realisation.php` | Template d'étude de cas |
| `page-agence.php` | Agence |
| `page-contact.php` | Contact |
| `page.php` | Pages génériques (mentions légales, confidentialité…) |
| `header.php` / `footer.php` | Layout partagé (nav, footer, barre mobile) |
| `template-parts/chatbot.php` | Widget chatbot flottant (démo) |
| `inc/` | icons · components · cpt · seo · contact |

## Gérer les réalisations (études de cas)

**Réalisations** dans l'admin. Chaque entrée = une carte + une page d'étude de cas :

- **Titre** = titre du projet ; **Contenu** = récit Contexte / Réponse / Résultats.
- **Image mise en avant** = couverture (sinon renseigner un fond CSS dans
  « Fond de couverture »).
- Métabox « Détails » : client, secteur, badge, métrique de carte, fiche mission
  (périmètre, année), tags, témoignage, et les 4 métriques d'en-tête
  (format `valeur|libellé`, une par ligne).

## Formulaire de contact

Traité par WordPress (`admin-post.php`, action `sdi_contact`) avec **nonce +
honeypot**, envoi via `wp_mail()`. Destinataire par défaut : `contact@sdi-connect.com`.

Pour changer l'adresse : option `sdi_contact_email`, ou le filtre :

```php
add_filter( 'sdi_contact_recipient', fn() => 'devis@sdi-connect.com' );
```

> En production, configurer un vrai transport SMTP (ex. plugin WP Mail SMTP) pour
> la délivrabilité.

## Réseaux sociaux & identité

**Apparence → Personnaliser → SDi — Réseaux sociaux** : URLs LinkedIn / Instagram /
Facebook (aussi injectées dans le `sameAs` du JSON-LD).

## Coordonnées (NAP)

Siège : 60 rue François 1er, 75008 Paris · Tél : 09 80 80 62 96 ·
`contact@sdi-connect.com` · RCS Dijon 827 966 946. Centralisées dans
`sdi_company()` (`inc/seo.php`).

## À faire côté production

- Remplacer les métriques/témoignages « plausibles mais fictifs » par des chiffres réels.
- Créer les pages **Mentions légales** et **Confidentialité** (slugs `mentions-legales`,
  `confidentialite`).
- Générer un `sitemap.xml` (natif WP) et vérifier `robots.txt`.
- Intégrer un vrai chatbot si souhaité (le widget actuel est une démo visuelle).
