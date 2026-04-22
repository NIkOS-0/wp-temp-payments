{{--
  Template Name: Авторизация
--}}

@extends('layouts.app')

@section('content')

@php
  $redirect     = esc_url_raw($_GET['redirect'] ?? home_url('/cabinet'));
  $auth_error   = sanitize_text_field($_GET['auth_error'] ?? '');
  $prefill_email = sanitize_email($_GET['email'] ?? '');
@endphp

<section class="min-h-[calc(100vh-80px)] gradient-canvas flex items-center justify-center py-16 px-5">
  <div class="w-full max-w-[440px]">

    {{-- Logo mark --}}
    <div class="flex justify-center mb-10">
      <a href="{{ home_url('/') }}" class="flex items-center gap-3 group">
        <div class="w-11 h-11 rounded-full bg-bark-900 flex items-center justify-center shadow-md">
          <span class="text-cream-100 font-black text-lg leading-none">e</span>
        </div>
        <span class="text-xl font-black tracking-tighter text-bark-900">e-diet</span>
      </a>
    </div>

    {{-- Error banner --}}
    @if($auth_error === 'link_expired')
      <div class="mb-6 px-4 py-3 bg-[--color-error-bg] border border-red-200 rounded-xl text-sm text-[--color-error] font-medium text-center">
        Срок действия ссылки истёк. Запросите новую.
      </div>
    @endif

    {{-- Card --}}
    <div class="auth-modal bg-surface border border-[--border] rounded-2xl overflow-hidden
                shadow-[0_24px_80px_rgba(28,13,0,0.10)]">

      {{-- Header --}}
      <div class="px-8 pt-8 pb-6 border-b border-[--border]">
        <h1 class="text-2xl font-bold text-bark-900 tracking-tight mb-1">Войти в кабинет</h1>
        <p class="text-sm text-bark-500">Введите email — получите одноразовый код или ссылку для входа</p>
      </div>

      {{-- Form --}}
      <div class="px-8 py-8">

        {{-- Step 1: Email --}}
        <div id="auth-step-email">
          <div class="input-group mb-5">
            <label class="label" for="auth-email">Email</label>
            <input
              id="auth-email"
              type="email"
              class="input input-lg"
              placeholder="your@email.com"
              value="{{ $prefill_email }}"
              autocomplete="email"
            >
          </div>

          <button id="auth-send-btn" type="button"
                  class="btn-dark btn-lg w-full text-base">
            Получить код
          </button>

          <p id="auth-step1-msg" class="mt-3 text-sm text-center hidden"></p>
        </div>

        {{-- Step 2: OTP code --}}
        <div id="auth-step-code" class="hidden">
          <p class="text-sm text-bark-500 mb-5">
            Код отправлен на <strong id="auth-email-display" class="text-bark-900"></strong>
          </p>

          <div class="input-group mb-5">
            <label class="label" for="auth-code">Код из письма</label>
            <input
              id="auth-code"
              type="text"
              inputmode="numeric"
              maxlength="6"
              class="input input-lg text-center tracking-[0.4em] font-bold text-xl"
              placeholder="— — — — — —"
              autocomplete="one-time-code"
            >
          </div>

          <button id="auth-verify-btn" type="button"
                  class="btn-dark btn-lg w-full text-base mb-4">
            Войти
          </button>

          <button id="auth-resend-btn" type="button"
                  class="btn-ghost btn-sm w-full text-bark-500">
            Отправить новый код
          </button>

          <p id="auth-step2-msg" class="mt-3 text-sm text-center hidden"></p>
        </div>

        {{-- Loading --}}
        <div id="auth-loading" class="hidden text-center py-4">
          <svg class="animate-spin h-6 w-6 text-terra-500 mx-auto" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
          </svg>
        </div>

        {{-- Divider --}}
        <div class="flex items-center gap-3 my-6">
          <div class="flex-1 divider"></div>
          <span class="text-xs text-bark-400">или</span>
          <div class="flex-1 divider"></div>
        </div>

        {{-- Check email hint --}}
        <p class="text-xs text-bark-400 text-center leading-relaxed">
          В письме будет кнопка «Войти одним кликом» — это быстрее, чем вводить код.
        </p>

      </div>
    </div>

    {{-- Back link --}}
    <div class="text-center mt-6">
      <a href="{{ home_url('/') }}" class="text-sm text-bark-400 hover:text-bark-700 transition-colors">
        ← На главную
      </a>
    </div>

  </div>
</section>

<script>
(function() {
  const AJAX   = '{{ admin_url('admin-ajax.php') }}';
  const NONCE  = '{{ wp_create_nonce('ediet_auth_nonce') }}';
  const REDIR  = '{{ $redirect }}';
  const DEBUG  = {{ (defined('WP_DEBUG') && WP_DEBUG) ? 'true' : 'false' }};

  let userEmail = '{{ $prefill_email }}';

  const stepEmail  = document.getElementById('auth-step-email');
  const stepCode   = document.getElementById('auth-step-code');
  const loading    = document.getElementById('auth-loading');
  const emailInput = document.getElementById('auth-email');
  const codeInput  = document.getElementById('auth-code');
  const sendBtn    = document.getElementById('auth-send-btn');
  const verifyBtn  = document.getElementById('auth-verify-btn');
  const resendBtn  = document.getElementById('auth-resend-btn');
  const msg1       = document.getElementById('auth-step1-msg');
  const msg2       = document.getElementById('auth-step2-msg');

  function showMsg(el, text, isError) {
    el.textContent = text;
    el.className = 'mt-3 text-sm text-center ' + (isError ? 'text-red-600' : 'text-green-700');
    el.classList.remove('hidden');
  }

  function setLoading(on) {
    loading.classList.toggle('hidden', !on);
    stepEmail.style.opacity = on ? '0.4' : '1';
    stepCode.style.opacity  = on ? '0.4' : '1';
  }

  function post(data, cb) {
    setLoading(true);
    const fd = new FormData();
    Object.entries({ ...data, nonce: NONCE, redirect: REDIR }).forEach(([k, v]) => fd.append(k, v));
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
        document.getElementById('auth-email-display').textContent = userEmail;

        if (DEBUG && res.data.dev_code) {
          console.group('%c🔑 e-diet Auth Debug', 'color:#c77b5a;font-weight:bold;font-size:14px');
          console.log('OTP Code:    ', res.data.dev_code);
          console.log('Magic URL:   ', res.data.dev_magic_url);
          console.log('Email:       ', userEmail);
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
    const code = codeInput.value.replace(/\s/g, '').trim();
    if (code.length < 6) { showMsg(msg2, 'Введите 6-значный код.', true); return; }

    post({ action: 'ediet_verify_otp', email: userEmail, code }, res => {
      if (res.success) {
        showMsg(msg2, 'Добро пожаловать! Перенаправление…', false);
        window.location.href = res.data.redirect || REDIR;
      } else {
        showMsg(msg2, res.data.message, true);
      }
    });
  });

  codeInput.addEventListener('keydown', e => { if (e.key === 'Enter') verifyBtn.click(); });
  codeInput.addEventListener('input', () => {
    codeInput.value = codeInput.value.replace(/\D/g, '');
  });

  resendBtn.addEventListener('click', () => {
    stepCode.classList.add('hidden');
    stepEmail.classList.remove('hidden');
    emailInput.value = userEmail;
    msg1.classList.add('hidden');
  });
})();
</script>

@endsection
