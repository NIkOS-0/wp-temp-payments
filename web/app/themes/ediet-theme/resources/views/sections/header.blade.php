@php
  $ticker_enabled = function_exists('get_field') ? get_field('header_ticker_enabled', 'option') : true;
  $ticker_items   = function_exists('get_field') ? (get_field('header_ticker_items', 'option') ?: []) : [];
  $header_logo    = function_exists('get_field') ? get_field('header_logo', 'option') : '';
  $cta_text       = function_exists('get_field') ? (get_field('header_cta_text', 'option') ?: 'Связаться с нами') : 'Связаться с нами';
  $cta_url        = function_exists('get_field') ? (get_field('header_cta_url', 'option') ?: home_url('/')) : home_url('/');

  // Default ticker items if none set
  if (empty($ticker_items)) {
    $ticker_items = [
      ['text' => 'ОГРАНИЧЕННОЕ ПРЕДЛОЖЕНИЕ — СКИДКА 50% НА ВСЁ'],
      ['text' => 'ПЕРСОНАЛЬНЫЕ ПРОТОКОЛЫ ПИТАНИЯ ОТ ВРАЧЕЙ'],
      ['text' => 'КОНСУЛЬТАЦИИ ОНЛАЙН 24/7 · С 2019 ГОДА'],
    ];
  }
@endphp

<style>
/* ══════════════════════════════════════════════════════════════════
   HEADER · e-diet · terra / bark / cream
══════════════════════════════════════════════════════════════════ */
#site-header,
#site-header * { box-sizing: border-box; }
#site-header { transition: background 0.35s ease; }
#site-header a { text-decoration: none !important; }
#site-header a:hover { text-decoration: none !important; }

#site-header {
  border-bottom-left-radius: 28px;
  border-bottom-right-radius: 28px;
}

/* ── Ticker tape ─────────────────────────────────────── */
@keyframes ticker-scroll {
  0%   { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}
.ticker-track {
  display: flex;
  width: max-content;
  animation: ticker-scroll 28s linear infinite;
  white-space: nowrap;
}
.ticker-track:hover { animation-play-state: paused; }

.hdr-top-bar {
  background: linear-gradient(90deg, #1e1209 0%, #2A1A10 50%, #1e1209 100%);
}
.hdr-ticker-item {
  display: inline-flex;
  align-items: center;
  gap: 16px;
  padding: 0 32px;
  font-family: 'Instrument Sans', 'Inter', sans-serif;
  font-size: 13px;
  font-weight: 500;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  color: rgba(245, 239, 226, 0.9);
}
.hdr-ticker-dot {
  display: inline-block;
  width: 5px; height: 5px;
  border-radius: 50%;
  background: var(--color-terra-400);
  flex-shrink: 0;
  box-shadow: 0 0 10px rgba(239, 148, 91, 0.6);
}

/* ── Secondary nav (top bar) ─────────────────────────── */
#header-secondary-nav {
  border-left: 1px solid rgba(245, 239, 226, 0.12);
}
#header-secondary-nav a {
  font-family: 'Instrument Sans', 'Inter', sans-serif;
  font-size: 13px;
  font-weight: 500;
  letter-spacing: 0.02em;
  color: rgba(245, 239, 226, 0.72);
  white-space: nowrap;
  transition: color 0.15s;
}
#header-secondary-nav a:hover { color: var(--color-terra-300); }

/* ── Main row (white) ────────────────────────────────── */
.hdr-main-row {
  position: relative;
  background: #ffffff;
}
/* Short centered divider between main-row and primary-strip */
.hdr-main-row::after {
  content: '';
  position: absolute;
  left: 50%;
  bottom: 0;
  transform: translateX(-50%);
  width: 80vw;
  height: 1px;
  background: linear-gradient(90deg,
    transparent 0%,
    rgba(42, 26, 16, 0.18) 50%,
    transparent 100%);
  pointer-events: none;
}

