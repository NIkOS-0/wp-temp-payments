@php
  $eyebrow = $block['eyebrow'] ?? 'Персональное питание · с 2019';
  $title_line_1 = $block['title_line_1'] ?? 'Возьмите здоровье';
  $title_line_2_pre = $block['title_line_2_pre'] ?? 'под';
  $title_line_2_italic = $block['title_line_2_italic'] ?? 'контроль';
  $kicker = $block['kicker'] ?? 'Есть <em>решения</em> под ваш диагноз — персональные протоколы питания, составленные врачами‑нутрициологами на основе анализов и медицинской карты.';
  $cta_1 = $block['cta_1'] ?? '';
  $cta_2 = $block['cta_2'] ?? '';
  
  $trust_items = $block['trust_items'] ?? [
      ['text' => '<strong>26 000+</strong>&nbsp;пациентов'],
      ['text' => '<strong>4.9</strong>&nbsp;средняя оценка'],
      ['text' => 'Сертифицированные&nbsp;<strong>врачи</strong>'],
      ['text' => 'Возврат <strong>14 дней</strong>']
  ];
  
  $badge_l_num = $block['badge_left_num'] ?? '26';
  $badge_l_k = $block['badge_left_k'] ?? 'диагнозов';
  $badge_l_v = $block['badge_left_v'] ?? 'под <em>ключ</em>';
  
  $badge_r_icon = $block['badge_right_icon'] ?? '⟡';
  $badge_r_k = $block['badge_right_k'] ?? 'нутрициолог';
  $badge_r_v = $block['badge_right_v'] ?? '<em>онлайн</em> 24/7';
  
  $seal_text = $block['seal_text'] ?? 'E‑DIET · ЗДОРОВЬЕ В ТАРЕЛКЕ · С 2019 ·';
  $seal_center = $block['seal_center'] ?? 'e.';
@endphp

<style>
/* SCOPED HERO STYLES */
#hero-wrapper {
  --sage-deep:    #2F3D2A;
  --sage:         #4A5E43;
  --sage-light:   #6B8562;
  --moss:         #8BA77F;
  --cream:        #F5EFE2;
  --cream-soft:   #EBE3D2;
  --cream-muted:  #A89F8B;
  --ink:          #2A1A10;
  --ink-soft:     #3A2418;
  --terracotta:   rgb(239,148,91);
  --terracotta-soft: #F4B491;
  --terracotta-deep: #D87A4A;
  --gold-leaf:    #C9A84C;
  --border:       rgba(42,26,16,0.10);
  --border-strong:rgba(42,26,16,0.22);
}

.hero {
  min-height: 75vh;
  position: relative;
  overflow: hidden;
  display: flex; align-items: center; justify-content: center;
  padding: 80px 32px 60px;
  background: var(--cream);
}
.hero * { box-sizing:border-box; }

