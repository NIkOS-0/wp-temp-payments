{{--
  Template Name: Products Catalog
--}}

@extends('layouts.app')

@section('content')
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap');

/* ═══ TOKENS ═══ */
:root {
  --app-bg:    #eef2f7;
  --surface:   #ffffff;
  --border:    #dde4ef;
  --border-s:  rgba(221,228,239,0.6);
  --text:      #0d1526;
  --sub:       #5b6a82;
  --muted:     #8fa0bb;
  --blue:      #2563eb;
  --blue-l:    #eff6ff;
  --blue-b:    #bfdbfe;
  --green:     #15803d;
  --green-l:   #f0fdf4;
  --orange:    #c2410c;
  --orange-l:  #fff7ed;
  --purple:    #6d28d9;
  --teal:      #0f766e;
  --r:         12px;
  --r-lg:      18px;
  --r-xl:      24px;
}

.prod-page {
  background: var(--app-bg);
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
  color: var(--text);
  font-size: 14px;
  line-height: 1.6;
  padding: 0 40px 100px;
  max-width: 1240px; 
  margin: 0 auto;
}

@media(max-width: 768px) {
  .prod-page { padding: 0 20px 80px; }
}

.prod-page a { text-decoration: none; color: inherit; }

/* ═══ BREADCRUMB ═══ */
.breadcrumb {
  display: flex; align-items: center; gap: 6px;
  padding: 18px 0 0; font-size: 11.5px; color: var(--muted);
}
.breadcrumb a { color: var(--muted); transition: color 0.15s; }
.breadcrumb a:hover { color: var(--blue); }
.breadcrumb .sep { font-size: 10px; }
.breadcrumb .cur { color: var(--text); font-weight: 500; }

/* ═══ SECTION UTILS ═══ */
.sec-head {
  display: flex; align-items: baseline; justify-content: space-between;
  margin-bottom: 16px;
}
.sec-title { font-size: 18px; font-weight: 800; letter-spacing: -0.025em; }
.sec-sub { font-size: 13px; color: var(--sub); margin-top: 2px; }

