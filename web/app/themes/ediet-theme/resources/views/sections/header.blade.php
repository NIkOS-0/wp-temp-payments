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
/* ── Header scoped ── */
#site-header * { box-sizing: border-box; }
#site-header a { text-decoration: none !important; }
#site-header a:hover { text-decoration: none !important; }

/* Ticker tape scroll */
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

/* Primary nav links */
#header-primary-nav a {
  font-family: 'Inter', sans-serif;
  font-size: 17px;
  font-weight: 400;
  letter-spacing: -0.6px;
  color: #000;
  text-decoration: none;
  white-space: nowrap;
  transition: color 0.15s;
}
#header-primary-nav a:hover { color: var(--color-terra-500); }

/* Secondary (top bar) nav links */
#header-secondary-nav a {
  font-family: 'Inter', sans-serif;
  font-size: 15px;
  font-weight: 400;
  letter-spacing: -0.6px;
  color: rgba(255,255,255,0.82);
  text-decoration: none;
  white-space: nowrap;
  transition: color 0.15s;
}
#header-secondary-nav a:hover { color: #fff; }

/* WP nav menu ul reset */
#header-primary-nav ul,
#header-secondary-nav ul {
  list-style: none;
  margin: 0; padding: 0;
  display: flex; align-items: center;
}
/* Primary */
#header-primary-nav ul { gap: 32px; }
/* Secondary */
#header-secondary-nav ul { gap: 28px; }

/* Hide sub-menus */
#header-primary-nav .sub-menu,
#header-secondary-nav .sub-menu { display: none; }