/* ── Logo ────────────────────────────────────────────── */
.hdr-brand {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  flex-shrink: 0;
}
.hdr-brand__mark {
  width: 36px; height: 36px;
  border-radius: 50%;
  background: linear-gradient(135deg, #EF945B 0%, #D87A4A 100%);
  display: flex; align-items: center; justify-content: center;
  box-shadow: 0 6px 18px rgba(216, 122, 74, 0.32);
}
.hdr-brand__mark span {
  color: #fff; font-weight: 900; font-size: 17px; line-height: 1;
}
.hdr-brand__text {
  font-family: 'Instrument Sans', 'Inter', sans-serif;
  font-size: 19px;
  font-weight: 800;
  letter-spacing: -0.045em;
  color: var(--color-bark-900);
}
.hdr-brand__text em { font-style: normal; color: var(--color-terra-500); }

/* ── Search bar ──────────────────────────────────────── */
.hdr-search {
  display: flex;
  align-items: center;
  gap: 0;
  background: var(--color-cream-100);
  border: 1px solid rgba(42, 26, 16, 0.08);
  border-radius: 9999px;
  height: 42px;
  padding: 0 6px 0 16px;
  flex: 1;
  max-width: 680px;
  transition: border-color 0.15s, background 0.15s, box-shadow 0.15s;
}
.hdr-search:focus-within {
  border-color: var(--color-terra-400);
  background: #fff;
  box-shadow: 0 0 0 3px rgba(244, 180, 145, 0.25);
}
.hdr-search input[type="search"] {
  flex: 1;
  background: transparent;
  border: none;
  outline: none;
  font-family: 'Instrument Sans', 'Inter', sans-serif;
  font-size: 14px;
  color: var(--color-bark-900);
  padding: 0 8px;
  min-width: 0;
}
.hdr-search input[type="search"]::placeholder {
  color: var(--color-bark-500);
  opacity: 0.8;
}
.hdr-search button[type="submit"] {
  background: var(--color-bark-900);
  color: var(--color-cream-100);
  border: none;
  border-radius: 9999px;
  cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  width: 32px; height: 32px;
  transition: background 0.15s, transform 0.12s;
}
.hdr-search button[type="submit"]:hover {
  background: var(--color-terra-500);
  transform: scale(1.05);
}
.hdr-search button[type="submit"] svg { width: 16px; height: 16px; }

/* ── CTA button ──────────────────────────────────────── */
.hdr-btn-contact {
  background: linear-gradient(125deg, #EF945B 0%, #eeaa7fff 25%, #f8ceb5ff 50%, #eeaa7fff 65%, #EF945B 100%);
  background-size: 200% 100%;
  animation: hdr-btn-shimmer 3.5s linear infinite;
  border: none;
  border-radius: 9999px;
  font-family: 'Instrument Sans', 'Inter', sans-serif;
  font-size: 14px;
  font-weight: 600;
  letter-spacing: -0.1px;
  color: #fff;
  height: 42px;
  padding: 0 22px;
  cursor: pointer;
  white-space: nowrap;
  display: inline-flex; align-items: center; gap: 6px;
  transition: transform 0.15s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 0.15s cubic-bezier(0.2, 0.8, 0.2, 1);
  flex-shrink: 0;
  box-shadow: 0 4px 14px rgba(216, 122, 74, 0.28);
}
@keyframes hdr-btn-shimmer {
  0%   { background-position: 200% 0; }
  100% { background-position: 0 0; }
}
.hdr-btn-contact:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(216, 122, 74, 0.4);
}
.hdr-btn-contact:active { transform: translateY(0); }

/* ── Icon buttons (fav, account) ─────────────────────── */
.hdr-icon-btn,
.hdr-account-icon,
.hdr-fav-btn {
  position: relative;
  width: 42px; height: 42px;
  border-radius: 50%;
  background: var(--color-cream-100);
  border: 1px solid rgba(42, 26, 16, 0.08);
  cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  color: var(--color-bark-900);
  transition: color 0.15s, background 0.15s, border-color 0.15s, transform 0.12s;
  flex-shrink: 0;
}
.hdr-account-icon:hover,
.hdr-fav-btn:hover {
  color: #fff;
  background: var(--color-terra-500);
  border-color: var(--color-terra-500);
  transform: translateY(-1px);
}
.hdr-fav-badge {
  position: absolute;
  top: -2px; right: -2px;
  min-width: 18px; height: 18px;
  padding: 0 5px;
  background: var(--color-terra-500);
  color: #fff;
  border: 2px solid #fff;
  border-radius: 9999px;
  font-size: 10px;
  font-weight: 700;
  display: none;
  align-items: center;
  justify-content: center;
  line-height: 1;
}

/* ── Primary strip · FROSTED GLASS with rounded bottom ── */
.hdr-primary-strip {
  position: relative;
  height: 48px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(253, 252, 249, 0.5);
  -webkit-backdrop-filter: saturate(170%) blur(22px);
  backdrop-filter: saturate(170%) blur(22px);
  border-bottom-left-radius: 28px;
  border-bottom-right-radius: 28px;
  box-shadow:
    0 14px 32px rgba(42, 26, 16, 0.08),
    0 2px 6px rgba(42, 26, 16, 0.04);
  /* subtle inner highlight at the rounded bottom */
  overflow: visible;
}
/* Soft top-edge gleam so frosted layer reads as a separate pane */
.hdr-primary-strip::before {
  content: '';
  position: absolute;
  top: 0; left: 16%; right: 16%;
  height: 1px;
  background: linear-gradient(90deg,
    transparent 0%,
    rgba(255, 255, 255, 0.9) 50%,
    transparent 100%);
  pointer-events: none;
}

/* ── Primary nav links ───────────────────────────────── */
#header-primary-nav a {
  font-family: 'Instrument Sans', 'Inter', sans-serif;
  font-size: 15px;
  font-weight: 500;
  letter-spacing: -0.15px;
  color: var(--color-bark-800);
  white-space: nowrap;
  padding: 8px 4px;
  position: relative;
  transition: color 0.15s;
}
#header-primary-nav a::after {
  content: '';
  position: absolute;
  left: 50%;
  bottom: 2px;
  transform: translateX(-50%) scaleX(0);
  transform-origin: center;
  width: 70%;
  height: 2px;
  border-radius: 2px;
  background: var(--color-terra-500);
  transition: transform 0.2s ease;
}
#header-primary-nav a:hover { color: var(--color-terra-500); }
#header-primary-nav a:hover::after,
#header-primary-nav .current-menu-item > a::after,
#header-primary-nav .current-menu-ancestor > a::after {
  transform: translateX(-50%) scaleX(1);
}
#header-primary-nav .current-menu-item > a,
#header-primary-nav .current-menu-ancestor > a {
  color: var(--color-terra-600);
  font-weight: 600;
}

