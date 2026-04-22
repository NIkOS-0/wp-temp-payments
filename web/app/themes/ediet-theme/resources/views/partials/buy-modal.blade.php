@if(!is_user_logged_in())
<div id="buy-modal" class="hidden fixed inset-0 z-[110] flex items-center justify-center p-4
     bg-bark-900/50 backdrop-blur-sm" role="dialog" aria-modal="true">
  <div class="relative bg-surface w-full max-w-md rounded-2xl border border-[--border]
              shadow-[0_32px_96px_rgba(28,13,0,0.22)]">

    <button id="buy-modal-close"
            class="absolute top-4 right-4 p-1.5 text-bark-400 hover:text-bark-900 rounded-lg hover:bg-cream-100 transition-colors">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>

    <div class="px-8 pt-8 pb-5 border-b border-[--border]">
      <div class="w-8 h-8 rounded-full bg-bark-900 flex items-center justify-center mb-4">
        <span class="text-cream-100 font-black text-sm leading-none">e</span>
      </div>
      <h2 class="text-xl font-bold text-bark-900 tracking-tight mb-1">Оформить заказ</h2>
      <p class="text-sm text-bark-500">Введите email — отправим код для входа и подтверждения</p>
    </div>

    <div class="px-8 py-7">
      <div id="buy-step-email">
        <div class="mb-5">
          <label class="block text-sm font-semibold text-bark-800 mb-2" for="buy-email">Email</label>
          <input id="buy-email" type="email"
                 class="w-full px-4 py-4 bg-cream-100 border border-[--border] rounded-2xl
                        text-bark-900 placeholder-bark-400 text-base font-medium
                        focus:outline-none focus:border-terra-400 focus:ring-2 focus:ring-terra-100
                        transition-all duration-150"
                 placeholder="your@email.com" autocomplete="email">
        </div>
        <button id="buy-send-btn"
                class="w-full inline-flex items-center justify-center gap-2 font-semibold leading-none
                       whitespace-nowrap cursor-pointer select-none transition-all duration-200
                       px-7 py-4 rounded-pill bg-bark-900 text-cream-100 hover:bg-bark-800 active:scale-[0.97] shadow-sm text-base">
          Продолжить
        </button>
        <p id="buy-msg1" class="mt-3 text-sm text-center hidden"></p>
      </div>

      <div id="buy-step-code" class="hidden">
        <p class="text-sm text-bark-500 mb-5">
          Код отправлен на <strong id="buy-email-display" class="text-bark-900"></strong>
        </p>
        <div class="mb-5">
          <label class="block text-sm font-semibold text-bark-800 mb-2" for="buy-code">Код из письма</label>
          <input id="buy-code" type="text" inputmode="numeric" maxlength="6"
                 class="w-full px-4 py-4 bg-cream-100 border border-[--border] rounded-2xl
                        text-bark-900 text-center tracking-[0.4em] font-bold text-xl
                        focus:outline-none focus:border-terra-400 focus:ring-2 focus:ring-terra-100
                        transition-all duration-150"
                 placeholder="— — — — — —" autocomplete="one-time-code">
        </div>
        <button id="buy-verify-btn"
                class="w-full inline-flex items-center justify-center gap-2 font-semibold leading-none
                       whitespace-nowrap cursor-pointer select-none transition-all duration-200
                       px-7 py-4 rounded-pill bg-bark-900 text-cream-100 hover:bg-bark-800 active:scale-[0.97] shadow-sm text-base mb-3">
          Войти и оформить
        </button>
        <button id="buy-resend-btn"
                class="w-full inline-flex items-center justify-center px-5 py-2.5 rounded-pill
                       font-semibold text-sm text-bark-500 hover:text-bark-900 hover:bg-cream-200 transition-all duration-200">
          Отправить новый код
        </button>
        <p id="buy-msg2" class="mt-3 text-sm text-center hidden"></p>
      </div>

      <div id="buy-loading" class="hidden text-center py-3">
        <svg class="animate-spin h-6 w-6 text-terra-500 mx-auto" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
        </svg>
      </div>
    </div>
  </div>
</div>