/* Search bar */
.hdr-search {
  display: flex;
  align-items: center;
  gap: 0;
  background: #EAEAEA;
  border-radius: 25px;
  height: 40px;
  padding: 0 6px 0 14px;
  flex: 1;
  max-width: 680px;
}
.hdr-search input[type="search"] {
  flex: 1;
  background: transparent;
  border: none;
  outline: none;
  font-family: 'Inter', sans-serif;
  font-size: 15px;
  color: #333;
  padding: 0 8px;
  min-width: 0;
}
.hdr-search input[type="search"]::placeholder { color: #49454F; }
.hdr-search button[type="submit"] {
  background: transparent;
  border: none;
  cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  padding: 6px;
  color: #49454F;
  transition: color 0.15s;
}
.hdr-search button[type="submit"]:hover { color: var(--color-terra-500); }

/* Contact button */
.hdr-btn-contact {
  background: #EAEAEA;
  border: none;
  border-radius: 25px;
  font-family: 'Inter', sans-serif;
  font-size: 15px;
  font-weight: 500;
  letter-spacing: -0.3px;
  color: #000;
  height: 40px;
  padding: 0 22px;
  cursor: pointer;
  text-decoration: none;
  white-space: nowrap;
  display: inline-flex; align-items: center;
  transition: background 0.15s, color 0.15s;
  flex-shrink: 0;
}
.hdr-btn-contact:hover { background: var(--color-terra-500); color: #fff; }

/* Account icon */
.hdr-account-icon {
  width: 40px; height: 40px;
  border-radius: 50%;
  background: transparent;
  border: none;
  cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  color: #1D1B20;
  transition: color 0.15s;
  flex-shrink: 0;
}
.hdr-account-icon:hover { color: var(--color-terra-500); }

/* Top bar + main row collapse transition */
.hdr-top-bar,
.hdr-main-row {
  transition: max-height 0.35s cubic-bezier(0.4, 0, 0.2, 1),
              opacity 0.28s ease,
              visibility 0.3s ease;
  overflow: hidden;
  opacity: 1;
  visibility: visible;
}
.hdr-top-bar { max-height: 45px; }
.hdr-main-row { max-height: 100px; }
.hdr-top-bar.collapsed,
.hdr-main-row.collapsed {
  max-height: 0;
  opacity: 0;
  visibility: hidden;
}

/* Favorites icon */
.hdr-fav-btn {
  position: relative;
  width: 40px; height: 40px;
  border-radius: 50%;
  background: transparent;
  border: none;
  cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  color: #1D1B20;
  transition: color 0.15s;
  flex-shrink: 0;
}
.hdr-fav-btn:hover { color: var(--color-terra-500); }
.hdr-fav-badge {
  position: absolute;
  top: 0; right: 0;
  width: 17px; height: 17px;
  background: var(--color-terra-500, #c77b5a);
  color: #fff;
  border-radius: 50%;
  font-size: 10px;
  font-weight: 700;
  display: none;
  align-items: center;
  justify-content: center;
  line-height: 1;
}

/* Responsive */
@media (max-width: 900px) {
  .hdr-top-bar { display: none !important; }
  .hdr-main-row { padding: 0 16px; }
  .hdr-search { display: none; }
  .hdr-primary-strip { display: none; }
  #mobile-menu-btn { display: flex !important; }
}
@media (min-width: 901px) {
  #mobile-menu-btn { display: none; }
}
</style>

<header id="site-header" class="fixed top-0 left-0 right-0 z-50" style="background:#fff; box-shadow: 0 1px 0 rgba(0,0,0,0.08);">

  {{-- ══ ROW 1: Ticker + Secondary Nav ══ --}}
  @if($ticker_enabled)
  <div id="hdr-top-bar" class="hdr-top-bar" style="background:#000; height:45px; display:flex; align-items:center; justify-content:space-between; position:relative;">

    {{-- Ticker tape (left-center) --}}
    <div style="overflow:hidden; flex:1; height:100%; display:flex; align-items:center; position:relative;">
      {{-- Fade edges --}}
      <div style="position:absolute;left:0;top:0;bottom:0;width:60px;background:linear-gradient(to right,#000,transparent);z-index:2;pointer-events:none;"></div>
      <div style="position:absolute;right:0;top:0;bottom:0;width:60px;background:linear-gradient(to left,#000,transparent);z-index:2;pointer-events:none;"></div>

      <div class="ticker-track">
        {{-- Duplicate items for seamless loop --}}
        @foreach(array_merge($ticker_items, $ticker_items) as $item)
          <span style="display:inline-flex;align-items:center;gap:16px;padding:0 32px;font-family:'Inter',sans-serif;font-size:14px;font-weight:400;letter-spacing:0.04em;color:#fff;">
            <span style="display:inline-block;width:6px;height:6px;border-radius:50%;background:rgba(255,255,255,0.4);flex-shrink:0;"></span>
            {{ $item['text'] }}
          </span>
        @endforeach
      </div>
    </div>

    {{-- Secondary Nav (right) --}}
    <div id="header-secondary-nav"
         style="display:flex;align-items:center;height:100%;padding:0 24px;border-left:1px solid rgba(255,255,255,0.2);flex-shrink:0;">
      @if(has_nav_menu('secondary_navigation'))
        {!! wp_nav_menu([
          'theme_location' => 'secondary_navigation',
          'container'      => false,
          'echo'           => false,
          'item_spacing'   => 'discard',
          'depth'          => 1,
        ]) !!}
      @else
        <ul style="display:flex;gap:28px;list-style:none;margin:0;padding:0;">
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
  <div id="hdr-main-row" class="hdr-main-row" style="height:80px; display:flex; align-items:center; gap:16px; padding:0 max(24px, calc((100vw - 1280px)/2 + 24px)); border-bottom:1px solid rgba(0,0,0,0.08);">

    {{-- Logo --}}
    <a href="{{ home_url('/') }}" class="shrink-0" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
      @if(!empty($header_logo))
        <img src="{{ $header_logo }}" alt="{{ get_bloginfo('name') }}" style="height:38px;width:auto;object-fit:contain;">
      @else
        <div style="display:flex;align-items:center;gap:8px;">
          <div style="width:34px;height:34px;border-radius:50%;background:#1c0d00;display:flex;align-items:center;justify-content:center;">
            <span style="color:#fefaf3;font-weight:900;font-size:16px;line-height:1;">e</span>
          </div>
          <span style="font-family:'Inter',sans-serif;font-size:18px;font-weight:800;letter-spacing:-0.6px;color:#000;">e-diet</span>
        </div>
      @endif
    </a>

    {{-- Search Bar --}}
    <form role="search" method="get" action="{{ home_url('/') }}" class="hdr-search" style="flex:1;max-width:680px;margin:0 auto;">
      <input type="hidden" name="post_type[]" value="book">
      <input type="hidden" name="post_type[]" value="course">
      <input type="hidden" name="post_type[]" value="consultation">
      <input type="search" name="s" placeholder="Поиск: книги, курсы, консультации…"
             value="{{ get_search_query() }}" autocomplete="off">
      <button type="submit" aria-label="Найти">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <circle cx="11" cy="11" r="8" stroke-width="2"/>
          <path d="M21 21l-4.35-4.35" stroke-width="2" stroke-linecap="round"/>
        </svg>
      </button>
    </form>

    {{-- CTA Button --}}
    <a href="{{ $cta_url }}" class="hdr-btn-contact">{{ $cta_text }}</a>

    {{-- Favorites Icon --}}
    <button id="header-fav-btn" class="hdr-fav-btn" aria-label="Избранное">
      <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
      </svg>
      <span id="fav-count" class="hdr-fav-badge">0</span>
    </button>

    {{-- Account Icon --}}
    @if(is_user_logged_in())
      <a href="{{ home_url('/cabinet') }}" class="hdr-account-icon" aria-label="Кабинет">
        <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z"/>
        </svg>
      </a>
    @else
      <button id="open-auth-modal" class="hdr-account-icon" aria-label="Войти">
        <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z"/>
        </svg>
      </button>
    @endif

    {{-- Mobile burger --}}
    <button type="button" id="mobile-menu-btn"
            style="display:none;padding:8px;border:none;background:transparent;cursor:pointer;"
            aria-label="Меню">
      <svg width="24" height="24" fill="none" stroke="#000" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
    </button>

  </div>

  {{-- ══ ROW 3: Primary Navigation ══ --}}
  <div class="hdr-primary-strip" style="height:44px; display:flex; align-items:center; justify-content:center; border-bottom:1px solid rgba(0,0,0,0.08);">
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
<div style="height:{{ $ticker_enabled ? '169px' : '124px' }};"></div>

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
      <div class="flex items-center gap-3 mb-4">
        <div class="w-8 h-8 rounded-full bg-bark-900 flex items-center justify-center shrink-0">
          <span class="text-cream-100 font-black text-sm leading-none">e</span>
        </div>
        <span class="font-black text-bark-900 tracking-tight">e-diet</span>
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
    const topBar = document.getElementById('hdr-top-bar');
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
          } else {
            // Scrolling up — reveal both rows
            topBar?.classList.remove('collapsed');
            mainRow?.classList.remove('collapsed');
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
})();
</script>