/* ── WP nav menu ul reset ────────────────────────────── */
#header-primary-nav ul,
#header-secondary-nav ul {
  list-style: none;
  margin: 0; padding: 0;
  display: flex; align-items: center;
}
#header-primary-nav ul   { gap: 36px; }
#header-secondary-nav ul { gap: 26px; }
#header-primary-nav .sub-menu,
#header-secondary-nav .sub-menu { display: none; }

/* ── Collapse transitions (scroll behaviour preserved) ── */
.hdr-top-bar,
.hdr-main-row {
  transition: max-height 0.35s cubic-bezier(0.4, 0, 0.2, 1),
              opacity 0.28s ease,
              visibility 0.3s ease;
  overflow: hidden;
  opacity: 1;
  visibility: visible;
}
.hdr-top-bar  { max-height: 45px; }
.hdr-main-row { max-height: 100px; }
.hdr-top-bar.collapsed,
.hdr-main-row.collapsed {
  max-height: 0;
  opacity: 0;
  visibility: hidden;
}

/* ── Mobile burger (default hidden on desktop) ───────── */
#mobile-menu-btn {
  display: none;
  width: 44px; height: 44px;
  padding: 0;
  border: 1px solid rgba(42, 26, 16, 0.1);
  border-radius: 12px;
  background: var(--color-cream-100);
  color: var(--color-bark-900);
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: background 0.15s, border-color 0.15s;
}
#mobile-menu-btn:hover {
  background: var(--color-bark-900);
  border-color: var(--color-bark-900);
  color: var(--color-cream-100);
}

/* ══════════════════════════════════════════════════════
   MOBILE · sandwich at the top
══════════════════════════════════════════════════════ */
@media (max-width: 900px) {
  .hdr-top-bar { display: none !important; }
  .hdr-primary-strip {
    /* Mobile keeps the rounded frosted "chin" via the main row */
    display: none !important;
  }
  .hdr-main-row {
    height: 64px !important;
    padding: 0 14px !important;
    gap: 10px !important;
    background: #ffffff;
    /* Apply the header rounded-bottom "chin" to main-row on mobile */
    border-bottom-left-radius: 22px;
    border-bottom-right-radius: 22px;
    box-shadow:
      0 10px 26px rgba(42, 26, 16, 0.08),
      0 2px 6px rgba(42, 26, 16, 0.04);
  }
  .hdr-main-row::after { display: none; }

  /* Reorder: [burger] [logo-center] [account] */
  #mobile-menu-btn { display: inline-flex !important; order: 0; }
  .hdr-brand      { order: 1; flex: 1; justify-content: center; }
  .hdr-search     { display: none !important; }
  .hdr-btn-contact{ display: none !important; }
  .hdr-fav-btn    { display: none !important; }
  .hdr-account-icon { order: 3; }

  /* Smaller brand on mobile */
  .hdr-brand__mark { width: 32px; height: 32px; }
  .hdr-brand__mark span { font-size: 15px; }
  .hdr-brand__text { font-size: 17px; }
}
@media (min-width: 901px) {
  #mobile-menu-btn { display: none; }
}

/* ── Header spacer — mobile override ── */
@media (max-width: 900px) {
  #hdr-spacer { height: 64px !important; }
}

