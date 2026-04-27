{{-- Template Name: Single Course Landing --}}
@extends('layouts.app')

@section('content')
@php
  $bid      = uniqid('crs_');
  $post_id  = get_the_ID();
  $buy_url  = get_permalink($post_id);
  $hero_img = !empty($course['gallery'])
              ? ($course['gallery'][0]['sizes']['large'] ?? $course['gallery'][0]['url'])
              : get_the_post_thumbnail_url($post_id, 'large');
@endphp

<style>
/* ═══════════════════════════════════════════
   COURSE LANDING — lp-* namespace
════════════════════════════════════════════ */
.lp-page { background: var(--color-canvas); font-family: 'Inter', sans-serif; }

/* ── Hero ── */
.lp-hero {
  position: relative;
  min-height: 540px;
  display: flex;
  align-items: center;
  overflow: hidden;
  background: linear-gradient(135deg, #2A1A10 0%, #4a3020 55%, #6a5040 100%);
}
.lp-hero__bg {
  position: absolute; inset: 0;
  background-size: cover; background-position: center top;
  opacity: 0.25;
}
.lp-hero__overlay {
  position: absolute; inset: 0;
  background: linear-gradient(90deg, rgba(42,26,16,.93) 40%, rgba(42,26,16,.30) 100%);
}
.lp-hero__body {
  position: relative; z-index: 1;
  max-width: 680px;
  padding: 88px 0;
}
.lp-hero__eyebrow {
  display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 18px;
}
.lp-hero__title {
  font-family: 'Inter', sans-serif;
  font-weight: 800;
  letter-spacing: -1.5px;
  line-height: 1.04;
  color: #F5EFE2;
  font-size: clamp(2rem, 4.5vw, 3.6rem);
  margin-bottom: 16px;
}
.lp-hero__sub {
  font-size: 16px; line-height: 1.65; color: #c9b89a; margin-bottom: 32px;
}
.lp-hero__meta {
  display: flex; align-items: center; gap: 20px; flex-wrap: wrap;
  margin-bottom: 32px;
}
.lp-hero__meta-item {
  display: flex; align-items: center; gap: 6px;
  font-size: 13px; color: #b0a090;
}
.lp-hero__meta-item svg { width: 16px; height: 16px; flex-shrink: 0; }
.lp-hero__author {
  display: flex; align-items: center; gap: 12px; margin-bottom: 36px;
}
.lp-hero__author-avatar {
  width: 44px; height: 44px; border-radius: 50%;
  object-fit: cover; border: 2px solid rgba(245,239,226,.35);
}
.lp-hero__author-name { font-size: 14px; font-weight: 600; color: #F5EFE2; }
.lp-hero__author-spec { font-size: 12px; color: #A89F8B; }
.lp-hero__cta { display: flex; gap: 12px; flex-wrap: wrap; align-items: center; }

/* ── Stats bar ── */
.lp-stats {
  display: flex;
  border: 1px solid var(--color-border);
  border-radius: 20px;
  background: #fff;
  overflow: hidden;
  box-shadow: 0 2px 16px rgba(42,26,16,.06);
}
.lp-stat {
  flex: 1; min-width: 0;
  padding: 28px 20px;
  display: flex; flex-direction: column; align-items: center; gap: 5px;
  border-right: 1px solid var(--color-border);
  text-align: center;
}
.lp-stat:last-child { border-right: none; }
.lp-stat__value { font-weight: 800; font-size: 32px; letter-spacing: -1.2px; color: #2A1A10; line-height: 1; }
.lp-stat__label { font-size: 11.5px; color: #A89F8B; line-height: 1.45; max-width: 100px; }

/* ── Sections ── */
.lp-s  { padding: 80px 0; }
.lp-sm { padding: 56px 0; }
.lp-s--canvas { background: var(--color-canvas); }
.lp-s--white  { background: #fff; }
.lp-s--warm   { background: var(--color-cream-100); }

/* ── Tabs ── */
.lp-tab-nav {
  display: flex; border-bottom: 1px solid var(--color-border);
  margin-bottom: 36px; gap: 0;
}
.lp-tab-btn {
  padding: 16px 28px; font-weight: 600; font-size: 14px;
  color: var(--color-bark-500); background: none; border: none;
  cursor: pointer; position: relative; transition: color .15s;
}
.lp-tab-btn.active { color: var(--color-bark-900); }
.lp-tab-btn.active::after {
  content: ''; position: absolute; bottom: -1px; left: 0; right: 0;
  height: 2px; background: var(--color-bark-900); border-radius: 2px 2px 0 0;
}
.lp-tab-pane { display: none; }
.lp-tab-pane.active { display: block; }
.lp-desc { font-size: 15px; line-height: 1.8; color: var(--color-bark-700); max-width: 780px; }
.lp-desc p  { margin-bottom: 16px; }
.lp-desc h2, .lp-desc h3 { color: var(--color-bark-900); font-weight: 700; margin: 28px 0 12px; }
.lp-quote {
  border-left: 3px solid var(--color-terra-300); border-radius: 0 12px 12px 0;
  padding: 16px 24px; background: var(--color-terra-50);
  font-style: italic; font-size: 15px; line-height: 1.7;
  color: var(--color-bark-700); margin: 24px 0;
}
.lp-use-cases { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 24px; }
.lp-use-case {
  background: var(--color-cream-100); border: 1px solid var(--color-border);
  border-radius: 14px; padding: 16px; display: flex; gap: 12px;
}
.lp-use-icon { font-size: 20px; line-height: 1; flex-shrink: 0; }

/* ── Program / TOC ── */
.lp-toc { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 10px; }
.lp-toc-item {
  display: flex; gap: 16px; align-items: flex-start;
  background: #fff; border: 1px solid var(--color-border);
  border-radius: 14px; padding: 16px 20px;
  transition: box-shadow .2s, border-color .2s;
}
.lp-toc-item:hover {
  box-shadow: 0 4px 20px rgba(42,26,16,.07);
  border-color: var(--color-border-strong);
}
.lp-toc-num {
  width: 28px; height: 28px; border-radius: 50%;
  background: var(--color-terra-100); color: var(--color-terra-700);
  font-weight: 800; font-size: 12px;
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
  margin-top: 2px;
}
.lp-toc-title { font-size: 14px; font-weight: 700; color: var(--color-bark-900); margin-bottom: 3px; }
.lp-toc-desc  { font-size: 13px; color: var(--color-bark-500); line-height: 1.5; }

/* ── Pricing ── */
.lp-pricing-wrap { display: flex; gap: 32px; align-items: flex-start; }
.lp-pricing-features { flex: 1; display: flex; flex-direction: column; gap: 11px; }
.lp-pf-head { font-size: 13px; font-weight: 700; color: var(--color-bark-500); text-transform: uppercase; letter-spacing: .1em; margin-bottom: 4px; }
.lp-pf-item { display: flex; align-items: flex-start; gap: 10px; font-size: 14.5px; color: var(--color-bark-800); line-height: 1.5; }
.lp-pf-check {
  width: 22px; height: 22px; background: #dcfce7; border-radius: 50%;
  display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px;
}
.lp-pf-check svg { width: 11px; height: 11px; }
.lp-pricing-aside {
  min-width: 260px; background: #fff;
  border: 1px solid var(--color-border); border-radius: 20px;
  padding: 28px 28px 24px; display: flex; flex-direction: column; gap: 14px;
  box-shadow: 0 6px 32px rgba(42,26,16,.08); text-align: center;
}
.lp-price-old { font-size: 18px; text-decoration: line-through; color: var(--color-bark-400); }
.lp-price-main { font-size: 52px; font-weight: 800; letter-spacing: -2.5px; color: #2A1A10; line-height: 1; }
.lp-discount-badge {
  display: inline-block; background: var(--color-terra-100); color: var(--color-terra-700);
  font-size: 12px; font-weight: 700; border-radius: 50px; padding: 4px 12px;
}

/* ── Channels ── */
.lp-channels { display: flex; gap: 10px; flex-wrap: wrap; }
.lp-channel {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 9px 16px; border-radius: 50px;
  border: 1px solid var(--color-border); background: var(--color-canvas);
  font-size: 13px; font-weight: 600; color: var(--color-bark-800);
}
.lp-channel svg { width: 18px; height: 18px; flex-shrink: 0; }

/* ── Author ── */
.lp-author { display: flex; gap: 36px; align-items: flex-start; }
.lp-author__photo {
  width: 128px; height: 128px; border-radius: 28px;
  border: 1px solid var(--color-border); overflow: hidden; flex-shrink: 0;
  background: var(--color-cream-200); display: flex; align-items: center; justify-content: center;
}
.lp-author__photo img { width: 100%; height: 100%; object-fit: cover; }
.lp-author__photo svg { width: 52px; height: 52px; opacity: .25; }
.lp-author__info { flex: 1; }
.lp-author__name { font-size: 22px; font-weight: 800; letter-spacing: -.5px; color: var(--color-bark-900); margin-bottom: 2px; }
.lp-author__spec { font-size: 13px; color: var(--color-bark-500); margin-bottom: 16px; }
.lp-author__bio { font-size: 14px; line-height: 1.75; color: var(--color-bark-700); margin-bottom: 16px; }
.lp-author__quote {
  border: 1px solid var(--color-border); border-radius: 12px;
  padding: 14px 20px; font-style: italic; font-size: 14px;
  color: var(--color-bark-600); line-height: 1.65; margin-bottom: 16px;
}

/* ── Tier cards ── */
.lp-tiers {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  align-items: start;
}
.lp-tier {
  background: #fff;
  border: 1.5px solid var(--color-border);
  border-radius: 24px;
  padding: 32px 28px 28px;
  display: flex; flex-direction: column; gap: 0;
  position: relative;
  transition: box-shadow .2s, transform .2s;
}
.lp-tier:hover { box-shadow: 0 8px 40px rgba(42,26,16,.10); transform: translateY(-2px); }
.lp-tier--popular {
  border-color: var(--color-bark-900);
  box-shadow: 0 12px 48px rgba(42,26,16,.14);
  transform: scale(1.03);
}
.lp-tier--popular:hover { transform: scale(1.03) translateY(-2px); }
.lp-tier__popular-badge {
  position: absolute; top: -13px; left: 50%; transform: translateX(-50%);
  background: var(--color-bark-900); color: var(--color-cream-100);
  font-size: 11px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
  padding: 5px 16px; border-radius: 50px; white-space: nowrap;
}
.lp-tier__name {
  font-size: 15px; font-weight: 800; color: var(--color-bark-900);
  letter-spacing: -.2px; margin-bottom: 4px;
}
.lp-tier__sub {
  font-size: 12.5px; color: var(--color-bark-400); margin-bottom: 20px;
  line-height: 1.4;
}
.lp-tier__price-old {
  font-size: 13px; text-decoration: line-through; color: var(--color-bark-400);
  margin-bottom: 2px;
}
.lp-tier__price {
  font-size: 40px; font-weight: 800; letter-spacing: -2px; color: var(--color-bark-900);
  line-height: 1; margin-bottom: 6px;
}
.lp-tier__price span { font-size: 20px; font-weight: 600; letter-spacing: 0; }
.lp-tier__disc {
  display: inline-block;
  background: var(--color-terra-100); color: var(--color-terra-700);
  font-size: 11px; font-weight: 700; border-radius: 50px;
  padding: 3px 10px; margin-bottom: 20px;
}
.lp-tier__divider {
  height: 1px; background: var(--color-border); margin-bottom: 20px;
}
.lp-tier__features {
  display: flex; flex-direction: column; gap: 9px; flex: 1; margin-bottom: 24px;
}
.lp-tier__feature {
  display: flex; align-items: flex-start; gap: 9px;
  font-size: 13px; line-height: 1.45; color: var(--color-bark-700);
}
.lp-tier__feature--off { opacity: .4; }
.lp-tier__fcheck {
  width: 18px; height: 18px; border-radius: 50%; flex-shrink: 0; margin-top: 1px;
  display: flex; align-items: center; justify-content: center;
}
.lp-tier__fcheck--on  { background: #dcfce7; }
.lp-tier__fcheck--off { background: var(--color-cream-200); }
.lp-tier__fcheck svg { width: 9px; height: 9px; }
.lp-tier__btn {
  display: block; width: 100%; text-align: center;
  padding: 14px 20px; border-radius: 50px; font-size: 14px; font-weight: 700;
  cursor: pointer; border: none; transition: all .2s; text-decoration: none;
}
.lp-tier__btn--dark {
  background: var(--color-bark-900); color: var(--color-cream-100);
}
.lp-tier__btn--dark:hover { background: var(--color-bark-800); transform: scale(.98); }
.lp-tier__btn--outline {
  background: transparent; color: var(--color-bark-900);
  border: 1.5px solid var(--color-bark-900);
}
.lp-tier__btn--outline:hover { background: var(--color-bark-900); color: var(--color-cream-100); }

/* ── Seats counter ── */
.lp-seats {
  display: flex; align-items: center; gap: 14px;
  background: var(--color-cream-100); border: 1px solid var(--color-border);
  border-radius: 16px; padding: 16px 22px; margin-top: 28px;
}
.lp-seats__bar-wrap {
  flex: 1; height: 6px; background: var(--color-cream-300); border-radius: 3px; overflow: hidden;
}
.lp-seats__bar { height: 100%; border-radius: 3px; transition: width .4s; }
.lp-seats__bar--green  { background: #16A34A; }
.lp-seats__bar--yellow { background: #d97706; }
.lp-seats__bar--red    { background: #ef4444; }
.lp-seats__count {
  font-size: 22px; font-weight: 800; letter-spacing: -1px; color: var(--color-bark-900);
  flex-shrink: 0;
}
.lp-seats__label { font-size: 12px; color: var(--color-bark-500); flex-shrink: 0; }

/* ── Responsive ── */
@media (max-width: 900px) {
  .lp-pricing-wrap { flex-direction: column; }
  .lp-pricing-aside { min-width: unset; width: 100%; }
  .lp-tiers { grid-template-columns: 1fr; gap: 16px; }
  .lp-tier--popular { transform: none; }
  .lp-tier--popular:hover { transform: translateY(-2px); }
}
@media (max-width: 768px) {
  .lp-hero { min-height: 420px; }
  .lp-hero__body { padding: 64px 0; }
  .lp-hero__title { font-size: clamp(1.7rem, 7vw, 2.8rem); }
  .lp-stat { padding: 20px 12px; }
  .lp-stat__value { font-size: 24px; }
  .lp-use-cases { grid-template-columns: 1fr; }
  .lp-s { padding: 56px 0; }
  .lp-author { flex-direction: column; gap: 20px; }
  .lp-author__photo { width: 96px; height: 96px; border-radius: 20px; }
  .lp-tab-btn { padding: 12px 14px; font-size: 13px; }
}
@media (max-width: 480px) {
  .lp-stats { flex-wrap: wrap; }
  .lp-stat { flex: 0 0 50%; border-bottom: 1px solid var(--color-border); }
  .lp-stat:nth-child(even) { border-right: none; }
  .lp-channels { gap: 8px; }
}
</style>

<div class="lp-page">

  {{-- ════════════════ 1. HERO ════════════════ --}}
  <section class="lp-hero">
    @if($hero_img)
      <div class="lp-hero__bg" style="background-image:url('{{ $hero_img }}')"></div>
    @endif
    <div class="lp-hero__overlay"></div>

    <div class="container-wide">
      <div class="lp-hero__body">

        <div class="lp-hero__eyebrow">
          @foreach($course['top_labels'] as $lbl)
            @php
              $lcls = $lbl['style'] === 'verified' ? 'badge-green' : 'badge-terra';
            @endphp
            <span class="{{ $lcls }}">{{ $lbl['text'] }}</span>
          @endforeach
        </div>

        <h1 class="lp-hero__title">{!! nl2br(esc_html($course['title'])) !!}</h1>

        @if($course['subtitle'])
          <p class="lp-hero__sub">{{ $course['subtitle'] }}</p>
        @endif

        @if(!empty($course['stats']))
          <div class="lp-hero__meta">
            @foreach(array_slice($course['stats'], 0, 3) as $st)
              <div class="lp-hero__meta-item">
                <svg viewBox="0 0 16 16" fill="none"><circle cx="8" cy="8" r="6" stroke="currentColor" stroke-width="1.4"/><path d="M8 5v3l2 1.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
                <strong style="color:#e8d8c0;">{{ $st['number'] }}</strong>
                <span>{{ $st['label'] }}</span>
              </div>
            @endforeach
          </div>
        @endif

        @if($author)
          <div class="lp-hero__author">
            @if($author['avatar_url'])
              <img class="lp-hero__author-avatar" src="{{ $author['avatar_url'] }}" alt="{{ $author['name'] }}">
            @endif
            <div>
              <div class="lp-hero__author-name">{{ $author['name'] }}</div>
              <div class="lp-hero__author-spec">{{ $author['spec'] }}</div>
            </div>
          </div>
        @endif

        <div class="lp-hero__cta">
          @if(is_user_logged_in())
            <button class="btn-primary btn-lg" onclick="window.location.href='{{ $buy_url }}'">Участвовать в курсе</button>
          @else
            <button class="btn-primary btn-lg" onclick="window.edietOpenBuyModal('{{ $buy_url }}')">Участвовать в курсе</button>
          @endif
        </div>

      </div>
    </div>
  </section>

  {{-- ════════════════ 2. STATS ════════════════ --}}
  @if(!empty($course['stats']))
  <section class="lp-sm lp-s--white">
    <div class="container-editorial">
      <div class="lp-stats">
        @foreach($course['stats'] as $st)
          <div class="lp-stat">
            <span class="lp-stat__value">{{ $st['number'] }}</span>
            <span class="lp-stat__label">{{ $st['label'] }}</span>
          </div>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  {{-- ════════════════ 3. DESCRIPTION + PROGRAM + REVIEWS ════════════════ --}}
  <section class="lp-s lp-s--canvas">
    <div class="container-editorial">

      <div class="lp-tab-nav">
        <button class="lp-tab-btn active" data-group="{{ $bid }}" data-pane="lp-desc-{{ $bid }}">О курсе</button>
        @if(!empty($course['contents']))
        <button class="lp-tab-btn" data-group="{{ $bid }}" data-pane="lp-program-{{ $bid }}">Программа</button>
        @endif
        <button class="lp-tab-btn" data-group="{{ $bid }}" data-pane="lp-reviews-{{ $bid }}">
          Отзывы
          <span style="background:rgba(42,26,16,.07);padding:2px 8px;border-radius:20px;font-size:11px;margin-left:6px;">{{ count($reviews) }}</span>
        </button>
      </div>

      {{-- Description --}}
      <div id="lp-desc-{{ $bid }}" class="lp-tab-pane active">
        <div class="lp-desc">
          @if(empty($course['description']))
            <p>Этот курс поможет вам выстроить правильное питание, разобраться в принципах здорового образа жизни и получить устойчивые результаты.</p>
          @else
            @foreach($course['description'] as $block)
              @if($block['acf_fc_layout'] === 'text')
                {!! $block['content'] !!}
              @elseif($block['acf_fc_layout'] === 'quote')
                <div class="lp-quote">{{ $block['text'] }}</div>
              @elseif($block['acf_fc_layout'] === 'use_cases')
                <div class="lp-use-cases">
                  @foreach($block['items'] as $uc)
                    <div class="lp-use-case">
                      <div class="lp-use-icon">✨</div>
                      <div>
                        <div style="font-weight:700;font-size:13px;color:var(--color-bark-900);margin-bottom:4px;">{{ $uc['title'] }}</div>
                        <div style="font-size:13px;color:var(--color-bark-600);line-height:1.5;">{{ $uc['desc'] }}</div>
                      </div>
                    </div>
                  @endforeach
                </div>
              @endif
            @endforeach
          @endif
        </div>
      </div>

      {{-- Program --}}
      @if(!empty($course['contents']))
      <div id="lp-program-{{ $bid }}" class="lp-tab-pane">
        <ul class="lp-toc">
          @foreach($course['contents'] as $idx => $ci)
            <li class="lp-toc-item">
              <div class="lp-toc-num">{{ $idx + 1 }}</div>
              <div>
                <div class="lp-toc-title">{{ $ci['title'] }}</div>
                @if(!empty($ci['desc']))
                  <div class="lp-toc-desc">{{ $ci['desc'] }}</div>
                @endif
              </div>
            </li>
          @endforeach
        </ul>
      </div>
      @endif

      {{-- Reviews --}}
      <div id="lp-reviews-{{ $bid }}" class="lp-tab-pane">
        @if(count($reviews) > 0)
          @include('blocks.featured_reviews', ['custom_reviews' => $reviews, 'title' => '', 'subtitle' => '', 'rv_theme' => 'light'])
        @else
          <p style="color:var(--color-bark-400);font-size:14px;padding:20px 0;">Отзывов пока нет. Участвуйте и станьте первым!</p>
        @endif
      </div>

    </div>
  </section>

  {{-- ════════════════ 4. PRICING TIERS ════════════════ --}}
  <section class="lp-s lp-s--white">
    <div class="container-editorial">
      <div class="text-overline mb-3">Стоимость</div>
      <h2 class="heading-display mb-10">Выберите тариф</h2>

      @php
        $tiers    = $course['pricing_tiers'];
        $seats    = $course['seats'];
        $has_tiers = !empty($tiers);

        /* Цвет полосы мест */
        $bar_color = 'lp-seats__bar--green';
        if ($seats['enabled'] && $seats['base'] > 0) {
          $pct = $seats['pct'];
          if ($pct <= 20) $bar_color = 'lp-seats__bar--red';
          elseif ($pct <= 50) $bar_color = 'lp-seats__bar--yellow';
        }
      @endphp

      @if($has_tiers)

        {{-- ── 3-column tier grid ── --}}
        <div class="lp-tiers">
          @foreach($tiers as $tidx => $tier)
            @php
              $is_popular = !empty($tier['tier_is_popular']);
              $t_price    = $tier['tier_price'] ?? '';
              $t_old      = $tier['tier_price_old'] ?? '';
              $t_disc     = 0;
              if ($t_old && $t_price) {
                $p_num = (float) preg_replace('/[^\d.]/', '', $t_price);
                $o_num = (float) preg_replace('/[^\d.]/', '', $t_old);
                if ($o_num > 0 && $p_num < $o_num) {
                  $t_disc = round((1 - $p_num / $o_num) * 100);
                }
              }
              $btn_class = $is_popular ? 'lp-tier__btn--dark' : 'lp-tier__btn--outline';
              $cta_text  = !empty($tier['tier_cta_text']) ? $tier['tier_cta_text'] : 'Купить';
            @endphp

            <div class="lp-tier {{ $is_popular ? 'lp-tier--popular' : '' }}">

              @if($is_popular)
                <div class="lp-tier__popular-badge">Популярный</div>
              @endif

              <div class="lp-tier__name">{{ $tier['tier_name'] ?? 'Тариф ' . ($tidx + 1) }}</div>
              @if(!empty($tier['tier_subtitle']))
                <div class="lp-tier__sub">{{ $tier['tier_subtitle'] }}</div>
              @else
                <div style="margin-bottom:20px;"></div>
              @endif

              @if($t_old)
                <div class="lp-tier__price-old">{{ $t_old }} ₽</div>
              @endif
              <div class="lp-tier__price">
                {{ $t_price ? $t_price : '—' }}
                @if($t_price)<span> ₽</span>@endif
              </div>
              @if($t_disc > 0)
                <div class="lp-tier__disc">−{{ $t_disc }}%</div>
              @else
                <div style="margin-bottom:20px;"></div>
              @endif

              <div class="lp-tier__divider"></div>

              @if(!empty($tier['tier_features']))
                <div class="lp-tier__features">
                  @foreach($tier['tier_features'] as $feat)
                    @php $included = isset($feat['included']) ? (bool)$feat['included'] : true; @endphp
                    <div class="lp-tier__feature {{ !$included ? 'lp-tier__feature--off' : '' }}">
                      <div class="lp-tier__fcheck {{ $included ? 'lp-tier__fcheck--on' : 'lp-tier__fcheck--off' }}">
                        @if($included)
                          <svg viewBox="0 0 9 9" fill="none"><path d="M1.5 4.5L3.5 6.5L7.5 2.5" stroke="#16A34A" stroke-width="1.5" stroke-linecap="round"/></svg>
                        @else
                          <svg viewBox="0 0 9 9" fill="none"><path d="M2.5 2.5L6.5 6.5M6.5 2.5L2.5 6.5" stroke="#A89F8B" stroke-width="1.3" stroke-linecap="round"/></svg>
                        @endif
                      </div>
                      <span>{{ $feat['text'] ?? '' }}</span>
                    </div>
                  @endforeach
                </div>
              @endif

              @if(is_user_logged_in())
                <button class="lp-tier__btn {{ $btn_class }}"
                        onclick="window.location.href='{{ $buy_url }}?tier={{ $tidx }}'">
                  {{ $cta_text }}
                </button>
              @else
                <button class="lp-tier__btn {{ $btn_class }}"
                        onclick="window.edietOpenBuyModal('{{ $buy_url }}?tier={{ $tidx }}')">
                  {{ $cta_text }}
                </button>
              @endif
            </div>
          @endforeach
        </div>

      @else

        {{-- ── Fallback: single price card ── --}}
        <div class="lp-pricing-wrap">
          <div class="lp-pricing-features">
            <div class="lp-pf-head">Что вы получите</div>
            @foreach($course['features'] as $f)
              <div class="lp-pf-item">
                <div class="lp-pf-check">
                  <svg viewBox="0 0 11 11" fill="none"><path d="M2.5 5.5L4.5 7.5L8.5 3.5" stroke="#16A34A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <span>{{ $f['text'] ?? $f['title'] ?? '' }}</span>
              </div>
            @endforeach
          </div>
          <div class="lp-pricing-aside">
            @if($course['price_old'])
              <div class="lp-price-old">{{ $course['price_old'] }} ₽</div>
            @endif
            <div class="lp-price-main">
              {{ $course['price'] ? $course['price'] . ' ₽' : 'Бесплатно' }}
            </div>
            @if($course['price_old'] && $course['price'])
              @php $disc = round(100 - ((float)$course['price'] / (float)$course['price_old']) * 100); @endphp
              @if($disc > 0)<div class="lp-discount-badge">Скидка −{{ $disc }}%</div>@endif
            @endif
            @if(is_user_logged_in())
              <button class="btn-primary btn-lg" style="width:100%;" onclick="window.location.href='{{ $buy_url }}'">Купить курс</button>
            @else
              <button class="btn-primary btn-lg" style="width:100%;" onclick="window.edietOpenBuyModal('{{ $buy_url }}')">Купить курс</button>
            @endif
            @include('partials.favorite-btn', ['post_id' => $post_id, 'class' => '!w-11 !h-11 !rounded-2xl'])
            @if($course['delivery_note'])
              <p class="text-caption text-center" style="margin-top:4px;">{{ $course['delivery_note'] }}</p>
            @endif
          </div>
        </div>

      @endif

      {{-- ── Seats counter ── --}}
      @if($seats['enabled'] && $seats['base'] > 0)
        <div class="lp-seats">
          <div>
            <div class="lp-seats__count">{{ max(0, $seats['current']) }}</div>
            <div class="lp-seats__label">мест<br>свободно</div>
          </div>
          <div class="lp-seats__bar-wrap">
            <div class="lp-seats__bar {{ $bar_color }}"
                 style="width:{{ max(4, $seats['pct']) }}%"></div>
          </div>
          <div style="font-size:12px;color:var(--color-bark-400);flex-shrink:0;">
            из {{ $seats['base'] }}
          </div>
        </div>
      @endif

      {{-- ── Channels / format ── --}}
      <div style="margin-top:48px;">
        <div class="text-overline mb-4">Формат обучения</div>
        <div class="lp-channels">
          <div class="lp-channel">
            <svg viewBox="0 0 24 24" fill="none"><rect x="2" y="7" width="14" height="12" rx="2" fill="#2D8CFF"/><path d="M16 10.8l5-3.2v9l-5-3.2v-2.6z" fill="#2D8CFF"/></svg>
            Видеоуроки
          </div>
          <div class="lp-channel">
            <svg viewBox="0 0 24 24" fill="none"><path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2z" fill="#229ED9"/><path d="M17.79 7.42L15.66 17.4c-.16.72-.59.9-1.2.56l-3.3-2.43-1.59 1.53c-.18.18-.33.33-.67.33l.24-3.36 6.1-5.51c.27-.24-.06-.37-.41-.14L7.28 12.9 4.02 11.9c-.7-.22-.71-.7.15-1.04l13.04-5.02c.58-.21 1.09.14.58 1.58z" fill="white"/></svg>
            Telegram-чат
          </div>
          <div class="lp-channel">
            <svg viewBox="0 0 24 24" fill="none"><path d="M12 2C6.477 2 2 6.477 2 12c0 1.89.524 3.655 1.435 5.16L2 22l4.98-1.424A9.953 9.953 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2z" fill="#25D366"/><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.1-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" fill="white"/></svg>
            WhatsApp
          </div>
          <div class="lp-channel">
            <svg viewBox="0 0 24 24" fill="none"><path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2z" fill="#EBE3D2"/><path d="M8 12h8M12 8v8" stroke="#6a5040" stroke-width="1.5" stroke-linecap="round"/></svg>
            Домашние задания
          </div>
        </div>
      </div>

    </div>
  </section>

  {{-- ════════════════ 5. AUTHOR ════════════════ --}}
  @if($author)
  <section class="lp-s lp-s--warm">
    <div class="container-editorial">
      <div class="text-overline mb-6">Автор курса</div>

      <div class="lp-author">
        <div class="lp-author__photo">
          @if($author['avatar_url'])
            <img src="{{ $author['avatar_url'] }}" alt="{{ $author['name'] }}">
          @else
            <svg viewBox="0 0 24 24" fill="none"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z" stroke="currentColor" stroke-width="1.5"/></svg>
          @endif
        </div>

        <div class="lp-author__info">
          <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px;">
            <div class="lp-author__name">{{ $author['name'] }}</div>
            @if($author['verified'])
              <span class="badge-green">✓ Верифицирован</span>
            @endif
          </div>
          <div class="lp-author__spec">{{ $author['spec'] }}</div>

          @if(!empty($author['stats']))
            <div class="lp-stats" style="max-width:460px;margin-bottom:20px;">
              @foreach($author['stats'] as $ast)
                <div class="lp-stat">
                  <span class="lp-stat__value" style="font-size:22px;">{{ $ast['value'] ?? '' }}</span>
                  <span class="lp-stat__label">{{ $ast['label'] ?? '' }}</span>
                </div>
              @endforeach
            </div>
          @endif

          @if($author['bio'])
            <div class="lp-author__bio">{!! $author['bio'] !!}</div>
          @endif

          @if($author['quote'])
            <div class="lp-author__quote">«{{ $author['quote'] }}»</div>
          @endif

          @if(!empty($author['tags']))
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
              @foreach(array_slice($author['tags'], 0, 5) as $tag)
                <span class="badge-cream">{{ $tag['text'] ?? $tag['name'] ?? $tag }}</span>
              @endforeach
            </div>
          @endif
        </div>
      </div>
    </div>
  </section>
  @endif

  {{-- ════════════════ 6. CROSS-SELLS ════════════════ --}}
  @if(count($cross_sells) > 0)
  <section class="lp-s lp-s--canvas">
    <div class="container-editorial">
      <h2 class="heading-display mb-8">Вас может заинтересовать</h2>
      <div class="dtc-grid">
        @foreach($cross_sells as $cs)
          @include('partials.dtc-card', ['item' => $cs])
        @endforeach
      </div>
    </div>
  </section>
  @endif

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll('.lp-tab-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var pane = btn.dataset.pane;
      btn.closest('.lp-tab-nav').querySelectorAll('.lp-tab-btn').forEach(function (b) {
        b.classList.remove('active');
      });
      btn.classList.add('active');
      var section = btn.closest('section') || document;
      section.querySelectorAll('.lp-tab-pane').forEach(function (p) {
        p.classList.remove('active');
      });
      var target = document.getElementById(pane);
      if (target) target.classList.add('active');
    });
  });
});
</script>

@endsection
