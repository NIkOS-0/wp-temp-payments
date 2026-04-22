<header class="fixed top-0 left-0 right-0 z-50 transition-all duration-300" id="site-header">
  <div class="transition-all duration-300 border-b border-transparent" id="site-header-inner">
    <div class="container-wide">
      <div class="flex justify-between items-center h-20">

        {{-- Logo --}}
        <a href="{{ home_url('/') }}" class="flex items-center gap-3 group shrink-0">
          <div class="w-9 h-9 rounded-full bg-bark-900 flex items-center justify-center
                      group-hover:bg-terra-500 transition-colors duration-200">
            <span class="text-cream-100 font-black text-base leading-none">e</span>
          </div>
          <span class="text-xl font-black tracking-tighter text-bark-900 leading-none">
            e-diet
          </span>
        </a>

        {{-- Desktop Nav --}}
        <nav class="hidden md:flex items-center gap-8">
          @if(has_nav_menu('primary_navigation'))
            {!! wp_nav_menu([
              'theme_location' => 'primary_navigation',
              'menu_class'     => 'flex items-center gap-8',
              'container'      => false,
              'echo'           => false,
              'item_spacing'   => 'discard',
              'walker'         => null,
            ]) !!}
          @endif
        </nav>

        {{-- Right actions --}}
        <div class="hidden md:flex items-center gap-3">
          {{-- Favorites count --}}
          <button id="header-fav-btn"
                  class="relative btn-ghost btn-sm p-2.5"
                  aria-label="Избранное">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
            <span id="fav-count"
                  class="absolute -top-0.5 -right-0.5 w-4 h-4 text-[10px] font-bold
                         bg-terra-500 text-white rounded-full hidden items-center justify-center">
              0
            </span>
          </button>

          @if(is_user_logged_in())
            <a href="{{ home_url('/cabinet') }}"
               class="btn-dark btn-sm text-sm">
              Кабинет
            </a>
          @else
            <button id="open-auth-modal"
                    class="btn-dark btn-sm text-sm">
              Войти
            </button>
          @endif
        </div>

        {{-- Mobile burger --}}
        <button type="button" id="mobile-menu-btn"
                class="md:hidden p-2 text-bark-700 hover:text-bark-900 transition-colors"
                aria-label="Меню">
          <svg class="h-6 w-6" id="burger-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
          </svg>
        </button>

      </div>
    </div>
  </div>

  {{-- Mobile menu --}}
  <div id="mobile-menu"
       class="hidden md:hidden bg-surface border-b border-[--border] shadow-lg">
    <div class="container-wide py-4 space-y-1">
      @if(has_nav_menu('primary_navigation'))
        {!! wp_nav_menu([
          'theme_location' => 'primary_navigation',
          'menu_class'     => 'space-y-1',
          'container'      => false,
          'echo'           => false,
        ]) !!}
      @endif
      <div class="pt-4 border-t border-[--border]">
        @if(is_user_logged_in())
          <a href="{{ home_url('/cabinet') }}" class="btn-dark btn-sm w-full block text-center">
            Кабинет
          </a>
        @else
          <button id="open-auth-modal-mobile"
                  class="btn-dark btn-sm w-full">
            Войти
          </button>
        @endif
      </div>
    </div>
  </div>
</header>

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

  // ── Scroll behaviour ──
  window.addEventListener('scroll', () => {
    const inner = document.getElementById('site-header-inner');
    if (window.scrollY > 20) {
      inner.classList.add('bg-surface/95', 'backdrop-blur-md', 'shadow-sm', 'border-[--border]');
    } else {
      inner.classList.remove('bg-surface/95', 'backdrop-blur-md', 'shadow-sm', 'border-[--border]');
    }
  }, { passive: true });

  // ── Mobile menu ──
  document.getElementById('mobile-menu-btn')?.addEventListener('click', () => {
    document.getElementById('mobile-menu').classList.toggle('hidden');
  });

  // ── Favorites counter ──
  (function syncFavCount() {
    try {
      const ids = JSON.parse(localStorage.getItem('ediet_favorites') || '[]');
      const cnt = document.getElementById('fav-count');
      if (!cnt) return;
      if (ids.length > 0) {
        cnt.textContent = ids.length > 9 ? '9+' : ids.length;
        cnt.classList.remove('hidden');
        cnt.classList.add('flex');
      }
    } catch(e) {}
  })();

  document.getElementById('header-fav-btn')?.addEventListener('click', () => {
    window.location.href = '{{ home_url('/cabinet') }}?tab=favorites';
  });

  // ── Auth modal ──
  @if(!is_user_logged_in())
  const modal   = document.getElementById('auth-modal');
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
    document.getElementById('mobile-menu').classList.add('hidden');
    openModal();
  });

  // Expose globally for buy modal trigger
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
