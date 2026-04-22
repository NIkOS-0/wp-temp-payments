{{-- Template Name: Single Book Card --}}
@extends('layouts.app')

@section('content')
  @php 
    $bid = uniqid('book_'); 
  @endphp

  <style>
    :root {
      --book-bg: #EFF2F6;
      --book-white: #FFFFFF;
      --book-text-dark: #0F172A;
      --book-text-mid: #64748B;
      --book-text-light: #757575;
      --book-border: #DDE4EF;
      --book-shadow: 0px 5px 10px rgba(100,120,180,0.13);
      --book-radius-card: 28px;
      --book-radius-btn: 14px;
    }
  
    .bg-main { background: var(--book-bg); font-family: 'Inter', sans-serif; color: var(--book-text-dark); min-width: 1160px; }
  
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
    .book-gallery-thumbs-wrap { display: flex; flex-direction: column; align-items: center; gap: 8px; }
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
    .book-tag-pdf { background: #EFF6FF; border: 1px solid #BFDBFE; color: #1E40AF; }
    .book-tag-verified { background: #DCFCE7; border: 1px solid #BBF7D0; color: #166534; }
    .book-tag-default { background: #F1F5F9; border: 1px solid #E2E8F0; color: #475569; }
  
    .book-product-title {
      font-family: 'Public Sans', sans-serif; font-weight: 700; font-size: 32px; line-height: 37px; letter-spacing: -1.2px; color: #0F172A; margin-bottom: 8px;
    }
    .book-author-line { font-size: 14px; color: var(--book-text-mid); margin-bottom: 16px; }
  
    /* Stats */
    .book-mini-stats {
      display: flex; border: 1px solid rgba(210,225,248,0.8); border-radius: 16px; background: rgba(255,255,255,0.5); backdrop-filter: blur(2.5px);
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
    ul.book-toc li::before { content: '•'; position: absolute; left: 0; color: #3B82F6; font-weight: bold; }
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
    .book-author-stats { display: flex; border: 1px solid rgba(210,225,248,0.8); border-radius: 16px; background: rgba(255,255,255,0.5); backdrop-filter: blur(2.5px); overflow: hidden; max-width: 574px; }
    .book-author-heading { font-weight: 800; font-size: 22px; line-height: 27px; letter-spacing: -0.55px; color: var(--book-text-dark); }
    .book-author-bio { font-size: 14px; line-height: 25px; color: var(--book-text-mid); }
    .book-author-quote { border-left: 3px solid #E2E8F0; font-style: italic; font-size: 13.5px; line-height: 22px; color: var(--book-text-mid); border: 1px solid rgba(217,217,217,0.3); border-radius: 10px; padding: 10px 20px; }
  
    /* CROSS SELL */
    .book-section-heading { font-family: 'Public Sans', sans-serif; font-weight: 700; font-size: 32px; line-height: 38px; letter-spacing: -1.2px; color: #000; padding: 12px 0 1px; }
    .book-cards-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 21px; position: relative; }
    .book-product-card { background: var(--book-white); border: 0.5px solid #B1B5C4; border-radius: 20px; overflow: hidden; display: flex; flex-direction: column; transition: box-shadow 0.2s; text-decoration: none; }
    .book-product-card:hover { box-shadow: 0 8px 24px rgba(100,120,180,0.18); }
    .book-card-img { height: 295px; background: rgba(112,152,223,0.41); position: relative; display: flex; align-items: center; justify-content: center; overflow: hidden; }
    .book-card-img-overlay { position: absolute; inset: 0; background: rgba(151,151,151,0.2); box-shadow: inset 0 0 15px rgba(0,0,0,0.25); }
    .book-card-book { width: 130px; height: 182px; background: linear-gradient(144.46deg, #5BB8E8 0%, #3A9BD5 100%); box-shadow: -6px 6px 18px rgba(0,0,0,0.25), 3px 0 0 rgba(0,0,0,0.1); border-radius: 4px 10px 10px 4px; position: relative; z-index: 1; display: flex; flex-direction: column; justify-content: flex-end; padding: 14px 12px; }
    .book-card-book::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 9px; background: rgba(0,0,0,0.15); border-radius: 4px 0 0 4px; }
    .book-card-book-title { font-weight: 700; font-size: 12px; color: #fff; text-shadow: 0 1px 4px rgba(0,0,0,0.2); padding-left: 10px; line-height: 1.3; }
    .book-card-body { padding: 0 20px 16px; display: flex; flex-direction: column; flex: 1; }
    .book-card-features { display: flex; flex-direction: column; gap: 6px; padding: 12px 0; flex: 1; }
    .book-card-feature { display: flex; align-items: center; gap: 8px; font-weight: 200; font-size: 13px; color: #000; }
    .book-card-check { width: 18px; height: 18px; background: #DCFCE7; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .book-card-check svg { width: 9px; height: 9px; }
    .book-card-footer { border-top: 0.5px solid #D9D9D9; padding-top: 12px; display: flex; align-items: center; justify-content: space-between; gap: 8px;}
    .book-card-price { font-weight: 600; font-size: 20px; letter-spacing: -0.4px; color: #000; }
    .book-card-delivery { font-weight: 200; font-size: 12px; color: #DDE4EF; margin-top: 2px; }
    .book-btn-card-buy { background: #000; color: #fff; border: none; border-radius: 10px; padding: 11.5px 18px; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 12.5px; cursor: pointer; transition: opacity 0.15s; }
  
    /* Navigation row like featured_products */
    .mpo-nav-left { display: flex; align-items: center; gap: 12px; }
    .mpo-arrow { width: 38px; height: 38px; border-radius: 50%; background: var(--book-white); border: 1.5px solid var(--book-border); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.18s; color: var(--book-text-dark); box-shadow: 0 1px 3px rgba(0,0,0,0.06); }
    .mpo-arrow svg { width: 14px; height: 14px; }
    .mpo-arrow:hover:not(:disabled) { background: var(--book-text-dark); color: #fff; border-color: var(--book-text-dark); }
    .mpo-arrow:disabled { opacity: 0.3; cursor: default; }
    .mpo-dots { display: flex; gap: 6px; align-items: center; }
    .mpo-dot { width: 7px; height: 7px; border-radius: 50%; background: #d1d5db; border: none; cursor: pointer; transition: all 0.28s; padding: 0; }
    .mpo-dot.on { background: var(--book-text-dark); width: 22px; border-radius: 4px; }
  
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
        <a href="/books">Книги</a>
        <span class="sep">/</span>
        <span class="current">{{ $book['title'] }}</span>
      </nav>

      <!-- HERO -->
      <section class="book-hero">
        <div class="book-gallery">
          <div class="book-gallery-main" id="book-gallery-main">
            @if($book['image_badge'])
              <span class="book-badge-bestseller">{{ $book['image_badge'] }}</span>
            @endif
            
            @if(!empty($book['gallery']))
              @foreach($book['gallery'] as $idx => $img)
                <img src="{{ $img['sizes']['large'] ?? $img['url'] }}" 
                     class="book-main-img {{ $idx === 0 ? 'active' : '' }}" 
                     data-index="{{ $idx }}"
                     style="width: 100%; height: 100%; object-fit: cover; position: absolute; top:0; left:0; border-radius: 20px; transition: opacity 0.3s; opacity: {{ $idx === 0 ? '1' : '0' }}; pointer-events: {{ $idx === 0 ? 'auto' : 'none' }};">
              @endforeach
            @else
              <div class="book-cover">
                <div class="book-cover-text">Книга</div>
                @if($book['subtitle'])
                  <div class="book-cover-sub">{{ $book['subtitle'] }}</div>
                @endif
              </div>
            @endif
          </div>

          @if(!empty($book['gallery']) && count($book['gallery']) > 1)
          <div class="book-gallery-thumbs-wrap" style="flex-direction: row; justify-content: center; gap: 12px; margin-top: 8px;">
            <button class="mpo-arrow" id="prev-{{ $bid }}" aria-label="Назад">
              <svg viewBox="0 0 14 14" fill="none"><path d="M9 2.5L4.5 7 9 11.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
            
            <div class="book-gallery-thumbs" id="thumb-container">
              @foreach($book['gallery'] as $idx => $img)
              <div class="book-thumb {{ $idx === 0 ? 'active' : '' }}" data-index="{{ $idx }}" style="background-image: url('{{ $img['sizes']['thumbnail'] ?? $img['url'] }}'); background-size: cover; background-position: center;">
              </div>
              @endforeach
            </div>
            
            <button class="mpo-arrow" id="next-{{ $bid }}" aria-label="Вперёд">
              <svg viewBox="0 0 14 14" fill="none"><path d="M5 2.5L9.5 7 5 11.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
            <div class="mpo-dots" id="dots-{{ $bid }}" style="display: none;"></div>
          </div>
          @endif
        </div>

        <div class="book-info">
          <div class="book-tags">
            @foreach($book['top_labels'] as $label)
              @php
                  $tClass = 'book-tag-default';
                  if($label['style'] === 'pdf') $tClass = 'book-tag-pdf';
                  if($label['style'] === 'verified') $tClass = 'book-tag-verified';
              @endphp
              <span class="book-tag {{ $tClass }}">{{ $label['text'] }}</span>
            @endforeach
          </div>

          <h1 class="book-product-title">{!! nl2br(esc_html($book['title'])) !!}</h1>
          
          @if($author)
            <p class="book-author-line">Автор: {{ $author['name'] }} · {{ $author['spec'] }}</p>
          @endif

          <div class="book-mini-stats">
            @foreach($book['stats'] as $stat)
              <div class="book-stat-cell">
                <span class="book-stat-value">{{ $stat['value'] }}</span>
                <span class="book-stat-label">{{ $stat['label'] }}</span>
              </div>
            @endforeach
          </div>

          <div class="book-features">
            @foreach($book['features'] as $f)
              <div class="book-feature-item">
                <div class="book-check-circle">
                  <svg viewBox="0 0 11 11" fill="none"><path d="M2 5.5L4.5 8L9 3" stroke="#16A34A" stroke-width="0.916667" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <span class="book-feature-text">{{ $f['text'] }}</span>
              </div>
            @endforeach
          </div>

          <div class="book-divider"></div>

          <div class="book-price-row">
            <span class="book-price-main">₽ {{ $book['price'] }}</span>
            @if($book['price_old'])
              <span class="book-price-old">₽ {{ $book['price_old'] }}</span>
              @php $discount = round((1 - ($book['price'] / $book['price_old'])) * 100); @endphp
              <span class="book-price-badge">−{{ $discount }}%</span>
            @endif
          </div>

          <p class="book-delivery-note">{{ $book['delivery_note'] }}</p>

          @php $buy_url = get_permalink(get_the_ID()); @endphp
          <div class="book-cta-row">
            @if(is_user_logged_in())
              <button class="book-btn-buy" onclick="window.location.href='{{ $buy_url }}'">Купить сейчас</button>
            @else
              <button class="book-btn-buy" onclick="window.edietOpenBuyModal('{{ $buy_url }}')">Купить сейчас</button>
            @endif
            @include('partials.favorite-btn', ['post_id' => get_the_ID(), 'class' => 'book-btn-icon w-[52px] h-[52px] rounded-[14px]'])
            <button class="book-btn-icon" title="Поделиться">
              <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                <circle cx="15" cy="3" r="2" stroke="#333" stroke-width="1.5"/><circle cx="15" cy="17" r="2" stroke="#333" stroke-width="1.5"/><circle cx="5" cy="10" r="2" stroke="#333" stroke-width="1.5"/><path d="M7 9L13 4.5M7 11L13 15.5" stroke="#333" stroke-width="1.5"/>
              </svg>
            </button>
          </div>
        </div>
      </section>

      <!-- TABS -->
      <section class="book-tabs-card">
        <div class="book-tab-nav">
          <button class="book-tab-btn active" data-target="tab-desc">Описание</button>
          @if(count($book['contents']) > 0)
            <button class="book-tab-btn" data-target="tab-contents">Содержание</button>
          @endif
          @if(count($reviews) > 0)
            <button class="book-tab-btn" data-target="tab-reviews">Отзывы</button>
          @endif
        </div>

        <div class="book-tab-content active" id="tab-desc">
          @if(!empty($book['description']))
            @foreach($book['description'] as $block)
              @if($block['acf_fc_layout'] === 'text_block')
                @if($block['title'])<h2 class="book-desc-heading">{{ $block['title'] }}</h2>@endif
                <div class="book-desc-paragraph">{!! $block['content'] !!}</div>
              @elseif($block['acf_fc_layout'] === 'quote_block')
                <div class="book-desc-quote">«{{ $block['quote'] }}»</div>
              @elseif($block['acf_fc_layout'] === 'use_cases_block')
                <div class="book-use-cases">
                  @foreach($block['items'] as $item)
                    <div class="book-use-case-card">
                      <div class="book-use-icon">
                        @if($item['icon'] === 'patient')
                          <svg viewBox="0 0 14 14" fill="none"><path d="M2 10V4a2 2 0 012-2h6a2 2 0 012 2v6" stroke="#3B82F6" stroke-width="1.05"/><path d="M7 4V2" stroke="#3B82F6" stroke-width="1.05"/></svg>
                        @elseif($item['icon'] === 'relative')
                          <svg viewBox="0 0 14 14" fill="none"><path d="M2 10V5a2 2 0 012-2h3M9 2h3v3M12 5v5a2 2 0 01-2 2H4a2 2 0 01-2-2" stroke="#3B82F6" stroke-width="1.05"/></svg>
                        @elseif($item['icon'] === 'student')
                          <svg viewBox="0 0 14 14" fill="none"><path d="M7 2v6M4 10h6" stroke="#3B82F6" stroke-width="1.05" stroke-linecap="round"/><rect x="2" y="2" width="10" height="10" rx="2" stroke="#3B82F6" stroke-width="1.05"/></svg>
                        @else
                          <svg viewBox="0 0 14 14" fill="none"><rect x="2" y="2" width="10" height="10" rx="2" stroke="#3B82F6" stroke-width="1.05"/></svg>
                        @endif
                      </div>
                      <div class="book-use-text">
                        <strong>{{ $item['title'] }}</strong>
                        <span>{{ $item['desc'] }}</span>
                      </div>
                    </div>
                  @endforeach
                </div>
              @endif
            @endforeach
          @endif
        </div>

        @if(count($book['contents']) > 0)
        <div class="book-tab-content" id="tab-contents">
          <ul class="book-toc">
            @foreach($book['contents'] as $ci)
              <li>
                <strong>{{ $ci['title'] }}</strong>
                @if($ci['desc'])<span>{{ $ci['desc'] }}</span>@endif
              </li>
            @endforeach
          </ul>
        </div>
        @endif

        @if(count($reviews) > 0)
        <div class="book-tab-content" id="tab-reviews">
           @include('blocks.featured_reviews', ['custom_reviews' => $reviews, 'hide_controls' => true])
        </div>
        @endif
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
                <svg viewBox="0 0 64 64" fill="none"><circle cx="32" cy="22" r="12" stroke="#fff" stroke-width="3.2"/><path d="M8 56c0-13.255 10.745-24 24-24s24 10.745 24 24" stroke="#fff" stroke-width="3.2" stroke-linecap="round"/></svg>
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
            @foreach($author['tags'] as $at)
              <span class="book-author-tag">{{ is_array($at) ? ($at['text'] ?? '') : $at }}</span>
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

          <div class="book-author-heading">О специалисте</div>
          <div class="book-author-bio">{!! $author['bio'] !!}</div>

          @if($author['quote'])
          <div class="book-author-quote">«{{ $author['quote'] }}»</div>
          @endif
        </div>
      </section>
      @endif

      <!-- CROSS-SELL -->
      @if(count($cross_sells) > 0)
      <h2 class="book-section-heading">С этим покупают</h2>

      <div class="book-cards-row">
        @foreach($cross_sells as $cs)
          <a href="{{ $cs['url'] }}" class="book-product-card" style="position: relative;">
            <div class="book-card-img" style="{{ !empty($cs['image']) ? 'background-image: url('.$cs['image'].'); background-size: cover; background-position: center;' : '' }}">
              @if(empty($cs['image']))
                <div class="book-card-img-overlay"></div>
                <div class="book-card-book" @if($cs['type_label'] === 'Курс') style="background: linear-gradient(135deg, #10B981 0%, #047857 100%)" @endif>
                  <div class="book-card-book-title">{!! nl2br(esc_html($cs['title'])) !!}</div>
                </div>
              @endif
              
              @if(!empty($cs['badge']))
                <span style="position: absolute; top: 12px; left: 12px; background: #EF4444; color: #fff; padding: 4px 12px; border-radius: 8px; font-weight: 700; font-size: 11px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); z-index: 10;">{{ $cs['badge'] }}</span>
              @endif
              
              <div class="absolute top-3 right-3 z-20" onclick="event.preventDefault();">
                @include('partials.favorite-btn', ['post_id' => $cs['id'], 'class' => '!w-8 !h-8 bg-white/80 backdrop-blur-sm rounded-full shadow-sm'])
              </div>
            </div>
            
            <div class="book-card-body">
              <div class="book-card-features">
                @foreach($cs['features'] as $f)
                  <div class="book-card-feature"><div class="book-card-check"><svg viewBox="0 0 9 9" fill="none"><path d="M1.5 4.5L3.5 6.5L7.5 2.5" stroke="#16A34A" stroke-width="1.5" stroke-linecap="round"/></svg></div>{{ $f['text'] ?? $f['title'] ?? 'Полезный материал' }}</div>
                @endforeach
              </div>
              <div class="book-card-footer">
                <div style="position: relative;">
                  @if(!empty($cs['price_old']))
                    <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 2px;">
                      <span style="font-size: 13.5px; text-decoration: line-through; color: #94A3B8;">{{ $cs['price_old'] }} ₽</span>
                      @php 
                        $p = (float)str_replace([' ', ','], ['', '.'], $cs['price']);
                        $po = (float)str_replace([' ', ','], ['', '.'], $cs['price_old']);
                      @endphp
                      @if($po > 0 && $p < $po)
                        <span style="background: #FEE2E2; border-radius: 4px; padding: 2px 6px; font-weight: 700; font-size: 10px; color: #991B1B;">−{{ round((1 - ($p / $po)) * 100) }}%</span>
                      @endif
                    </div>
                  @endif
                  <div class="book-card-price">{{ $cs['price'] }} ₽</div>
                  <div class="book-card-delivery">{{ $cs['delivery'] }}</div>
                </div>
                <button class="book-btn-card-buy">{{ $cs['type_label'] === 'Курс' ? 'Участвовать' : 'Купить' }}</button>
              </div>
            </div>
          </a>
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

      // GALLERY PAGINATION LOGIC (like featured products dots)
      const bid = "{{ $bid }}";
      const thumbs = document.querySelectorAll('.book-thumb');
      const dotsContainer = document.getElementById('dots-' + bid);
      const prevBtn = document.getElementById('prev-' + bid);
      const nextBtn = document.getElementById('next-' + bid);
      
      const numItems = thumbs.length;
      if (numItems === 0) return;

      let current = 0;

      function renderDots() {
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
