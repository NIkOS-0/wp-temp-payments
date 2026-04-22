@php $accepted = isset($_COOKIE['ediet_cookies_ok']); @endphp
@if(!$accepted)
<div id="cookie-banner"
     class="fixed bottom-0 left-0 right-0 z-[200] p-4 md:p-6
            translate-y-full transition-transform duration-500 ease-out"
     style="will-change: transform;">
  <div class="max-w-3xl mx-auto bg-bark-900 text-cream-100 rounded-xl shadow-2xl
              flex flex-col sm:flex-row items-start sm:items-center gap-4 px-6 py-5">

    <div class="flex-1 min-w-0">
      <p class="text-sm leading-relaxed text-cream-200">
        Мы используем cookies для улучшения сервиса и персонализации контента.
        <a href="{{ home_url('/privacy') }}"
           class="text-peach-300 hover:text-peach-200 underline transition-colors ml-1">
          Политика конфиденциальности
        </a>
      </p>
    </div>

    <div class="flex items-center gap-2 shrink-0">
      <button id="cookie-accept"
              class="inline-flex items-center justify-center gap-2 font-semibold leading-none
                     whitespace-nowrap cursor-pointer transition-all duration-200
                     px-5 py-2.5 rounded-pill bg-cream-100 text-bark-900
                     hover:bg-white active:scale-[0.97] text-sm">
        Принять
      </button>
      <button id="cookie-decline"
              class="inline-flex items-center justify-center px-4 py-2.5 rounded-pill
                     font-semibold text-sm text-cream-400 hover:text-cream-100
                     hover:bg-bark-800 transition-all duration-200">
        Отклонить
      </button>
    </div>
  </div>
</div>

<script>
(function() {
  const banner = document.getElementById('cookie-banner');
  if (!banner) return;

  // Slide in after short delay
  requestAnimationFrame(() => {
    setTimeout(() => {
      banner.style.transform = 'translateY(0)';
    }, 800);
  });

  function dismiss(accept) {
    banner.style.transform = 'translateY(100%)';
    if (accept) {
      const d = new Date();
      d.setFullYear(d.getFullYear() + 1);
      document.cookie = 'ediet_cookies_ok=1;path=/;expires=' + d.toUTCString();
    }
    setTimeout(() => banner.remove(), 500);
  }

  document.getElementById('cookie-accept')?.addEventListener('click', () => dismiss(true));
  document.getElementById('cookie-decline')?.addEventListener('click', () => dismiss(false));
})();
</script>
@endif
