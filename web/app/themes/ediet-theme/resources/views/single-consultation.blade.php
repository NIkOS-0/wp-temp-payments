{{-- Template Name: Single Consultation Landing --}}
@extends('layouts.app')

@section('content')
@php
  $bid      = uniqid('cns_');
  $post_id  = get_the_ID();
  $buy_url  = get_permalink($post_id);
  $hero_img = !empty($service['gallery'])
              ? ($service['gallery'][0]['sizes']['large'] ?? $service['gallery'][0]['url'])
              : get_the_post_thumbnail_url($post_id, 'large');

  /* Title splitting — last word becomes italic accent, like the homepage hero. */
  $title_raw   = trim($service['title']);
  $title_words = preg_split('/\s+/', $title_raw);
  $title_last  = array_pop($title_words);
  $title_lead  = implode(' ', $title_words);

  $c1 = get_field('hero_scrim_color_1', $post_id) ?: 'rgba(245,239,226,.96)';
  $c2 = get_field('hero_scrim_color_2', $post_id) ?: 'rgba(245,239,226,.78)';
  $c3 = get_field('hero_scrim_color_3', $post_id) ?: 'rgba(245,239,226,.42)';
  $c4 = get_field('hero_scrim_color_4', $post_id) ?: 'rgba(245,239,226,.18)';
@endphp