/* organic gradient mesh — iridescent, shifting blobs */
.hero .mesh {
  position: absolute; inset: 0; z-index: 0;
  pointer-events: none;
  overflow: hidden;
}
.hero .blob {
  position: absolute;
  border-radius: 50%;
  filter: blur(90px);
  will-change: transform, background, filter;
  mix-blend-mode: multiply;
}
.hero .blob.a {
  width: 820px; height: 820px;
  top: -200px; right: -140px;
  background: conic-gradient(from 0deg,
    rgba(120,210,210,0.55),
    rgba(130,200,140,0.5),
    rgba(120,210,210,0.55));
  animation: hero-drift-a 22s ease-in-out infinite alternate,
             hero-hue-a 14s linear infinite;
}
.hero .blob.b {
  width: 680px; height: 680px;
  bottom: -180px; left: -120px;
  background: conic-gradient(from 180deg,
    rgba(130,200,140,0.55),
    rgba(120,210,210,0.5),
    rgba(130,200,140,0.55));
  animation: hero-drift-b 26s ease-in-out infinite alternate,
             hero-hue-b 18s linear infinite;
}
.hero .blob.c {
  width: 480px; height: 480px;
  top: 38%; left: 48%;
  transform: translate(-50%, -50%);
  background: conic-gradient(from 90deg,
    rgba(120,210,210,0.45),
    rgba(130,200,140,0.45),
    rgba(120,210,210,0.45));
  animation: hero-drift-c 30s ease-in-out infinite alternate,
             hero-hue-c 16s linear infinite;
}
.hero .blob.d {
  width: 360px; height: 360px;
  top: 65%; right: 18%;
  background: radial-gradient(circle, rgba(130,200,140,0.55) 0%, rgba(130,200,140,0) 65%);
  animation: hero-drift-d 24s ease-in-out infinite alternate,
             hero-pulse-d 7s ease-in-out infinite;
}
@keyframes hero-drift-a {
  0%   { transform: translate(0, 0) scale(1); }
  50%  { transform: translate(-40px, 40px) scale(1.05); }
  100% { transform: translate(-80px, 100px) scale(1.12); }
}
@keyframes hero-drift-b {
  0%   { transform: translate(0, 0) scale(1); }
  50%  { transform: translate(60px, -30px) scale(1.08); }
  100% { transform: translate(100px, -80px) scale(1.15); }
}
@keyframes hero-drift-c {
  0%   { transform: translate(-50%, -50%) scale(1); }
  50%  { transform: translate(-42%, -55%) scale(1.08); }
  100% { transform: translate(-30%, -62%) scale(1.18); }
}
@keyframes hero-drift-d {
  0%   { transform: translate(0, 0) scale(1); }
  100% { transform: translate(-60px, -50px) scale(1.2); }
}
@keyframes hero-hue-a { to { transform: rotate(360deg); filter: blur(90px) hue-rotate(25deg); } }
@keyframes hero-hue-b {
  0%   { filter: blur(90px) hue-rotate(0deg); }
  50%  { filter: blur(100px) hue-rotate(-20deg); }
  100% { filter: blur(90px) hue-rotate(0deg); }
}
@keyframes hero-hue-c {
  0%   { filter: blur(90px) hue-rotate(0deg); }
  50%  { filter: blur(110px) hue-rotate(30deg); }
  100% { filter: blur(90px) hue-rotate(0deg); }
}
@keyframes hero-pulse-d {
  0%,100% { opacity: 0.85; }
  50%     { opacity: 0.45; }
}

/* ── INTERACTIVE CANVAS — floating seeds/leaves that react to cursor ── */
.hero .bg-canvas {
  position: absolute; inset: 0; z-index: 1;
  pointer-events: none;
}
.hero .cursor-glow {
  position: absolute;
  width: 420px; height: 420px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(120,210,210,0.28) 0%, rgba(130,200,140,0.15) 40%, transparent 70%);
  filter: blur(20px);
  pointer-events: none;
  transform: translate(-50%, -50%);
  left: 50%; top: 50%;
  transition: opacity .6s ease;
  opacity: 0;
  z-index: 1;
  mix-blend-mode: multiply;
}
.hero .grain {
  position: absolute; inset: 0; z-index: 2;
  pointer-events: none;
  opacity: 0.08;
  mix-blend-mode: multiply;
  background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='200' height='200'><filter id='n'><feTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='2' stitchTiles='stitch'/><feColorMatrix values='0 0 0 0 0  0 0 0 0 0  0 0 0 0 0  0 0 0 0.6 0'/></filter><rect width='100%' height='100%' filter='url(%23n)'/></svg>");
}
.hero-inner {
  position: relative; z-index: 3;
  max-width: 1280px; width:100%;
  text-align: center;
}
.eyebrow-row {
  display:flex; align-items:center; justify-content:center;
  gap:18px; margin-bottom: 44px;
  opacity:0; animation: hero-rise .9s ease .1s forwards;
}
.eyebrow-row .line-decor {
  height:1px; width:56px; background: var(--border-strong);
}
.eyebrow-row .text-decor {
  font-size: 11px;
  letter-spacing: 0.26em;
  text-transform: uppercase;
  color: var(--ink-soft);
  font-weight: 500;
}
.eyebrow-row .leaf {
  width: 14px; height: 14px;
  color: var(--terracotta);
}

