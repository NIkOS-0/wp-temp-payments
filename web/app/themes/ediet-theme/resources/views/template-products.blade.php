{{--
  Template Name: Products Catalog
--}}

@extends('layouts.app')

@section('content')

@php
  $baseUrl      = strtok($_SERVER['REQUEST_URI'], '?');
  $activeCats   = !empty($_GET['category']) ? explode(',', sanitize_text_field($_GET['category'])) : [];
  $activePrices = !empty($_GET['price'])    ? explode(',', sanitize_text_field($_GET['price']))    : [];
  $activeTypes  = !empty($_GET['type'])     ? explode(',', sanitize_text_field($_GET['type']))     : [];
  $sortVal      = sanitize_text_field($_GET['sort'] ?? '');

  $priceOptions = [
    'up_to_2000'   => 'До 2 000 ₽',
    '2000_to_5000' => '2 000–5 000 ₽',
    'over_5000'    => 'Свыше 5 000 ₽',
  ];
  $typeOptions = [
    'consultation' => 'Консультации',
    'course'       => 'Курсы',
    'book'         => 'Книги / МПО',
  ];
@endphp

<div style="background:#F5EFE2;min-height:100vh;font-family:'Instrument Sans','Inter',sans-serif;color:#2A1A10;">
<div class="container-editorial py-8 pb-24">

  {{-- ── BREADCRUMB ── --}}
  <nav class="flex items-center gap-1.5 text-xs mb-8" style="color:#A89F8B;">
    <a href="/" style="color:#A89F8B;" class="hover:text-terra-500 transition-colors duration-150">Главная</a>
    <svg width="10" height="10" viewBox="0 0 10 10" fill="none" style="opacity:.5;"><path d="M3.5 1.5L6.5 5L3.5 8.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
    <span style="color:#2A1A10;font-weight:500;">Каталог продуктов</span>
  </nav>

  {{-- ── HERO ── --}}
  <div class="relative bg-white rounded-3xl overflow-hidden mb-6" style="border:1px solid rgba(42,26,16,0.10);box-shadow:0 4px 24px rgba(42,26,16,0.06);">
    <div class="absolute top-0 left-0 right-0" style="height:2px;background:linear-gradient(90deg,#EF945B 0%,#D87A4A 45%,#EBE3D2 100%);"></div>
    <div class="px-8 py-8 lg:px-12 lg:py-10">
      <p class="text-overline mb-3">Доказательная медицина · Нутрициология</p>
      <h1 style="font-family:'Playfair Display',serif;font-size:clamp(26px,4vw,40px);font-weight:700;color:#2A1A10;line-height:1.15;letter-spacing:-0.02em;margin-bottom:12px;">
        Каталог <em style="font-style:italic;color:#EF945B;">продуктов</em>
      </h1>
      <p style="font-size:14px;color:#6a5040;line-height:1.75;max-width:520px;">
        Консультации специалистов интегративной медицины, авторские курсы по здоровью и готовые планы питания — всё для вашего оздоровления.
      </p>
    </div>
  </div>

  {{-- ── CATEGORY PILL BAR ── --}}
  @php
    $pillBase = array_filter([
      !empty($activePrices) ? 'price=' . implode(',', $activePrices) : '',
      !empty($activeTypes)  ? 'type='  . implode(',', $activeTypes)  : '',
      ($sortVal && $sortVal !== 'hierarchy') ? 'sort=' . $sortVal : '',
    ]);
  @endphp
  <div id="pill-bar" class="flex flex-wrap gap-2 mb-6">
    <a href="{{ $baseUrl . (!empty($pillBase) ? '?' . implode('&', $pillBase) : '') }}"
       class="prod-pill {{ empty($activeCats) ? 'prod-pill--on' : '' }}">Все направления</a>
    @foreach($categories as $cat)
      @php
        $pillParams = array_filter([
          'category=' . $cat->slug,
          !empty($activePrices) ? 'price=' . implode(',', $activePrices) : '',
          !empty($activeTypes)  ? 'type='  . implode(',', $activeTypes)  : '',
          ($sortVal && $sortVal !== 'hierarchy') ? 'sort=' . $sortVal : '',
        ]);
      @endphp
      <a href="?{{ implode('&', $pillParams) }}"
         class="prod-pill {{ in_array($cat->slug, $activeCats) ? 'prod-pill--on' : '' }}">
        {{ $cat->name }}
      </a>
    @endforeach
  </div>

  {{-- ── CATALOG ── --}}
  <div class="flex flex-col lg:flex-row gap-5 items-start">

    {{-- ── SIDEBAR ── --}}
    <aside class="w-full lg:w-[230px] shrink-0 lg:sticky" style="top:84px;" id="filter-sidebar">
      <form id="filter-form" action="{{ $baseUrl }}" method="GET">
        <div class="bg-white rounded-2xl overflow-hidden" style="border:1px solid rgba(42,26,16,0.10);box-shadow:0 2px 12px rgba(42,26,16,0.05);">

          {{-- Header --}}
          <div class="flex items-center justify-between px-5 py-4" style="border-bottom:1px solid rgba(42,26,16,0.08);">
            <span style="font-size:11px;font-weight:800;letter-spacing:0.12em;text-transform:uppercase;color:#2A1A10;">Фильтры</span>
            <a href="{{ $baseUrl }}" class="filter-reset" style="font-size:11px;font-weight:600;color:#EF945B;text-decoration:none;">Сбросить</a>
          </div>

          <div class="px-5 py-4 flex flex-col gap-5">

            {{-- Type --}}
            <div>
              <p class="filter-group-label">Тип продукта</p>
              @foreach($typeOptions as $val => $label)
                @php $isOn = in_array($val, $activeTypes); @endphp
                <label class="filter-row {{ $isOn ? 'filter-row--on' : '' }}">
                  <input type="checkbox" name="type" value="{{ $val }}" class="sr-only" {{ $isOn ? 'checked' : '' }}>
                  <span class="filter-box">
                    @if($isOn)
                      <svg viewBox="0 0 9 9" fill="none" width="9" height="9"><path d="M1.5 4.5L3.5 6.5L7.5 2.5" stroke="#fff" stroke-width="1.7" stroke-linecap="round"/></svg>
                    @endif
                  </span>
                  {{ $label }}
                </label>
              @endforeach
            </div>

            <div style="height:1px;background:rgba(42,26,16,0.08);"></div>

            {{-- Category --}}
            <div>
              <p class="filter-group-label">Направление</p>
              @foreach($categories as $cat)
                @php $isOn = in_array($cat->slug, $activeCats); @endphp
                <label class="filter-row {{ $isOn ? 'filter-row--on' : '' }}">
                  <input type="checkbox" name="category" value="{{ $cat->slug }}" class="sr-only" {{ $isOn ? 'checked' : '' }}>
                  <span class="filter-box">
                    @if($isOn)
                      <svg viewBox="0 0 9 9" fill="none" width="9" height="9"><path d="M1.5 4.5L3.5 6.5L7.5 2.5" stroke="#fff" stroke-width="1.7" stroke-linecap="round"/></svg>
                    @endif
                  </span>
                  {{ $cat->name }}
                  @if($cat->count > 0)
                    <span class="ml-auto" style="font-size:11px;color:#A89F8B;">{{ $cat->count }}</span>
                  @endif
                </label>
              @endforeach
            </div>

            <div style="height:1px;background:rgba(42,26,16,0.08);"></div>

            {{-- Price --}}
            <div>
              <p class="filter-group-label">Цена</p>
              @foreach($priceOptions as $val => $label)
                @php $isOn = in_array($val, $activePrices); @endphp
                <label class="filter-row {{ $isOn ? 'filter-row--on' : '' }}">
                  <input type="checkbox" name="price" value="{{ $val }}" class="sr-only" {{ $isOn ? 'checked' : '' }}>
                  <span class="filter-box">
                    @if($isOn)
                      <svg viewBox="0 0 9 9" fill="none" width="9" height="9"><path d="M1.5 4.5L3.5 6.5L7.5 2.5" stroke="#fff" stroke-width="1.7" stroke-linecap="round"/></svg>
                    @endif
                  </span>
                  {{ $label }}
                </label>
              @endforeach
            </div>

          </div>

          {{-- Non-JS submit --}}
          <div class="px-5 pb-5">
            <button type="submit" style="width:100%;padding:10px;background:#2A1A10;color:#F5EFE2;border:none;border-radius:50px;font-family:inherit;font-size:13px;font-weight:600;cursor:pointer;transition:opacity .15s;" onmouseover="this.style.opacity='.82'" onmouseout="this.style.opacity='1'">
              Применить
            </button>
          </div>

        </div>
      </form>
    </aside>

    {{-- ── PRODUCTS AREA ── --}}
    <div class="flex-1 min-w-0" id="products-area">

      {{-- Sort bar --}}
      <div class="flex items-center justify-between mb-4 px-1">
        <p id="products-count" style="font-size:12.5px;color:#6a5040;">
          @if($totalProducts > 0)
            @php $from = ($currentPage - 1) * $perPage + 1; $to = min($currentPage * $perPage, $totalProducts); @endphp
            Показано <strong style="color:#2A1A10;font-weight:700;">{{ $from }}–{{ $to }}</strong> из <strong style="color:#2A1A10;font-weight:700;">{{ $totalProducts }}</strong>
          @else
            Ничего не найдено
          @endif
        </p>
        <select id="sort-select" style="padding:7px 14px;border-radius:50px;border:1.5px solid rgba(42,26,16,0.18);background:#fff;font-family:inherit;font-size:12.5px;color:#2A1A10;cursor:pointer;outline:none;appearance:none;-webkit-appearance:none;padding-right:28px;background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12' fill='none'%3E%3Cpath d='M2.5 4.5L6 8L9.5 4.5' stroke='%232A1A10' stroke-width='1.4' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E\");background-repeat:no-repeat;background-position:right 10px center;">
          <option value="hierarchy" {{ $sortVal === '' || $sortVal === 'hierarchy' ? 'selected' : '' }}>По типу</option>
          <option value="price_asc"  {{ $sortVal === 'price_asc'  ? 'selected' : '' }}>По цене ↑</option>
          <option value="price_desc" {{ $sortVal === 'price_desc' ? 'selected' : '' }}>По цене ↓</option>
        </select>
      </div>

      {{-- Products grid --}}
      <div id="products-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;">
        @if(count($products) > 0)
          @foreach($products as $product)
            @if($product['post_type'] === 'consultation')
              <div style="grid-column:1 / -1;">
                @include('partials.card-consultation', ['item' => $product])
              </div>
            @else
              <div>
                @include('partials.card-' . $product['post_type'], ['item' => $product])
              </div>
            @endif
          @endforeach
        @else
          <div style="grid-column:1 / -1;padding:48px 24px;text-align:center;border:2px dashed rgba(42,26,16,0.12);border-radius:20px;">
            <p style="color:#A89F8B;font-size:14px;margin-bottom:12px;">По вашему запросу ничего не найдено.</p>
            <a href="{{ $baseUrl }}" class="filter-reset" style="font-size:13px;font-weight:600;color:#EF945B;text-decoration:none;">Сбросить фильтры</a>
          </div>
        @endif
      </div>

      {{-- Pagination --}}
      @if($totalPages > 1)
        @php
          // Build page items with ellipsis
          $pageItems = [];
          if ($totalPages <= 7) {
              $pageItems = range(1, $totalPages);
          } else {
              $pageItems[] = 1;
              if ($currentPage > 3) $pageItems[] = '…';
              for ($i = max(2, $currentPage - 1); $i <= min($totalPages - 1, $currentPage + 1); $i++) {
                  $pageItems[] = $i;
              }
              if ($currentPage < $totalPages - 2) $pageItems[] = '…';
              $pageItems[] = $totalPages;
          }

          // URL builder for page links (preserves active filters)
          $buildPageUrl = function(int $page) use ($activeCats, $activePrices, $activeTypes, $sortVal, $baseUrl): string {
              $p = array_filter([
                  !empty($activeCats)   ? 'category=' . implode(',', $activeCats)   : '',
                  !empty($activePrices) ? 'price='    . implode(',', $activePrices) : '',
                  !empty($activeTypes)  ? 'type='     . implode(',', $activeTypes)  : '',
                  ($sortVal && $sortVal !== 'hierarchy') ? 'sort=' . $sortVal       : '',
                  $page > 1             ? 'paged='    . $page                       : '',
              ]);
              return $baseUrl . (!empty($p) ? '?' . implode('&', $p) : '');
          };
        @endphp

        <nav id="pagination" class="flex items-center justify-center gap-1.5 mt-8 flex-wrap">
          {{-- Prev --}}
          @if($currentPage > 1)
            <a href="{{ $buildPageUrl($currentPage - 1) }}" class="page-btn page-btn--arrow" data-page="{{ $currentPage - 1 }}">
              <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 2L4 7L9 12"/></svg>
              Назад
            </a>
          @endif

          {{-- Pages --}}
          @foreach($pageItems as $item)
            @if($item === '…')
              <span class="page-ellipsis">…</span>
            @else
              <a href="{{ $buildPageUrl($item) }}"
                 class="page-btn {{ $item === $currentPage ? 'page-btn--on' : '' }}"
                 data-page="{{ $item }}">{{ $item }}</a>
            @endif
          @endforeach

          {{-- Next --}}
          @if($currentPage < $totalPages)
            <a href="{{ $buildPageUrl($currentPage + 1) }}" class="page-btn page-btn--arrow" data-page="{{ $currentPage + 1 }}">
              Далее
              <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 2L10 7L5 12"/></svg>
            </a>
          @endif
        </nav>
      @endif

    </div>
    {{-- /.products-area --}}

  </div>
  {{-- /.flex catalog --}}

