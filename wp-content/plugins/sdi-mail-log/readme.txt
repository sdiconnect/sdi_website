=== SDi Mail Log ===
Contributors: sdi
Tags: email, log, smtp, debug, deliverability
Requires at least: 6.0
Tested up to: 6.5
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later

Journalise tous les e-mails envoyés par le site — même en cas d'échec d'envoi —
et permet de les consulter, rechercher et renvoyer.

== Description ==

SDi Mail Log garde une trace de **chaque e-mail** qui quitte le site via la
fonction standard `wp_mail()` de WordPress : formulaire de contact, agent IA,
notifications WordPress, autres extensions… Même si l'envoi échoue (clé d'API
invalide, SMTP indisponible, quota dépassé), l'e-mail et l'erreur sont
enregistrés — vous ne perdez plus jamais une demande.

Depuis **Outils → Suivi des e-mails** :

* Liste de tous les e-mails (date, destinataire, objet, source, statut).
* Statut **Envoyé** / **Échec** (avec le message d'erreur exact en cas d'échec).
* Recherche par destinataire, objet ou contenu ; filtre par statut.
* Vue détaillée : en-têtes, contenu complet, erreur.
* **Renvoyer** un e-mail en un clic (utile après un incident SMTP).
* Réglages : durée de conservation (purge automatique) et journalisation ou non
  du corps des e-mails.
* Bouton pour vider tout le journal.

Aucune configuration requise : activez l'extension, tout est journalisé
automatiquement.

== Installation ==

1. Téléversez le dossier `sdi-mail-log` dans `/wp-content/plugins/` (ou installez le zip via Extensions → Ajouter).
2. Activez l'extension.
3. Rendez-vous dans **Outils → Suivi des e-mails**.

== Notes ==

* Si vous utilisez une extension SMTP (ex. WP Mail SMTP), les e-mails sont
  également journalisés ici : vous disposez d'une double trace.
* Le contenu des e-mails peut inclure des données personnelles : pensez à une
  durée de conservation raisonnable (30 jours par défaut) selon votre politique RGPD.

== Changelog ==

= 1.0.0 =
* Version initiale : journalisation de tous les envois `wp_mail`, back-office de
  consultation/recherche, détail, renvoi, réglages de rétention et purge auto.