/* ══════════════════════════════════════════════════════
   MOBILE MENU PANEL — restyled
══════════════════════════════════════════════════════ */
#mobile-menu {
  background: var(--color-cream-50, #fdfcf9);
  border-top: 1px solid rgba(42, 26, 16, 0.06);
  box-shadow: 0 14px 32px rgba(42, 26, 16, 0.08);
  border-bottom-left-radius: 22px;
  border-bottom-right-radius: 22px;
}
#mobile-menu ul {
  list-style: none; margin: 0; padding: 0;
  display: flex; flex-direction: column; gap: 2px;
}
#mobile-menu li a {
  display: block;
  padding: 12px 14px;
  border-radius: 12px;
  font-size: 15px;
  font-weight: 500;
  color: var(--color-bark-900);
  transition: background 0.15s, color 0.15s;
}
#mobile-menu li a:hover {
  background: rgba(239, 148, 91, 0.12);
  color: var(--color-terra-600);
}

/* ── Search autocomplete dropdown ────────────────── */
.hdr-suggest-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 10px 14px;
  cursor: pointer;
  transition: background 0.12s;
  text-decoration: none;
  color: #2A1A10;
}
.hdr-suggest-item:hover { background: rgba(42,26,16,0.04); }
.hdr-suggest-item:focus { background: rgba(42,26,16,0.06); outline: none; }
.hdr-suggest-thumb {
  width: 44px; height: 44px; border-radius: 10px;
  background: #EBE3D2; object-fit: cover; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center;
  font-size: 20px; overflow: hidden;
}
.hdr-suggest-thumb img { width:100%; height:100%; object-fit:cover; border-radius:10px; }
.hdr-suggest-type {
  font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 50px;
  background: #EBE3D2; color: #6a5040; display: inline-block; margin-bottom: 2px;
}
.hdr-suggest-title {
  font-size: 13px; font-weight: 600; line-height: 1.3; color: #2A1A10;
}
.hdr-suggest-price {
  font-size: 12px; font-weight: 700; color: #2A1A10; margin-left: auto; flex-shrink: 0;
}
.hdr-suggest-loading {
  padding: 16px; text-align: center; font-size: 12.5px; color: #A89F8B;
}
</style>

