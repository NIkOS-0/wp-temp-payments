@php $post_id = $post_id ?? get_the_ID(); @endphp
<button
  class="ediet-fav-btn inline-flex items-center justify-center w-9 h-9 rounded-full
         bg-cream-100 hover:bg-terra-100 border border-[--border]
         text-bark-400 hover:text-terra-500 transition-all duration-200 {{ $class ?? '' }}"
  data-post-id="{{ $post_id }}"
  aria-label="Добавить в избранное"
  title="В избранное"
>
  <!-- outline heart (default) -->
  <svg class="fav-icon-outline w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
  </svg>
  <!-- filled heart (active) -->
  <svg class="fav-icon-filled w-4 h-4 hidden text-terra-500" fill="currentColor" viewBox="0 0 24 24">
    <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
  </svg>
</button>

<script>
(function() {
  // Init favorites system once
  if (window.__edietFavInit) return;
  window.__edietFavInit = true;

  function getFavs() {
    try { return JSON.parse(localStorage.getItem('ediet_favorites') || '[]').map(Number); }
    catch(e) { return []; }
  }
  function saveFavs(ids) {
    localStorage.setItem('ediet_favorites', JSON.stringify(ids));
    // sync cookie for PHP merge on login
    document.cookie = 'ediet_guest_favorites=' + encodeURIComponent(JSON.stringify(ids)) + ';path=/;max-age=2592000';
    updateHeaderCount(ids.length);
  }
  function updateHeaderCount(n) {
    const el = document.getElementById('fav-count');
    if (!el) return;
    if (n > 0) { el.textContent = n > 9 ? '9+' : n; el.classList.remove('hidden'); el.classList.add('flex'); }
    else { el.classList.add('hidden'); el.classList.remove('flex'); }
  }

  function setActive(btn, active) {
    btn.querySelector('.fav-icon-outline').classList.toggle('hidden', active);
    btn.querySelector('.fav-icon-filled').classList.toggle('hidden', !active);
    btn.classList.toggle('text-terra-500', active);
  }

  // Load server state for logged-in users
  function loadServerFavs() {
    if (!window.edietAuth || !edietAuth.isLoggedIn) return;
    const fd = new FormData();
    fd.append('action', 'ediet_get_favorites');
    fd.append('nonce', edietAuth.nonce);
    fetch(edietAuth.ajaxUrl, { method: 'POST', body: fd })
      .then(r => r.json())
      .then(res => {
        if (res.success) {
          const ids = res.data.ids || [];
          localStorage.setItem('ediet_favorites', JSON.stringify(ids));
          updateHeaderCount(ids.length);
          syncAllButtons();
        }
      });
  }

  function syncAllButtons() {
    const favs = getFavs();
    document.querySelectorAll('.ediet-fav-btn').forEach(btn => {
      const id = Number(btn.dataset.postId);
      setActive(btn, favs.includes(id));
    });
    updateHeaderCount(favs.length);
  }

  // Handle click
  document.addEventListener('click', function(e) {
    const btn = e.target.closest('.ediet-fav-btn');
    if (!btn) return;
    e.preventDefault(); e.stopPropagation();

    const postId = Number(btn.dataset.postId);
    if (!postId) return;

    if (window.edietAuth && edietAuth.isLoggedIn) {
      // Logged in: AJAX
      const fd = new FormData();
      fd.append('action', 'ediet_toggle_favorite');
      fd.append('nonce', edietAuth.nonce);
      fd.append('post_id', postId);
      fetch(edietAuth.ajaxUrl, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(res => {
          if (res.success) {
            const favs = getFavs();
            if (res.data.added) { if (!favs.includes(postId)) favs.push(postId); }
            else { const i = favs.indexOf(postId); if (i > -1) favs.splice(i, 1); }
            saveFavs(favs);
            syncAllButtons();
          }
        });
    } else {
      // Guest: localStorage
      const favs = getFavs();
      const idx = favs.indexOf(postId);
      if (idx > -1) favs.splice(idx, 1); else favs.push(postId);
      saveFavs(favs);
      syncAllButtons();
    }
  });

  // Init
  document.addEventListener('DOMContentLoaded', function() {
    syncAllButtons();
    loadServerFavs();
  });
  if (document.readyState !== 'loading') { syncAllButtons(); loadServerFavs(); }
})();
</script>