h1.hero-title-main {
  font-family: 'Playfair Display', serif;
  font-weight: 500;
  font-size: clamp(54px, 8.2vw, 132px);
  line-height: 0.96;
  letter-spacing: -0.035em;
  color: var(--ink);
  max-width: 1200px;
  margin: 0 auto;
  text-wrap: balance;
}
h1.hero-title-main .line-1,
h1.hero-title-main .line-2 {
  display: block;
  opacity:0; transform: translateY(30px);
  animation: hero-rise 1.1s cubic-bezier(.2,.8,.2,1) forwards;
}
h1.hero-title-main .line-1 { animation-delay: .2s; }
h1.hero-title-main .line-2 { animation-delay: .38s; }
h1.hero-title-main em {
  font-style: italic;
  font-weight: 400;
  color: var(--ink);
  position: relative;
}
h1.hero-title-main em .uline {
  position:absolute;
  left:-2%; right:-2%;
  bottom: -0.05em;
  width: 104%;
  height: 0.22em;
  pointer-events:none;
}
h1.hero-title-main em .uline path {
  stroke: var(--terracotta);
  stroke-width: 3;
  fill: none;
  stroke-linecap: round;
  stroke-dasharray: 600;
  stroke-dashoffset: 600;
  animation: hero-draw 1.6s ease .9s forwards;
}
h1.hero-title-main .accent-dot {
  display:inline-block;
  width: 0.22em; height: 0.22em;
  background: var(--terracotta);
  border-radius:50%;
  vertical-align: 0.12em;
  margin-left: 0.08em;
  transform: translateY(-0.02em);
}
.hero .kicker {
  margin-top: 36px;
  font-size: 18px;
  color: var(--ink-soft);
  opacity: 0.8;
  font-weight: 400;
  max-width: 640px;
  margin-left:auto; margin-right:auto;
  line-height: 1.65;
  opacity:0; animation: hero-rise 1s ease .55s forwards;
}
.hero .kicker em {
  font-family: 'Playfair Display', serif;
  font-style: italic;
  font-weight: 500;
  color: var(--terracotta);
}

.hero-cta {
  margin-top: 52px;
  display:flex; gap:14px; align-items:center; justify-content:center;
  flex-wrap: wrap;
  opacity:0; animation: hero-rise 1s ease .7s forwards;
}
.hero-cta-btn-primary {
  background: var(--ink);
  color: var(--cream);
  font-family: 'Instrument Sans', 'Inter', sans-serif;
  font-size: 16px; font-weight: 500;
  padding: 20px 38px;
  border-radius: 100px;
  border: none; cursor: pointer; text-decoration: none;
  transition: background .25s, transform .15s, box-shadow .25s;
  display: inline-flex; align-items: center; gap: 12px;
  letter-spacing: 0.005em;
  box-shadow: 0 8px 24px -8px rgba(28,36,25,0.28);
}
.hero-cta-btn-primary:hover {
  background: var(--terracotta);
  color: var(--ink);
  transform: translateY(-2px);
  box-shadow: 0 14px 30px -10px rgba(239,148,91,0.45);
}
.hero-cta-btn-primary .arrow { transition: transform .25s; }
.hero-cta-btn-primary:hover .arrow { transform: translateX(4px); }

.hero-cta-btn-secondary {
  background: transparent;
  color: var(--ink);
  font-family: 'Instrument Sans', 'Inter', sans-serif;
  font-size: 16px; font-weight: 500;
  padding: 19px 32px;
  border-radius: 100px;
  border: 1.5px solid var(--ink);
  cursor: pointer; text-decoration: none;
  transition: all .25s;
  display: inline-flex; align-items: center; gap: 12px;
}
.hero-cta-btn-secondary:hover {
  background: var(--ink);
  color: var(--cream);
  transform: translateY(-2px);
}
.hero-cta-btn-secondary .pulse {
  width: 8px; height: 8px; border-radius:50%;
  background: var(--terracotta);
  position: relative;
}
.hero-cta-btn-secondary .pulse::before {
  content:'';
  position: absolute; inset: -4px;
  border-radius:50%;
  background: var(--terracotta);
  opacity: 0.5;
  animation: hero-pulse 1.8s ease-out infinite;
}

.trust-row {
  margin-top: 64px;
  display:flex; gap: 40px; align-items:center; justify-content:center;
  flex-wrap: wrap;
  opacity:0; animation: hero-rise 1s ease .9s forwards;
}
.trust-item {
  display:flex; align-items:center; gap:10px;
  font-size: 13px;
  color: var(--ink-soft);
  opacity: 0.75;
}
.trust-item .t-dot {
  width:5px; height:5px; border-radius:50%;
  background: var(--terracotta);
}
.trust-item strong {
  font-family: 'Playfair Display', serif;
  font-style: italic;
  font-weight: 500;
  color: var(--terracotta);
  opacity: 1;
}

.hero-badge {
  position: absolute;
  z-index: 3;
  background: rgba(245,239,226,0.75);
  border: 1px solid var(--border-strong);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  border-radius: 18px;
  padding: 16px 20px;
  display:flex; align-items:center; gap: 14px;
  opacity: 0;
  animation: hero-rise 1.1s ease forwards;
  box-shadow: 0 12px 30px -14px rgba(28,36,25,0.2);
}
.badge-l {
  left: 4%; top: 36%;
  transform: rotate(-4deg);
  animation-delay: 1.2s;
}
.badge-r {
  right: 4%; top: 46%;
  transform: rotate(3deg);
  animation-delay: 1.35s;
}
.hero-badge:hover { transform: rotate(0) translateY(-3px); transition: transform .3s; }