</div>
</div>

<style>
/* ── Pill filter bar ── */
.prod-pill {
  display: inline-flex; align-items: center;
  padding: 6px 16px;
  border-radius: 50px;
  font-size: 12.5px; font-weight: 500;
  border: 1.5px solid rgba(42,26,16,0.15);
  background: #EBE3D2; color: #6a5040;
  cursor: pointer; transition: all 0.15s;
  text-decoration: none; white-space: nowrap;
}
.prod-pill:hover { border-color: rgba(42,26,16,0.30); color: #2A1A10; }
.prod-pill--on { background: #2A1A10; border-color: #2A1A10; color: #F5EFE2; font-weight: 600; }

/* ── Sidebar filter rows ── */
.filter-group-label {
  font-size: 10px; font-weight: 700; text-transform: uppercase;
  letter-spacing: 0.08em; color: #A89F8B; margin-bottom: 10px;
}
.filter-row {
  display: flex; align-items: center; gap: 8px;
  font-size: 12.5px; color: #6a5040;
  padding: 5px 0; cursor: pointer; border-radius: 8px;
  transition: color 0.14s; user-select: none;
}
.filter-row:hover { color: #2A1A10; }
.filter-row--on { color: #2A1A10; font-weight: 600; }
.filter-box {
  width: 16px; height: 16px; border-radius: 5px;
  border: 1.5px solid rgba(42,26,16,0.20); flex-shrink: 0;
  background: #EBE3D2; display: flex; align-items: center; justify-content: center;
  transition: all 0.14s;
}
.filter-row--on .filter-box { background: #2A1A10; border-color: #2A1A10; }

/* ── Pagination ── */
.page-btn {
  display: inline-flex; align-items: center; justify-content: center; gap: 5px;
  min-width: 36px; height: 36px; border-radius: 50%;
  font-size: 13px; font-weight: 600;
  border: 1.5px solid rgba(42,26,16,0.15);
  background: #fff; color: #6a5040;
  text-decoration: none; transition: all 0.15s; cursor: pointer; white-space: nowrap;
}
.page-btn:hover { border-color: rgba(42,26,16,0.30); color: #2A1A10; }
.page-btn--on { background: #2A1A10; border-color: #2A1A10; color: #F5EFE2; }
.page-btn--arrow { border-radius: 50px; padding: 0 14px; font-size: 12.5px; }
.page-ellipsis {
  display: inline-flex; align-items: center; justify-content: center;
  width: 36px; height: 36px; font-size: 13px; color: #A89F8B;
}

/* ── Mobile ── */
@media (max-width: 768px) {
  #products-grid { grid-template-columns: 1fr !important; }
  #products-grid > div { grid-column: 1 / -1 !important; }
}
@media (max-width: 640px) {
  #products-grid { grid-template-columns: 1fr !important; }
}
</style>

<script>
(function () {
  function buildParams(paged) {
    const form = document.getElementById('filter-form');
    if (!form) return new URLSearchParams();

    const cats   = Array.from(form.querySelectorAll('input[name="category"]:checked')).map(e => e.value);
    const prices = Array.from(form.querySelectorAll('input[name="price"]:checked')).map(e => e.value);
    const types  = Array.from(form.querySelectorAll('input[name="type"]:checked')).map(e => e.value);
    const sort   = (document.getElementById('sort-select') || {}).value || 'hierarchy';

    const p = new URLSearchParams();
    if (cats.length)   p.set('category', cats.join(','));
    if (prices.length) p.set('price',    prices.join(','));
    if (types.length)  p.set('type',     types.join(','));
    if (sort && sort !== 'hierarchy') p.set('sort', sort);
    if (paged && paged > 1)           p.set('paged', paged);
    return p;
  }

  function updateFilterVisuals() {
    document.querySelectorAll('#filter-form .filter-row').forEach(row => {
      const cb   = row.querySelector('input[type="checkbox"]');
      const box  = row.querySelector('.filter-box');
      if (!cb || !box) return;
      const isOn = cb.checked;
      row.classList.toggle('filter-row--on', isOn);
      box.innerHTML = isOn
        ? '<svg viewBox="0 0 9 9" fill="none" width="9" height="9"><path d="M1.5 4.5L3.5 6.5L7.5 2.5" stroke="#fff" stroke-width="1.7" stroke-linecap="round"/></svg>'
        : '';
    });
  }

  function scrollToProducts() {
    const area = document.getElementById('products-area');
    if (!area) return;
    const top = area.getBoundingClientRect().top + window.scrollY - 96;
    window.scrollTo({ top: Math.max(0, top), behavior: 'smooth' });
  }

  function refreshPage(url, scroll) {
    window.history.pushState({}, '', url);

    const grid = document.getElementById('products-grid');
    if (grid) grid.style.opacity = '0.35';

    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
      .then(r => r.text())
      .then(html => {
        const doc = new DOMParser().parseFromString(html, 'text/html');

        // Replace products area (grid + sort bar)
        const oldArea = document.getElementById('products-area');
        const newArea = doc.getElementById('products-area');
        if (oldArea && newArea) oldArea.innerHTML = newArea.innerHTML;

        // Sync sidebar checkboxes from new server state
        const newSidebar = doc.getElementById('filter-sidebar');
        const oldForm    = document.getElementById('filter-form');
        if (oldForm && newSidebar) {
          oldForm.querySelectorAll('input[type="checkbox"]').forEach(cb => {
            const selector = `input[name="${cb.name}"][value="${CSS.escape(cb.value)}"]`;
            const newCb = newSidebar.querySelector(selector);
            if (newCb) cb.checked = newCb.checked;
          });
          updateFilterVisuals();
        }

        // Replace pill-bar
        const oldPill = document.getElementById('pill-bar');
        const newPill = doc.getElementById('pill-bar');
        if (oldPill && newPill) oldPill.innerHTML = newPill.innerHTML;

        if (scroll) scrollToProducts();
      })
      .catch(err => {
        const g = document.getElementById('products-grid');
        if (g) g.style.opacity = '1';
        console.error(err);
      });
  }

  // ── Checkbox change (delegated) ──
  document.addEventListener('change', function (e) {
    if (e.target.type === 'checkbox' && e.target.closest('#filter-form')) {
      updateFilterVisuals();
      const params = buildParams();
      const url = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
      refreshPage(url);
      return;
    }
    // Sort select
    if (e.target.id === 'sort-select') {
      const params = buildParams();
      const url = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
      refreshPage(url);
    }
  });

  // ── Pagination, pill & reset clicks (delegated) ──
  document.addEventListener('click', function (e) {
    // Pagination
    const pageBtn = e.target.closest('.page-btn[data-page]');
    if (pageBtn) {
      e.preventDefault();
      const page = parseInt(pageBtn.getAttribute('data-page'), 10);
      const params = buildParams(page);
      const url = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
      refreshPage(url, true);
      return;
    }

    // Category pills
    const pill = e.target.closest('.prod-pill');
    if (pill) {
      e.preventDefault();
      refreshPage(pill.getAttribute('href'), false);
      return;
    }

    // Reset
    const reset = e.target.closest('.filter-reset');
    if (reset) {
      e.preventDefault();
      refreshPage(reset.getAttribute('href') || window.location.pathname, false);
    }
  });

  window.addEventListener('popstate', () => window.location.reload());
})();
</script>

@endsection
