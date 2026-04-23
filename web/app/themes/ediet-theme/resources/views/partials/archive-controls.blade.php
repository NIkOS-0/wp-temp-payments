@php
  /**
   * Archive filter bar + infinite scroll.
   * Include BEFORE #archive-grid. Sentinel and loading divs are rendered here
   * but the JS positions them after the grid via DOM (sentinel is appended after grid in JS).
   *
   * Required variables:
   *   $archivePostType  string  'book' | 'course' | 'consultation'
   *   $archiveHasMore   bool
   */
  $ajaxUrl = admin_url('admin-ajax.php');
@endphp

{{-- ── Filter bar ── --}}
<div class="archive-filter-bar">
  <span class="archive-filter-bar__label">Сортировка:</span>
  <button class="archive-filter-pill active" data-sort="date_desc">Новинки</button>
  <button class="archive-filter-pill" data-sort="price_asc">
    <svg width="11" height="11" viewBox="0 0 11 11" fill="none" aria-hidden="true"><path d="M5.5 2v7M2 6l3.5 3.5L9 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
    Цена&nbsp;↑
  </button>
  <button class="archive-filter-pill" data-sort="price_desc">
    <svg width="11" height="11" viewBox="0 0 11 11" fill="none" aria-hidden="true"><path d="M5.5 9V2M2 5l3.5-3.5L9 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
    Цена&nbsp;↓
  </button>
</div>

<script>
(function () {
  'use strict';

  const AJAX_URL  = @json($ajaxUrl);
  const POST_TYPE = @json($archivePostType);
  let   hasMore   = @json($archiveHasMore ? true : false);

  let page  = 1;
  let busy  = false;
  let sort  = 'date_desc';

  /* Defer until grid is in the DOM */
  document.addEventListener('DOMContentLoaded', function () {
    const grid    = document.getElementById('archive-grid');
    const pills   = document.querySelectorAll('.archive-filter-pill');

    if (!grid) return;

    /* ── Sentinel & status elements injected after grid ── */
    const sentinel  = document.createElement('div');
    sentinel.id     = 'archive-sentinel';
    sentinel.style.height = '1px';

    const loadingEl = document.createElement('div');
    loadingEl.id        = 'archive-loading';
    loadingEl.className = 'archive-loading hidden';
    loadingEl.innerHTML = '<div class="archive-loading__spinner"></div>Загружаем...';

    const endEl = document.createElement('div');
    endEl.id        = 'archive-end';
    endEl.className = 'archive-end-msg hidden';
    endEl.textContent = 'Все материалы загружены';

    grid.insertAdjacentElement('afterend', sentinel);
    sentinel.insertAdjacentElement('afterend', loadingEl);
    loadingEl.insertAdjacentElement('afterend', endEl);

    /* ── Filter pills ── */
    pills.forEach(pill => {
      pill.addEventListener('click', () => {
        const newSort = pill.dataset.sort;
        if (newSort === sort) return;

        sort = newSort;
        pills.forEach(p => p.classList.toggle('active', p === pill));

        grid.innerHTML = '';
        endEl.classList.add('hidden');
        page    = 0;
        hasMore = true;
        load();
      });
    });

    /* ── Fetch & append ── */
    function load() {
      if (busy || !hasMore) return;
      busy = true;
      loadingEl.classList.remove('hidden');
      page++;

      fetch(`${AJAX_URL}?action=ediet_archive_load&post_type=${POST_TYPE}&page=${page}&sort=${sort}`)
        .then(r => r.json())
        .then(data => {
          if (data.items && data.items.length) {
            const frag = document.createDocumentFragment();
            data.items.forEach(item => {
              const tmp = document.createElement('div');
              tmp.innerHTML = buildCard(item);
              frag.appendChild(tmp.firstElementChild);
            });
            grid.appendChild(frag);
          }
          hasMore = !!data.has_more;
          if (!hasMore) endEl.classList.remove('hidden');
        })
        .catch(() => {})
        .finally(() => {
          busy = false;
          loadingEl.classList.add('hidden');
        });
    }

    /* ── IntersectionObserver ── */
    if ('IntersectionObserver' in window) {
      new IntersectionObserver(entries => {
        if (entries[0].isIntersecting) load();
      }, { rootMargin: '300px' }).observe(sentinel);
    }

    /* ── Build dtc-card HTML ── */
    function buildCard(item) {
      const tagMap  = { 'Курс': 'dtc-card__tag--course', 'Консультация': 'dtc-card__tag--doctor' };
      const tagCls  = tagMap[item.type_label] || 'dtc-card__tag--plan';
      const btnMap  = { 'Курс': 'Участвовать', 'Консультация': 'Записаться', 'Клиника': 'Перейти' };
      const btnText = btnMap[item.type_label] || 'Купить';

      const p  = px(item.price);
      const po = px(item.price_old);
      const d  = (po > 0 && p > 0 && p < po) ? Math.round((1 - p / po) * 100) : 0;

      const grad = item.type_label === 'Курс'
        ? 'background:linear-gradient(135deg,#EF945B 0%,#D87A4A 100%)'
        : 'background:linear-gradient(144deg,#5BB8E8 0%,#3A9BD5 100%)';

      const img = item.image
        ? `<img src="${e(item.image)}" alt="${e(item.title)}" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;">`
        : `<div class="dtc-card__cover" style="${grad}"><div class="dtc-card__cover-title">${e(item.title)}</div></div>`;

      const badge = item.badge ? `<span class="dtc-card__badge">${e(item.badge)}</span>` : '';

      const discTag = d > 0 ? `<span class="dtc-card__tag dtc-card__tag--sale">−${d}%</span>` : '';

      const feats = (item.features || []).slice(0, 3).map(f =>
        `<div class="dtc-card__feature"><div class="dtc-card__check"><svg viewBox="0 0 9 9" fill="none"><path d="M1.5 4.5L3.5 6.5L7.5 2.5" stroke="#16A34A" stroke-width="1.5" stroke-linecap="round"/></svg></div>${e(f.text || f.title || '')}</div>`
      ).join('');

      const prOld = item.price_old ? `<span class="dtc-card__price-old">${e(item.price_old)} ₽</span>` : '';
      const pr    = item.price     ? `<span class="dtc-card__price">${e(item.price)} ₽</span>` : '';
      const del   = item.delivery  ? `<span class="dtc-card__delivery">${e(item.delivery)}</span>` : '';

      return `<a href="${e(item.url)}" class="dtc-card">
        <div class="dtc-card__img">${img}${badge}</div>
        <div class="dtc-card__tags"><span class="dtc-card__tag ${tagCls}">${e(item.type_label)}</span>${discTag}</div>
        <div class="dtc-card__body">
          <h3 class="dtc-card__title">${e(item.title)}</h3>
          ${feats ? `<div class="dtc-card__features">${feats}</div>` : ''}
          <div class="dtc-card__footer">
            <div class="dtc-card__price-block">${prOld}${pr}${del}</div>
            <button class="dtc-card__btn">${btnText}</button>
          </div>
        </div>
      </a>`;
    }

    function px(v) { return parseFloat(String(v || '').replace(/\s/g, '').replace(',', '.')) || 0; }
    function e(s)  { return String(s || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
  });
})();
</script>
