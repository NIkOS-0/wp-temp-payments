@extends('layouts.app')

@section('content')

@php
  $q         = $searchQuery ?? get_search_query();
  $active    = $activeType  ?? '';
  $total     = $totalResults ?? 0;
  $baseSearch = home_url('/') . '?post_type[]=book&post_type[]=course&post_type[]=consultation&s=' . urlencode($q);

  $tabs = [
    ''             => 'Все результаты',
    'consultation' => 'Консультации',
    'course'       => 'Курсы',
    'book'         => 'Книги / МПО',
  ];
  $tabUrl = fn($type) => home_url('/') . '?post_type[]=book&post_type[]=course&post_type[]=consultation&s=' . urlencode($q) . ($type ? '&type=' . $type : '');
@endphp

<div style="background:#F5EFE2;min-height:100vh;font-family:'Instrument Sans','Inter',sans-serif;color:#2A1A10;">
<div class="container-editorial py-8 pb-24">

  {{-- ── BREADCRUMB ── --}}
  <nav class="flex items-center gap-1.5 text-xs mb-8" style="color:#A89F8B;">
    <a href="/" style="color:#A89F8B;" class="hover:text-terra-500 transition-colors">Главная</a>
    <svg width="10" height="10" viewBox="0 0 10 10" fill="none" style="opacity:.5;"><path d="M3.5 1.5L6.5 5L3.5 8.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
    <span style="color:#2A1A10;font-weight:500;">Поиск</span>
  </nav>

  {{-- ── HERO ── --}}
  <div class="relative bg-white rounded-3xl overflow-hidden mb-6"
       style="border:1px solid rgba(42,26,16,0.10);box-shadow:0 4px 24px rgba(42,26,16,0.06);">
    <div class="absolute top-0 left-0 right-0"
         style="height:2px;background:linear-gradient(90deg,#EF945B 0%,#D87A4A 45%,#EBE3D2 100%);"></div>

    <div class="px-8 py-8 lg:px-12 lg:py-10">
      <p class="text-overline mb-3">Поиск по каталогу</p>

      @if($q)
        <h1 style="font-family:'Playfair Display',serif;font-size:clamp(22px,3.5vw,36px);font-weight:700;color:#2A1A10;line-height:1.2;letter-spacing:-0.02em;margin-bottom:6px;">
          «<em style="font-style:italic;color:#EF945B;">{{ esc_html($q) }}</em>»
        </h1>
        <p style="font-size:14px;color:#A89F8B;margin-bottom:20px;">
          @if($total > 0)
            Найдено {{ $total }} {{ $total === 1 ? 'результат' : ($total < 5 ? 'результата' : 'результатов') }}
          @else
            По вашему запросу ничего не найдено
          @endif
        </p>
      @else
        <h1 style="font-family:'Playfair Display',serif;font-size:clamp(24px,3.5vw,38px);font-weight:700;color:#2A1A10;line-height:1.2;letter-spacing:-0.02em;margin-bottom:16px;">
          Поиск по каталогу
        </h1>
      @endif

      {{-- Search form --}}
      <form role="search" method="get" action="{{ home_url('/') }}"
            style="display:flex;align-items:center;gap:0;background:#F5EFE2;border:1.5px solid rgba(42,26,16,0.15);border-radius:50px;height:50px;padding:0 8px 0 20px;max-width:580px;transition:all .15s;">
        <input type="hidden" name="post_type[]" value="book">
        <input type="hidden" name="post_type[]" value="course">
        <input type="hidden" name="post_type[]" value="consultation">
        @if($active)<input type="hidden" name="type" value="{{ $active }}">@endif
        <input type="search" name="s" value="{{ esc_attr($q) }}"
               placeholder="Введите запрос…"
               style="flex:1;background:transparent;border:none;outline:none;font-family:inherit;font-size:15px;color:#2A1A10;padding:0 10px 0 0;"
               autofocus>
        <button type="submit"
                style="background:#2A1A10;color:#F5EFE2;border:none;border-radius:50px;cursor:pointer;display:flex;align-items:center;justify-content:center;height:36px;padding:0 18px;gap:6px;font-family:inherit;font-size:13px;font-weight:600;transition:background .15s;white-space:nowrap;">
          <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="8" stroke-width="2"/>
            <path d="M21 21l-4.35-4.35" stroke-width="2" stroke-linecap="round"/>
          </svg>
          Найти
        </button>
      </form>
    </div>
  </div>

  @if($q)
    {{-- ── TYPE TABS ── --}}
    <div class="flex flex-wrap gap-2 mb-6">
      @foreach($tabs as $typeKey => $tabLabel)
        <a href="{{ $tabUrl($typeKey) }}"
           class="prod-pill {{ $active === $typeKey ? 'prod-pill--on' : '' }}">
          {{ $tabLabel }}
        </a>
      @endforeach
    </div>

    {{-- ── RESULTS ── --}}
    @if($total > 0)
      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;" id="search-grid">
        @foreach($results as $item)
          @if($item['post_type'] === 'consultation')
            <div style="grid-column:1 / -1;">
              @include('partials.card-consultation', ['item' => $item])
            </div>
          @else
            <div>
              @include('partials.card-' . $item['post_type'], ['item' => $item])
            </div>
          @endif
        @endforeach
      </div>

    @else
      {{-- Empty state --}}
      <div class="bg-white rounded-3xl p-12 text-center"
           style="border:1px solid rgba(42,26,16,0.10);">
        <div style="font-size:52px;margin-bottom:16px;opacity:.3;">🔍</div>
        <h2 style="font-size:20px;font-weight:700;color:#2A1A10;margin-bottom:10px;">
          Ничего не найдено
        </h2>
        <p style="font-size:14px;color:#A89F8B;max-width:380px;margin:0 auto 24px;line-height:1.7;">
          По запросу «{{ esc_html($q) }}» нет результатов. Попробуйте другое слово или перейдите в каталог.
        </p>
        <div class="flex items-center justify-center gap-3 flex-wrap">
          <a href="{{ home_url('/products') }}"
             style="display:inline-flex;align-items:center;gap:6px;padding:10px 22px;background:#2A1A10;color:#F5EFE2;border-radius:50px;font-size:13px;font-weight:600;text-decoration:none;transition:opacity .15s;"
             onmouseover="this.style.opacity='.82'" onmouseout="this.style.opacity='1'">
            Перейти в каталог
          </a>
          <a href="{{ home_url('/') }}"
             style="display:inline-flex;align-items:center;padding:10px 22px;border:1.5px solid rgba(42,26,16,0.18);border-radius:50px;font-size:13px;font-weight:600;color:#6a5040;text-decoration:none;transition:all .15s;"
             onmouseover="this.style.borderColor='rgba(42,26,16,0.35)';this.style.color='#2A1A10'" onmouseout="this.style.borderColor='rgba(42,26,16,0.18)';this.style.color='#6a5040'">
            На главную
          </a>
        </div>
      </div>
    @endif

  @else
    {{-- No query — invite to search --}}
    <div class="bg-white rounded-3xl p-12 text-center"
         style="border:1px solid rgba(42,26,16,0.10);">
      <p style="font-size:15px;color:#A89F8B;">Введите запрос в строку выше, чтобы найти нужный продукт.</p>
    </div>
  @endif

</div>
</div>

<style>
.prod-pill {
  display: inline-flex; align-items: center;
  padding: 6px 16px; border-radius: 50px;
  font-size: 12.5px; font-weight: 500;
  border: 1.5px solid rgba(42,26,16,0.15);
  background: #EBE3D2; color: #6a5040;
  cursor: pointer; transition: all 0.15s;
  text-decoration: none; white-space: nowrap;
}
.prod-pill:hover { border-color: rgba(42,26,16,0.30); color: #2A1A10; }
.prod-pill--on { background: #2A1A10; border-color: #2A1A10; color: #F5EFE2; font-weight: 600; }

@media (max-width: 768px) {
  #search-grid { grid-template-columns: 1fr !important; }
  #search-grid > div { grid-column: 1 / -1 !important; }
}
</style>

@endsection