<header id="site-header" class="fixed top-0 left-0 right-0 z-50" style="background:#ffffff;">

  {{-- ══ ROW 1: Ticker + Secondary Nav ══ --}}
  @if($ticker_enabled)
  <div id="hdr-top-bar" class="hdr-top-bar" style="height:45px; display:flex; align-items:center; justify-content:space-between; position:relative;">

    {{-- Ticker tape (left-center) --}}
    <div style="overflow:hidden; flex:1; height:100%; display:flex; align-items:center; position:relative;">
      {{-- Fade edges --}}
      <div style="position:absolute;left:0;top:0;bottom:0;width:60px;background:linear-gradient(to right,#2A1A10,transparent);z-index:2;pointer-events:none;"></div>
      <div style="position:absolute;right:0;top:0;bottom:0;width:60px;background:linear-gradient(to left,#2A1A10,transparent);z-index:2;pointer-events:none;"></div>

      <div class="ticker-track">
        {{-- Duplicate items for seamless loop --}}
        @foreach(array_merge($ticker_items, $ticker_items) as $item)
          <span class="hdr-ticker-item">
            <span class="hdr-ticker-dot"></span>
            {{ $item['text'] }}
          </span>
        @endforeach
      </div>
    </div>

    {{-- Secondary Nav (right) --}}
    <div id="header-secondary-nav"
         style="display:flex;align-items:center;height:100%;padding:0 24px;flex-shrink:0;">
      @if(has_nav_menu('secondary_navigation'))
        {!! wp_nav_menu([
          'theme_location' => 'secondary_navigation',
          'container'      => false,
          'echo'           => false,
          'item_spacing'   => 'discard',
          'depth'          => 1,
        ]) !!}
      @else
        <ul>
          <li><a href="{{ home_url('/') }}">О нас / Миссия</a></li>
          <li><a href="{{ home_url('/') }}">Отзывы</a></li>
          <li><a href="{{ home_url('/') }}">Контакты</a></li>
          <li><a href="{{ home_url('/') }}">Партнерам</a></li>
        </ul>
      @endif
    </div>

  </div>
  @endif

  {{-- ══ ROW 2: Logo + Search + CTA + Account ══ --}}
  <div id="hdr-main-row" class="hdr-main-row" style="height:80px; display:flex; align-items:center; gap:16px; padding:0 max(24px, calc((100vw - 1280px)/2 + 24px));">

    {{-- Mobile burger (appears on small screens, hidden on desktop via CSS) --}}
    <button type="button" id="mobile-menu-btn" aria-label="Меню">
      <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
    </button>

    {{-- Logo --}}
    <a href="{{ home_url('/') }}" class="hdr-brand">
      @if(!empty($header_logo))
        <img src="{{ $header_logo }}" alt="{{ get_bloginfo('name') }}" style="height:38px;width:auto;object-fit:contain;">
      @else
        <span class="hdr-brand__mark"><span>e</span></span>
        <span class="hdr-brand__text">e-diet<em>.</em></span>
      @endif
    </a>

    {{-- Search Bar --}}
    <form role="search" method="get" action="{{ home_url('/') }}" class="hdr-search" style="margin:0 auto;position:relative;">
      <input type="hidden" name="post_type[]" value="book">
      <input type="hidden" name="post_type[]" value="course">
      <input type="hidden" name="post_type[]" value="consultation">
      <input type="search" id="hdr-search-input" name="s"
             placeholder="Поиск: книги, курсы, консультации…"
             value="{{ get_search_query() }}" autocomplete="off">
      <button type="submit" aria-label="Найти">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <circle cx="11" cy="11" r="8" stroke-width="2"/>
          <path d="M21 21l-4.35-4.35" stroke-width="2" stroke-linecap="round"/>
        </svg>
      </button>

      {{-- Autocomplete dropdown --}}
      <div id="hdr-search-dropdown"
           style="display:none;position:absolute;top:calc(100% + 8px);left:0;right:0;
                  background:#fff;border-radius:18px;border:1px solid rgba(42,26,16,0.12);
                  box-shadow:0 16px 48px rgba(42,26,16,0.14);overflow:hidden;z-index:999;">
        <div id="hdr-search-items"></div>
        <div id="hdr-search-footer"
             style="display:none;padding:10px 14px;border-top:1px solid rgba(42,26,16,0.08);text-align:center;">
          <a id="hdr-search-all" href="#"
             style="font-size:12.5px;font-weight:600;color:#EF945B;text-decoration:none;">
            Смотреть все результаты →
          </a>
        </div>
      </div>
    </form>

    {{-- CTA Button --}}
    <a href="{{ $cta_url }}" class="hdr-btn-contact">{{ $cta_text }}</a>

    {{-- Favorites Icon --}}
    <button id="header-fav-btn" class="hdr-fav-btn" aria-label="Избранное">
      <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
      </svg>
      <span id="fav-count" class="hdr-fav-badge">0</span>
    </button>

    {{-- Account Icon --}}
    @if(is_user_logged_in())
      <a href="{{ home_url('/cabinet') }}" class="hdr-account-icon" aria-label="Кабинет">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z"/>
        </svg>
      </a>
    @else
      <button id="open-auth-modal" class="hdr-account-icon" aria-label="Войти">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z"/>
        </svg>
      </button>
    @endif

  </div>

  {{-- ══ ROW 3: Primary Navigation · frosted glass, rounded bottom ══ --}}
  <div class="hdr-primary-strip">
    <div id="header-primary-nav">
      @if(has_nav_menu('primary_navigation'))
        {!! wp_nav_menu([
          'theme_location' => 'primary_navigation',
          'container'      => false,
          'echo'           => false,
          'item_spacing'   => 'discard',
          'depth'          => 1,
        ]) !!}
      @else
        <ul>
          <li><a href="{{ home_url('/') }}">Заболевания</a></li>
          <li><a href="{{ home_url('/') }}">Консультации</a></li>
          <li><a href="{{ home_url('/') }}">Курсы</a></li>
          <li><a href="{{ home_url('/') }}">Книги (МПО)</a></li>
          <li><a href="{{ home_url('/') }}">Лекарства и методы</a></li>
          <li><a href="{{ home_url('/') }}">Блог / База знаний</a></li>
        </ul>
      @endif
    </div>
  </div>

  {{-- Mobile menu --}}
  <div id="mobile-menu"
       style="display:none; background:#fff; border-top:1px solid rgba(0,0,0,0.08); box-shadow:0 8px 24px rgba(0,0,0,0.1);">
    <div style="padding:16px 24px; display:flex; flex-direction:column; gap:4px;">
      @if(has_nav_menu('primary_navigation'))
        {!! wp_nav_menu([
          'theme_location' => 'primary_navigation',
          'container'      => false,
          'echo'           => false,
          'depth'          => 1,
        ]) !!}
      @endif

      {{-- Mobile search --}}
      <form role="search" method="get" action="{{ home_url('/') }}" class="hdr-search" style="margin-top:12px;max-width:100%;">
        <input type="hidden" name="post_type[]" value="book">
        <input type="hidden" name="post_type[]" value="course">
        <input type="hidden" name="post_type[]" value="consultation">
        <input type="search" name="s" placeholder="Поиск…" value="{{ get_search_query() }}" autocomplete="off">
        <button type="submit" aria-label="Найти">
          <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="8" stroke-width="2"/>
            <path d="M21 21l-4.35-4.35" stroke-width="2" stroke-linecap="round"/>
          </svg>
        </button>
      </form>

      <div style="padding-top:12px;border-top:1px solid rgba(0,0,0,0.08);margin-top:8px;">
        @if(is_user_logged_in())
          <a href="{{ home_url('/cabinet') }}" class="btn-dark btn-sm" style="display:block;text-align:center;">Кабинет</a>
        @else
          <button id="open-auth-modal-mobile" class="btn-dark btn-sm" style="width:100%;">Войти</button>
        @endif
      </div>
    </div>
  </div>