.badge-circle {
  width: 44px; height: 44px; border-radius:50%;
  background: var(--ink); color: var(--cream);
  display:flex; align-items:center; justify-content:center;
  font-family:'Playfair Display', serif;
  font-style: italic;
  font-weight: 500;
  font-size: 18px;
  flex-shrink:0;
}
.badge-circle.alt { background: var(--terracotta); color: var(--ink); }
.badge-text .k { font-size: 10px; letter-spacing: 0.14em; text-transform: uppercase; color: var(--cream-muted); font-weight:500; line-height: 1; }
.badge-text .v {
  font-family: 'Playfair Display', serif;
  font-size: 17px; font-weight: 600; color: var(--ink);
  letter-spacing: -0.01em; line-height: 1.1; margin-top: 2px;
}
.badge-text .v em { font-style: italic; font-weight: 400; color: var(--terracotta); }

.scroll-hint {
  position: absolute;
  bottom: 32px; left: 50%;
  transform: translateX(-50%);
  z-index: 3;
  display:flex; flex-direction:column; align-items:center; gap:10px;
  font-size: 11px; letter-spacing: 0.22em; text-transform:uppercase;
  color: var(--cream-muted);
  opacity: 0; animation: hero-rise 1s ease 1.5s forwards;
}
.scroll-hint .line-v {
  width: 1px; height: 36px;
  background: linear-gradient(to bottom, var(--cream-muted), transparent);
  position: relative;
  overflow: hidden;
}
.scroll-hint .line-v::before {
  content:'';
  position:absolute; top:0; left:0;
  width:100%; height: 12px;
  background: var(--terracotta);
  animation: hero-scroll-dot 2s ease-in-out infinite;
}

.hero-seal {
  position: absolute;
  right: 6%; top: 16%;
  width: 92px; height: 92px;
  z-index: 3;
  opacity: 0;
  animation: hero-rise 1s ease 1.1s forwards;
}
.hero-seal svg { width:100%; height:100%; }
.hero-seal .rotor { animation: hero-rotate 20s linear infinite; transform-origin:center; }
.seal-inner {
  position:absolute; inset:0;
  display:flex; align-items:center; justify-content:center;
  font-family: 'Playfair Display', serif;
  font-style: italic;
  font-size: 22px;
  color: var(--ink);
  font-weight: 500;
}

@keyframes hero-rise {
  from { opacity: 0; transform: translateY(30px); }
  to   { opacity: 1; transform: translateY(0); }
}
@keyframes hero-draw { to { stroke-dashoffset: 0; } }
@keyframes hero-pulse {
  0%   { transform: scale(1); opacity: 0.6; }
  100% { transform: scale(2.8); opacity: 0; }
}
@keyframes hero-scroll-dot {
  0%   { transform: translateY(-100%); }
  100% { transform: translateY(400%); }
}
@keyframes hero-rotate { to { transform: rotate(360deg); } }

@media (max-width: 900px) {
  .hero { padding: 120px 20px 80px; }
  .hero-badge { display: none; }
  .hero-seal { display: none; }
  .trust-row { gap: 20px; }
  .eyebrow-row .line-decor { width: 32px; }
}
@media (max-width: 600px) {
  .hero-cta { flex-direction: column; width:100%; }
  .hero-cta-btn-primary, .hero-cta-btn-secondary { width:100%; justify-content:center; }
}
</style>

