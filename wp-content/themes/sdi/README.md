# Thème WordPress SDi

Thème sur-mesure pour **SDi — Solutions Digitales Intégrées** (sdi-connect.com),
agence web & IA à Dijon et Paris. Recréation haute-fidélité de la refonte « Claude
Design » dans un thème WordPress natif, sans page builder ni dépendance à un plugin.

## Nouveautés v1.0.10

- **Anti-spam renforcé sur tous les formulaires** (contact, accueil, pages
  d'atterrissage — même moteur). En plus du nonce et du premier pot de miel :
  - **2ᵉ pot de miel** (champ « url » caché) ;
  - **piège temporel** (soumission en < 3 s = bot) ;
  - **rejet des scripts non-latins** (cyrillique, CJK, arabe, hébreu, thaï,
    hangûl, kana) — bloque le spam type « Прывітанне… » sans gêner le français ;
  - **filtrage des liens** (nom = URL, ou ≥ 2 liens dans le message) et
    quelques mots-clés spam ;
  - **limite par IP** (5 envois / 15 min).

  Les envois suspects sont **silencieusement ignorés** (le bot croit avoir
  réussi). Aucune clé ni service externe requis. Pour une protection encore
  plus forte, un CAPTCHA invisible (Cloudflare Turnstile) peut être ajouté.

## Nouveautés v1.0.9

- **Mockups du hero gérables depuis Personnaliser** (Apparence → Personnaliser →
  « SDi — Hero (réalisations) ») : **3 emplacements** (image + libellé). Chaque
  emplacement correspond à une position fixe du collage (arrière / principale /
  avant). Laissez une image vide pour conserver le visuel par défaut fourni.
  Les images téléversées sont utilisées telles quelles (les défauts restent en
  WebP responsive). Idéal : ~1024×683.

## Nouveautés v1.0.8

- **Nouveau hero de la page d'accueil** (« Claude Design ») : le bloc « tableau de
  bord » abstrait est remplacé par un **collage de 3 vraies réalisations clients**
  (Tereos, Audialys, Orvitis) — mockups superposés, légèrement inclinés, fondus
  dans le fond navy (voile + étalonnage CSS), avec **flottement animé** et deux
  **pastilles de preuve sociale** (« +38 % leads/mois » en verre dépoli, note
  Google 4,9/5). Passe automatiquement en 1 colonne sous ~840px (sans media query).
- Visuels servis en **WebP responsive** (`srcset` 512/1024, < 60 Ko chacun),
  `fetchpriority="high"` sur le mockup principal (LCP). Fichiers dans
  `assets/img/hero/`.
- Typo conservée (police maison du thème) : aucune webfont supplémentaire chargée.
  Note Google reprise depuis Personnaliser (avis clients).

## Nouveautés v1.0.7

- **Barre d'action mobile repensée** : sur mobile, les boutons « Appeler » et
  « Nous contacter » sont remplacés par **un seul appel à l'action fort vers
  l'agent IA** — « Parler à un conseiller » (dégradé de marque, pastille « en
  ligne »). Il ouvre le chat de l'extension **SDi Agent IA** (via
  `data-sdi-bot-open`) ; sans l'extension, il mène à la page Contact.
- Le **lanceur flottant** de l'extension est masqué sur mobile pour ne laisser
  **qu'un seul CTA** (la barre), sans doublon.

## Nouveautés v1.0.6

- Page d'accueil : la section « Réalisations » n'affiche plus que **3 projets**
  (ligne pleine) suivis d'un **bouton CTA centré « Voir toutes nos réalisations »**.

## Nouveautés v1.0.5

- **Icônes « glass 3D »** (dégradé + reflet + halo, aux couleurs SDi) sur les
  groupes d'icônes clés de la page d'accueil : Services, Capacités IA (variante
  cyan sur fond sombre), Pourquoi SDi et Méthode. Vectoriel (CSS), donc net et léger.

## Nouveautés v1.0.4

- **Uniformisation automatique des logos clients** : n'importe quel logo ajouté
  (en couleur, foncé ou clair) est automatiquement rendu en blanc/muet sur le
  bandeau sombre, pour un rendu homogène sans retouche. Survol = logo un peu plus lumineux.