</header>

{{-- Spacer to account for the fixed header height --}}
<div id="hdr-spacer" style="height:{{ $ticker_enabled ? '169px' : '125px' }};"></div>

{{-- ════ AUTH MODAL ════ --}}
@if(!is_user_logged_in())
<div id="auth-modal"
     class="hidden fixed inset-0 z-[100] auth-modal-backdrop"
     role="dialog" aria-modal="true" aria-label="Авторизация">

  <div class="auth-modal relative" id="auth-modal-box">

    {{-- Close --}}
    <button id="auth-modal-close"
            class="absolute top-4 right-4 p-1.5 text-bark-400 hover:text-bark-900 transition-colors rounded-lg hover:bg-cream-100"
            aria-label="Закрыть">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>

    {{-- Header --}}
    <div class="px-8 pt-8 pb-5 border-b border-[--border]">
      <div class="flex items-center gap-3 group mb-4">
        <img src="/app/uploads/2026/04/sloj-1-1.png" alt="e-diet" class="h-8 w-auto mix-blend-multiply">
      </div>
      <h2 class="text-xl font-bold text-bark-900 tracking-tight mb-1">Войти</h2>
      <p class="text-sm text-bark-500">Введите email — отправим код и ссылку для входа</p>
    </div>

    {{-- Body --}}
    <div class="px-8 py-7">

      {{-- Step 1: email --}}
      <div id="modal-step-email">
        <div class="input-group mb-5">
          <label class="label" for="modal-email">Email</label>
          <input id="modal-email" type="email" class="input input-lg"
                 placeholder="your@email.com" autocomplete="email">
        </div>
        <button id="modal-send-btn" class="btn-dark btn-lg w-full">
          Получить код
        </button>
        <p id="modal-msg1" class="mt-3 text-sm text-center hidden"></p>
      </div>

      {{-- Step 2: code --}}
      <div id="modal-step-code" class="hidden">
        <p class="text-sm text-bark-500 mb-5">
          Код отправлен на <strong id="modal-email-display" class="text-bark-900"></strong>
        </p>
        <div class="input-group mb-5">
          <label class="label" for="modal-code">Код из письма</label>
          <input id="modal-code" type="text" inputmode="numeric" maxlength="6"
                 class="input input-lg text-center tracking-[0.4em] font-bold text-xl"
                 placeholder="— — — — — —" autocomplete="one-time-code">
        </div>
        <button id="modal-verify-btn" class="btn-dark btn-lg w-full mb-3">
          Войти
        </button>
        <button id="modal-resend-btn" class="btn-ghost btn-sm w-full text-bark-500">
          Отправить новый код
        </button>
        <p id="modal-msg2" class="mt-3 text-sm text-center hidden"></p>
      </div>

      {{-- spinner --}}
      <div id="modal-loading" class="hidden text-center py-3">
        <svg class="animate-spin h-6 w-6 text-terra-500 mx-auto" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
        </svg>
      </div>

      <p class="text-xs text-bark-400 text-center mt-5 leading-relaxed">
        Нет аккаунта? Он создаётся автоматически при первой покупке.
      </p>
    </div>

  </div>
</div>
@endif