<script>
(function() {
  const modal   = document.getElementById('buy-modal');
  if (!modal) return;

  let userEmail    = '';
  let redirectUrl  = '';
  const DEBUG = {{ (defined('WP_DEBUG') && WP_DEBUG) ? 'true' : 'false' }};

  const closeModal = () => { modal.classList.add('hidden'); document.body.style.overflow = ''; };
  window.edietOpenBuyModal = function(url) {
    redirectUrl = url || '{{ home_url('/cabinet') }}';
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    // Reset to step 1
    document.getElementById('buy-step-email').classList.remove('hidden');
    document.getElementById('buy-step-code').classList.add('hidden');
    document.getElementById('buy-msg1').classList.add('hidden');
    setTimeout(() => document.getElementById('buy-email')?.focus(), 100);
  };

  document.getElementById('buy-modal-close')?.addEventListener('click', closeModal);
  modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

  function showMsg(el, text, isError) {
    el.textContent = text;
    el.className = 'mt-3 text-sm text-center ' + (isError ? 'text-red-600' : 'text-green-700');
    el.classList.remove('hidden');
  }
  function setLoading(on) {
    document.getElementById('buy-loading').classList.toggle('hidden', !on);
  }
  function post(data, cb) {
    setLoading(true);
    const NONCE = window.edietAuth ? edietAuth.nonce : '';
    const AJAX  = window.edietAuth ? edietAuth.ajaxUrl : '{{ admin_url('admin-ajax.php') }}';
    const fd = new FormData();
    Object.entries({ ...data, nonce: NONCE, redirect: redirectUrl }).forEach(([k,v]) => fd.append(k,v));
    fetch(AJAX, { method: 'POST', body: fd })
      .then(r => r.json())
      .then(res => { setLoading(false); cb(res); })
      .catch(() => { setLoading(false); cb({ success: false, data: { message: 'Ошибка соединения.' } }); });
  }

  function sendOtp() {
    userEmail = document.getElementById('buy-email').value.trim();
    if (!userEmail) { showMsg(document.getElementById('buy-msg1'), 'Введите email.', true); return; }
    post({ action: 'ediet_send_otp', email: userEmail }, res => {
      if (res.success) {
        document.getElementById('buy-step-email').classList.add('hidden');
        document.getElementById('buy-step-code').classList.remove('hidden');
        document.getElementById('buy-email-display').textContent = userEmail;
        if (DEBUG && res.data?.dev_code) {
          console.group('%c e-diet Buy Debug', 'color:#c77b5a;font-weight:bold');
          console.log('OTP:', res.data.dev_code, '| Magic:', res.data.dev_magic_url);
          console.groupEnd();
        }
      } else {
        showMsg(document.getElementById('buy-msg1'), res.data.message, true);
      }
    });
  }

  document.getElementById('buy-send-btn')?.addEventListener('click', sendOtp);
  document.getElementById('buy-email')?.addEventListener('keydown', e => { if(e.key==='Enter') sendOtp(); });

  document.getElementById('buy-verify-btn')?.addEventListener('click', () => {
    const code = document.getElementById('buy-code').value.replace(/\D/g,'');
    if (code.length < 6) { showMsg(document.getElementById('buy-msg2'), 'Введите 6-значный код.', true); return; }
    post({ action: 'ediet_verify_otp', email: userEmail, code }, res => {
      if (res.success) {
        showMsg(document.getElementById('buy-msg2'), 'Авторизован! Перенаправляем…', false);
        setTimeout(() => { window.location.href = res.data.redirect || redirectUrl; }, 600);
      } else {
        showMsg(document.getElementById('buy-msg2'), res.data.message, true);
      }
    });
  });

  document.getElementById('buy-code')?.addEventListener('keydown', e => {
    if (e.key==='Enter') document.getElementById('buy-verify-btn').click();
  });
  document.getElementById('buy-code')?.addEventListener('input', e => {
    e.target.value = e.target.value.replace(/\D/g,'');
  });
  document.getElementById('buy-resend-btn')?.addEventListener('click', () => {
    document.getElementById('buy-step-code').classList.add('hidden');
    document.getElementById('buy-step-email').classList.remove('hidden');
    document.getElementById('buy-email').value = userEmail;
  });
})();
</script>
@endif
