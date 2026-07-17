=== SDi Agent IA ===
Contributors: sdi
Tags: chatbot, ai, mistral, lead generation, chat
Requires at least: 6.0
Tested up to: 6.5
Requires PHP: 7.4
Stable tag: 1.0.2
License: GPLv2 or later

Agent conversationnel IA (chatbot) pour le site SDi : widget flottant
« Parler à un agent commercial IA », propulsé par Mistral, avec export de la
discussion par e-mail.

== Description ==

SDi Agent IA ajoute un widget de chat flottant en bas de votre site. Il répond
aux questions de vos visiteurs à l'aide d'un modèle d'IA (Mistral par défaut,
ou toute API compatible OpenAI), qualifie les leads et peut envoyer la
conversation par e-mail à l'adresse de votre choix.

Réglages (Réglages → SDi Agent IA) :

* Activer / désactiver le widget
* Texte du bouton (branding) et nom de l'agent
* Message d'accueil
* Fournisseur IA (Mistral / OpenAI compatible), modèle et clé API
* « Éducation » de l'agent via un prompt système
* E-mail de réception de fin de conversation (modifiable)
* Proposition au visiteur de laisser ses coordonnées
* Couleur d'accent

La clé API reste stockée côté serveur : elle n'est jamais exposée aux visiteurs.
Les appels au modèle sont effectués depuis le serveur WordPress.

== Installation ==

1. Téléversez le dossier `sdi-agent-ia` dans `/wp-content/plugins/` (ou installez le zip via Extensions → Ajouter).
2. Activez l'extension.
3. Allez dans Réglages → SDi Agent IA, collez votre clé API Mistral et personnalisez l'agent.

== Fonctionnement ==

* Le visiteur clique sur le bouton flottant et discute avec l'agent.
* À la fin, il peut laisser son e-mail : la conversation est envoyée à votre adresse.
* Sans clé API, le widget fonctionne en mode « capture de lead » (message d'attente + envoi par e-mail).

== Changelog ==

= 1.0.2 =
* Fenêtre de chat agrandie et centrée à l'ouverture (≈75 % de la largeur et de la hauteur, ancrée en bas) pour une expérience immersive.
* Fond assombri (backdrop) derrière la fenêtre ; clic sur le fond ou touche Échap pour réduire.
* Bouton « Réduire » plus visible dans l'en-tête (la conversation est conservée).

= 1.0.1 =
* Bouton lanceur centré en bas de page.
* Envoi automatique de la conversation à SDi en fin d'échange (fermeture du chat ou départ de la page), en plus du bouton manuel — plus aucun lead perdu.
* Détection automatique de l'e-mail saisi dans la discussion.

= 1.0.0 =
* Version initiale : widget flottant, réglages back-office, intégration Mistral / OpenAI, export e-mail de la conversation.
