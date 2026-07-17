/* SDi Agent IA — front-end chat widget */
(function () {
  'use strict';
  if (typeof SDI_AI === 'undefined') { return; }

  var cfg = SDI_AI;
  var history = [];        // [{role, content}]
  var busy = false;
  var greeted = false;
  var transcriptSent = false;
  var userMsgCount = 0;    // number of visitor messages
  var lastLead = {};       // captured {name, email}

  var SPARK = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 2.8a1 1 0 0 1 2 0l1 5.3a2 2 0 0 0 1.6 1.6l5.3 1a1 1 0 0 1 0 2l-5.3 1a2 2 0 0 0-1.6 1.6l-1 5.3a1 1 0 0 1-2 0l-1-5.3a2 2 0 0 0-1.6-1.6l-5.3-1a1 1 0 0 1 0-2l5.3-1A2 2 0 0 0 10 8.1z"/></svg>';
  var SEND  = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 21.7a.5.5 0 0 0 .94-.02l6.5-19a.5.5 0 0 0-.64-.64l-19 6.5a.5.5 0 0 0-.02.94l7.9 3.2a2 2 0 0 1 1.1 1.1z"/><path d="m21.85 2.15-10.94 10.94"/></svg>';
  var CLOSE = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>';

  function el(tag, cls, html) {
    var e = document.createElement(tag);
    if (cls) { e.className = cls; }
    if (html !== undefined) { e.innerHTML = html; }
    return e;
  }
  function esc(s) { var d = document.createElement('div'); d.textContent = s; return d.innerHTML; }

  // ---- Build DOM ----
  var root = el('div', 'sdi-ai');
  root.style.setProperty('--sdi-ai-accent', cfg.accent || '#1D6EFF');

  var launcher = el('button', 'sdi-ai__launcher');
  launcher.type = 'button';
  launcher.setAttribute('aria-label', cfg.buttonLabel || 'Parler à un agent IA');
  launcher.innerHTML = '<span class="sdi-ai__spark">' + SPARK + '</span><span class="sdi-ai__launcher-label">' + esc(cfg.buttonLabel || 'Parler à un agent IA') + '</span><span class="sdi-ai__launcher-dot"></span>';

  var panel = el('div', 'sdi-ai__panel');
  panel.setAttribute('role', 'dialog');
  panel.setAttribute('aria-label', cfg.agentName || 'Agent IA');
  panel.innerHTML =
    '<div class="sdi-ai__head">' +
      '<span class="sdi-ai__avatar">' + SPARK + '</span>' +
      '<div class="sdi-ai__head-txt"><div class="sdi-ai__name">' + esc(cfg.agentName || 'Conseiller IA') + '</div><div class="sdi-ai__status"><i></i>En ligne · propulsé par IA</div></div>' +
      '<button type="button" class="sdi-ai__close" aria-label="Fermer">' + CLOSE + '</button>' +
    '</div>' +
    '<div class="sdi-ai__body" data-body></div>' +
    '<div class="sdi-ai__foot">' +
      '<div class="sdi-ai__inputrow">' +
        '<input type="text" class="sdi-ai__input" data-input placeholder="Écrivez votre message…" aria-label="Votre message">' +
        '<button type="button" class="sdi-ai__send" data-send aria-label="Envoyer">' + SEND + '</button>' +
      '</div>' +
      '<div class="sdi-ai__actions"><button type="button" class="sdi-ai__link" data-transcript>Être recontacté par un conseiller</button></div>' +
    '</div>';

  root.appendChild(panel);
  root.appendChild(launcher);

  var body    = panel.querySelector('[data-body]');
  var input   = panel.querySelector('[data-input]');
  var sendBtn = panel.querySelector('[data-send]');

  function scrollDown() { body.scrollTop = body.scrollHeight; }

  function addMsg(role, text) {
    var m = el('div', 'sdi-ai__msg ' + (role === 'assistant' ? 'sdi-ai__msg--bot' : 'sdi-ai__msg--user'));
    m.innerHTML = esc(text);
    body.appendChild(m);
    scrollDown();
  }

  function showTyping() {
    var t = el('div', 'sdi-ai__typing');
    t.innerHTML = '<span></span><span></span><span></span>';
    t.setAttribute('data-typing', '1');
    body.appendChild(t);
    scrollDown();
    return t;
  }

  function post(action, extra) {
    var data = new URLSearchParams();
    data.append('action', action);
    data.append('nonce', cfg.nonce);
    data.append('history', JSON.stringify(history));
    Object.keys(extra || {}).forEach(function (k) { data.append(k, extra[k]); });
    return fetch(cfg.ajaxUrl, { method: 'POST', body: data, credentials: 'same-origin' })
      .then(function (r) { return r.json(); });
  }

  function send() {
    var text = (input.value || '').trim();
    if (!text || busy) { return; }
    input.value = '';
    addMsg('user', text);
    history.push({ role: 'user', content: text });
    userMsgCount++;
    // Auto-detect an e-mail typed in the chat, so leads are actionable.
    if (!lastLead.email) {
      var m = text.match(/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}/i);
      if (m) { lastLead.email = m[0]; }
    }
    busy = true; sendBtn.disabled = true;
    var typing = showTyping();

    post('sdi_ai_chat', { message: text }).then(function (res) {
      typing.remove();
      var reply = (res && res.success && res.data && res.data.reply) ? res.data.reply
        : "Désolé, une erreur est survenue. Réessayez ou appelez-nous au 09 80 80 62 96.";
      addMsg('assistant', reply);
      history.push({ role: 'assistant', content: reply });
    }).catch(function () {
      typing.remove();
      addMsg('assistant', "Connexion interrompue. Réessayez dans un instant.");
    }).finally(function () {
      busy = false; sendBtn.disabled = false; input.focus();
    });
  }

  // ---- Transcript / lead capture ----
  function sendTranscript(extra) {
    // Send the conversation to SDi (deduplicated).
    if (transcriptSent || userMsgCount === 0) { return Promise.resolve(); }
    transcriptSent = true;
    return post('sdi_ai_transcript', Object.assign({ page: window.location.href }, lastLead, extra || {}));
  }

  function showLeadForm() {
    if (body.querySelector('[data-lead]')) { return; }
    var wrap = el('div', 'sdi-ai__lead');
    wrap.setAttribute('data-lead', '1');
    var fields = cfg.collectEmail
      ? '<label>Vos coordonnées pour être recontacté</label>' +
        '<input type="text" data-name placeholder="Votre nom" value="' + esc(lastLead.name || '') + '">' +
        '<input type="email" data-email placeholder="Votre e-mail" value="' + esc(lastLead.email || '') + '">'
      : '<label>Envoyer cette conversation à l\'équipe SDi ?</label>';
    wrap.innerHTML = fields +
      '<div style="display:flex;gap:8px;margin-top:4px;">' +
        '<button type="button" class="sdi-ai__btn" data-send-transcript>Envoyer</button>' +
        '<button type="button" class="sdi-ai__btn sdi-ai__btn--ghost" data-cancel>Annuler</button>' +
      '</div>';
    body.appendChild(wrap);
    scrollDown();

    wrap.querySelector('[data-cancel]').addEventListener('click', function () { wrap.remove(); });
    wrap.querySelector('[data-send-transcript]').addEventListener('click', function () {
      var nameEl = wrap.querySelector('[data-name]');
      var mailEl = wrap.querySelector('[data-email]');
      if (nameEl) { lastLead.name = nameEl.value; }
      if (mailEl) { lastLead.email = mailEl.value; }
      wrap.querySelector('[data-send-transcript]').disabled = true;
      // Force a fresh send even if an auto-send already happened.
      transcriptSent = false;
      sendTranscript().then(function (res) {
        wrap.remove();
        var msg = (res && res.data && res.data.message) ? res.data.message
          : 'Merci ! Votre message a bien été transmis. Nous vous recontactons vite.';
        var note = el('div', 'sdi-ai__notice', esc(msg));
        body.appendChild(note); scrollDown();
      }).catch(function () {
        transcriptSent = false;
        wrap.querySelector('[data-send-transcript]').disabled = false;
      });
    });
  }

  // Fire-and-forget send when the visitor leaves the page (keeps the lead).
  function flushBeacon() {
    if (transcriptSent || userMsgCount === 0 || !navigator.sendBeacon) { return; }
    transcriptSent = true;
    var data = new URLSearchParams();
    data.append('action', 'sdi_ai_transcript');
    data.append('nonce', cfg.nonce);
    data.append('history', JSON.stringify(history));
    data.append('page', window.location.href);
    if (lastLead.name) { data.append('name', lastLead.name); }
    if (lastLead.email) { data.append('email', lastLead.email); }
    try { navigator.sendBeacon(cfg.ajaxUrl, data); } catch (e) {}
  }
  window.addEventListener('pagehide', flushBeacon);
  document.addEventListener('visibilitychange', function () {
    if (document.visibilityState === 'hidden') { flushBeacon(); }
  });

  // ---- Open / close ----
  function open() {
    root.classList.add('is-open');
    if (!greeted) {
      greeted = true;
      addMsg('assistant', cfg.welcome);
      history.push({ role: 'assistant', content: cfg.welcome });
    }
    setTimeout(function () { input.focus(); }, 60);
  }
  function close() {
    root.classList.remove('is-open');
    // Auto-send the conversation to SDi so no lead is lost, even without the button.
    sendTranscript();
  }

  launcher.addEventListener('click', open);
  panel.querySelector('.sdi-ai__close').addEventListener('click', close);
  sendBtn.addEventListener('click', send);
  input.addEventListener('keydown', function (e) { if (e.key === 'Enter') { e.preventDefault(); send(); } });
  panel.querySelector('[data-transcript]').addEventListener('click', showLeadForm);

  function ready(fn) { if (document.readyState !== 'loading') { fn(); } else { document.addEventListener('DOMContentLoaded', fn); } }
  ready(function () {
    document.body.appendChild(root);
    // Theme integration: any [data-sdi-bot-open] trigger opens the chat.
    document.querySelectorAll('[data-sdi-bot-open]').forEach(function (t) {
      t.addEventListener('click', function (e) { e.preventDefault(); open(); });
    });
  });
})();
