<div id="ymyl-popup"
     class="hidden fixed bottom-5 right-5 z-[150] w-full max-w-sm
            bg-cream-100 border border-[--border] rounded-xl shadow-xl p-5"
     role="alert">

  <button id="ymyl-close"
          class="absolute top-3 right-3 p-1 text-bark-400 hover:text-bark-900 rounded-lg
                 hover:bg-cream-200 transition-colors"
          aria-label="Закрыть">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
    </svg>
  </button>

  <div class="flex gap-3">
    <div class="w-8 h-8 rounded-full bg-terra-100 flex items-center justify-center shrink-0 mt-0.5">
      <svg class="w-4 h-4 text-terra-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
    </div>
    <div>
      <p class="text-xs font-semibold text-bark-900 mb-1">Информационный материал</p>
      <p class="text-xs text-bark-500 leading-relaxed">
        Материалы сайта носят информационный характер и не заменяют консультацию врача.
        Перед применением проконсультируйтесь со специалистом.
      </p>
      <a href="{{ home_url('/disclaimer') }}"
         class="text-xs text-terra-600 hover:text-terra-700 font-medium mt-2 inline-block transition-colors">
        Подробнее →
      </a>
    </div>
  </div>
</div>

<script>
(function() {
  const popup = document.getElementById('ymyl-popup');
  if (!popup) return;

  const KEY = 'ediet_ymyl_dismissed';
  const DAY = 86400000;

  const dismissed = localStorage.getItem(KEY);
  if (dismissed && (Date.now() - Number(dismissed)) < DAY) return;

  // Show after 3 seconds
  setTimeout(() => {
    popup.classList.remove('hidden');
    popup.style.opacity = '0';
    popup.style.transform = 'translateY(12px)';
    popup.style.transition = 'opacity 0.3s, transform 0.3s';
    requestAnimationFrame(() => {
      popup.style.opacity = '1';
      popup.style.transform = 'translateY(0)';
    });
  }, 3000);

  function closePopup() {
    popup.style.opacity = '0';
    popup.style.transform = 'translateY(8px)';
    localStorage.setItem(KEY, Date.now().toString());
    setTimeout(() => popup.remove(), 300);
  }

  document.getElementById('ymyl-close')?.addEventListener('click', closePopup);
})();
</script>