## Nouveautés v1.0.3

- **Logos clients gérables depuis Personnaliser** (Apparence → Personnaliser →
  « SDi — Logos clients ») : **12 emplacements** (image + texte alternatif).
  Laissez un emplacement vide pour le masquer. Défauts pré-remplis avec les logos
  fournis.
- Le **bandeau « Ils nous font confiance » prend désormais toute la largeur**
  de l'écran (défilement bord à bord avec fondu sur les côtés).

## Nouveautés v1.0.2

- **Avis gérables depuis Personnaliser** (Apparence → Personnaliser → « SDi — Avis
  clients ») : 3 avis (nom, fonction, texte, étoiles) + note moyenne + nombre d'avis.
- **Étoiles pleines dorées** partout (au lieu du contour), pour ne pas laisser
  croire à un mauvais avis.
- **Header retravaillé** : bouton « Nous contacter » en dégradé (bien visible),
  logo plus grand qui se réduit au défilement (sticky shrink), et **halo animé
  bleu/violet** en fond de bannière.
- **Pages Confidentialité + Mentions légales** créées automatiquement.
- **Page de vente campagne** « Agent IA pour entreprise à Dijon » (`/agent-ia-dijon/`)
  avec formulaire de capture, pensée pour vos campagnes webmarketing (source de lead
  tracée dans l'e-mail reçu).
- **RCS Paris** (au lieu de RCS Dijon) dans le pied de page.
- Le chatbot de démo du thème est retiré : il est remplacé par l'extension
  **SDi Agent IA** (chatbot réel, voir le dossier `wp-content/plugins/sdi-agent-ia`).

## Nouveautés v1.0.1

- **12 nouvelles pages SEO** créées automatiquement à la mise à jour du thème
  (services détaillés, IA, zones locales) — voir « Pages marketing (v1.0.1) ».
- **Compatibilité Yoast / Rank Math** : si un plugin SEO est actif, le thème lui
  cède le titre, la meta description, l'Open Graph et le fil d'ariane. Il conserve
  toutefois le schema **LocalBusiness + AggregateRating** (4,9/109 avis) et le
  schema **FAQ**, que Yoast gratuit ne fournit pas.
- **Mega titles + meta descriptions** pré-remplis dans Yoast pour chaque page.
- **Correction de contenu** : plus aucune mention de « sans sous-traitance / en
  interne » (formulations neutres axées interlocuteur unique et maîtrise
  de bout en bout).
- **Maillage interne** renforcé (footer, pages zones ↔ services, FAQ).

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

## Pages marketing (v1.0.1)

Ces pages sont **créées automatiquement** dès que le thème passe en v1.0.1
(à l'activation, ou au premier chargement de l'admin après mise à jour), avec leurs
**mega title + meta description Yoast** déjà renseignés :

| Slug | Page |
|---|---|
| `creation-site-internet` | Création de site internet |
| `site-e-commerce` | Site e-commerce |
| `referencement-seo` | Référencement SEO |
| `google-ads` | Google Ads |
| `saas-sur-mesure` | SaaS sur-mesure |
| `agents-ia-sur-mesure` | Agents IA sur-mesure |
| `automatisation-ia` | Automatisation IA |
| `agence-web-dijon` | Agence web Dijon |
| `seo-dijon-cote-dor` | SEO Dijon & Côte-d'Or |
| `agence-web-paris` | Agence web Paris |
| `seo-national` | SEO national |
| `france-entiere` | France entière |

Le **contenu et le design** de ces pages vivent dans le thème
(`inc/landing-data.php` + `inc/landing.php`, rendus via `page.php`). Pour modifier
un texte, éditez `inc/landing-data.php`. Les titres/descriptions Yoast restent
modifiables normalement depuis l'encart Yoast de chaque page.

> **Import manuel (optionnel)** : un fichier `sdi-pages-v1.0.1.wxr.xml` est fourni
> pour créer ces pages via **Outils → Importer → WordPress**, si vous préférez.
> **N'utilisez qu'UNE méthode** : soit la création automatique du thème, soit
> l'import WXR — pas les deux, pour éviter les doublons.

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