<script>
(function() {
  const AJAX  = '{{ admin_url('admin-ajax.php') }}';
  const NONCE = '{{ wp_create_nonce('ediet_auth_nonce') }}';
  const DEBUG = {{ (defined('WP_DEBUG') && WP_DEBUG) ? 'true' : 'false' }};

  // ── Mobile menu ──
  document.getElementById('mobile-menu-btn')?.addEventListener('click', () => {
    const menu = document.getElementById('mobile-menu');
    menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
  });

  // ── Scroll: collapse/reveal top bar + main row ──
  (function() {
    const header  = document.getElementById('site-header');
    const topBar  = document.getElementById('hdr-top-bar');
    const mainRow = document.getElementById('hdr-main-row');
    let lastY = window.scrollY;
    let ticking = false;

    window.addEventListener('scroll', () => {
      if (!ticking) {
        window.requestAnimationFrame(() => {
          const currentY = window.scrollY;
          if (currentY > lastY && currentY > 60) {
            // Scrolling down — collapse both rows
            topBar?.classList.add('collapsed');
            mainRow?.classList.add('collapsed');
            header.style.background = 'transparent';
          } else {
            // Scrolling up — reveal both rows
            topBar?.classList.remove('collapsed');
            mainRow?.classList.remove('collapsed');
            header.style.background = '#ffffff';
          }
          lastY = currentY;
          ticking = false;
        });
        ticking = true;
      }
    }, { passive: true });
  })();

  // ── Favorites counter ──
  (function syncFavCount() {
    try {
      const ids = JSON.parse(localStorage.getItem('ediet_favorites') || '[]');
      const cnt = document.getElementById('fav-count');
      if (!cnt) return;
      if (ids.length > 0) {
        cnt.textContent = ids.length > 9 ? '9+' : ids.length;
        cnt.style.display = 'flex';
      }
    } catch(e) {}
  })();

  document.getElementById('header-fav-btn')?.addEventListener('click', () => {
    window.location.href = '{{ home_url('/cabinet') }}?tab=favorites';
  });

  // ── Auth modal ──
  @if(!is_user_logged_in())
  const modal = document.getElementById('auth-modal');
  if (!modal) return;

  const closeModal = () => {
    modal.classList.add('hidden');
    document.body.style.overflow = '';
  };
  const openModal = (redirect) => {
    if (redirect) modal.dataset.redirect = redirect;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    setTimeout(() => document.getElementById('modal-email')?.focus(), 100);
  };

  document.getElementById('auth-modal-close')?.addEventListener('click', closeModal);
  modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

  document.getElementById('open-auth-modal')?.addEventListener('click', () => openModal());
  document.getElementById('open-auth-modal-mobile')?.addEventListener('click', () => {
    document.getElementById('mobile-menu').style.display = 'none';
    openModal();
  });

  window.edietOpenAuthModal = openModal;

  // ── OTP logic ──
  let userEmail = '';

  const stepEmail  = document.getElementById('modal-step-email');
  const stepCode   = document.getElementById('modal-step-code');
  const loading    = document.getElementById('modal-loading');
  const emailInput = document.getElementById('modal-email');
  const codeInput  = document.getElementById('modal-code');
  const sendBtn    = document.getElementById('modal-send-btn');
  const verifyBtn  = document.getElementById('modal-verify-btn');
  const resendBtn  = document.getElementById('modal-resend-btn');
  const msg1       = document.getElementById('modal-msg1');
  const msg2       = document.getElementById('modal-msg2');

  function showMsg(el, text, isError) {
    el.textContent = text;
    el.className = 'mt-3 text-sm text-center ' + (isError ? 'text-red-600' : 'text-green-700');
    el.classList.remove('hidden');
  }

  function setLoading(on) {
    loading.classList.toggle('hidden', !on);
    stepEmail.style.opacity = on ? '0.5' : '1';
    stepCode.style.opacity  = on ? '0.5' : '1';
    if (on) { stepEmail.style.pointerEvents = 'none'; stepCode.style.pointerEvents = 'none'; }
    else    { stepEmail.style.pointerEvents = ''; stepCode.style.pointerEvents = ''; }
  }

  function post(data, cb) {
    setLoading(true);
    const fd = new FormData();
    const redirect = modal.dataset.redirect || '{{ home_url('/cabinet') }}';
    Object.entries({ ...data, nonce: NONCE, redirect }).forEach(([k, v]) => fd.append(k, v));
    fetch(AJAX, { method: 'POST', body: fd })
      .then(r => r.json())
      .then(res => { setLoading(false); cb(res); })
      .catch(() => { setLoading(false); cb({ success: false, data: { message: 'Ошибка соединения.' } }); });
  }

  function sendOtp() {
    userEmail = emailInput.value.trim();
    if (!userEmail) { showMsg(msg1, 'Введите email.', true); return; }
    post({ action: 'ediet_send_otp', email: userEmail }, res => {
      if (res.success) {
        stepEmail.classList.add('hidden');
        stepCode.classList.remove('hidden');
        document.getElementById('modal-email-display').textContent = userEmail;
        msg1.classList.add('hidden');
        if (DEBUG && res.data?.dev_code) {
          console.group('%c🔑 e-diet Auth Debug', 'color:#c77b5a;font-weight:bold;font-size:14px');
          console.log('OTP Code:  ', res.data.dev_code);
          console.log('Magic URL: ', res.data.dev_magic_url);
          console.log('Email:     ', userEmail);
          console.groupEnd();
        }
      } else {
        showMsg(msg1, res.data.message, true);
      }
    });
  }

  sendBtn.addEventListener('click', sendOtp);
  emailInput.addEventListener('keydown', e => { if (e.key === 'Enter') sendOtp(); });

  verifyBtn.addEventListener('click', () => {
    const code = codeInput.value.replace(/\D/g, '');
    if (code.length < 6) { showMsg(msg2, 'Введите 6-значный код.', true); return; }
    post({ action: 'ediet_verify_otp', email: userEmail, code }, res => {
      if (res.success) {
        showMsg(msg2, 'Добро пожаловать!', false);
        setTimeout(() => { window.location.href = res.data.redirect || '{{ home_url('/cabinet') }}'; }, 500);
      } else {
        showMsg(msg2, res.data.message, true);
      }
    });
  });

  codeInput.addEventListener('keydown', e => { if (e.key === 'Enter') verifyBtn.click(); });
  codeInput.addEventListener('input',   () => { codeInput.value = codeInput.value.replace(/\D/g, ''); });

  resendBtn.addEventListener('click', () => {
    stepCode.classList.add('hidden');
    stepEmail.classList.remove('hidden');
    emailInput.value = userEmail;
    msg1.classList.add('hidden');
  });
  @endif

  // ── Live search autocomplete ──────────────────────────────────────────
  (function () {
    const input    = document.getElementById('hdr-search-input');
    const dropdown = document.getElementById('hdr-search-dropdown');
    const items    = document.getElementById('hdr-search-items');
    const footer   = document.getElementById('hdr-search-footer');
    const allLink  = document.getElementById('hdr-search-all');
    const AJAX     = '{{ admin_url('admin-ajax.php') }}';

    if (!input || !dropdown) return;

    let timer = null;
    let lastQ = '';
    let current = -1;
    let suggestionLinks = [];

    function openDrop() { dropdown.style.display = 'block'; }
    function closeDrop() { dropdown.style.display = 'none'; current = -1; }

    function buildSearchUrl(q) {
      return '/?post_type%5B%5D=book&post_type%5B%5D=course&post_type%5B%5D=consultation&s=' + encodeURIComponent(q);
    }

    function renderItems(data, q) {
      if (!data.length) {
        items.innerHTML = '<div class="hdr-suggest-loading">Ничего не найдено</div>';
        footer.style.display = 'none';
        return;
      }

      items.innerHTML = data.map((r, i) => {
        const thumb = r.image
          ? `<div class="hdr-suggest-thumb"><img src="${r.image}" alt=""></div>`
          : `<div class="hdr-suggest-thumb">📄</div>`;
        return `<a href="${r.url}" class="hdr-suggest-item" tabindex="0" data-i="${i}">
          ${thumb}
          <div style="flex:1;min-width:0;">
            <div class="hdr-suggest-type">${r.label}</div>
            <div class="hdr-suggest-title">${r.title}</div>
          </div>
          ${r.price ? `<div class="hdr-suggest-price">${r.price}</div>` : ''}
        </a>`;
      }).join('');

      allLink.href = buildSearchUrl(q);
      footer.style.display = 'block';

      suggestionLinks = Array.from(items.querySelectorAll('.hdr-suggest-item'));
    }

    function fetchSuggestions(q) {
      items.innerHTML = '<div class="hdr-suggest-loading">Поиск…</div>';
      footer.style.display = 'none';
      openDrop();

      fetch(`${AJAX}?action=ediet_search&q=${encodeURIComponent(q)}`)
        .then(r => r.json())
        .then(data => renderItems(data, q))
        .catch(() => { items.innerHTML = '<div class="hdr-suggest-loading">Ошибка загрузки</div>'; });
    }

    input.addEventListener('input', () => {
      const q = input.value.trim();
      if (q === lastQ) return;
      lastQ = q;
      current = -1;

      clearTimeout(timer);
      if (q.length < 2) { closeDrop(); return; }

      timer = setTimeout(() => fetchSuggestions(q), 280);
    });

    // Keyboard navigation inside dropdown
    input.addEventListener('keydown', (e) => {
      if (dropdown.style.display === 'none') return;
      if (e.key === 'ArrowDown') {
        e.preventDefault();
        current = Math.min(current + 1, suggestionLinks.length - 1);
        suggestionLinks[current]?.focus();
      } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        current = Math.max(current - 1, -1);
        if (current === -1) input.focus();
        else suggestionLinks[current]?.focus();
      } else if (e.key === 'Escape') {
        closeDrop();
        input.blur();
      }
    });

    // Keep focus inside dropdown for arrow nav
    dropdown.addEventListener('keydown', (e) => {
      if (e.key === 'ArrowDown') {
        e.preventDefault();
        current = Math.min(current + 1, suggestionLinks.length - 1);
        suggestionLinks[current]?.focus();
      } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        current = Math.max(current - 1, -1);
        if (current === -1) input.focus();
        else suggestionLinks[current]?.focus();
      } else if (e.key === 'Escape') {
        closeDrop(); input.focus();
      }
    });

    // Close on outside click
    document.addEventListener('click', (e) => {
      if (!input.closest('form').contains(e.target)) closeDrop();
    });

    input.addEventListener('focus', () => {
      if (input.value.trim().length >= 2 && lastQ) openDrop();
    });
  })();
})();
</script>