/* ═══ HERO ═══ */
.hero {
  margin-top: 20px;
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--r-xl);
  padding: 36px 40px 36px;
  display: grid;
  grid-template-columns: 1fr 340px;
  gap: 40px;
  align-items: center;
  position: relative;
  overflow: hidden;
  box-shadow: 0 1px 0 rgba(255,255,255,0.9) inset, 0 4px 24px rgba(0,0,0,0.04);
}
@media(max-width: 1024px) {
  .hero { grid-template-columns: 1fr; }
}
.hero::before {
  content: '';
  position: absolute; top: 0; left: 0; right: 0; height: 3px;
  background: linear-gradient(90deg, var(--orange) 0%, #f97316 40%, var(--blue) 100%);
}
.hero-bg-icon {
  position: absolute; right: 360px; top: 50%;
  transform: translateY(-50%);
  font-size: 160px; opacity: 0.04;
  pointer-events: none; user-select: none; line-height: 1;
}

.hero-title {
  font-size: clamp(26px, 3vw, 38px);
  font-weight: 900; letter-spacing: -0.035em; line-height: 1.08;
  margin-bottom: 14px;
}
.hero-title em { font-style: italic; color: var(--blue); }
.hero-desc {
  font-size: 13.5px; color: var(--sub); line-height: 1.75;
  max-width: 500px; margin-bottom: 22px;
}

/* ═══ TAG FILTER BAR ═══ */
.tag-bar {
  display: flex; gap: 7px; flex-wrap: wrap;
  padding: 14px 18px;
  background: var(--surface); border: 1px solid var(--border);
  border-radius: var(--r-lg);
  margin-bottom: 0;
  box-shadow: 0 1px 4px rgba(0,0,0,0.03);
}
.tag-pill {
  font-size: 12.5px; font-weight: 500;
  background: var(--app-bg); border: 1.5px solid var(--border);
  border-radius: 50px; padding: 5px 14px; display: inline-block;
  color: var(--sub); cursor: pointer; transition: all 0.15s;
  white-space: nowrap; text-decoration: none;
}
.tag-pill:hover { border-color: var(--blue-b); color: var(--text); }
.tag-pill.on {
  background: var(--blue); border-color: var(--blue);
  color: #fff; font-weight: 600;
}

/* ═══ CATALOG LAYOUT ═══ */
.catalog-wrap {
  display: grid;
  grid-template-columns: 240px 1fr;
  gap: 20px;
  align-items: start;
}
@media(max-width: 900px) {
  .catalog-wrap { grid-template-columns: 1fr; }
}

/* Filter sidebar */
.filter-panel {
  background: var(--surface); border: 1px solid var(--border);
  border-radius: var(--r-lg); padding: 20px;
  position: sticky; top: 90px;
  box-shadow: 0 1px 4px rgba(0,0,0,0.03);
}
.filter-head { font-size: 11px; font-weight: 800; letter-spacing: 0.12em; text-transform: uppercase; color: var(--text); margin-bottom: 16px; display: flex; align-items: center; justify-content: space-between; }
.filter-reset { font-size: 11px; font-weight: 500; color: var(--blue); text-decoration: none; letter-spacing: 0; text-transform: none; }
.filter-reset:hover { text-decoration: underline; }
.filter-group { margin-bottom: 18px; }
.fg-label { font-size: 10.5px; font-weight: 700; color: var(--muted); margin-bottom: 8px; letter-spacing: 0.06em; text-transform: uppercase; }
.fo {
  display: flex; align-items: center; gap: 8px;
  font-size: 12.5px; color: var(--sub); padding: 5px 0;
  cursor: pointer; border-radius: 6px;
  transition: color 0.14s;
  user-select: none; text-decoration: none;
}
.fo:hover { color: var(--text); }
.fo.on { color: var(--text); font-weight: 600; }
.fo-box {
  width: 16px; height: 16px; border-radius: 5px;
  border: 1.5px solid var(--border); flex-shrink: 0;
  background: var(--app-bg); display: flex; align-items: center; justify-content: center;
  font-size: 9px; color: #fff; transition: all 0.14s;
}
.fo.on .fo-box { background: var(--blue); border-color: var(--blue); }
.fo-count { margin-left: auto; font-size: 11px; color: var(--muted); }
.filter-divider { height: 1px; background: var(--border); margin: 14px 0; }

/* ═══ MPO GRID ═══ */
.mpo-sort-bar {
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 14px; padding: 0 2px;
}
.mpo-count { font-size: 12.5px; color: var(--sub); }
.mpo-count strong { color: var(--text); font-weight: 700; }
.sort-select {
  padding: 6px 12px; border-radius: 8px; border: 1.5px solid var(--border);
  background: var(--surface); font-family: inherit; font-size: 12.5px;
  color: var(--text); cursor: pointer; outline: none;
}

.mpo-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 14px;
}
@media(max-width: 1200px) { .mpo-grid { grid-template-columns: repeat(2, 1fr); } }
@media(max-width: 640px) { .mpo-grid { grid-template-columns: 1fr; } }