<div id="hero-wrapper">
  <!-- HERO -->
  <section class="hero" id="hero">
    <div class="mesh">
      <div class="blob a"></div>
      <div class="blob b"></div>
      <div class="blob c"></div>
      <div class="blob d"></div>
    </div>
    <!-- cursor-following glow -->
    <div class="cursor-glow" id="cursorGlow"></div>
    <div class="grain"></div>

    <!-- floating badges -->
    <div class="hero-badge badge-l">
      <div class="badge-circle">{{ $badge_l_num }}</div>
      <div class="badge-text">
        <div class="k">{{ $badge_l_k }}</div>
        <div class="v">{!! $badge_l_v !!}</div>
      </div>
    </div>

    <div class="hero-badge badge-r">
      <div class="badge-circle alt">{{ $badge_r_icon }}</div>
      <div class="badge-text">
        <div class="k">{{ $badge_r_k }}</div>
        <div class="v">{!! $badge_r_v !!}</div>
      </div>
    </div>

    <!-- rotating seal -->
    <div class="hero-seal" aria-hidden="true">
      <svg viewBox="0 0 100 100">
        <defs>
          <path id="circle-path" d="M 50, 50 m -36, 0 a 36,36 0 1,1 72,0 a 36,36 0 1,1 -72,0" />
        </defs>
        <g class="rotor">
          <text font-family="Instrument Sans, sans-serif" font-size="8.5" letter-spacing="2" fill="#2A1A10">
            <textPath href="#circle-path">
              {{ $seal_text }}
            </textPath>
          </text>
        </g>
      </svg>
      <div class="seal-inner">{{ $seal_center }}</div>
    </div>

    <div class="hero-inner">
      <div class="eyebrow-row">
        <div class="line-decor"></div>
        <svg class="leaf" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
          <path d="M10 1 C 4 6, 4 14, 10 19 C 16 14, 16 6, 10 1 Z M10 5 L10 17" stroke="currentColor" stroke-width="1" fill="none"/>
        </svg>
        <span class="text-decor">{{ $eyebrow }}</span>
        <svg class="leaf" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
          <path d="M10 1 C 4 6, 4 14, 10 19 C 16 14, 16 6, 10 1 Z M10 5 L10 17" stroke="currentColor" stroke-width="1" fill="none"/>
        </svg>
        <div class="line-decor"></div>
      </div>

      <h1 class="hero-title-main">
        <span class="line-1">{{ $title_line_1 }}</span>
        <span class="line-2">{{ $title_line_2_pre }} <em>{{ $title_line_2_italic }}<svg class="uline" viewBox="0 0 300 10" preserveAspectRatio="none"><path d="M2 7 Q 50 1, 100 5 T 200 5 T 298 4"/></svg></em><span class="accent-dot"></span></span>
      </h1>

      <p class="kicker">
        {!! $kicker !!}
      </p>

      <div class="hero-cta">
        @if(!empty($cta_1) && !empty($cta_1['url']))
        <a class="hero-cta-btn-primary" href="{{ $cta_1['url'] }}" target="{{ $cta_1['target'] ?? '_self' }}">
          {{ $cta_1['title'] }}
          <span class="arrow">→</span>
        </a>
        @endif
        @if(!empty($cta_2) && !empty($cta_2['url']))
        <a class="hero-cta-btn-secondary" href="{{ $cta_2['url'] }}" target="{{ $cta_2['target'] ?? '_self' }}">
          <span class="pulse"></span>
          {{ $cta_2['title'] }}
        </a>
        @endif
      </div>

      <div class="trust-row">
        @if(!empty($trust_items))
            @foreach($trust_items as $item)
                <div class="trust-item"><span class="t-dot"></span> {!! $item['text'] !!}</div>
            @endforeach
        @endif
      </div>
    </div>

    <div class="scroll-hint">
      <span>Прокрутите</span>
      <div class="line-v"></div>
    </div>
  </section>
</div>

<script>
  /* ─── Cursor-reactive glow + blob parallax ─── */
  (function(){
    const hero = document.getElementById('hero');
    const glow = document.getElementById('cursorGlow');
    if (!hero || !glow) return;
    const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (reduced) return;

    const blobs = hero.querySelectorAll('.blob');
    let targetX = 0, targetY = 0, curX = 0, curY = 0;
    let raf = null;

    function tick(){
      curX += (targetX - curX) * 0.08;
      curY += (targetY - curY) * 0.08;
      blobs.forEach((b, i) => {
        const k = (i + 1) * 8;
        b.style.setProperty('--px', (curX * k) + 'px');
        b.style.setProperty('--py', (curY * k) + 'px');
        // layer cursor shift on top of existing keyframe transform
        b.style.translate = `${curX * k}px ${curY * k}px`;
      });
      raf = requestAnimationFrame(tick);
    }

    hero.addEventListener('pointermove', (e) => {
      const r = hero.getBoundingClientRect();
      const x = e.clientX - r.left;
      const y = e.clientY - r.top;
      targetX = (x / r.width  - 0.5);
      targetY = (y / r.height - 0.5);
      glow.style.opacity = '1';
      glow.style.left = x + 'px';
      glow.style.top  = y + 'px';
      if (!raf) tick();
    });
    hero.addEventListener('pointerleave', () => {
      targetX = 0; targetY = 0;
      glow.style.opacity = '0';
    });
  })();
</script>