<style>
/* ═══════════════════════════════════════════
   CONSULTATION LANDING — lp-* namespace
   Aesthetic: aligned with homepage hero
════════════════════════════════════════════ */
.bg-main { background: #F5EFE2; font-family: 'Inter', sans-serif; color: var(--color-bark-900); min-width: 1160px; }
.lp-page {
  max-width: 1160px;
  margin: 0 auto;
  padding: 24px 20px 108px;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

/* ── Hero: full-bleed background image with cream mesh + scrim ── */
.lp-hero {
  position: relative;
  overflow: hidden;
  isolation: isolate;
  padding: 100px 40px 80px;
  min-height: 560px;
  background: var(--color-canvas);
  border-radius: 24px;
  border: 1px solid rgba(255,255,255,0.88);
  box-shadow: 0px 5px 20px rgba(42,26,16,0.08);
}
.lp-hero__bg {
  position: absolute; inset: 0; z-index: 0;
  background-size: cover; background-position: center;
}
.lp-hero__scrim {
  position: absolute; inset: 0; z-index: 1; pointer-events: none;
}
.lp-hero__mesh { position: absolute; inset: 0; z-index: 1; pointer-events: none; overflow: hidden; }
.lp-hero__blob {
  position: absolute; border-radius: 50%;
  filter: blur(110px); mix-blend-mode: multiply; opacity: .55;
}
.lp-hero__blob.a { width: 720px; height: 720px; top: -220px; right: -140px;
  background: conic-gradient(from 0deg, rgba(120,210,210,.45), rgba(130,200,140,.4), rgba(120,210,210,.45));
  animation: lp-drift-a 22s ease-in-out infinite alternate; }
.lp-hero__blob.b { width: 600px; height: 600px; bottom: -180px; left: -120px;
  background: conic-gradient(from 180deg, rgba(244,180,145,.5), rgba(239,148,91,.35), rgba(244,180,145,.5));
  animation: lp-drift-b 26s ease-in-out infinite alternate; }
.lp-hero__grain {
  position: absolute; inset: 0; z-index: 2; pointer-events: none;
  opacity: .06; mix-blend-mode: multiply;
  background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='200' height='200'><filter id='n'><feTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='2' stitchTiles='stitch'/><feColorMatrix values='0 0 0 0 0  0 0 0 0 0  0 0 0 0 0  0 0 0 0.6 0'/></filter><rect width='100%' height='100%' filter='url(%23n)'/></svg>");
}
@keyframes lp-drift-a { 0%{transform:translate(0,0) scale(1);} 100%{transform:translate(-60px,80px) scale(1.1);} }
@keyframes lp-drift-b { 0%{transform:translate(0,0) scale(1);} 100%{transform:translate(80px,-50px) scale(1.12);} }

.lp-hero__inner {
  position: relative; z-index: 3;
}
.lp-hero__body { max-width: 680px; }

.lp-hero__eyebrow {
  display: flex; align-items: center; gap: 14px;
  margin-bottom: 28px;
}
.lp-hero__eyebrow .line-decor { height: 1px; width: 48px; background: var(--color-border-strong); }
.lp-hero__eyebrow .leaf { width: 14px; height: 14px; color: var(--color-terra-500); }
.lp-hero__eyebrow .text-decor {
  font-size: 11px; letter-spacing: 0.26em; text-transform: uppercase;
  color: var(--color-bark-700); font-weight: 500;
}

.lp-hero__labels {
  display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 24px;
}
.lp-hero__label {
  display: inline-flex; align-items: center;
  padding: 5px 14px; border-radius: 9999px;
  font-size: 12px; font-weight: 600; line-height: 1.4;
  letter-spacing: .01em;
}

.lp-hero__title {
  font-family: var(--font-serif), 'Playfair Display', serif;
  font-weight: 500;
  font-size: clamp(2.4rem, 5vw, 4.4rem);
  line-height: 1.0;
  letter-spacing: -0.025em;
  color: var(--color-bark-900);
  margin-bottom: 20px;
  text-wrap: balance;
}
.lp-hero__title em {
  font-style: italic; font-weight: 400;
  color: var(--color-bark-900);
  display: inline-block;
}

.lp-hero__sub {
  font-size: 17px; line-height: 1.7;
  color: var(--color-bark-700); opacity: .85;
  margin-bottom: 28px; max-width: 540px;
}
.lp-hero__sub em {
  font-family: var(--font-serif), 'Playfair Display', serif;
  font-style: italic; font-weight: 500;
  color: var(--color-terra-500);
}

.lp-hero__author {
  display: flex; align-items: center; gap: 12px; margin-bottom: 32px;
  padding: 12px 16px 12px 12px;
  background: rgba(255,255,255,.7);
  border: 1px solid var(--color-border);
  border-radius: 9999px;
  width: fit-content;
  backdrop-filter: blur(6px);
}
.lp-hero__author-avatar {
  width: 44px; height: 44px; border-radius: 50%;
  object-fit: cover;
  border: 1px solid var(--color-border-strong);
}
.lp-hero__author-name { font-size: 14px; font-weight: 700; color: var(--color-bark-900); }
.lp-hero__author-spec { font-size: 12px; color: var(--color-bark-500); }
.lp-hero__cta { display: flex; gap: 12px; flex-wrap: wrap; align-items: center; }

/* ── Stats bar ── */
.lp-stats {
  display: flex;
  border: 1px solid var(--color-border);
  border-radius: 24px;
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
.lp-stat__value {
  font-family: var(--font-serif), serif;
  font-weight: 600; font-size: 36px; letter-spacing: -1.2px;
  color: var(--color-bark-900); line-height: 1;
}
.lp-stat__value em { font-style: italic; color: var(--color-terra-500); font-weight: 500; }
.lp-stat__label { font-size: 11.5px; color: var(--color-bark-500); line-height: 1.45; max-width: 110px; }

/* ── Sections ── */
.lp-s  { padding: 60px 40px; border-radius: 24px; border: 1px solid rgba(255,255,255,0.88); box-shadow: 0px 5px 20px rgba(42,26,16,0.08); }
.lp-sm { padding: 40px; border-radius: 24px; border: 1px solid rgba(255,255,255,0.88); box-shadow: 0px 5px 20px rgba(42,26,16,0.08); }
.lp-s--canvas { background: var(--color-canvas); }
.lp-s--white  { background: #fff; }
.lp-s--warm   { background: var(--color-cream-100); }
.lp-s--author { 
  background: radial-gradient(ellipse at top left, #3A2418 0%, #2A1A10 55%, #1e1209 100%); 
  color: var(--color-cream-100);
  border: 1px solid rgba(255,255,255,0.1);
  box-shadow: 0px 5px 20px rgba(0,0,0,0.15);
}

.lp-section-eyebrow {
  display: flex; align-items: center; gap: 12px; margin-bottom: 14px;
}
.lp-section-eyebrow .line-decor { height: 1px; width: 36px; background: var(--color-border-strong); }
.lp-section-eyebrow span {
  font-size: 11px; letter-spacing: .22em; text-transform: uppercase;
  color: var(--color-terra-600); font-weight: 600;
}
.lp-section-title {
  font-family: var(--font-serif), 'Playfair Display', serif;
  font-weight: 500;
  font-size: clamp(1.8rem, 3vw, 2.6rem);
  line-height: 1.1; letter-spacing: -.02em;
  color: var(--color-bark-900);
  margin-bottom: 8px;
  text-wrap: balance;
}
.lp-section-title em {
  font-style: italic; font-weight: 500;
  color: var(--color-bark-700);
}
.lp-section-lead {
  font-size: 15px; line-height: 1.7;
  color: var(--color-bark-600); max-width: 620px;
}

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
  height: 2px; background: var(--color-terra-500); border-radius: 2px 2px 0 0;
}
.lp-tab-pane { display: none; }
.lp-tab-pane.active { display: block; }
.lp-desc { font-size: 15.5px; line-height: 1.85; color: var(--color-bark-700); }
.lp-desc p  { margin-bottom: 16px; }
.lp-desc h2, .lp-desc h3 {
  font-family: var(--font-serif), serif;
  color: var(--color-bark-900); font-weight: 600;
  margin: 32px 0 14px;
  letter-spacing: -.01em;
}
.lp-quote {
  border-left: 3px solid var(--color-terra-400); border-radius: 0 12px 12px 0;
  padding: 18px 26px; background: var(--color-terra-50);
  font-family: var(--font-serif), serif;
  font-style: italic; font-size: 17px; line-height: 1.6;
  color: var(--color-bark-700); margin: 24px 0;
}
.lp-use-cases { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-top: 24px; }
.lp-use-case {
  background: var(--color-cream-100); border: 1px solid var(--color-border);
  border-radius: 16px; padding: 18px; display: flex; gap: 12px;
  transition: border-color .2s, transform .2s;
}
.lp-use-case:hover { border-color: var(--color-border-strong); transform: translateY(-2px); }
.lp-use-icon { font-size: 22px; line-height: 1; flex-shrink: 0; }

/* ── Tabs (book-style) ── */
.cns-tabs-card {
  background: #fff;
  border: 1px solid rgba(255,255,255,0.88);
  box-shadow: 0px 5px 20px rgba(42,26,16,0.08);
  border-radius: 24px;
  padding: 12px 40px 0;
}
.cns-tab-nav { display: flex; border-bottom: 1px solid #E5E7EB; }
.cns-tab-btn {
  padding: 20px 28px; font-weight: 600; font-size: 14px; letter-spacing: -0.3px; color: var(--color-bark-400);
  background: none; border: none; cursor: pointer; position: relative; transition: color 0.15s; white-space: nowrap;
}
.cns-tab-btn.active { color: var(--color-bark-900); }
.cns-tab-btn.active::after { content: ''; position: absolute; bottom: -1px; left: 0; right: 0; height: 2px; background: var(--color-bark-900); border-radius: 2px 2px 0 0; }
.cns-tab-content { padding: 36px 0 40px; display: none; }
.cns-tab-content.active { display: block; }

/* ── Pricing tiers ── */
.lp-tiers {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 22px;
  align-items: stretch;
}
.lp-tier {
  background: #fff;
  border: 1.5px solid var(--color-border);
  border-radius: 28px;
  padding: 36px 30px 30px;
  display: flex; flex-direction: column;
  position: relative;
  transition: box-shadow .25s, transform .25s, border-color .25s;
}
.lp-tier:hover {
  box-shadow: 0 14px 44px rgba(42,26,16,.11);
  transform: translateY(-3px);
  border-color: var(--color-border-strong);
}
.lp-tier--featured {
  border-color: var(--color-bark-900);
  box-shadow: 0 16px 56px rgba(42,26,16,.16);
  transform: scale(1.03);
}
.lp-tier--featured:hover { transform: scale(1.03) translateY(-3px); }
.lp-tier__custom-badge {
  position: absolute; top: -14px; left: 50%; transform: translateX(-50%);
  background: var(--color-bark-900); color: var(--color-cream-100);
  font-size: 11px; font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
  padding: 6px 18px; border-radius: 50px; white-space: nowrap;
}
.lp-tier__name {
  font-size: 16px; font-weight: 800; color: var(--color-bark-900);
  letter-spacing: -.2px; margin-bottom: 6px;
}
.lp-tier__sub {
  font-size: 13px; color: var(--color-bark-500); margin-bottom: 22px;
  line-height: 1.45;
}
.lp-tier__price-row {
  display: flex; align-items: center; gap: 8px;
  margin-bottom: 4px;
}
.lp-tier__price-old {
  font-size: 14px; text-decoration: line-through; color: var(--color-bark-400);
}
.lp-tier__disc {
  display: inline-flex; align-items: center;
  background: var(--color-terra-100); color: var(--color-terra-700);
  font-size: 10.5px; font-weight: 700; border-radius: 50px;
  padding: 2px 8px; line-height: 1.4;
  white-space: nowrap;
}
.lp-tier__price {
  font-family: var(--font-serif), serif;
  font-size: 44px; font-weight: 600; letter-spacing: -1.5px;
  color: var(--color-bark-900);
  line-height: 1; margin-bottom: 22px;
}
.lp-tier__price span { font-size: 22px; font-weight: 500; letter-spacing: 0; color: var(--color-bark-500); }
.lp-tier__divider {
  height: 1px; background: var(--color-border); margin-bottom: 22px;
}
.lp-tier__features {
  display: flex; flex-direction: column; gap: 10px;
  flex: 1; margin-bottom: 26px;
}
.lp-tier__feature {
  display: flex; align-items: flex-start; gap: 10px;
  font-size: 13.5px; line-height: 1.5; color: var(--color-bark-700);
}
.lp-tier__feature--off { opacity: .42; }
.lp-tier__fcheck {
  width: 18px; height: 18px; border-radius: 50%; flex-shrink: 0; margin-top: 1px;
  display: flex; align-items: center; justify-content: center;
}
.lp-tier__fcheck--on  { background: #dcfce7; }
.lp-tier__fcheck--off { background: var(--color-cream-200); }
.lp-tier__fcheck svg { width: 9px; height: 9px; }
.lp-tier__btn {
  display: block; width: 100%; text-align: center;
  padding: 15px 22px; border-radius: 50px;
  font-size: 14px; font-weight: 700;
  cursor: pointer; border: none; transition: all .2s; text-decoration: none;
}
.lp-tier__btn--dark {
  background: var(--color-bark-900); color: var(--color-cream-100);
}
.lp-tier__btn--dark:hover { background: var(--color-terra-500); color: var(--color-bark-900); transform: translateY(-2px); }
.lp-tier__btn--outline {
  background: transparent; color: var(--color-bark-900);
  border: 1.5px solid var(--color-bark-900);
}
.lp-tier__btn--outline:hover { background: var(--color-bark-900); color: var(--color-cream-100); }

/* ── Single price card (fallback when no tiers) ── */
.lp-pricing-wrap { display: flex; gap: 32px; align-items: flex-start; }
.lp-pricing-features { flex: 1; display: flex; flex-direction: column; gap: 11px; }
.lp-pf-head {
  font-size: 12px; font-weight: 700;
  color: var(--color-bark-700); text-transform: uppercase;
  letter-spacing: .15em; margin-bottom: 6px;
}
.lp-pf-item {
  display: flex; align-items: flex-start; gap: 12px;
  font-size: 15px; color: var(--color-bark-800); line-height: 1.55;
}
.lp-pf-check {
  width: 24px; height: 24px; background: #dcfce7; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0; margin-top: 1px;
}
.lp-pf-check svg { width: 12px; height: 12px; }
.lp-pricing-aside {
  min-width: 280px; background: #fff;
  border: 1px solid var(--color-border); border-radius: 24px;
  padding: 30px 30px 26px; display: flex; flex-direction: column; gap: 14px;
  box-shadow: 0 6px 32px rgba(42,26,16,.08); text-align: center;
}
.lp-price-row {
  display: flex; align-items: center; justify-content: center; gap: 8px;
}
.lp-price-old { font-size: 16px; text-decoration: line-through; color: var(--color-bark-400); }
.lp-discount-badge {
  display: inline-flex; align-items: center;
  background: var(--color-terra-100); color: var(--color-terra-700);
  font-size: 11px; font-weight: 700; border-radius: 50px;
  padding: 2px 9px; line-height: 1.4;
  white-space: nowrap;
}
.lp-price-main {
  font-family: var(--font-serif), serif;
  font-size: 56px; font-weight: 600; letter-spacing: -2.5px;
  color: var(--color-bark-900); line-height: 1;
}

/* ── Channels (own background panel) ── */
.lp-channels-panel {
  margin-top: 64px;
  background: var(--color-bark-900);
  color: var(--color-cream-100);
  border-radius: 28px;
  padding: 36px 40px;
  position: relative;
  overflow: hidden;
}
.lp-channels-panel::before {
  content: ''; position: absolute; inset: 0;
  background: radial-gradient(circle at 85% 20%, rgba(239,148,91,.18) 0%, transparent 55%),
              radial-gradient(circle at 10% 90%, rgba(120,210,210,.10) 0%, transparent 60%);
  pointer-events: none;
}
.lp-channels-panel > * { position: relative; }
.lp-channels-panel .lp-section-eyebrow span { color: var(--color-terra-400); }
.lp-channels-panel .lp-section-eyebrow .line-decor { background: rgba(245,239,226,.25); }
.lp-channels-panel-title {
  font-family: var(--font-serif), serif;
  font-weight: 500; font-size: 22px; letter-spacing: -.4px;
  color: var(--color-cream-100); margin-bottom: 18px;
}
.lp-channels { display: flex; gap: 10px; flex-wrap: wrap; }
.lp-channel {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 10px 18px; border-radius: 50px;
  border: 1px solid rgba(245,239,226,.18);
  background: rgba(245,239,226,.06);
  font-size: 13px; font-weight: 600; color: var(--color-cream-100);
  transition: background .2s, border-color .2s, transform .2s;
}
.lp-channel:hover {
  background: rgba(245,239,226,.12);
  border-color: rgba(245,239,226,.32);
  transform: translateY(-1px);
}
.lp-channel svg { width: 18px; height: 18px; flex-shrink: 0; }

/* ── Author ── */
.lp-s--author .lp-section-eyebrow span { color: var(--color-terra-400); }
.lp-s--author .lp-section-eyebrow .line-decor { background: rgba(245,239,226,.25); }
.lp-s--author .lp-section-title, .lp-s--author .lp-section-title em { color: var(--color-cream-100); }
.lp-author { display: flex; gap: 40px; align-items: flex-start; }
.lp-author__photo {
  width: 180px; display: flex; flex-direction: column; align-items: center; gap: 16px; flex-shrink: 0;
}
.lp-author__photo-img {
  width: 144px; height: 144px; border-radius: 32px;
  border: 1px solid rgba(255,255,255,0.1); overflow: hidden;
  background: var(--color-cream-200); display: flex; align-items: center; justify-content: center;
}
.lp-author__photo-img img { width: 100%; height: 100%; object-fit: cover; }
.lp-author__photo-img svg { width: 56px; height: 56px; opacity: .25; }
.lp-author__info { flex: 1; }
.lp-author__name {
  font-family: var(--font-serif), serif;
  font-size: 26px; font-weight: 600; letter-spacing: -.5px;
  color: var(--color-bark-900); margin-bottom: 2px;
}
.lp-s--author .lp-author__name { color: var(--color-cream-100); }
.lp-author__spec { font-size: 13.5px; color: var(--color-bark-500); margin-bottom: 18px; }
.lp-s--author .lp-author__spec { color: rgba(245,239,226,0.7); }
.lp-author__bio { font-size: 14.5px; line-height: 1.75; color: var(--color-bark-700); margin-bottom: 16px; }
.lp-s--author .lp-author__bio { color: rgba(245,239,226,0.85); }
.lp-author__quote {
  border: 1px solid var(--color-border); border-radius: 14px;
  padding: 16px 22px;
  font-family: var(--font-serif), serif;
  font-style: italic; font-size: 15px;
  color: var(--color-bark-600); line-height: 1.65; margin-bottom: 16px;
  background: rgba(255,255,255,.5);
}
.lp-s--author .lp-author__quote {
  background: rgba(255, 255, 255, 0.05); border-color: rgba(255, 255, 255, 0.1); color: rgba(245, 239, 226, 0.85);
}
.lp-s--author .lp-stats {
  background: rgba(245, 239, 226, 0.05); border-color: rgba(255, 255, 255, 0.1); backdrop-filter: blur(4px); box-shadow: none;
}
.lp-s--author .lp-stat { border-right-color: rgba(255, 255, 255, 0.1); }
.lp-s--author .lp-stat__value { color: var(--color-cream-100); }
.lp-s--author .lp-stat__label { color: rgba(245, 239, 226, 0.7); }

/* ── Responsive ── */
@media (max-width: 900px) {
  .bg-main { min-width: 0 !important; }
  .lp-page { padding: 16px 16px 80px; gap: 16px; }
  .lp-pricing-wrap { flex-direction: column; }
  .lp-pricing-aside { min-width: unset; width: 100%; }
  .lp-tiers { grid-template-columns: 1fr; gap: 16px; }
  .lp-tier--featured { transform: none; }
  .lp-tier--featured:hover { transform: translateY(-3px); }
  .lp-author { gap: 24px; }
  .lp-channels-panel { padding: 28px 24px; }
}
@media (max-width: 768px) {
  .lp-hero { padding: 80px 20px 64px; min-height: 460px; }
  .lp-hero__title { font-size: clamp(1.9rem, 7.5vw, 3rem); }
  .lp-hero__scrim {
    background: linear-gradient(180deg, rgba(245,239,226,.92) 0%, rgba(245,239,226,.86) 60%, rgba(245,239,226,.65) 100%) !important;
  }
  .lp-stat { padding: 20px 12px; }
  .lp-stat__value { font-size: 26px; }
  .lp-use-cases { grid-template-columns: 1fr; }
  .lp-s { padding: 40px 20px; }
  .lp-sm { padding: 32px 20px; }
  .lp-author { flex-direction: column; gap: 20px; align-items: center; text-align: center; }
  .lp-author__name { margin-left: auto; margin-right: auto; justify-content: center; display: flex; width: 100%; }
  .lp-author__photo { width: auto; }
  .lp-author__photo-img { width: 104px; height: 104px; border-radius: 22px; }
  .lp-tab-btn { padding: 12px 14px; font-size: 13px; }
}
@media (max-width: 480px) {
  .lp-page { padding: 12px 12px 72px; }
  .lp-hero { padding: 60px 16px 40px; }
  .lp-s { padding: 32px 16px; }
  .lp-stats { flex-wrap: wrap; }
  .lp-stat { flex: 0 0 50%; border-bottom: 1px solid rgba(255,255,255,0.1); }
  .lp-stat:nth-child(even) { border-right: none; }
  .lp-channels { gap: 8px; }
  .lp-tier { padding: 28px 22px 24px; }
  .lp-tier__price { font-size: 38px; }
}

/* ── Scroll-reveal ── */
.lp-reveal {
  opacity: 0;
  transform: translateY(28px);
  transition: opacity .55s cubic-bezier(.22,1,.36,1), transform .55s cubic-bezier(.22,1,.36,1);
}
.lp-reveal.is-visible {
  opacity: 1;
  transform: none;
}
.lp-reveal-delay-1 { transition-delay: .08s; }
.lp-reveal-delay-2 { transition-delay: .16s; }
.lp-reveal-delay-3 { transition-delay: .24s; }

/* ── Count-up shimmer on values ── */
.lp-stat__value.counting {
  background: linear-gradient(90deg, var(--color-bark-900) 40%, var(--color-terra-500) 50%, var(--color-bark-900) 60%);
  background-size: 200%;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  animation: lp-shimmer-count .9s ease-out forwards;
}
@keyframes lp-shimmer-count {
  from { background-position: 100% 0; }
  to   { background-position: -100% 0; }
}

/* ── CTA button pulse ring ── */
.btn-dark.btn-lg {
  position: relative;
  isolation: isolate;
  overflow: visible;
}
.btn-dark.btn-lg::before {
  content: '';
  position: absolute;
  inset: -4px;
  border-radius: 50px;
  background: transparent;
  border: 2px solid var(--color-terra-400);
  opacity: 0;
  animation: lp-pulse-ring 2.8s ease-out 1.2s infinite;
  pointer-events: none;
}
@keyframes lp-pulse-ring {
  0%   { transform: scale(1);   opacity: .55; }
  70%  { transform: scale(1.1); opacity: 0;   }
  100% { transform: scale(1.1); opacity: 0;   }
}

/* ── Hero floating particles canvas ── */
#lp-particles {
  position: absolute; inset: 0; z-index: 2;
  pointer-events: none; overflow: hidden;
}
#lp-particles span {
  position: absolute;
  display: block;
  border-radius: 50%;
  background: var(--color-terra-400);
  opacity: 0;
  animation: lp-float-particle linear infinite;
}
@keyframes lp-float-particle {
  0%   { transform: translateY(0)   scale(1);   opacity: 0; }
  10%  { opacity: .18; }
  90%  { opacity: .10; }
  100% { transform: translateY(-120px) scale(.7); opacity: 0; }
}

/* ── Tier card spotlight glow on hover ── */
.lp-tier {
  overflow: visible;
}
.lp-tier::after {
  content: '';
  position: absolute; top: -60px; left: -60px;
  width: 180px; height: 180px;
  background: radial-gradient(circle, rgba(239,148,91,.18) 0%, transparent 70%);
  border-radius: 50%;
  opacity: 0;
  pointer-events: none;
  transition: opacity .3s;
  transform: translate(var(--mx,0), var(--my,0));
}
.lp-tier:hover::after { opacity: 1; }

/* ── Use-case card lift ── */
.lp-use-case {
  transition: border-color .2s, transform .25s cubic-bezier(.34,1.56,.64,1), box-shadow .25s;
}
.lp-use-case:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 24px rgba(42,26,16,.10);
}

/* ── Section eyebrow line grow ── */
.lp-section-eyebrow .line-decor {
  transition: width .5s ease;
}
.is-visible .lp-section-eyebrow .line-decor { width: 60px; }

@media (prefers-reduced-motion: reduce) {
  .lp-reveal, .lp-reveal.is-visible { opacity: 1; transform: none; transition: none; }
  .btn-dark.btn-lg::before, #lp-particles span, .lp-bg-blob { animation: none !important; }
}

/* ── Background aurora blobs ── */
.lp-bg-blobs {
  position: fixed;
  inset: 0;
  z-index: 0;
  pointer-events: none;
  overflow: hidden;
}
.lp-bg-blob {
  position: absolute;
  border-radius: 50%;
  filter: blur(90px);
  mix-blend-mode: multiply;
  will-change: transform;
}
.lp-bg-blob--1 {
  width: 700px; height: 700px;
  top: -200px; right: -180px;
  background: radial-gradient(circle, rgba(239,148,91,.28) 0%, transparent 70%);
  animation: lpBlob1 28s ease-in-out infinite alternate;
}
.lp-bg-blob--2 {
  width: 600px; height: 600px;
  bottom: 0; left: -160px;
  background: radial-gradient(circle, rgba(120,210,210,.22) 0%, transparent 70%);
  animation: lpBlob2 34s ease-in-out infinite alternate;
}
.lp-bg-blob--3 {
  width: 480px; height: 480px;
  top: 35%; left: 30%;
  background: radial-gradient(circle, rgba(201,168,76,.18) 0%, transparent 70%);
  animation: lpBlob3 22s ease-in-out infinite alternate;
}
.lp-bg-blob--4 {
  width: 380px; height: 380px;
  top: 60%; right: 5%;
  background: radial-gradient(circle, rgba(180,140,220,.15) 0%, transparent 70%);
  animation: lpBlob4 40s ease-in-out infinite alternate;
}
@keyframes lpBlob1 {
  0%   { transform: translate(0,0) scale(1); }
  50%  { transform: translate(-80px, 120px) scale(1.12); }
  100% { transform: translate(60px, 40px) scale(.95); }
}
@keyframes lpBlob2 {
  0%   { transform: translate(0,0) scale(1); }
  40%  { transform: translate(100px,-80px) scale(1.08); }
  100% { transform: translate(40px, 60px) scale(1.15); }
}
@keyframes lpBlob3 {
  0%   { transform: translate(0,0) scale(1); }
  60%  { transform: translate(-60px, 80px) scale(.9); }
  100% { transform: translate(80px,-40px) scale(1.1); }
}
@keyframes lpBlob4 {
  0%   { transform: translate(0,0) scale(1); }
  50%  { transform: translate(-100px,-60px) scale(1.2); }
  100% { transform: translate(50px, 80px) scale(.9); }
}
</style>

<div class="bg-main" style="position:relative;">
<div class="lp-bg-blobs" aria-hidden="true">
  <div class="lp-bg-blob lp-bg-blob--1"></div>
  <div class="lp-bg-blob lp-bg-blob--2"></div>
  <div class="lp-bg-blob lp-bg-blob--3"></div>
  <div class="lp-bg-blob lp-bg-blob--4"></div>
</div>
<div class="lp-page" style="position:relative;z-index:1;">

  {{-- ════════════════ 1. HERO ════════════════ --}}
  <section class="lp-hero">
    @if($hero_img)
      <div class="lp-hero__bg" style="background-image:url('{{ $hero_img }}');"></div>
    @endif
    <div class="lp-hero__scrim" style="background: linear-gradient(95deg, {{ $c1 }} 0%, {{ $c2 }} 38%, {{ $c3 }} 70%, {{ $c4 }} 100%), linear-gradient(180deg, rgba(245,239,226,.45) 0%, rgba(245,239,226,0) 30%, rgba(245,239,226,.55) 100%);"></div>
    <div id="lp-particles" aria-hidden="true"></div>
    <div class="lp-hero__mesh">
      <div class="lp-hero__blob a"></div>
      <div class="lp-hero__blob b"></div>
    </div>
    <div class="lp-hero__grain"></div>

    <div class="lp-hero__inner">
      <div class="lp-hero__body">

        <div class="lp-hero__eyebrow">
          <div class="line-decor"></div>
          <svg class="leaf" viewBox="0 0 20 20" fill="none" aria-hidden="true">
            <path d="M10 1 C 4 6, 4 14, 10 19 C 16 14, 16 6, 10 1 Z M10 5 L10 17" stroke="currentColor" stroke-width="1" fill="none"/>
          </svg>
          <span class="text-decor">Индивидуальная консультация</span>
        </div>

        @if(!empty($service['top_labels']))
          <div class="lp-hero__labels">
            @foreach($service['top_labels'] as $lbl)
              <span class="lp-hero__label"
                    style="background: {{ $lbl['bg_color'] }}; color: {{ $lbl['text_color'] }};">
                {{ $lbl['text'] }}
              </span>
            @endforeach
          </div>
        @endif

        <h1 class="lp-hero__title">
          @if($title_lead !== '')
            {{ $title_lead }} <em>{{ $title_last }}</em>
          @else
            {{ $title_last }}
          @endif
        </h1>

        @if($service['subtitle'])
          <p class="lp-hero__sub">{!! $service['subtitle'] !!}</p>
        @endif

        <div class="lp-hero__cta">
          @if(is_user_logged_in())
            <button class="btn-dark btn-lg" onclick="window.location.href='{{ $buy_url }}#tarify'">Записаться на консультацию</button>
          @else
            <button class="btn-dark btn-lg" onclick="window.edietOpenBuyModal('{{ $buy_url }}')">Записаться на консультацию</button>
          @endif
          @include('partials.favorite-btn', ['post_id' => $post_id, 'class' => '!w-12 !h-12 !rounded-full bg-white/70 backdrop-blur'])
        </div>
      </div>
    </div>
  </section>

  {{-- ════════════════ 2. STATS ════════════════ --}}
  @if(!empty($service['stats']))
  <section class="lp-sm lp-s--canvas">
    <div class="lp-stats">
      @foreach($service['stats'] as $st)
        <div class="lp-stat">
          <span class="lp-stat__value">{{ $st['number'] }}</span>
          <span class="lp-stat__label">{{ $st['label'] }}</span>
        </div>
      @endforeach
    </div>
  </section>
  @endif

  {{-- ════════════════ 2.5 AUTHOR (after stats) ════════════════ --}}
  @if($author)
  <section class="lp-s lp-s--author">
      <div class="lp-section-eyebrow">
        <div class="line-decor"></div>
        <span>Специалист</span>
      </div>
      <h2 class="lp-section-title" style="margin-bottom:36px;">{!! $author['section_heading'] !!}</h2>

      <div class="lp-author">
        <div class="lp-author__photo">
          <div class="lp-author__photo-img">
            @php
               $author_post_id = get_page_by_title($author['name'], OBJECT, 'expert') ? get_page_by_title($author['name'], OBJECT, 'expert')->ID : null;
               $acf_avatar = $author_post_id ? get_field('doc_avatar', $author_post_id) ?: get_field('avatar', $author_post_id) : null;
               if(is_array($acf_avatar)) $acf_avatar = $acf_avatar['sizes']['medium'] ?? $acf_avatar['url'];
               $final_avatar = $acf_avatar ?: $author['avatar_url'];
            @endphp
            @if($final_avatar)
              <img src="{{ $final_avatar }}" alt="{{ $author['name'] }}">
            @else
              <svg viewBox="0 0 24 24" fill="none"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z" stroke="currentColor" stroke-width="1.5"/></svg>
            @endif
          </div>
          
          @if(!empty($author['tags']))
            <div style="display:flex;gap:6px;flex-wrap:wrap;justify-content:center;">
              @foreach(array_slice($author['tags'], 0, 5) as $tag)
                <span class="badge-cream" style="background: rgba(245,239,226,0.1); border: 1px solid rgba(245,239,226,0.2); color: var(--color-cream-100); border-radius: 50px; padding: 4px 12px; font-size: 11px;">
                  {{ is_array($tag) ? ($tag['text'] ?? ($tag['name'] ?? '')) : $tag }}
                </span>
              @endforeach
            </div>
          @endif
        </div>

        <div class="lp-author__info">
          <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px;flex-wrap:wrap;" class="lp-author__name-wrap">
            <div class="lp-author__name">{{ $author['name'] }}</div>
            @if($author['verified'])
              <span class="badge-green" style="background:#DCFCE7; color:#15803D; font-size:11px; padding:2px 8px; border-radius:4px; font-weight:700;">✓ Верифицирован</span>
            @endif
          </div>
          <div class="lp-author__spec">{{ $author['spec'] }}</div>

          @if(!empty($author['stats']))
            <div class="lp-stats" style="max-width:480px;margin-bottom:22px;">
              @foreach($author['stats'] as $ast)
                <div class="lp-stat">
                  <span class="lp-stat__value" style="font-size:24px;">{{ $ast['value'] ?? '' }}</span>
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
        </div>
      </div>
  </section>
  @endif

  {{-- ════════════════ 3. DESCRIPTION + REVIEWS ════════════════ --}}
  <div class="cns-tabs-card">
    <div class="cns-tab-nav">
      <button class="cns-tab-btn active" data-cns-target="cns-desc-{{ $bid }}">О консультации</button>
      @if(count($reviews) > 0)
        <button class="cns-tab-btn" data-cns-target="cns-reviews-{{ $bid }}">
          Отзывы
          <span style="background:rgba(42,26,16,.07);padding:2px 8px;border-radius:20px;font-size:11px;margin-left:6px;">{{ count($reviews) }}</span>
        </button>
      @endif
    </div>

    <div class="cns-tab-content active" id="cns-desc-{{ $bid }}">
      <div class="lp-desc">
        @if(empty($service['description']))
          <p>Индивидуальная консультация поможет вам разобраться в вашем питании, получить персональный план и поддержку специалиста.</p>
        @else
          @foreach($service['description'] as $block)
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
                      <div style="font-weight:700;font-size:14px;color:var(--color-bark-900);margin-bottom:4px;">{{ $uc['title'] }}</div>
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

    @if(count($reviews) > 0)
    <div class="cns-tab-content" id="cns-reviews-{{ $bid }}">
      @include('blocks.featured_reviews', ['custom_reviews' => $reviews, 'hide_controls' => true, 'rv_theme' => 'light'])
    </div>
    @else
    <div class="cns-tab-content" id="cns-reviews-{{ $bid }}" style="display:none;">
      <p style="color:var(--color-bark-400);font-size:14px;">Отзывов пока нет. Будьте первыми!</p>
    </div>
    @endif
  </div>

  {{-- ════════════════ 4. PRICING ════════════════ --}}
  <section class="lp-s lp-s--canvas" id="tarify">

      <div class="lp-section-eyebrow">
        <div class="line-decor"></div>
        <span>Стоимость</span>
      </div>
      <h2 class="lp-section-title">Выберите <em>формат</em> консультации</h2>
      <p class="lp-section-lead">Каждый тариф включает работу с врачом-нутрициологом — выберите глубину сопровождения.</p>

      @php
        $tiers     = $service['pricing_tiers'] ?? [];
        $has_tiers = !empty($tiers);
      @endphp

      @if($has_tiers)

        {{-- ── 3-column tier grid ── --}}
        <div class="lp-tiers" style="margin-top:48px;">
          @foreach($tiers as $tidx => $tier)
            @php
              $t_badge    = trim($tier['tier_badge'] ?? '');
              $has_badge  = $t_badge !== '';
              $t_badge_bg = !empty($tier['tier_badge_bg']) ? $tier['tier_badge_bg'] : '';
              $t_badge_fg = !empty($tier['tier_badge_fg']) ? $tier['tier_badge_fg'] : '';
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
              $btn_class = $has_badge ? 'lp-tier__btn--dark' : 'lp-tier__btn--outline';
              $cta_text  = !empty($tier['tier_cta_text']) ? $tier['tier_cta_text'] : 'Записаться';
              $badge_style = '';
              if ($t_badge_bg) $badge_style .= 'background:' . $t_badge_bg . ';';
              if ($t_badge_fg) $badge_style .= 'color:' . $t_badge_fg . ';';
            @endphp

            <div class="lp-tier {{ $has_badge ? 'lp-tier--featured' : '' }}">

              @if($has_badge)
                <div class="lp-tier__custom-badge" @if($badge_style) style="{{ $badge_style }}" @endif>{{ $t_badge }}</div>
              @endif

              <div class="lp-tier__name">{{ $tier['tier_name'] ?? 'Тариф ' . ($tidx + 1) }}</div>
              @if(!empty($tier['tier_subtitle']))
                <div class="lp-tier__sub">{{ $tier['tier_subtitle'] }}</div>
              @else
                <div style="margin-bottom:22px;"></div>
              @endif

              @if($t_old || $t_disc > 0)
                <div class="lp-tier__price-row">
                  @if($t_old)
                    <span class="lp-tier__price-old">{{ $t_old }} ₽</span>
                  @endif
                  @if($t_disc > 0)
                    <span class="lp-tier__disc">−{{ $t_disc }}%</span>
                  @endif
                </div>
              @endif
              <div class="lp-tier__price">
                {{ $t_price ? $t_price : '—' }}
                @if($t_price)<span> ₽</span>@endif
              </div>

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
        <div class="lp-pricing-wrap" style="margin-top:40px;">

          <div class="lp-pricing-features">
            <div class="lp-pf-head">Что входит в консультацию</div>
            @foreach($service['features'] as $f)
              <div class="lp-pf-item">
                <div class="lp-pf-check">
                  <svg viewBox="0 0 11 11" fill="none"><path d="M2.5 5.5L4.5 7.5L8.5 3.5" stroke="#16A34A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <span>{{ $f['text'] ?? $f['title'] ?? '' }}</span>
              </div>
            @endforeach
          </div>

          <div class="lp-pricing-aside">
            @php
              $disc = 0;
              if ($service['price_old'] && $service['price'] && (int)$service['price'] > 0) {
                $disc = round(100 - ((float)$service['price'] / (float)$service['price_old']) * 100);
              }
            @endphp

            @if($service['price_old'] || $disc > 0)
              <div class="lp-price-row">
                @if($service['price_old'])
                  <span class="lp-price-old">{{ $service['price_old'] }} ₽</span>
                @endif
                @if($disc > 0)
                  <span class="lp-discount-badge">−{{ $disc }}%</span>
                @endif
              </div>
            @endif

            <div class="lp-price-main">
              {{ $service['price'] ? $service['price'] . ' ₽' : 'Бесплатно' }}
            </div>

            @if(is_user_logged_in())
              <button class="btn-dark btn-lg" style="width:100%;" onclick="window.location.href='{{ $buy_url }}'">Записаться</button>
            @else
              <button class="btn-dark btn-lg" style="width:100%;" onclick="window.edietOpenBuyModal('{{ $buy_url }}')">Записаться</button>
            @endif

            @if($service['delivery_note'])
              <p class="text-caption text-center" style="margin-top:4px;">{{ $service['delivery_note'] }}</p>
            @endif
          </div>
        </div>

      @endif

      {{-- Channels --}}
      <div class="lp-channels-panel">
        <div class="lp-section-eyebrow">
          <div class="line-decor"></div>
          <span>Форматы связи</span>
        </div>
        <div class="lp-channels-panel-title">Удобный мессенджер на выбор</div>
        <div class="lp-channels">
          <div class="lp-channel">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M12 2C6.477 2 2 6.477 2 12c0 1.89.524 3.655 1.435 5.16L2 22l4.98-1.424A9.953 9.953 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2z" fill="#25D366"/>
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.1-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" fill="white"/>
            </svg>
            WhatsApp
          </div>
          <div class="lp-channel">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2z" fill="#229ED9"/>
              <path d="M17.79 7.42L15.66 17.4c-.16.72-.59.9-1.2.56l-3.3-2.43-1.59 1.53c-.18.18-.33.33-.67.33l.24-3.36 6.1-5.51c.27-.24-.06-.37-.41-.14L7.28 12.9 4.02 11.9c-.7-.22-.71-.7.15-1.04l13.04-5.02c.58-.21 1.09.14.58 1.58z" fill="white"/>
            </svg>
            Telegram
          </div>
          <div class="lp-channel">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2z" fill="#7360F2"/>
              <path d="M16.5 7.5H7.5v5.5c0 1.1.9 2 2 2h.5v2l2-2h2.5c1.1 0 2-.9 2-2V9.5c0-1.1-.9-2-2-2z" fill="white" opacity=".9"/>
            </svg>
            Viber
          </div>
          <div class="lp-channel">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect x="2" y="6" width="14" height="12" rx="2.5" fill="#2D8CFF"/>
              <path d="M16 10.5l5-3v9l-5-3v-3z" fill="#2D8CFF"/>
            </svg>
            Zoom
          </div>
        </div>
      </div>
  </section>

  {{-- ════════════════ 6. CROSS-SELLS ════════════════ --}}
  @if(count($cross_sells) > 0)
  <section class="lp-s lp-s--canvas">
      <div class="lp-section-eyebrow">
        <div class="line-decor"></div>
        <span>Полезное рядом</span>
      </div>
      <h2 class="lp-section-title" style="margin-bottom:32px;">С этим <em>покупают</em></h2>
      <div class="dtc-grid">
        @foreach($cross_sells as $cs)
          @include('partials.dtc-card', ['item' => $cs])
        @endforeach
      </div>
  </section>
  @endif

</div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

  /* ── Tab switching: lp-tab-btn ── */
  document.querySelectorAll('.lp-tab-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var pane = btn.dataset.pane;
      btn.closest('.lp-tab-nav').querySelectorAll('.lp-tab-btn').forEach(function (b) { b.classList.remove('active'); });
      btn.classList.add('active');
      var section = btn.closest('section') || document;
      section.querySelectorAll('.lp-tab-pane').forEach(function (p) { p.classList.remove('active'); });
      var target = document.getElementById(pane);
      if (target) target.classList.add('active');
    });
  });

  /* ── Tab switching: cns-tab-btn ── */
  document.querySelectorAll('.cns-tab-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var targetId = btn.dataset.cnsTarget;
      var card = btn.closest('.cns-tabs-card');
      card.querySelectorAll('.cns-tab-btn').forEach(function (b) { b.classList.remove('active'); });
      card.querySelectorAll('.cns-tab-content').forEach(function (c) { c.classList.remove('active'); });
      btn.classList.add('active');
      var target = document.getElementById(targetId);
      if (target) target.classList.add('active');
    });
  });

  /* ── Scroll-reveal ── */
  var revealEls = document.querySelectorAll('.lp-hero, .lp-sm, .lp-s, .cns-tabs-card, .cns-tabs-card');
  var seen = new WeakSet();
  var revealObserver = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting && !seen.has(entry.target)) {
        seen.add(entry.target);
        entry.target.classList.add('lp-reveal', 'is-visible');
        // stagger children
        entry.target.querySelectorAll('.lp-tier, .lp-stat, .lp-use-case, .lp-channel, .lp-author__photo, .lp-author__info').forEach(function (child, i) {
          child.classList.add('lp-reveal');
          setTimeout(function () { child.classList.add('is-visible'); }, 60 + i * 60);
        });
      }
    });
  }, { threshold: 0.08 });
  revealEls.forEach(function (el) { revealObserver.observe(el); });

  /* ── Count-up animation for stat values ── */
  function countUp(el) {
    var raw = el.textContent.replace(/[^\d]/g, '');
    var target = parseInt(raw, 10);
    if (!raw || isNaN(target)) return;
    var suffix = el.textContent.replace(/[\d]/g, '');
    var start = 0;
    var duration = 900;
    var startTime = null;
    el.classList.add('counting');
    function step(ts) {
      if (!startTime) startTime = ts;
      var progress = Math.min((ts - startTime) / duration, 1);
      var eased = 1 - Math.pow(1 - progress, 3);
      el.textContent = Math.round(start + (target - start) * eased) + suffix;
      if (progress < 1) requestAnimationFrame(step);
      else { el.classList.remove('counting'); el.textContent = target + suffix; }
    }
    requestAnimationFrame(step);
  }
  var statObserver = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.querySelectorAll('.lp-stat__value').forEach(countUp);
        statObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.4 });
  document.querySelectorAll('.lp-stats').forEach(function (el) { statObserver.observe(el); });

  /* ── Hero floating particles ── */
  var pWrap = document.getElementById('lp-particles');
  if (pWrap) {
    var colors = ['rgba(239,148,91,.35)', 'rgba(201,168,76,.3)', 'rgba(120,210,210,.25)', 'rgba(239,148,91,.2)'];
    for (var i = 0; i < 18; i++) {
      (function () {
        var s = document.createElement('span');
        var size = 4 + Math.random() * 10;
        var left = 5 + Math.random() * 90;
        var bottom = -size;
        var dur = 6 + Math.random() * 10;
        var delay = Math.random() * 12;
        var color = colors[Math.floor(Math.random() * colors.length)];
        s.style.cssText = [
          'width:' + size + 'px',
          'height:' + size + 'px',
          'left:' + left + '%',
          'bottom:' + bottom + 'px',
          'background:' + color,
          'animation-duration:' + dur + 's',
          'animation-delay:' + delay + 's',
          'filter:blur(' + (size * .25) + 'px)'
        ].join(';');
        pWrap.appendChild(s);
      }());
    }
  }

  /* ── Tier card spotlight glow follow cursor ── */
  document.querySelectorAll('.lp-tier').forEach(function (card) {
    card.addEventListener('mousemove', function (e) {
      var rect = card.getBoundingClientRect();
      var x = e.clientX - rect.left;
      var y = e.clientY - rect.top;
      card.style.setProperty('--mx', (x - 90) + 'px');
      card.style.setProperty('--my', (y - 90) + 'px');
    });
  });

});
</script>

@endsection
