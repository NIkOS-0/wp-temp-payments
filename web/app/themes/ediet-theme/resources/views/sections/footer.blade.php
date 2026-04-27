@php
  // ─── ACF: load footer options with sensible fallbacks ───────────────────
  $f = function ($key, $default = null) {
    if (!function_exists('get_field')) return $default;
    $v = get_field($key, 'option');
    return ($v === null || $v === false || $v === '') ? $default : $v;
  };

  $brand_logo       = $f('footer_brand_logo', '');
  $brand_tagline    = $f('footer_brand_tagline', 'Персонализированные решения для здорового питания. Научно-обоснованный подход к вашим целям.');

  $nl_enabled       = (bool) $f('footer_newsletter_enabled', true);
  $nl_title         = $f('footer_newsletter_title', 'Подпишитесь на рассылку');
  $nl_subtitle      = $f('footer_newsletter_subtitle', 'Получайте первыми новости, акции и материалы от наших экспертов.');
  $nl_placeholder   = $f('footer_newsletter_placeholder', 'Ваш e-mail');
  $nl_button        = $f('footer_newsletter_button', 'Подписаться');

  $columns          = $f('footer_columns', []) ?: [];

  $social_title     = $f('footer_social_title', 'Мы в соцсетях');
  $social_items     = $f('footer_social_items', []) ?: [];

  $contacts_title   = $f('footer_contacts_title', 'Связаться с нами');
  $contacts_items   = $f('footer_contacts_items', []) ?: [];

  $trust_title      = $f('footer_trust_title', 'Знаки доверия');
  $trust_items      = $f('footer_trust_items', []) ?: [];

  $payment_title    = $f('footer_payment_title', 'Оплата и валюты');
  $payment_items    = $f('footer_payment_items', []) ?: [];
  $security_note    = $f('footer_security_note', 'Все платежи защищены протоколом TLS 1.3. Данные карт не хранятся на наших серверах. PCI DSS Level 1 Compliant.');

  $req_title        = $f('footer_requisites_title', 'Реквизиты');
  $req_content      = $f('footer_requisites', '');

  $bottom_links     = $f('footer_bottom_links', []) ?: [];
  $copyright_tpl    = $f('footer_copyright', '© %year% E-Diet. Все права защищены.');
  $copyright_text   = str_replace('%year%', date('Y'), $copyright_tpl);

  $lang_enabled     = (bool) $f('footer_lang_enabled', true);
  $lang_label       = $f('footer_lang_label', 'RUS');

  // ─── Inline SVG icon map for social types ───────────────────────────────
  $social_icon = function ($type) {
    $map = [
      'youtube'         => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M23.5 7.3c-.3-1-1-1.8-2-2.1C19.5 4.7 12 4.7 12 4.7s-7.5 0-9.5.5c-1 .3-1.7 1.1-2 2.1C0 9.3 0 12 0 12s0 2.7.5 4.7c.3 1 1 1.8 2 2.1 2 .5 9.5.5 9.5.5s7.5 0 9.5-.5c1-.3 1.7-1.1 2-2.1.5-2 .5-4.7.5-4.7s0-2.7-.5-4.7zM9.6 15.8V8.2l6.4 3.8-6.4 3.8z"/></svg>',
      'telegram'        => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M9.78 18.65l.28-4.23 7.68-6.92c.34-.31-.07-.46-.52-.19L7.74 13.3 3.64 12c-.88-.25-.89-.86.2-1.3l15.97-6.16c.73-.33 1.43.18 1.15 1.3l-2.72 12.81c-.19.91-.74 1.13-1.5.71L12.6 16.3l-1.99 1.93c-.23.23-.42.42-.83.42z"/></svg>',
      'telegram-channel'=> '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M9.78 18.65l.28-4.23 7.68-6.92c.34-.31-.07-.46-.52-.19L7.74 13.3 3.64 12c-.88-.25-.89-.86.2-1.3l15.97-6.16c.73-.33 1.43.18 1.15 1.3l-2.72 12.81c-.19.91-.74 1.13-1.5.71L12.6 16.3l-1.99 1.93c-.23.23-.42.42-.83.42z"/></svg>',
      'whatsapp'        => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.5 14.4c-.3-.1-1.7-.9-2-1-.3-.1-.5-.1-.7.1-.2.2-.8.9-.9 1.1-.2.2-.3.2-.6.1-.3-.1-1.2-.5-2.3-1.5-.8-.7-1.4-1.7-1.6-2-.2-.3 0-.4.1-.5.1-.1.3-.3.4-.4.1-.1.2-.3.3-.4.1-.2 0-.3 0-.4-.1-.1-.7-1.7-.9-2.3-.2-.6-.5-.5-.7-.5h-.6c-.2 0-.5.1-.7.3-.3.3-1 1-1 2.4 0 1.4 1 2.8 1.2 2.9.1.2 2 3.2 5 4.5 1.7.7 2.4.8 3.3.7.5-.1 1.7-.7 1.9-1.4.2-.7.2-1.3.2-1.4-.2-.1-.4-.2-.6-.3zM12 2.2C6.6 2.2 2.3 6.5 2.3 11.9c0 1.7.5 3.4 1.3 4.9L2 22l5.3-1.4c1.4.8 3 1.2 4.7 1.2 5.4 0 9.7-4.3 9.7-9.7S17.4 2.2 12 2.2z"/></svg>',
      'vk'              => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M13.16 16.02c-.38.33-1.07.15-1.54-.2-.66-.52-1.48-1.29-2-2.06-.55-.9-.9-1.67-1.06-1.92-.32-.47-.82-.37-.82.1v3.7c0 .26-.21.48-.47.48H5.84c-.87 0-3.58-3.3-3.58-6.6 0-.54.28-.89.6-1 .18-.06.38-.06.55 0 .24.07.46.22.59.45.62 1.14 1.47 2.88 2.61 4.31.36.45.85.46.9-.07.1-1.05.05-2.73-.03-3.81-.05-.59-.25-.85-.58-.98-.11-.04-.22-.13-.14-.24.3-.39.7-.43 1.12-.43H8.8c.52 0 .78.35.78.87v3.76c0 .4.32.52.61.24 1.15-1.09 2.44-2.77 3.3-4.38.18-.33.55-.5.91-.48h2.02c.53 0 .79.31.65.72-.43 1.37-1.56 2.96-2.71 4.45-.23.3-.26.58.06.95.67.78 1.97 2.05 2.3 2.98.22.6-.02.93-.65.93h-2.06c-.33 0-.58-.1-.85-.38-.44-.47-1-1.12-1.5-1.66-.43-.45-.66-.38-1.15.1z"/></svg>',
      'instagram'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>',
      'tiktok'          => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M19.6 6.8a5.9 5.9 0 0 1-3.4-1.1 5.9 5.9 0 0 1-2.3-3.7h-3.4v12.4a2.6 2.6 0 1 1-2.6-2.6c.3 0 .5 0 .8.1V8.5a6.1 6.1 0 0 0-.8-.1A6 6 0 1 0 13.8 14V8.3a9.3 9.3 0 0 0 5.8 2z"/></svg>',
      'dzen'            => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2c-.1 4.6-.4 6.7-1.6 7.9C9.2 11.1 7 11.4 2.5 11.5v1c4.5.1 6.7.4 7.9 1.6 1.2 1.2 1.5 3.3 1.6 7.9h1c.1-4.6.4-6.7 1.6-7.9 1.2-1.2 3.3-1.5 7.9-1.6v-1c-4.6-.1-6.7-.4-7.9-1.6C13.4 8.7 13.1 6.6 13 2h-1z"/></svg>',
      'facebook'        => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M22 12a10 10 0 1 0-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2V8.5h-1.3c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.5 2.9h-2.3v7A10 10 0 0 0 22 12z"/></svg>',
      'x'               => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.5 3h3.3l-7.2 8.2L22 21h-6.6l-5.2-6.7L4.3 21H1l7.7-8.8L1.3 3H8l4.7 6.2L17.5 3zm-1.1 16h1.8L7.7 4.9H5.7L16.4 19z"/></svg>',
      'youtube-music'   => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm-2 14V8l7 4-7 4z"/></svg>',
    ];
    return $map[$type] ?? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M10 14a5 5 0 0 0 7.1 0l3-3a5 5 0 0 0-7.1-7.1l-1.7 1.8"/><path d="M14 10a5 5 0 0 0-7.1 0l-3 3a5 5 0 0 0 7.1 7.1l1.7-1.8"/></svg>';
  };

  $contact_icon = function ($type) {
    $map = [
      'whatsapp' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.5 14.4c-.3-.1-1.7-.9-2-1-.3-.1-.5-.1-.7.1-.2.2-.8.9-.9 1.1-.2.2-.3.2-.6.1-.3-.1-1.2-.5-2.3-1.5-.8-.7-1.4-1.7-1.6-2-.2-.3 0-.4.1-.5.1-.1.3-.3.4-.4.1-.1.2-.3.3-.4.1-.2 0-.3 0-.4-.1-.1-.7-1.7-.9-2.3-.2-.6-.5-.5-.7-.5h-.6c-.2 0-.5.1-.7.3-.3.3-1 1-1 2.4 0 1.4 1 2.8 1.2 2.9.1.2 2 3.2 5 4.5 1.7.7 2.4.8 3.3.7.5-.1 1.7-.7 1.9-1.4.2-.7.2-1.3.2-1.4-.2-.1-.4-.2-.6-.3zM12 2.2C6.6 2.2 2.3 6.5 2.3 11.9c0 1.7.5 3.4 1.3 4.9L2 22l5.3-1.4c1.4.8 3 1.2 4.7 1.2 5.4 0 9.7-4.3 9.7-9.7S17.4 2.2 12 2.2z"/></svg>',
      'telegram' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M9.78 18.65l.28-4.23 7.68-6.92c.34-.31-.07-.46-.52-.19L7.74 13.3 3.64 12c-.88-.25-.89-.86.2-1.3l15.97-6.16c.73-.33 1.43.18 1.15 1.3l-2.72 12.81c-.19.91-.74 1.13-1.5.71L12.6 16.3l-1.99 1.93c-.23.23-.42.42-.83.42z"/></svg>',
      'email'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>',
      'phone'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .3 1.9.6 2.7a2 2 0 0 1-.5 2.1L7.9 9.9a16 16 0 0 0 6 6l1.4-1.4a2 2 0 0 1 2.1-.4c.8.3 1.7.5 2.7.6a2 2 0 0 1 1.7 2z"/></svg>',
    ];
    return $map[$type] ?? $map['email'];
  };
