<?php
/**
 * Floating chatbot demo widget (front page). Purely visual mock, per the design.
 *
 * @package SDi
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<div class="sdi-bot" data-sdi-bot>
	<div class="sdi-bot__panel" role="dialog" aria-label="Assistant SDi (démo)">
		<div style="background:var(--gradient-navy);padding:18px 20px;display:flex;align-items:center;gap:12px;position:relative;">
			<div style="position:absolute;inset:0;background:var(--gradient-halo);"></div>
			<span style="position:relative;width:40px;height:40px;border-radius:12px;background:var(--gradient-brand);display:flex;align-items:center;justify-content:center;color:#fff;"><?php sdi_the_icon( 'sparkles', 20 ); ?></span>
			<div style="position:relative;flex:1;">
				<div style="color:#fff;font-weight:600;font-size:15px;">Assistant SDi</div>
				<div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--navy-300);"><span style="width:7px;height:7px;border-radius:50%;background:var(--green-400);display:inline-block;"></span>En ligne · propulsé par IA</div>
			</div>
			<button type="button" data-sdi-bot-close aria-label="Fermer" style="position:relative;width:32px;height:32px;border-radius:9px;border:none;background:rgba(255,255,255,0.1);color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;"><?php sdi_the_icon( 'x', 18 ); ?></button>
		</div>
		<div style="flex:1;overflow-y:auto;padding:20px;display:flex;flex-direction:column;gap:14px;background:var(--surface-page);">
			<div style="align-self:flex-start;max-width:82%;background:var(--white);border:1px solid var(--border-subtle);border-radius:16px 16px 16px 4px;padding:12px 15px;font-size:14px;line-height:1.5;color:var(--text-body);box-shadow:var(--shadow-xs);">Bonjour 👋 Je suis l'assistant IA de SDi. Je peux estimer votre projet, prendre un rendez-vous ou répondre à vos questions. Que puis-je faire pour vous ?</div>
			<div style="align-self:flex-end;max-width:82%;background:var(--brand-primary);color:#fff;border-radius:16px 16px 4px 16px;padding:12px 15px;font-size:14px;line-height:1.5;">Je veux refaire mon site + un chatbot pour mon domaine viticole.</div>
			<div style="align-self:flex-start;max-width:82%;background:var(--white);border:1px solid var(--border-subtle);border-radius:16px 16px 16px 4px;padding:12px 15px;font-size:14px;line-height:1.5;color:var(--text-body);box-shadow:var(--shadow-xs);">Parfait. Un site vitrine e-commerce + un agent IA de conseil client, c'est notre spécialité. Souhaitez-vous un <strong style="color:var(--brand-primary);">audit gratuit</strong> avec un chiffrage ?</div>
			<div style="align-self:flex-start;display:flex;gap:5px;padding:12px 15px;background:var(--white);border:1px solid var(--border-subtle);border-radius:16px;width:fit-content;box-shadow:var(--shadow-xs);">
				<span style="width:7px;height:7px;border-radius:50%;background:var(--navy-400);animation:sdiType 1.2s infinite;"></span>
				<span style="width:7px;height:7px;border-radius:50%;background:var(--navy-400);animation:sdiType 1.2s .2s infinite;"></span>
				<span style="width:7px;height:7px;border-radius:50%;background:var(--navy-400);animation:sdiType 1.2s .4s infinite;"></span>
			</div>
		</div>
		<div style="padding:14px 16px;border-top:1px solid var(--border-subtle);background:var(--white);display:flex;gap:10px;align-items:center;">
			<input type="text" placeholder="Écrivez votre message…" aria-label="Votre message" style="flex:1;border:1px solid var(--border-default);border-radius:12px;padding:11px 14px;font-family:var(--font-sans);font-size:14px;outline:none;color:var(--text-strong);">
			<button type="button" aria-label="Envoyer" style="width:44px;height:44px;flex-shrink:0;border-radius:12px;border:none;background:var(--gradient-brand);color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;"><?php sdi_the_icon( 'send', 18 ); ?></button>
		</div>
	</div>
	<button type="button" class="sdi-bot__fab" data-sdi-bot-toggle aria-label="Ouvrir l'assistant IA">
		<span class="sdi-bot__ico-open"><?php sdi_the_icon( 'message-circle', 26 ); ?></span>
		<span class="sdi-bot__ico-close"><?php sdi_the_icon( 'x', 26 ); ?></span>
	</button>
</div>
