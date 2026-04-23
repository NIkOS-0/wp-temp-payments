{{-- Template Name: Single Course Card --}}
@extends('layouts.app')

@section('content')
  @php 
    $bid = uniqid('crs_'); 
  @endphp

  <style>
    :root {
      --book-bg: #F5EFE2;
      --book-white: #FFFFFF;
      --book-text-dark: #2A1A10;
      --book-text-mid: #6a5040;
      --book-text-light: #A89F8B;
      --book-border: rgba(42,26,16,0.12);
      --book-shadow: 0px 5px 20px rgba(42,26,16,0.08);
      --book-radius-card: 24px;
      --book-radius-btn: 50px;
    }

    .bg-main { background: var(--book-bg); font-family: 'Instrument Sans', 'Inter', sans-serif; color: var(--book-text-dark); min-width: 1160px; }
  
    .book-page {
      max-width: 1160px;
      margin: 0 auto;
      padding: 24px 20px 108px;
      display: flex;
      flex-direction: column;
      gap: 20px;
    }
  
    /* BREADCRUMB */
    .book-breadcrumb {
      display: flex; align-items: center; gap: 8px; padding-top: 16px; font-size: 11.5px; color: var(--book-text-light);
    }
    .book-breadcrumb a { color: var(--book-text-light); text-decoration: none; }
    .book-breadcrumb .sep { font-size: 12px; }
    .book-breadcrumb .current { font-weight: 500; color: #000; font-size: 12px; }
  
    /* HERO */
    .book-hero {
      background: var(--book-white); border: 1px solid rgba(255,255,255,0.88);
      box-shadow: var(--book-shadow); border-radius: var(--book-radius-card);
      padding: 40px; display: flex; gap: 40px;
    }
  
    /* Gallery */
    .book-gallery { width: 420px; flex-shrink: 0; display: flex; flex-direction: column; gap: 12px; }
    .book-gallery-main {
      width: 420px; height: 400px; background: linear-gradient(133.6deg, #E8EDF5 0%, #D0D9E8 100%);
      border-radius: 20px; display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden;
    }
    .book-badge-bestseller {
      position: absolute; top: 16px; left: 16px; background: rgba(217,217,217,0.7);
      border: 1px solid rgba(255,255,255,0.5); border-radius: 30px; padding: 2px 12px;
      font-family: 'Roboto', sans-serif; font-weight: 600; font-size: 11px; letter-spacing: 1.65px; color: #fff; z-index: 2;
    }
    .book-cover {
      width: 200px; height: 280px; background: linear-gradient(144.46deg, #5BB8E8 0%, #3A9BD5 100%);
      box-shadow: -8px 8px 24px rgba(0,0,0,0.25), 4px 0px 0px rgba(0,0,0,0.1); border-radius: 6px 14px 14px 6px;
      position: relative; display: flex; flex-direction: column; justify-content: flex-end; padding: 20px 16px;
    }
    .book-cover::before {
      content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 12px; background: rgba(0,0,0,0.15); border-radius: 6px 0 0 6px;
    }
    .book-cover-text { font-weight: 800; font-size: 18px; color: #fff; text-shadow: 0 1px 8px rgba(0,0,0,0.2); line-height: 1.2; padding-left: 14px; }
    .book-cover-sub { font-weight: 400; font-size: 11px; color: rgba(255,255,255,0.8); padding-left: 14px; margin-top: 4px; }
  
    /* Thumbnails */
    .book-gallery-thumbs-wrap { display: flex; flex-direction: row; align-items: center; justify-content: center; gap: 12px; margin-top: 8px;}
    .book-gallery-thumbs { display: flex; gap: 8px; align-items: center; }
    .book-thumb {
      width: 60px; height: 60px; background: #E0E5EF; border-radius: 10px; display: flex; align-items: center; justify-content: center;
      cursor: pointer; transition: border 0.15s;
    }
    .book-thumb.active { border: 2px solid #000; }
    .book-thumb svg { width: 28px; height: 28px; opacity: 0.5; }
  
    /* Info */
    .book-info { flex: 1; position: relative; display: flex; flex-direction: column; gap: 0; }
    .book-tags { display: flex; gap: 8px; margin-bottom: 8px; }
    .book-tag { padding: 2px 10px; border-radius: 50px; font-weight: 700; font-size: 10.5px; letter-spacing: 0.315px; }
    .book-tag-pdf { background: #fde8d4; border: 1px solid #F4B491; color: #D87A4A; }
    .book-tag-verified { background: #DCFCE7; border: 1px solid #BBF7D0; color: #166534; }
    .book-tag-default { background: #EBE3D2; border: 1px solid rgba(42,26,16,0.15); color: #6a5040; }
  
    .book-product-title {
      font-family: 'Public Sans', sans-serif; font-weight: 700; font-size: 32px; line-height: 37px; letter-spacing: -1.2px; color: #0F172A; margin-bottom: 8px;
    }
    .book-author-line { font-size: 14px; color: var(--book-text-mid); margin-bottom: 16px; }
  
    /* Stats */
    .book-mini-stats {
      display: flex; border: 1px solid rgba(42,26,16,0.12); border-radius: 16px; background: rgba(245,239,226,0.6); backdrop-filter: blur(2.5px);
      overflow: hidden; margin-bottom: 24px;
    }
    .book-stat-cell {
      flex: 1; padding: 14px 22px; display: flex; flex-direction: column; align-items: center; gap: 3px; border-right: 1px solid rgba(210,225,248,0.6);
    }
    .book-stat-cell:last-child { border-right: none; }
    .book-stat-value { font-weight: 800; font-size: 22px; line-height: 27px; letter-spacing: -0.66px; color: #2F2F2F; }
    .book-stat-label { font-size: 10.5px; color: var(--book-text-mid); text-align: center; }
  
    /* Features */
    .book-features { display: flex; flex-direction: column; gap: 10px; margin-bottom: 24px; }
    .book-feature-item { display: flex; align-items: center; gap: 10px; }
    .book-check-circle { width: 22px; height: 22px; background: #DCFCE7; border-radius: 11px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .book-check-circle svg { width: 11px; height: 11px; }
    .book-feature-text { font-weight: 200; font-size: 14px; letter-spacing: -0.4px; color: var(--book-text-dark); }
    .book-divider { height: 0.5px; background: #D9D9D9; margin-bottom: 16px; }
  
    /* Price */
    .book-price-row { display: flex; align-items: center; gap: 12px; margin-bottom: 8px; }
    .book-price-main { font-weight: 600; font-size: 36px; line-height: 44px; letter-spacing: -1.2px; color: #000; }
    .book-price-old { font-size: 18px; text-decoration: line-through; color: var(--book-text-light); }
    .book-price-badge { background: #FEE2E2; border: 1px solid #FECACA; border-radius: 50px; padding: 3px 10px; font-weight: 700; font-size: 11px; color: #991B1B; }
    .book-delivery-note { font-weight: 200; font-size: 12px; letter-spacing: -0.4px; color: var(--book-text-light); margin-bottom: 16px; }
  
    /* CTA */
    .book-cta-row { display: flex; gap: 12px; }
    .book-btn-buy {
      flex: 1; height: 52px; background: #000; border-radius: var(--book-radius-btn); border: none; color: #fff;
      font-weight: 700; font-size: 15px; letter-spacing: -0.2px; cursor: pointer; transition: opacity 0.15s;
    }
    .book-btn-buy:hover { opacity: 0.85; }
    .book-btn-icon {
      width: 52px; height: 52px; background: rgba(217,217,217,0.5); border: 1px solid #E5E7EB; border-radius: var(--book-radius-btn);
      display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0; transition: background 0.15s;
    }
    .book-btn-icon:hover { background: rgba(217,217,217,0.8); }
  
    /* TABS */
    .book-tabs-card {
      background: var(--book-white); border: 1px solid rgba(255,255,255,0.88); box-shadow: var(--book-shadow); border-radius: var(--book-radius-card); padding: 12px 40px 0;
    }
    .book-tab-nav { display: flex; border-bottom: 1px solid #E5E7EB; }
    .book-tab-btn {
      padding: 20px 28px; font-weight: 600; font-size: 14px; letter-spacing: -0.3px; color: var(--book-text-light);
      background: none; border: none; cursor: pointer; position: relative; transition: color 0.15s;
    }
    .book-tab-btn.active { color: #000; }
    .book-tab-btn.active::after { content: ''; position: absolute; bottom: -1px; left: 0; right: 0; height: 2px; background: #000; border-radius: 2px 2px 0 0; }
    .book-tab-content { padding: 36px 0; display: none; }
    .book-tab-content.active { display: block; }
  
    .book-desc-heading { font-family: 'Public Sans', sans-serif; font-weight: 700; font-size: 18px; line-height: 27px; letter-spacing: -0.4px; color: var(--book-text-dark); margin-bottom: 16px; }
    .book-desc-paragraph { font-size: 14px; line-height: 25px; color: var(--book-text-mid); margin-bottom: 16px; }
    .book-desc-quote {
      border-left: 3px solid #E2E8F0; border-radius: 14px; font-style: italic; font-size: 13.5px; line-height: 22px; color: var(--book-text-mid);
      margin-bottom: 24px; border: 1px solid rgba(217,217,217,0.3); padding: 10px 20px;
    }
    .book-use-cases { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 24px;}
    .book-use-case-card { background: #F8FAFC; border: 1px solid #E5E7EB; border-radius: 14px; padding: 14px 16px; display: flex; gap: 10px; align-items: flex-start; }
    .book-use-icon { width: 28px; height: 28px; background: #EFF6FF; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .book-use-icon svg { width: 14px; height: 14px; }
    .book-use-text strong { display: block; font-size: 13px; font-weight: 700; color: var(--book-text-dark); margin-bottom: 2px; }
    .book-use-text span { font-size: 13px; color: var(--book-text-mid); line-height: 20px; }
    ul.book-toc { list-style: none; padding: 0; margin: 0; }
    ul.book-toc li { margin-bottom: 16px; font-size: 14px; color: var(--book-text-dark); padding-left: 12px; position: relative; }
    ul.book-toc li::before { content: '•'; position: absolute; left: 0; color: #16A34A ; font-weight: bold; }
    ul.book-toc li span { display: block; font-size: 13px; color: var(--book-text-mid); margin-top: 4px; }
  
    /* AUTHOR */
    .book-author-card { background: var(--book-white); border: 1px solid rgba(255,255,255,0.9); box-shadow: var(--book-shadow); border-radius: var(--book-radius-card); display: flex; overflow: hidden; }
    .book-author-sidebar { width: 290px; flex-shrink: 0; border-right: 1px solid var(--book-border); padding: 36px 20px 40px; display: flex; flex-direction: column; align-items: center; gap: 20px; }
    .book-author-avatar-wrap { position: relative; width: 120px; height: 120px; }
    .book-author-avatar {
      width: 120px; height: 120px; background: linear-gradient(140deg, #BFBFBF 0%, #9E9E9E 55%, #fff 100%);
      box-shadow: inset 0 1px 0 rgba(255,255,255,0.5); border-radius: 34px; display: flex; align-items: center; justify-content: center; overflow:hidden;
    }
    .book-author-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .book-author-avatar svg { width: 60px; height: 60px; opacity: 0.4; }
    .book-author-verified {
      position: absolute; right: -6px; bottom: -6px; width: 30px; height: 30px; background: linear-gradient(135deg, #22C55E 0%, #15803D 100%);
      border: 3px solid #EDF1F7; box-shadow: 0 3px 10px rgba(34,197,94,0.4); border-radius: 15px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 13px;
    }
    .book-author-name { font-weight: 800; font-size: 17px; letter-spacing: -0.425px; color: var(--book-text-dark); text-align: center; }
    .book-author-spec { font-size: 11.5px; color: var(--book-text-mid); text-align: center; }
    .book-author-tags { display: flex; flex-direction: column; align-items: center; gap: 6px; }
    .book-author-tag { background: #E7EEF1; border: 1px solid #BFBFBF; border-radius: 50px; padding: 5px 16px; font-weight: 600; font-size: 11px; color: var(--book-text-mid); white-space: nowrap; }
    .book-author-main { flex: 1; padding: 36px 40px; display: flex; flex-direction: column; justify-content: center; gap: 24px; }
    .book-author-stats { display: flex; border: 1px solid rgba(42,26,16,0.12); border-radius: 16px; background: rgba(245,239,226,0.6); backdrop-filter: blur(2.5px); overflow: hidden; max-width: 574px; }
    .book-author-heading { font-weight: 800; font-size: 22px; line-height: 27px; letter-spacing: -0.55px; color: var(--book-text-dark); }
    .book-author-bio { font-size: 14px; line-height: 25px; color: var(--book-text-mid); }
    .book-author-quote { border-left: 3px solid #E2E8F0; font-style: italic; font-size: 13.5px; line-height: 22px; color: var(--book-text-mid); border: 1px solid rgba(217,217,217,0.3); border-radius: 10px; padding: 10px 20px; }
  
    /* CROSS SELL */
    .book-section-heading { font-weight: 700; font-size: 32px; line-height: 38px; letter-spacing: -1.2px; color: #2A1A10; padding: 12px 0 1px; }
    .book-cards-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; position: relative; }
  
    /* Consult card inside cross sells */
    .consult-card { background: var(--book-white); border: 1.5px solid var(--book-border); border-radius: 18px; padding: 18px; display: flex; flex-direction: column; gap: 11px; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.03); text-decoration: none; color: inherit; }
    .consult-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
    .cc-rec { display: inline-flex; align-items: center; gap: 4px; font-size: 10px; font-weight: 700; background: #dcfce7; color: #166534; border-radius: 50px; padding: 2px 8px; width: fit-content; }
    .cc-doc { display: flex; align-items: center; gap: 11px; }
    .cc-avatar { width: 44px; height: 44px; border-radius: 12px; background: var(--book-bg); border: 1px solid var(--book-border); display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; overflow:hidden;}
    .cc-avatar img { width: 100%; height: 100%; object-fit: cover;}
    .cc-name { font-size: 13.5px; font-weight: 700; }
    .cc-spec { font-size: 11px; color: #2563eb; font-weight: 600; }
    .cc-exp { font-size: 10.5px; color: var(--book-text-mid); }
    .cc-tags { display: flex; flex-wrap: wrap; gap: 5px; margin-top: auto;}
    .cc-tag { font-size: 10.5px; font-weight: 500; background: var(--book-bg); border: 1px solid var(--book-border); border-radius: 50px; padding: 3px 9px; color: var(--book-text-mid); }
    .cc-foot { display: flex; align-items: center; justify-content: space-between; padding-top: 10px; border-top: 1px solid var(--book-border); margin-top: auto;}
    .cc-price { font-size: 16px; font-weight: 800; letter-spacing: -0.02em;}
    .cc-price-sub { font-size: 10.5px; color: var(--book-text-mid); }
    .cc-btn { background: var(--book-text-dark); color: #fff; border: none; padding: 8px 16px; border-radius: 9px; font-family: inherit; font-size: 12px; font-weight: 700; cursor: pointer; transition: background 0.18s; }
    .cc-btn:hover { background: #2563eb; }

    /* Navigation row like featured_products */
    .mpo-nav-left { display: flex; align-items: center; gap: 12px; }
    .mpo-arrow { width: 38px; height: 38px; border-radius: 50%; background: var(--book-white); border: 1.5px solid var(--book-border); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.18s; color: var(--book-text-dark); box-shadow: 0 1px 3px rgba(0,0,0,0.06); }
    .mpo-arrow svg { width: 14px; height: 14px; }
    .mpo-arrow:hover:not(:disabled) { background: var(--book-text-dark); color: #fff; border-color: var(--book-text-dark); }
    .mpo-dots { display: flex; gap: 6px; align-items: center; display: none;}
    
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
    .book-hero, .book-tabs-card, .book-author-card, .book-cards-row { animation: fadeInUp 0.45s ease both; }
    .book-tabs-card { animation-delay: 0.07s; }
    .book-author-card { animation-delay: 0.14s; }
    .book-cards-row { animation-delay: 0.21s; }
  </style>

  <div class="bg-main">
    <div class="book-page">

      <!-- BREADCRUMB -->
      <nav class="book-breadcrumb">
        <a href="/">Главная</a>
        <span class="sep">/</span>
        <a href="/products">Продукты</a>
        <span class="sep">/</span>
        <span class="current">{{ $course['title'] }}</span>
      </nav>

      <!-- HERO -->
      <section class="book-hero">
        <div class="book-gallery">
          <div class="book-gallery-main" id="book-gallery-main">
            @if($course['image_badge'])
              <span class="book-badge-bestseller">{{ $course['image_badge'] }}</span>
            @endif
            
            @if(!empty($course['gallery']))
              @foreach($course['gallery'] as $idx => $img)
                <img src="{{ $img['sizes']['large'] ?? $img['url'] }}" 
                     class="book-main-img {{ $idx === 0 ? 'active' : '' }}" 
                     data-index="{{ $idx }}"
                     style="width: 100%; height: 100%; object-fit: cover; position: absolute; top:0; left:0; border-radius: 20px; transition: opacity 0.3s; opacity: {{ $idx === 0 ? '1' : '0' }}; pointer-events: {{ $idx === 0 ? 'auto' : 'none' }};">
              @endforeach
            @else
              <div class="book-cover" style="background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%); border-radius: 12px; box-shadow: -8px 8px 24px rgba(37,99,235,0.25);">
                <div class="book-cover-text">Курс</div>
                @if($course['subtitle'])
                  <div class="book-cover-sub">{{ $course['subtitle'] }}</div>
                @endif
              </div>
            @endif
          </div>

          @if(!empty($course['gallery']) && count($course['gallery']) > 1)
          <div class="book-gallery-thumbs-wrap">
            <button class="mpo-arrow" id="prev-{{ $bid }}" aria-label="Назад">
              <svg viewBox="0 0 14 14" fill="none"><path d="M9 2.5L4.5 7 9 11.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
            <div class="book-gallery-thumbs" id="thumb-container">
              @foreach($course['gallery'] as $idx => $img)
              <div class="book-thumb {{ $idx === 0 ? 'active' : '' }}" data-index="{{ $idx }}" style="background-image: url('{{ $img['sizes']['thumbnail'] ?? $img['url'] }}'); background-size: cover; background-position: center;">
              </div>
              @endforeach
            </div>
            <button class="mpo-arrow" id="next-{{ $bid }}" aria-label="Вперёд">
              <svg viewBox="0 0 14 14" fill="none"><path d="M5 2.5L9.5 7 5 11.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
            <div class="mpo-dots" id="dots-{{ $bid }}"></div>
          </div>
          @endif
        </div>

        <div class="book-info">
          <div class="book-tags">
            @foreach($course['top_labels'] as $label)
              @php
                  $tClass = 'book-tag-default';
                  if($label['style'] === 'pdf') $tClass = 'book-tag-pdf';
                  if($label['style'] === 'verified') $tClass = 'book-tag-verified';
              @endphp
              <span class="book-tag {{ $tClass }}">{{ $label['text'] }}</span>
            @endforeach
          </div>

          <h1 class="book-product-title">{!! nl2br(esc_html($course['title'])) !!}</h1>
          
          @if($author)
            <p class="book-author-line">Автор: {{ $author['name'] }} · {{ $author['spec'] }}</p>
          @endif

          @if(!empty($course['stats']))
          <div class="book-mini-stats">
            @foreach($course['stats'] as $st)
            <div class="book-stat-cell">
              <span class="book-stat-value">{{ $st['number'] }}</span>
              <span class="book-stat-label">{{ $st['label'] }}</span>
            </div>
            @endforeach
          </div>
          @endif

          <div class="book-features">
            @foreach($course['features'] as $f)
            <div class="book-feature-item">
              <div class="book-check-circle"><svg viewBox="0 0 11 11" fill="none"><path d="M2.5 5.5L4.5 7.5L8.5 3.5" stroke="#16A34A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
              <span class="book-feature-text">{{ $f['text'] ?? $f['title'] ?? 'Полезно для здоровья' }}</span>
            </div>
            @endforeach
          </div>

          <div class="book-divider"></div>

          <div class="book-price-row">
            @if($course['price_old'])
              <span class="book-price-old">{{ $course['price_old'] }} ₽</span>
              @if($course['price'] && (int)$course['price'] > 0)
                <span class="book-price-badge">−{{ round(100 - ((float)$course['price'] / (float)$course['price_old']) * 100) }}%</span>
              @endif
            @endif
          </div>
          <div class="book-price-row" style="margin-bottom: 2px;">
            <span class="book-price-main">{{ $course['price'] ? $course['price'] . ' ₽' : 'Бесплатно' }}</span>
          </div>
          
          <div class="book-delivery-note">{{ $course['delivery_note'] }}</div>

          @php $buy_url = get_permalink(get_the_ID()); @endphp
          <div class="book-cta-row">
            @if(is_user_logged_in())
              <button class="book-btn-buy" onclick="window.location.href='{{ $buy_url }}'">Участвовать</button>
            @else
              <button class="book-btn-buy" onclick="window.edietOpenBuyModal('{{ $buy_url }}')">Участвовать</button>
            @endif
            @include('partials.favorite-btn', ['post_id' => get_the_ID(), 'class' => 'book-btn-icon w-[52px] h-[52px] rounded-[14px]'])
          </div>
        </div>
      </section>

      <!-- TABS -->
      <section class="book-tabs-card">
        <div class="book-tab-nav">
          <button class="book-tab-btn active" data-target="tab-desc">Описание</button>
          <button class="book-tab-btn" data-target="tab-contents">Программа курса</button>
          <button class="book-tab-btn" data-target="tab-reviews">Отзывы 
            <span style="background: rgba(15,23,42,0.06); padding: 2px 8px; border-radius: 20px; font-size: 11px; margin-left: 6px;">{{ count($reviews) }}</span>
          </button>
        </div>

        <!-- Desc Tab -->
        <div class="book-tab-content active" id="tab-desc">
          <h2 class="book-desc-heading">О курсе</h2>
          <div class="book-desc-paragraph">
             @if(empty($course['description']))
               <p>Подробное описание курса поможет вам лучше понять, какие результаты вы получите после прохождения.</p>
             @endif
             @foreach($course['description'] as $block)
               @if($block['acf_fc_layout'] === 'text')
                 {!! $block['content'] !!}
               @elseif($block['acf_fc_layout'] === 'quote')
                 <div class="book-desc-quote">{{ $block['text'] }}</div>
               @elseif($block['acf_fc_layout'] === 'use_cases')
                 <div class="book-use-cases">
                    @foreach($block['items'] as $uc)
                      <div class="book-use-case-card">
                        <div class="book-use-icon">✨</div>
                        <div class="book-use-text">
                          <strong>{{ $uc['title'] }}</strong>
                          <span>{{ $uc['desc'] }}</span>
                        </div>
                      </div>
                    @endforeach
                 </div>
               @endif
             @endforeach
          </div>
        </div>

        <!-- Contents Tab -->
        <div class="book-tab-content" id="tab-contents">
          <h2 class="book-desc-heading">Что внутри курса</h2>
          @if(count($course['contents']) > 0)
            <ul class="book-toc">
              @foreach($course['contents'] as $ci)
              <li>
                <strong>{{ $ci['title'] }}</strong>
                @if(!empty($ci['desc']))
                  <span>{{ $ci['desc'] }}</span>
                @endif
              </li>
              @endforeach
            </ul>
          @else
            <div class="book-desc-paragraph text-slate-400">Программа курса активно дополняется...</div>
          @endif
        </div>

        <!-- Reviews Tab -->
        <div class="book-tab-content" id="tab-reviews">
          <h2 class="book-desc-heading">Отзывы студентов</h2>
          @if(count($reviews) > 0)
            @include('blocks.featured_reviews', ['items' => $reviews, 'title' => '', 'subtitle' => '', 'rv_theme' => 'light'])
          @else
            <div class="book-desc-paragraph text-slate-400">Отзывов пока нет. Участвуйте и станьте первым!</div>
          @endif
        </div>
      </section>
      
      <!-- AUTHOR -->
      @if($author)
      <section class="book-author-card">
        <div class="book-author-sidebar">
          <div class="book-author-avatar-wrap">
            <div class="book-author-avatar">
              @if($author['avatar_url'])
                <img src="{{ $author['avatar_url'] }}" alt="{{ $author['name'] }}">
              @else
                <svg viewBox="0 0 24 24" fill="none"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
              @endif
            </div>
            @if($author['verified'])
              <div class="book-author-verified">✓</div>
            @endif
          </div>
          <div>
            <div class="book-author-name">{{ $author['name'] }}</div>
            <div class="book-author-spec">{{ $author['spec'] }}</div>
          </div>
          @if(!empty($author['tags']))
          <div class="book-author-tags">
            @foreach(array_slice($author['tags'], 0, 3) as $tag)
              <div class="book-author-tag">{{ $tag['text'] ?? $tag['name'] ?? $tag }}</div>
            @endforeach
          </div>
          @endif
        </div>

        <div class="book-author-main">
          @if(!empty($author['stats']))
          <div class="book-author-stats">
            @foreach($author['stats'] as $ast)
              <div class="book-stat-cell">
                <span class="book-stat-value">{{ $ast['value'] ?? '' }}</span>
                <span class="book-stat-label">{{ $ast['label'] ?? '' }}</span>
              </div>
            @endforeach
          </div>
          @endif

          <div class="book-author-heading">Об авторе</div>
          <div class="book-author-bio">{!! $author['bio'] !!}</div>

          @if($author['quote'])
          <div class="book-author-quote">«{{ $author['quote'] }}»</div>
          @endif
        </div>
      </section>
      @endif

      <!-- CROSS-SELL -->
      @if(count($cross_sells) > 0)
      <h2 class="book-section-heading">Вас может заинтересовать</h2>
      <div class="book-cards-row">
        @foreach($cross_sells as $cs)
          @include('partials.dtc-card', ['item' => $cs])
        @endforeach
      </div>
      @endif

    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // TABS LOGIC
      const tabBtns = document.querySelectorAll('.book-tab-btn');
      const tabContents = document.querySelectorAll('.book-tab-content');
      tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
          tabBtns.forEach(b => b.classList.remove('active'));
          tabContents.forEach(c => c.classList.remove('active'));
          btn.classList.add('active');
          document.getElementById(btn.dataset.target).classList.add('active');
        });
      });

      // GALLERY PAGINATION LOGIC
      const bid = "{{ $bid }}";
      const thumbs = document.querySelectorAll('.book-thumb');
      const dotsContainer = document.getElementById('dots-' + bid);
      const prevBtn = document.getElementById('prev-' + bid);
      const nextBtn = document.getElementById('next-' + bid);
      
      const numItems = thumbs.length;
      if (numItems === 0) return;

      let current = 0;

      function renderDots() {
        if(!dotsContainer) return;
        dotsContainer.innerHTML = '';
        for (let i = 0; i < numItems; i++) {
          const d = document.createElement('button');
          d.className = 'mpo-dot' + (i === current ? ' on' : '');
          d.addEventListener('click', () => setGallery(i));
          dotsContainer.appendChild(d);
        }
      }

      function setGallery(index) {
        if (index < 0) index = numItems - 1;
        else if (index >= numItems) index = 0;
        current = index;
        
        thumbs.forEach((t, i) => {
          if (i === current) t.classList.add('active');
          else t.classList.remove('active');
        });

        const mainImgs = document.querySelectorAll('.book-main-img');
        mainImgs.forEach((img, i) => {
          if (i === current) {
            img.style.opacity = '1';
            img.style.pointerEvents = 'auto';
          } else {
            img.style.opacity = '0';
            img.style.pointerEvents = 'none';
          }
        });

        if (dotsContainer) {
          Array.from(dotsContainer.children).forEach((d, i) => {
            if (i === current) d.classList.add('on');
            else d.classList.remove('on');
          });
        }
      }

      if (prevBtn) prevBtn.addEventListener('click', () => setGallery(current - 1));
      if (nextBtn) nextBtn.addEventListener('click', () => setGallery(current + 1));
      thumbs.forEach((t, i) => {
        t.addEventListener('click', () => setGallery(i));
      });

      renderDots();
      setGallery(0);
    });
  </script>
@endsection