@endphp

<style>
/* ══════════════════════════════════════════════════════════════════
   FOOTER · e-diet · dark chocolate + onyx
══════════════════════════════════════════════════════════════════ */
.ediet-footer {
  font-family: 'Instrument Sans', 'Inter', sans-serif;
  color: var(--color-cream-100);
}
.ediet-footer a { text-decoration: none; }
.ediet-footer * { box-sizing: border-box; }

/* ── Main section (bark-900) ─────────────────────────── */
.ediet-footer__main {
  position: relative;
  background: radial-gradient(ellipse at top left, #3A2418 0%, #2A1A10 55%, #1e1209 100%);
  overflow: hidden;
  isolation: isolate;
}

/* ── Container ────────────────────────────────────────── */
.ediet-footer__container {
  position: relative;
  z-index: 2;
}

/* ── Floating blobs ───────────────────────────────────── */
.ediet-footer__blobs { position: absolute; inset: 0; z-index: 0; pointer-events: none; }
.ediet-footer__blob {
  position: absolute;
  border-radius: 50%;
  filter: blur(80px);
  opacity: 0.55;
  will-change: transform;
}
.ediet-footer__blob--1 {
  width: 520px; height: 520px;
  background: radial-gradient(circle, #b85e30 0%, transparent 70%);
  top: -160px; right: -120px;
  animation: ediet-blob-1 28s ease-in-out infinite;
}
.ediet-footer__blob--2 {
  width: 420px; height: 420px;
  background: radial-gradient(circle, #6a5040 0%, transparent 70%);
  bottom: 20%; left: -140px;
  animation: ediet-blob-2 34s ease-in-out infinite;
}
.ediet-footer__blob--3 {
  width: 300px; height: 300px;
  background: radial-gradient(circle, #C9A84C 0%, transparent 70%);
  top: 38%; right: 18%;
  opacity: 0.3;
  animation: ediet-blob-3 40s ease-in-out infinite;
}
@keyframes ediet-blob-1 {
  0%,100% { transform: translate(0,0) scale(1); }
  33%     { transform: translate(-60px, 40px) scale(1.1); }
  66%     { transform: translate(40px, 80px) scale(0.95); }
}
@keyframes ediet-blob-2 {
  0%,100% { transform: translate(0,0) scale(1); }
  50%     { transform: translate(80px, -60px) scale(1.12); }
}
@keyframes ediet-blob-3 {
  0%,100% { transform: translate(0,0) scale(1); }
  50%     { transform: translate(-50px, 30px) scale(0.9); }
}

/* ── SVG curves overlay ───────────────────────────────── */
.ediet-footer__curves {
  position: absolute;
  inset: 0;
  z-index: 1;
  width: 100%;
  height: 100%;
  pointer-events: none;
  opacity: 0.5;
}
.ediet-footer__curves path { stroke: rgba(239, 148, 91, 0.12); fill: none; stroke-width: 1; }
.ediet-footer__curves path.is-dashed { stroke-dasharray: 2 8; stroke: rgba(245, 239, 226, 0.08); }

/* ── Grain texture ────────────────────────────────────── */
.ediet-footer__grain {
  position: absolute;
  inset: 0;
  z-index: 1;
  pointer-events: none;
  opacity: 0.35;
  mix-blend-mode: overlay;
  background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='220' height='220'><filter id='n'><feTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='2' stitchTiles='stitch'/><feColorMatrix values='0 0 0 0 0  0 0 0 0 0  0 0 0 0 0  0 0 0 0.5 0'/></filter><rect width='100%25' height='100%25' filter='url(%23n)'/></svg>");
}

/* ── Typography helpers ───────────────────────────────── */
.ediet-footer h4.section-heading {
  color: var(--color-cream-100);
  font-weight: 700;
  letter-spacing: 0.2em;
  text-transform: uppercase;
  font-size: 11px;
  margin: 0 0 20px;
}
.ediet-footer .muted { color: #A89F8B; }
.ediet-footer .divider { border-top: 1px solid rgba(245, 239, 226, 0.06); }

/* ── Brand block ──────────────────────────────────────── */
.ediet-brand {
  display: inline-flex; align-items: center; gap: 10px;
  margin-bottom: 18px;
}
.ediet-brand__mark {
  width: 44px; height: 44px; border-radius: 50%;
  background: linear-gradient(135deg, #EF945B 0%, #D87A4A 100%);
  display: flex; align-items: center; justify-content: center;
  box-shadow: 0 8px 24px rgba(216, 122, 74, 0.35);
}
.ediet-brand__mark span {
  color: #fff; font-weight: 900; font-size: 20px; line-height: 1;
}
.ediet-brand__text {
  font-size: 22px; font-weight: 800; letter-spacing: -0.04em;
  color: var(--color-cream-100);
}
.ediet-brand__text em { font-style: normal; color: #EF945B; }

/* ── Newsletter form ──────────────────────────────────── */
.ediet-newsletter {
  position: relative;
  display: flex;
  align-items: center;
  gap: 0;
  background: rgba(245, 239, 226, 0.05);
  border: 1px solid rgba(245, 239, 226, 0.1);
  border-radius: 9999px;
  padding: 6px;
  max-width: 460px;
  transition: border-color .2s, background .2s;
}
.ediet-newsletter:focus-within {
  border-color: rgba(239, 148, 91, 0.5);
  background: rgba(245, 239, 226, 0.08);
}
.ediet-newsletter input {
  flex: 1; background: transparent; border: 0; outline: 0;
  color: var(--color-cream-100);
  padding: 12px 18px;
  font-size: 14px;
  font-family: inherit;
}
.ediet-newsletter input::placeholder { color: rgba(168, 159, 139, 0.8); }
.ediet-newsletter button {
  background: var(--color-cream-100);
  color: var(--color-bark-900);
  border: 0;
  border-radius: 9999px;
  padding: 11px 22px;
  font-weight: 700; font-size: 13px; letter-spacing: -0.2px;
  cursor: pointer;
  transition: background .2s, transform .15s;
  font-family: inherit;
}
.ediet-newsletter button:hover { background: #EF945B; color: #fff; }
.ediet-newsletter button:active { transform: scale(0.97); }

/* ── Footer columns (menu lists) ──────────────────────── */
.footer-col-menu,
.footer-col-menu ul {
  list-style: none; margin: 0; padding: 0;
}
.footer-col-menu li { margin-bottom: 10px; }
.footer-col-menu a {
  color: rgba(245, 239, 226, 0.65);
  font-size: 14px;
  font-weight: 400;
  letter-spacing: -0.2px;
  transition: color .15s, padding .2s;
  display: inline-block;
  position: relative;
}
.footer-col-menu a:hover { color: var(--color-terra-400); padding-left: 6px; }
.footer-col-menu .sub-menu { display: none; }

/* ── Social / Contact cards ───────────────────────────── */
.ediet-contact-card {
  display: flex; align-items: center; gap: 14px;
  padding: 14px 16px;
  background: rgba(245, 239, 226, 0.04);
  border: 1px solid rgba(245, 239, 226, 0.07);
  border-radius: 14px;
  transition: background .2s, border-color .2s, transform .2s;
}
.ediet-contact-card:hover {
  background: rgba(239, 148, 91, 0.08);
  border-color: rgba(239, 148, 91, 0.35);
  transform: translateY(-2px);
}
.ediet-contact-card__icon {
  width: 40px; height: 40px; border-radius: 50%;
  background: rgba(239, 148, 91, 0.15);
  color: #EF945B;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.ediet-contact-card__icon svg { width: 20px; height: 20px; }
.ediet-contact-card__title {
  font-size: 14px; font-weight: 700; color: var(--color-cream-100);
  line-height: 1.2; margin: 0;
}
.ediet-contact-card__sub {
  font-size: 12px; color: rgba(168, 159, 139, 0.9);
  margin: 2px 0 0;
}

.ediet-social-pill {
  display: inline-flex; align-items: center; gap: 10px;
  padding: 10px 16px 10px 12px;
  background: rgba(245, 239, 226, 0.05);
  border: 1px solid rgba(245, 239, 226, 0.08);
  border-radius: 9999px;
  color: rgba(245, 239, 226, 0.85);
  font-size: 13px; font-weight: 500;
  transition: background .2s, border-color .2s, color .2s, transform .15s;
}
.ediet-social-pill:hover {
  background: #EF945B;
  border-color: #EF945B;
  color: #fff;
  transform: translateY(-1px);
}
.ediet-social-pill__icon {
  width: 28px; height: 28px; border-radius: 50%;
  background: rgba(239, 148, 91, 0.18);
  color: #EF945B;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
  transition: background .2s, color .2s;
}
.ediet-social-pill__icon svg { width: 14px; height: 14px; }
.ediet-social-pill__icon img { width: 14px; height: 14px; object-fit: contain; }
.ediet-social-pill:hover .ediet-social-pill__icon { background: rgba(255,255,255,0.2); color: #fff; }

/* ── Payment chips ────────────────────────────────────── */
.ediet-pay-chip {
  display: inline-flex; align-items: center; gap: 8px;
  background: rgba(245, 239, 226, 0.05);
  border: 1px solid rgba(245, 239, 226, 0.08);
  border-radius: 10px;
  padding: 8px 14px;
  font-size: 13px; font-weight: 600;
  color: rgba(245, 239, 226, 0.9);
}
.ediet-pay-chip img { height: 16px; width: auto; object-fit: contain; }
.ediet-currency-chip {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 6px 12px; border-radius: 9999px;
  background: rgba(201, 168, 76, 0.12);
  border: 1px solid rgba(201, 168, 76, 0.35);
  color: #d4a94a;
  font-size: 12px; font-weight: 700;
  letter-spacing: 0.05em;
}

/* ── Trust badges ─────────────────────────────────────── */
.ediet-trust-card {
  display: flex; flex-direction: column; align-items: center; text-align: center;
  padding: 20px 16px;
  background: rgba(245, 239, 226, 0.04);
  border: 1px solid rgba(245, 239, 226, 0.07);
  border-radius: 16px;
  transition: transform .2s, border-color .2s;
}
.ediet-trust-card:hover { transform: translateY(-3px); border-color: rgba(239, 148, 91, 0.35); }
.ediet-trust-card__icon {
  width: 48px; height: 48px; border-radius: 50%;
  background: rgba(245, 239, 226, 0.06);
  display: flex; align-items: center; justify-content: center;
  margin-bottom: 10px;
}
.ediet-trust-card__icon img { width: 28px; height: 28px; object-fit: contain; }
.ediet-trust-card__title {
  font-size: 13px; font-weight: 700; color: var(--color-cream-100);
  line-height: 1.2;
}
.ediet-trust-card__sub {
  font-size: 11px; color: rgba(168, 159, 139, 0.9);
  margin-top: 3px;
}

/* ── Requisites WYSIWYG ───────────────────────────────── */
.ediet-requisites {
  color: rgba(245, 239, 226, 0.7);
  font-size: 13px;
  line-height: 1.7;
}
.ediet-requisites p { margin: 0 0 6px; }
.ediet-requisites strong { color: var(--color-cream-100); font-weight: 600; }
.ediet-requisites a { color: #EF945B; border-bottom: 1px dashed rgba(239, 148, 91, 0.4); }
.ediet-requisites a:hover { color: #F4B491; }

/* ── Bottom onyx strip ────────────────────────────────── */
.ediet-footer__bottom {
  background: #140c05;
  border-top: 1px solid rgba(245, 239, 226, 0.05);
  padding: 22px 0;
  position: relative;
}
.ediet-footer__bottom .container-editorial {
  display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between;
  gap: 18px;
}
.ediet-footer__copy {
  color: rgba(168, 159, 139, 0.8);
  font-size: 12px;
  margin: 0;
}
.ediet-footer__bottom-links {
  display: flex; flex-wrap: wrap; gap: 22px;
  list-style: none; margin: 0; padding: 0;
}
.ediet-footer__bottom-links a {
  color: rgba(168, 159, 139, 0.75);
  font-size: 12px;
  transition: color .15s;
}
.ediet-footer__bottom-links a:hover { color: var(--color-terra-400); }
.ediet-lang-switch {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 6px 12px;
  border-radius: 9999px;
  background: rgba(245, 239, 226, 0.04);
  border: 1px solid rgba(245, 239, 226, 0.08);
  color: rgba(245, 239, 226, 0.75);
  font-size: 12px; font-weight: 600;
  letter-spacing: 0.08em;
  cursor: default;
}
.ediet-lang-switch svg { width: 14px; height: 14px; }

/* ── Reduced motion ───────────────────────────────────── */
@media (prefers-reduced-motion: reduce) {
  .ediet-footer__blob { animation: none !important; }
}

/* ── Prevent horizontal overflow ─────────────────────── */
.ediet-footer { overflow-x: hidden; }
.ediet-footer__main { overflow-x: hidden; }
.ediet-footer__bottom { overflow-x: hidden; }

/* Force long words to break ────────────────────────────*/
.ediet-requisites { word-break: break-word; overflow-wrap: break-word; }
.ediet-footer__copy { word-break: break-word; overflow-wrap: break-word; }
.footer-col-menu a { word-break: break-word; }

/* ── Responsive tweaks ────────────────────────────────── */
@media (max-width: 768px) {
  .ediet-footer__blob { filter: blur(60px); opacity: 0.3; }
  .ediet-footer__main { overflow: hidden; }
}

@media (max-width: 640px) {
  /* Main section padding */
  .ediet-footer__main .container-editorial { padding-top: 48px !important; padding-bottom: 24px !important; }

  /* Top grid: reduce gap */
  .ediet-top-grid { gap: 28px !important; padding-bottom: 32px !important; }

  /* Newsletter: stack input + button vertically */
  .ediet-newsletter {
    flex-direction: column;
    border-radius: 18px;
    padding: 10px;
    gap: 8px;
    max-width: 100%;
  }
  .ediet-newsletter input { padding: 12px 16px; width: 100%; border-radius: 9999px; }
  .ediet-newsletter button { width: 100%; border-radius: 9999px; padding: 13px 22px; }

  /* Columns: single column on very small screens */
  .ediet-columns-grid { grid-template-columns: 1fr !important; gap: 24px !important; padding: 32px 0 !important; }

  /* Trust: keep 2 cols */
  .ediet-trust-grid { gap: 10px !important; padding-bottom: 24px !important; }

  /* Bottom strip */
  .ediet-footer__bottom { padding: 18px 0; }
  .ediet-footer__bottom .container-editorial { flex-direction: column; align-items: flex-start; gap: 12px; }
  .ediet-footer__bottom-links { gap: 14px; }
}
</style>

<footer id="site-footer" class="ediet-footer" role="contentinfo">

  {{-- ══ MAIN SECTION (bark-900) ══ --}}
  <div class="ediet-footer__main">

    {{-- Animated blur blobs --}}
    <div class="ediet-footer__blobs" aria-hidden="true">
      <span class="ediet-footer__blob ediet-footer__blob--1"></span>
      <span class="ediet-footer__blob ediet-footer__blob--2"></span>
      <span class="ediet-footer__blob ediet-footer__blob--3"></span>
    </div>

    {{-- Abstract SVG curves --}}
    <svg class="ediet-footer__curves" aria-hidden="true" preserveAspectRatio="none" viewBox="0 0 1600 900">
      <path d="M 0 140 C 300 60, 600 260, 900 160 S 1400 100, 1600 200"/>
      <path d="M 0 380 C 280 320, 520 480, 820 420 S 1340 360, 1600 460"/>
      <path d="M 0 660 C 260 600, 560 740, 900 680 S 1380 600, 1600 720" class="is-dashed"/>
      <circle cx="1240" cy="240" r="1.5" fill="rgba(239,148,91,0.6)"/>
      <circle cx="220"  cy="520" r="1.5" fill="rgba(201,168,76,0.5)"/>
      <circle cx="820"  cy="760" r="1.5" fill="rgba(239,148,91,0.5)"/>
    </svg>

    {{-- Grain --}}
    <div class="ediet-footer__grain" aria-hidden="true"></div>

    <div class="container-editorial relative z-10" style="padding-top:72px; padding-bottom:36px;">

      {{-- ══ TOP: BRAND + NEWSLETTER ══ --}}
      <div style="display:grid; gap:40px; grid-template-columns: 1fr; padding-bottom:48px;" class="ediet-top-grid">
        <div>
          <a href="{{ home_url('/') }}" class="ediet-brand">
            @if(!empty($brand_logo))
              <img src="{{ $brand_logo }}" alt="{{ get_bloginfo('name') }}" style="height:40px;width:auto;object-fit:contain;">
            @else
              <span class="ediet-brand__mark"><span>e</span></span>
              <span class="ediet-brand__text">e-diet<em>.</em></span>
            @endif
          </a>
          @if($brand_tagline)
            <p style="color:rgba(245,239,226,0.65); font-size:14px; line-height:1.65; max-width:420px; margin:0;">
              {{ $brand_tagline }}
            </p>
          @endif
        </div>

        @if($nl_enabled)
        <div>
          <h4 class="section-heading">{{ $nl_title }}</h4>
          @if($nl_subtitle)
            <p style="color:rgba(245,239,226,0.65); font-size:13px; line-height:1.6; margin:0 0 16px; max-width:440px;">{{ $nl_subtitle }}</p>
          @endif
          <form class="ediet-newsletter" action="" method="post" onsubmit="event.preventDefault();">
            <input type="email" name="email" placeholder="{{ $nl_placeholder }}" autocomplete="email" required>
            <button type="submit">{{ $nl_button }}</button>
          </form>
        </div>
        @endif
      </div>

      {{-- ══ MENU COLUMNS ══ --}}
      @if(!empty($columns))
      <div class="divider" style="padding-top:48px;"></div>
      <div class="ediet-columns-grid" style="display:grid; gap:32px; padding:48px 0; grid-template-columns: repeat(2, 1fr);">
        @foreach($columns as $col)
          <div>
            @if(!empty($col['title']))
              <h4 class="section-heading">{{ $col['title'] }}</h4>
            @endif
            @if(!empty($col['menu']))
              {!! wp_nav_menu([
                'menu'        => $col['menu'],
                'container'   => false,
                'echo'        => false,
                'menu_class'  => 'footer-col-menu',
                'depth'       => 1,
                'fallback_cb' => false,
                'items_wrap'  => '<ul class="%2$s">%3$s</ul>',
              ]) !!}
            @endif
          </div>
        @endforeach
      </div>
      @endif        

      {{-- ══ TRUST BADGES ══ --}}
      @if(!empty($trust_items))
      <div class="divider"></div>
      <div style="padding:40px 0;">
        <h4 class="section-heading">{{ $trust_title }}</h4>
        <div style="display:grid; gap:14px; grid-template-columns: repeat(2, 1fr);" class="ediet-trust-grid">
          @foreach($trust_items as $t)
            <div class="ediet-trust-card">
              @if(!empty($t['icon']))
                <div class="ediet-trust-card__icon"><img src="{{ $t['icon'] }}" alt=""></div>
              @endif
              <div class="ediet-trust-card__title">{{ $t['title'] ?? '' }}</div>
              @if(!empty($t['subtitle']))
                <div class="ediet-trust-card__sub">{{ $t['subtitle'] }}</div>
              @endif
            </div>
          @endforeach
        </div>
      </div>
      @endif

      {{-- ══ REQUISITES ══ --}}
      @if(!empty($req_content))
      <div class="divider"></div>
      <div style="padding:40px 0 8px;">
        <h4 class="section-heading">{{ $req_title }}</h4>
        <div class="ediet-requisites">{!! $req_content !!}</div>
      </div>
      @endif

    </div>
  </div>

  {{-- ══ BOTTOM ONYX STRIP ══ --}}
  <div class="ediet-footer__bottom">
    <div class="container-editorial relative z-10">
      <p class="ediet-footer__copy">{{ $copyright_text }}</p>

      @if(!empty($bottom_links))
        <ul class="ediet-footer__bottom-links">
          @foreach($bottom_links as $bl)
            <li><a href="{{ $bl['url'] ?? '#' }}">{{ $bl['label'] ?? '' }}</a></li>
          @endforeach
        </ul>
      @endif

      @if($lang_enabled)
        <span class="ediet-lang-switch" title="Переключение языка скоро">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <circle cx="12" cy="12" r="10"/>
            <path d="M2 12h20"/>
            <path d="M12 2a15 15 0 0 1 0 20"/>
            <path d="M12 2a15 15 0 0 0 0 20"/>
          </svg>
          {{ $lang_label }}
        </span>
      @endif
    </div>
  </div>
</footer>

<style>
/* ══ Responsive grid overrides (kept separate to sidestep Blade @ parsing) ══ */
@media (min-width: 768px) {
  #site-footer .ediet-top-grid { grid-template-columns: 1.1fr 1fr !important; align-items: start; }
  #site-footer .ediet-middle-grid { grid-template-columns: 1fr 1fr 1.1fr !important; }
  #site-footer .ediet-columns-grid { grid-template-columns: repeat(4, 1fr) !important; }
  #site-footer .ediet-trust-grid { grid-template-columns: repeat(4, 1fr) !important; }
}
/* ══ Footer: prevent container overflow on narrow phones ══ */
#site-footer .container-editorial {
  min-width: 0 !important;
  max-width: 100% !important;
  box-sizing: border-box !important;
}
</style>
