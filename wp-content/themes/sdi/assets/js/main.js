/* SDi — front-end interactions: reveal, counters, mobile menu, chatbot. */
(function () {
  'use strict';

  // Signal JS is active (enables reveal hiding without FOUC).
  document.documentElement.classList.add('sdi-js');

  var reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function ready(fn) {
    if (document.readyState !== 'loading') { fn(); }
    else { document.addEventListener('DOMContentLoaded', fn); }
  }

  ready(function () {
    /* ---- Sticky header shrink on scroll ---- */
    var header = document.querySelector('[data-sdi-header]');
    if (header) {
      var onScroll = function () {
        if (window.scrollY > 8) { header.classList.add('is-scrolled'); }
        else { header.classList.remove('is-scrolled'); }
      };
      onScroll();
      window.addEventListener('scroll', onScroll, { passive: true });
    }

    /* ---- Mobile menu ---- */
    var burger = document.querySelector('[data-sdi-burger]');
    var menu = document.querySelector('[data-sdi-mobile-menu]');
    if (burger && menu) {
      burger.addEventListener('click', function () {
        var open = menu.classList.toggle('is-open');
        burger.setAttribute('aria-expanded', open ? 'true' : 'false');
      });
      menu.querySelectorAll('a').forEach(function (a) {
        a.addEventListener('click', function () { menu.classList.remove('is-open'); });
      });
    }

    /* ---- Scroll reveal ---- */
    var reveals = [].slice.call(document.querySelectorAll('.sdi-reveal'));
    var show = function (el) {
      var d = el.getAttribute('data-reveal-delay') || 0;
      el.style.transitionDelay = d + 'ms';
      el.classList.add('is-visible');
    };
    if (reduced) {
      reveals.forEach(show);
    } else if ('IntersectionObserver' in window) {
      var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (en) {
          if (en.isIntersecting) { show(en.target); io.unobserve(en.target); }
        });
      }, { threshold: 0.12, rootMargin: '0px 0px -6% 0px' });
      reveals.forEach(function (el) { io.observe(el); });
      // Safety fallback if observer never fires.
      setTimeout(function () {
        reveals.forEach(function (el) { if (!el.classList.contains('is-visible')) show(el); });
      }, 2400);
    } else {
      reveals.forEach(show);
    }

    /* ---- Animated counters ---- */
    var counters = [].slice.call(document.querySelectorAll('[data-count-to]'));
    var runCount = function (el) {
      var to = parseFloat(el.getAttribute('data-count-to'));
      var dec = parseInt(el.getAttribute('data-count-dec') || '0', 10);
      var pre = el.getAttribute('data-count-prefix') || '';
      var suf = el.getAttribute('data-count-suffix') || '';
      var fmt = function (v) { return dec ? v.toFixed(dec).replace('.', ',') : String(Math.round(v)); };
      if (reduced) { el.textContent = pre + fmt(to) + suf; return; }
      var dur = 1500, start = null;
      var step = function (now) {
        if (start === null) { start = now; }
        var p = Math.min((now - start) / dur, 1);
        var e = 1 - Math.pow(1 - p, 3);
        el.textContent = pre + fmt(to * e) + suf;
        if (p < 1) { requestAnimationFrame(step); }
      };
      requestAnimationFrame(step);
    };
    if (counters.length && 'IntersectionObserver' in window) {
      var cio = new IntersectionObserver(function (entries) {
        entries.forEach(function (en) {
          if (en.isIntersecting) { runCount(en.target); cio.unobserve(en.target); }
        });
      }, { threshold: 0.4 });
      counters.forEach(function (el) { cio.observe(el); });
    } else {
      counters.forEach(runCount);
    }

    /* ---- Chatbot demo widget ---- */
    var bot = document.querySelector('[data-sdi-bot]');
    if (bot) {
      var fab = bot.querySelector('[data-sdi-bot-toggle]');
      var closeBtns = bot.querySelectorAll('[data-sdi-bot-close]');
      var openTriggers = document.querySelectorAll('[data-sdi-bot-open]');
      var toggle = function () { bot.classList.toggle('is-open'); };
      var open = function () { bot.classList.add('is-open'); };
      var close = function () { bot.classList.remove('is-open'); };
      if (fab) { fab.addEventListener('click', toggle); }
      closeBtns.forEach(function (b) { b.addEventListener('click', close); });
      openTriggers.forEach(function (b) { b.addEventListener('click', function (e) { e.preventDefault(); open(); }); });
    }

    /* ---- Smooth-scroll helper for elements that must offset the sticky header ---- */
    document.querySelectorAll('[data-scroll-to]').forEach(function (trigger) {
      trigger.addEventListener('click', function (e) {
        var sel = trigger.getAttribute('data-scroll-to');
        var target = sel && document.querySelector(sel);
        if (target) {
          e.preventDefault();
          var top = target.getBoundingClientRect().top + window.scrollY - 60;
          window.scrollTo({ top: top, behavior: reduced ? 'auto' : 'smooth' });
        }
      });
    });
  });
})();