/* BOOK & COURSE CARDS */
.book-product-card { background: #FFFFFF; border: 0.5px solid #B1B5C4; border-radius: 20px; overflow: hidden; display: flex; flex-direction: column; transition: box-shadow 0.2s; text-decoration: none; }
.book-product-card:hover { box-shadow: 0 8px 24px rgba(100,120,180,0.18); }
.book-card-img { height: 260px; background: rgba(112,152,223,0.41); position: relative; display: flex; align-items: center; justify-content: center; overflow: hidden; }
.book-card-img-overlay { position: absolute; inset: 0; background: rgba(151,151,151,0.2); box-shadow: inset 0 0 15px rgba(0,0,0,0.25); }
.book-card-book { width: 130px; height: 182px; background: linear-gradient(144.46deg, #5BB8E8 0%, #3A9BD5 100%); box-shadow: -6px 6px 18px rgba(0,0,0,0.25), 3px 0 0 rgba(0,0,0,0.1); border-radius: 4px 10px 10px 4px; position: relative; z-index: 1; display: flex; flex-direction: column; justify-content: flex-end; padding: 14px 12px; }
.book-card-book::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 9px; background: rgba(0,0,0,0.15); border-radius: 4px 0 0 4px; }
.book-card-book-title { font-weight: 700; font-size: 12px; color: #fff; text-shadow: 0 1px 4px rgba(0,0,0,0.2); padding-left: 10px; line-height: 1.3; }
.book-card-body { padding: 0 20px 16px; display: flex; flex-direction: column; flex: 1; }
.book-card-features { display: flex; flex-direction: column; gap: 6px; padding: 12px 0; flex: 1; }
.book-card-feature { display: flex; align-items: center; gap: 8px; font-weight: 400; font-size: 11.5px; color: var(--sub); line-height: 1.35; }
.book-card-check { width: 18px; height: 18px; background: #DCFCE7; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 2px;}
.book-card-check svg { width: 9px; height: 9px; }
.book-card-footer { border-top: 0.5px solid #D9D9D9; padding-top: 12px; display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-top: auto;}
.book-card-price { font-weight: 800; font-size: 18px; letter-spacing: -0.4px; color: var(--text); }
.book-card-delivery { font-weight: 400; font-size: 10.5px; color: #64748B; margin-top: 2px; }
.book-btn-card-buy { background: var(--text); color: #fff; border: none; border-radius: 10px; padding: 9px 16px; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 12.5px; cursor: pointer; transition: background 0.18s; white-space: nowrap; }
.book-btn-card-buy:hover { background: var(--blue); }

/* CONSULT CARDS */
.consult-card { background: var(--surface); border: 1.5px solid var(--border); border-radius: var(--r-lg); padding: 18px; display: flex; flex-direction: column; gap: 11px; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.03); text-decoration: none; color: inherit; }
.consult-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
.cc-rec { display: inline-flex; align-items: center; gap: 4px; font-size: 10px; font-weight: 700; background: #dcfce7; color: #166534; border-radius: 50px; padding: 2px 8px; width: fit-content; }
.cc-doc { display: flex; align-items: center; gap: 11px; }
.cc-avatar { width: 44px; height: 44px; border-radius: 12px; background: var(--app-bg); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; overflow:hidden;}
.cc-avatar img { width: 100%; height: 100%; object-fit: cover;}
.cc-name { font-size: 13.5px; font-weight: 700; }
.cc-spec { font-size: 11px; color: var(--blue); font-weight: 600; }
.cc-exp { font-size: 10.5px; color: var(--muted); }
.cc-tags { display: flex; flex-wrap: wrap; gap: 5px; margin-top: auto;}
.cc-tag { font-size: 10.5px; font-weight: 500; background: var(--app-bg); border: 1px solid var(--border); border-radius: 50px; padding: 3px 9px; color: var(--sub); }
.cc-foot { display: flex; align-items: center; justify-content: space-between; padding-top: 10px; border-top: 1px solid var(--border); margin-top: auto;}
.cc-price { font-size: 16px; font-weight: 800; letter-spacing: -0.02em;}
.cc-price-sub { font-size: 10.5px; color: var(--muted); }
.cc-btn { background: var(--text); color: #fff; border: none; padding: 8px 16px; border-radius: 9px; font-family: inherit; font-size: 12px; font-weight: 700; cursor: pointer; transition: background 0.18s; }
.cc-btn:hover { background: var(--blue); }
</style>

<div class="prod-page">
  <!-- ── BREADCRUMB ── -->
  <div class="breadcrumb">
    <a href="/">Главная</a><span class="sep">›</span>
    <span class="cur">Каталог продуктов</span>
  </div>

  <!-- ── HERO ── -->
  <div class="hero">
    <div class="hero-bg-icon">🏥</div>
    <div>
      <h1 class="hero-title">Каталог <em>продуктов</em></h1>
      <p class="hero-desc">Здесь собраны все наши продукты: от индивидуальных консультаций специалистов до детальных курсов и готовых планов питания (МПО).</p>
    </div>
  </div>

  <!-- ── TAG FILTER ── -->
  <div style="margin-top:24px;">
    <div class="tag-bar">
      <a href="{{ strtok($_SERVER["REQUEST_URI"], '?') }}" class="tag-pill {{ empty($_GET['category']) ? 'on' : '' }}">Все направления</a>
      @php $activeCategoriesPill = !empty($_GET['category']) ? explode(',', $_GET['category']) : []; @endphp
      @foreach($categories as $cat)
         <a href="?category={{ $cat->slug }}{{ !empty($_GET['price']) ? '&price='.$_GET['price'] : '' }}{{ !empty($_GET['sort']) ? '&sort='.$_GET['sort'] : '' }}" class="tag-pill {{ in_array($cat->slug, $activeCategoriesPill) ? 'on' : '' }}">{{ $cat->name }}</a>
      @endforeach
    </div>
  </div>

  <!-- ── CATALOG ── -->
  <div class="sec-head" style="margin-top:32px;">
    <div>
      <div class="sec-title">📋 Каталог продуктов</div>
      <div class="sec-sub">Выберите необходимый вам продукт или консультацию</div>
    </div>
  </div>

  <div class="catalog-wrap">
    
    <!-- Sidebar filters -->
    <div class="filter-panel">
      <form action="{{ strtok($_SERVER["REQUEST_URI"], '?') }}" method="GET">
        <div class="filter-head">
          Фильтры
          <a href="{{ strtok($_SERVER["REQUEST_URI"], '?') }}" class="filter-reset">Сбросить</a>
        </div>

        <div class="filter-group">
          <div class="fg-label">Направление (Диагноз)</div>
          @php $activeCategories = !empty($_GET['category']) ? explode(',', sanitize_text_field($_GET['category'])) : []; @endphp
          @foreach($categories as $cat)
            @php $isActive = in_array($cat->slug, $activeCategories); @endphp
            <label class="fo {{ $isActive ? 'on' : '' }}">
              <input type="checkbox" name="category" value="{{ $cat->slug }}" class="hidden" {{ $isActive ? 'checked' : '' }}>
              <div class="fo-box">{{ $isActive ? '✓' : '' }}</div>{{ $cat->name }}<span class="fo-count">{{ $cat->count }}</span>
            </label>
          @endforeach
        </div>

        <div class="filter-divider"></div>

        <div class="filter-group">
           <div class="fg-label">Цена</div>
           @php $activePrices = !empty($_GET['price']) ? explode(',', sanitize_text_field($_GET['price'])) : []; @endphp
           
           <label class="fo {{ in_array('up_to_2000', $activePrices) ? 'on' : '' }}">
             <input type="checkbox" name="price" value="up_to_2000" class="hidden" {{ in_array('up_to_2000', $activePrices) ? 'checked' : '' }}>
             <div class="fo-box">{{ in_array('up_to_2000', $activePrices) ? '✓' : '' }}</div>До 2 000 ₽
           </label>
           
           <label class="fo {{ in_array('2000_to_5000', $activePrices) ? 'on' : '' }}">
             <input type="checkbox" name="price" value="2000_to_5000" class="hidden" {{ in_array('2000_to_5000', $activePrices) ? 'checked' : '' }}>
             <div class="fo-box">{{ in_array('2000_to_5000', $activePrices) ? '✓' : '' }}</div>2 000–5 000 ₽
           </label>
           
           <label class="fo {{ in_array('over_5000', $activePrices) ? 'on' : '' }}">
             <input type="checkbox" name="price" value="over_5000" class="hidden" {{ in_array('over_5000', $activePrices) ? 'checked' : '' }}>
             <div class="fo-box">{{ in_array('over_5000', $activePrices) ? '✓' : '' }}</div>Свыше 5 000 ₽
           </label>
        </div>

        <div class="filter-divider"></div>

        <div class="filter-group hidden">
          <div class="fg-label">Сортировка (через JS)</div>
          @php $sortVal = $_GET['sort'] ?? ''; @endphp
          <input type="hidden" name="sort" class="hidden-sort" value="{{ $sortVal }}">
        </div>
      </form>
    </div>

    <!-- MPO cards / Main Content -->
    <div class="mpo-grid-wrap">
      <div class="mpo-sort-bar">
        <div class="mpo-count">Показано <strong>{{ count($products) }}</strong> элементов</div>
        <select class="sort-select">
          <option value="hierarchy" {{ $sortVal === '' || $sortVal === 'hierarchy' ? 'selected' : '' }}>По иерархии</option>
          <option value="price_asc" {{ $sortVal === 'price_asc' ? 'selected' : '' }}>По цене ↑</option>
          <option value="price_desc" {{ $sortVal === 'price_desc' ? 'selected' : '' }}>По цене ↓</option>
        </select>
      </div>

      <div class="mpo-grid">
        @if(count($products) > 0)
          @foreach($products as $product)
            @if($product['post_type'] === 'consultation')
              @include('partials.card-consultation', ['item' => $product])
            @elseif($product['post_type'] === 'course')
              @include('partials.card-course', ['item' => $product])
            @elseif($product['post_type'] === 'book')
              @include('partials.card-book', ['item' => $product])
            @endif
          @endforeach
        @else
          <div style="grid-column: 1 / -1; padding: 40px; text-align: center; border: 2px dashed var(--border); border-radius: var(--r-lg); color: var(--sub);">
            По вашему запросу ничего не найдено.
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function refreshFilters(url) {
        window.history.pushState({}, '', url);
        const grid = document.querySelector('.mpo-grid');
        if (grid) grid.style.opacity = '0.5';
        
        fetch(url)
            .then(res => res.text())
            .then(html => {
                const doc = new DOMParser().parseFromString(html, 'text/html');
                
                const parts = ['.tag-bar', '.catalog-wrap'];
                parts.forEach(selector => {
                    const oldEl = document.querySelector(selector);
                    const newEl = doc.querySelector(selector);
                    if (oldEl && newEl) {
                        oldEl.innerHTML = newEl.innerHTML;
                    }
                });
            })
            .catch(err => {
                if (grid) grid.style.opacity = '1';
                console.error(err);
            });
    }

    document.addEventListener('change', function(e) {
        if (e.target.closest('.filter-panel') || e.target.classList.contains('sort-select')) {
            const form = document.querySelector('.filter-panel form');
            if (!form) return;
            
            const categories = Array.from(form.querySelectorAll('input[name="category"]:checked')).map(el => el.value);
            const prices = Array.from(form.querySelectorAll('input[name="price"]:checked')).map(el => el.value);
            
            const sortSelect = document.querySelector('.sort-select');
            const sort = sortSelect ? sortSelect.value : 'hierarchy';
            
            const params = new URLSearchParams();
            if (categories.length) params.set('category', categories.join(','));
            if (prices.length) params.set('price', prices.join(','));
            if (sort && sort !== 'hierarchy') params.set('sort', sort);
            
            let url = window.location.pathname;
            if (params.toString()) url += '?' + params.toString();
            
            refreshFilters(url);
        }
    });

    document.addEventListener('click', function(e) {
        const pill = e.target.closest('.tag-pill');
        if (pill) {
            e.preventDefault();
            refreshFilters(pill.getAttribute('href'));
            return;
        }
        
        const resetBtn = e.target.closest('.filter-reset');
        if (resetBtn) {
            e.preventDefault();
            refreshFilters(resetBtn.getAttribute('href'));
            return;
        }
    });

    window.addEventListener('popstate', function() {
        window.location.reload();
    });
});
</script>
@endsection
