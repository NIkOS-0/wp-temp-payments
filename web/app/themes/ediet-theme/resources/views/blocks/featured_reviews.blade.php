@php
  $revs = $custom_reviews ?? [];
  if (empty($revs)) {
    $revs = get_posts([
      'post_type'      => 'review',
      'posts_per_page' => 5,
      'post_status'    => 'publish',
    ]);
  }

  $bid = $bid ?? uniqid();
@endphp

<style>
/* ═══════════════════════════════════════════════════════
   REVIEWS BLOCK — teal gradient palette, scoped
═══════════════════════════════════════════════════════ */
#rv-{{ $bid }} {
  --rv-bg-0:     #e9f2ef;
  --rv-bg-1:     #c7dcd4;
  --rv-bg-2:     #9bbfb4;
  --rv-bg-3:     #6fa396;
  --rv-deep:     #2d5a4f;
  --rv-ink:      #0f2e27;
  --rv-lime:     #c4e3a8;
  --rv-gold:     #EF945B;
  --rv-surface:  rgba(255,255,255,0.92);
  --rv-border:   rgba(15,46,39,0.14);
  --rv-pill:     9999px;

  position: relative;
  overflow: hidden;
  padding: 120px 32px 140px;
  isolation: isolate;
  background:
    linear-gradient(180deg,
      var(--rv-bg-0)  0%,
      var(--rv-bg-1) 35%,
      var(--rv-bg-2) 72%,
      var(--rv-bg-3) 100%);
  color: var(--rv-ink);
}

/* Blobs */
#rv-{{ $bid }} .rv-blobs { position:absolute;inset:0;pointer-events:none;z-index:0; }
#rv-{{ $bid }} .rv-blob  { position:absolute;border-radius:50%;filter:blur(90px);mix-blend-mode:multiply;will-change:transform; }
#rv-{{ $bid }} .rv-blob.a { width:680px;height:680px;top:-180px;left:-160px;background:radial-gradient(circle,rgba(45,90,79,.55),transparent 65%);animation:rv-drift-a 24s ease-in-out infinite alternate; }
#rv-{{ $bid }} .rv-blob.b { width:540px;height:540px;bottom:-140px;right:-120px;background:radial-gradient(circle,rgba(196,227,168,.55),transparent 65%);animation:rv-drift-b 30s ease-in-out infinite alternate; }
#rv-{{ $bid }} .rv-blob.c { width:460px;height:460px;top:40%;left:55%;background:radial-gradient(circle,rgba(111,163,150,.5),transparent 65%);animation:rv-drift-c 34s ease-in-out infinite alternate; }
#rv-{{ $bid }} .rv-blob.d { width:320px;height:320px;top:12%;right:14%;background:radial-gradient(circle,rgba(239,148,91,.35),transparent 65%);animation:rv-drift-d 26s ease-in-out infinite alternate; }
@keyframes rv-drift-a { 0%{transform:translate(0,0) scale(1)} 100%{transform:translate(120px,100px) scale(1.1)} }
@keyframes rv-drift-b { 0%{transform:translate(0,0) scale(1.05)} 100%{transform:translate(-120px,-80px) scale(.95)} }
@keyframes rv-drift-c { 0%{transform:translate(-50%,-50%) scale(1)} 100%{transform:translate(-40%,-60%) scale(1.15)} }
@keyframes rv-drift-d { 0%{transform:translate(0,0) scale(1)} 100%{transform:translate(-60px,40px) scale(1.1)} }

/* Grain */
#rv-{{ $bid }} .rv-grain { position:absolute;inset:0;pointer-events:none;z-index:1;opacity:.22;mix-blend-mode:overlay;background-image:url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='260' height='260'><filter id='n'><feTurbulence type='fractalNoise' baseFrequency='0.92' numOctaves='2' seed='7' stitchTiles='stitch'/><feColorMatrix values='0 0 0 0 0.06  0 0 0 0 0.18  0 0 0 0 0.15  0 0 0 0.55 0'/></filter><rect width='100%25' height='100%25' filter='url(%23n)'/></svg>"); }

/* Arc */
#rv-{{ $bid }} .rv-arc { position:absolute;inset:0;z-index:2;pointer-events:none;width:100%;height:100%; }
#rv-{{ $bid }} .rv-arc svg { width:100%;height:100%;display:block;overflow:visible; }
#rv-{{ $bid }} .rv-arc path { fill:none;stroke:rgba(15,46,39,.55);stroke-width:1.6;stroke-linecap:round;stroke-dasharray:4 9;stroke-dashoffset:var(--arc-len,2400);transition:stroke-dashoffset .1s linear; }
#rv-{{ $bid }} .rv-arc .wp { fill:var(--rv-gold);transform-origin:center;transform-box:fill-box;opacity:0;transition:opacity .4s ease,transform .4s ease; }
#rv-{{ $bid }} .rv-arc .wp.on { opacity:1; }
#rv-{{ $bid }} .rv-arc .wp-ring { fill:none;stroke:var(--rv-gold);stroke-width:1;opacity:0;transition:opacity .4s ease; }
#rv-{{ $bid }} .rv-arc .wp-ring.on { opacity:.55; }

/* Inner */
#rv-{{ $bid }} .rv-inner { position:relative;z-index:5;max-width:1240px;margin:0 auto; }

/* Header */
#rv-{{ $bid }} .rv-head { display:flex;align-items:flex-end;justify-content:space-between;gap:32px;flex-wrap:wrap;margin-bottom:56px; }
#rv-{{ $bid }} .rv-eyebrow { display:inline-flex;align-items:center;gap:10px;font-size:11px;font-weight:600;letter-spacing:.22em;text-transform:uppercase;color:var(--rv-deep);margin-bottom:16px; }
#rv-{{ $bid }} .rv-eyebrow::before { content:"";width:28px;height:1px;background:var(--rv-deep);opacity:.6; }
#rv-{{ $bid }} .rv-eyebrow .dot { width:6px;height:6px;border-radius:50%;background:var(--rv-gold); }
#rv-{{ $bid }} .rv-title { font-family:'Playfair Display',Georgia,serif;font-weight:500;font-size:clamp(38px,5.4vw,72px);line-height:1.02;letter-spacing:-.02em;color:var(--rv-ink);max-width:760px;text-wrap:balance; }
#rv-{{ $bid }} .rv-title em { font-style:italic;font-weight:400;color:var(--rv-deep); }
#rv-{{ $bid }} .rv-link { display:inline-flex;align-items:center;gap:10px;font-size:12px;letter-spacing:.2em;text-transform:uppercase;color:var(--rv-ink);text-decoration:none;padding:14px 20px;border:1px solid var(--rv-border);border-radius:9999px;background:rgba(255,255,255,.4);backdrop-filter:blur(8px);transition:all .25s;font-weight:500; }
#rv-{{ $bid }} .rv-link:hover { background:var(--rv-ink);color:#F5EFE2;border-color:var(--rv-ink);transform:translateY(-1px); }

/* Stage */
#rv-{{ $bid }} .rv-stage { position:relative;width:100%;height:560px;display:flex;align-items:center;justify-content:center; }

/* Card */
#rv-{{ $bid }} .rv-card { position:absolute;width:min(100%,920px);display:flex;background:rgba(255,255,255,.92);backdrop-filter:blur(16px);border:1px solid rgba(255,255,255,.7);border-left:4px solid var(--rv-deep);border-radius:28px;padding:14px;overflow:hidden;box-shadow:0 1px 2px rgba(15,46,39,.04),0 40px 80px -40px rgba(15,46,39,.35);transition:transform .65s cubic-bezier(.2,.8,.2,1),opacity .65s ease,filter .65s ease;will-change:transform,opacity,filter; }
#rv-{{ $bid }} .rv-card.is-active { transform:translateX(0) scale(1);opacity:1;filter:blur(0);z-index:20; }
#rv-{{ $bid }} .rv-card.is-prev { transform:translateX(-42%) scale(.86);opacity:.55;filter:blur(2px);z-index:10;pointer-events:none; }
#rv-{{ $bid }} .rv-card.is-next { transform:translateX(42%) scale(.86);opacity:.55;filter:blur(2px);z-index:10;pointer-events:none; }
#rv-{{ $bid }} .rv-card.is-hidden { transform:translateX(0) scale(.7);opacity:0;z-index:1;pointer-events:none; }

/* Media panel */
#rv-{{ $bid }} .rv-media { position:relative;flex:0 0 42%;min-height:420px;border-radius:20px;overflow:hidden;background:linear-gradient(135deg,var(--rv-bg-1),var(--rv-bg-3)); }
#rv-{{ $bid }} .rv-media img { position:absolute;inset:0;width:100%;height:100%;object-fit:cover; }
#rv-{{ $bid }} .rv-media::after { content:"";position:absolute;inset:0;background:radial-gradient(120% 80% at 50% 100%,rgba(15,46,39,.35),transparent 60%);pointer-events:none; }
#rv-{{ $bid }} .rv-placeholder { position:absolute;inset:0;background:repeating-linear-gradient(135deg,rgba(255,255,255,.12) 0 10px,rgba(255,255,255,0) 10px 20px),linear-gradient(135deg,var(--rv-bg-2),var(--rv-deep));display:flex;align-items:center;justify-content:center; }
#rv-{{ $bid }} .rv-play { position:absolute;inset:0;margin:auto;width:76px;height:76px;border-radius:50%;background:rgba(255,255,255,.92);backdrop-filter:blur(6px);display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 10px 30px -10px rgba(15,46,39,.55);transition:transform .3s,background .3s;z-index:3; }
#rv-{{ $bid }} .rv-play:hover { transform:scale(1.08);background:#fff; }
#rv-{{ $bid }} .rv-author-pill { position:absolute;left:16px;right:16px;bottom:16px;display:flex;align-items:center;gap:12px;padding:12px 14px;background:rgba(255,255,255,.94);backdrop-filter:blur(12px);border-radius:16px;box-shadow:0 8px 22px -10px rgba(15,46,39,.45);z-index:3; }
#rv-{{ $bid }} .rv-avatar { width:44px;height:44px;border-radius:50%;background:var(--rv-bg-1);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--rv-deep);font-family:'Playfair Display',Georgia,serif;font-style:italic;font-weight:500;font-size:18px; }
#rv-{{ $bid }} .rv-avatar img { width:100%;height:100%;object-fit:cover;border-radius:50%; }
#rv-{{ $bid }} .rv-author-name { font-weight:600;color:var(--rv-ink);font-size:14px;line-height:1.15; }
#rv-{{ $bid }} .rv-author-diag { font-size:11px;color:var(--rv-deep);margin-top:3px;opacity:.75;letter-spacing:.02em; }

/* Body */
#rv-{{ $bid }} .rv-body { flex:1;padding:38px 44px;display:flex;flex-direction:column;justify-content:center;position:relative;min-height:420px; }
#rv-{{ $bid }} .rv-qmark { position:absolute;top:18px;left:28px;font-family:'Playfair Display',Georgia,serif;font-weight:700;font-size:120px;color:var(--rv-bg-1);line-height:.8;pointer-events:none;user-select:none; }
#rv-{{ $bid }} .rv-ba { display:flex;flex-direction:column;gap:20px;position:relative;z-index:2;margin-top:12px; }
#rv-{{ $bid }} .rv-badge { display:inline-flex;align-items:center;gap:8px;align-self:flex-start;background:rgba(196,227,168,.5);border:1px solid rgba(45,90,79,.2);color:var(--rv-deep);font-size:10.5px;font-weight:600;letter-spacing:.18em;text-transform:uppercase;padding:6px 12px;border-radius:9999px;margin-bottom:4px; }
#rv-{{ $bid }} .rv-row-label { display:flex;align-items:center;gap:8px;font-size:10.5px;letter-spacing:.22em;text-transform:uppercase;font-weight:600;color:var(--rv-deep);opacity:.7;margin-bottom:8px; }
#rv-{{ $bid }} .rv-row-label .d { width:6px;height:6px;border-radius:50%;background:var(--rv-deep);opacity:.4; }
#rv-{{ $bid }} .after-label { opacity:1; }
#rv-{{ $bid }} .after-label .d { background:var(--rv-gold);opacity:1; }
#rv-{{ $bid }} .rv-text-before { font-size:14.5px;line-height:1.55;color:rgba(15,46,39,.72);font-style:italic;padding-right:8px; }
#rv-{{ $bid }} .rv-text-after { font-style:normal;color:var(--rv-ink);font-weight:500;background:rgba(196,227,168,.32);border:1px solid rgba(45,90,79,.12);padding:14px 16px;border-radius:12px;font-size:14.5px;line-height:1.55; }
#rv-{{ $bid }} .rv-text-plain { font-size:15px;line-height:1.6;color:rgba(15,46,39,.82);position:relative;z-index:2;margin-top:18px;max-width:440px; }

/* Controls */
#rv-{{ $bid }} .rv-controls { margin-top:48px;display:flex;align-items:center;justify-content:center;gap:20px; }
#rv-{{ $bid }} .rv-btn { width:54px;height:54px;border-radius:50%;background:rgba(255,255,255,.65);backdrop-filter:blur(10px);border:1px solid var(--rv-border);color:var(--rv-ink);cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .25s; }
#rv-{{ $bid }} .rv-btn:hover { background:var(--rv-ink);color:#F5EFE2;border-color:var(--rv-ink);transform:translateY(-1px); }
#rv-{{ $bid }} .rv-dots { display:flex;align-items:center;gap:10px;padding:10px 18px;background:rgba(255,255,255,.65);backdrop-filter:blur(10px);border-radius:9999px;border:1px solid var(--rv-border); }
#rv-{{ $bid }} .rv-dot { width:8px;height:8px;border-radius:50%;background:rgba(15,46,39,.22);border:none;padding:0;cursor:pointer;transition:all .3s; }
#rv-{{ $bid }} .rv-dot:hover { background:rgba(15,46,39,.45); }
#rv-{{ $bid }} .rv-dot.is-on { width:30px;border-radius:9999px;background:var(--rv-ink); }

/* Video modal — scoped by own ID, not by section */
#rv-modal-{{ $bid }} { display:none;position:fixed;inset:0;background:rgba(0,0,0,.82);z-index:9999;align-items:center;justify-content:center; }
#rv-modal-{{ $bid }}.open { display:flex; }
#rv-modal-{{ $bid }} .rv-modal-inner { position:relative;width:min(90vw,960px);aspect-ratio:16/9;background:#000;border-radius:16px;overflow:hidden; }
#rv-modal-{{ $bid }} .rv-modal-inner iframe,
#rv-modal-{{ $bid }} .rv-modal-inner video { position:absolute;inset:0;width:100%;height:100%;border:none; }
#rv-modal-{{ $bid }} .rv-modal-close { position:absolute;top:-46px;right:0;background:transparent;border:none;color:#fff;cursor:pointer;font-size:28px;line-height:1; }

@media (max-width:820px) {
  #rv-{{ $bid }} { padding:80px 18px 100px; }
  #rv-{{ $bid }} .rv-stage { height:720px; }
  #rv-{{ $bid }} .rv-card { flex-direction:column;padding:10px; }
  #rv-{{ $bid }} .rv-media { flex:0 0 auto;min-height:260px;width:100%; }
  #rv-{{ $bid }} .rv-body { padding:28px 24px 24px;min-height:auto; }
  #rv-{{ $bid }} .rv-card.is-prev,
  #rv-{{ $bid }} .rv-card.is-next { display:none; }
  #rv-{{ $bid }} .rv-qmark { font-size:80px;top:8px;left:16px; }
}
</style>

<section id="rv-{{ $bid }}">

  {{-- Blobs --}}
  <div class="rv-blobs" aria-hidden="true">
    <div class="rv-blob a"></div>
    <div class="rv-blob b"></div>
    <div class="rv-blob c"></div>
    <div class="rv-blob d"></div>
  </div>

  {{-- Grain --}}
  <div class="rv-grain" aria-hidden="true"></div>

  {{-- Scroll arc --}}
  <div class="rv-arc" id="rv-arc-{{ $bid }}" aria-hidden="true">
    <svg viewBox="0 0 1600 900" preserveAspectRatio="none">
      <path id="rv-path-{{ $bid }}" d="M -40 560 Q 360 180, 820 230 T 1660 120"/>
      <g>
        <circle class="wp-ring" data-at="0.10" cx="170" cy="430" r="14"/>
        <circle class="wp"      data-at="0.10" cx="170" cy="430" r="5"/>
        <circle class="wp-ring" data-at="0.28" cx="430" cy="180" r="10"/>
        <circle class="wp"      data-at="0.28" cx="430" cy="180" r="4"/>
        <circle class="wp-ring" data-at="0.46" cx="680" cy="330" r="12"/>
        <circle class="wp"      data-at="0.46" cx="680" cy="330" r="5"/>
        <circle class="wp-ring" data-at="0.62" cx="980" cy="130" r="8"/>
        <circle class="wp"      data-at="0.62" cx="980" cy="130" r="3.5"/>
        <circle class="wp-ring" data-at="0.78" cx="1210" cy="270" r="13"/>
        <circle class="wp"      data-at="0.78" cx="1210" cy="270" r="5"/>
        <circle class="wp-ring" data-at="0.92" cx="1460" cy="70" r="10"/>
        <circle class="wp"      data-at="0.92" cx="1460" cy="70" r="4"/>
      </g>
    </svg>
  </div>

  <div class="rv-inner">

    {{-- Header --}}
    @if(empty($hide_controls))
    <header class="rv-head">
      <div>
        <div class="rv-eyebrow"><span class="dot"></span> Реальные пациенты · 2019–2026</div>
        <h2 class="rv-title">
          {!! !empty($block['title'])
            ? $block['title']
            : 'Истории, которые <em>дают надежду</em>' !!}
        </h2>
      </div>
      <a href="/reviews" class="rv-link">
        Смотреть все отзывы
        <svg viewBox="0 0 14 14" fill="none"><path d="M3 7h8M7.5 3.5L11 7l-3.5 3.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </a>
    </header>
    @endif

    @if(count($revs) > 0)

    {{-- Slider Stage --}}
    <div class="rv-stage" id="rv-stage-{{ $bid }}">
      @foreach($revs as $i => $rev)
        @php
          $type        = get_field('type', $rev->ID) ?: 'text';
          $before_text = get_field('before_text', $rev->ID) ?: get_post_field('post_content', $rev->ID);
          $after_text  = get_field('after_text',  $rev->ID);
          $media_url   = get_field('media',        $rev->ID); // returns url due to return_format
          $video_url   = get_field('video_url',    $rev->ID);
          $author_name = get_the_title($rev->ID);
          $initials    = mb_strtoupper(mb_substr($author_name, 0, 1) . (mb_strpos($author_name, ' ') !== false ? mb_substr($author_name, mb_strpos($author_name, ' ')+1, 1) : ''));
          $diagnosis   = '';
          $rel_diseases = get_field('related_diseases', $rev->ID);
          if (!empty($rel_diseases)) {
            $d0 = is_array($rel_diseases) ? $rel_diseases[0] : $rel_diseases;
            $diagnosis = get_the_title(is_object($d0) ? $d0->ID : $d0);
          }

          // YouTube embed conversion
          $embed_url = '';
          if ($video_url) {
            if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_\-]+)/', $video_url, $m)) {
              $embed_url = 'https://www.youtube.com/embed/' . $m[1] . '?autoplay=1&rel=0';
            } elseif (str_ends_with($video_url, '.mp4')) {
              $embed_url = $video_url;
            }
          }
        @endphp

        <article class="rv-card" data-idx="{{ $i }}" data-embed="{{ $embed_url }}" data-is-mp4="{{ str_ends_with($embed_url, '.mp4') ? '1' : '0' }}">

          {{-- LEFT: Media --}}
          <div class="rv-media">
            @if($media_url)
              <img src="{{ $media_url }}" alt="{{ $author_name }}" loading="lazy">
            @else
              <div class="rv-placeholder">
                <svg width="72" height="72" viewBox="0 0 24 24" fill="rgba(255,255,255,0.5)"><path d="M12 12c2.2 0 4-1.8 4-4s-1.8-4-4-4-4 1.8-4 4 1.8 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
              </div>
            @endif

            @if($type === 'video' && $embed_url)
            <div class="rv-play rv-play-btn" role="button" aria-label="Смотреть видео">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="#2d5a4f" style="margin-left:4px"><path d="M8 5v14l11-7z"/></svg>
            </div>
            @endif

            {{-- Author pill --}}
            <div class="rv-author-pill">
              <div class="rv-avatar">{{ $initials }}</div>
              <div>
                <div class="rv-author-name">{{ $author_name }}</div>
                @if($diagnosis)
                  <div class="rv-author-diag">Диагноз: {{ $diagnosis }}</div>
                @endif
              </div>
            </div>
          </div>

          {{-- RIGHT: Content --}}
          <div class="rv-body">
            <span class="rv-qmark">"</span>

            @if($type === 'before_after')
              <div class="rv-ba" style="position:relative;z-index:2;margin-top:12px;">
                <span class="rv-badge">Результаты до / после</span>
                <div>
                  <div class="rv-row-label"><span class="d"></span> Было</div>
                  <div class="rv-text-before">{!! $before_text !!}</div>
                </div>
                <div>
                  <div class="rv-row-label after-label"><span class="d"></span> Стало</div>
                  <div class="rv-text-after">{!! $after_text !!}</div>
                </div>
              </div>
            @else
              <p class="rv-text-plain" style="position:relative;z-index:2;">{!! $before_text !!}</p>
            @endif
          </div>

        </article>
      @endforeach
    </div>

    {{-- Controls --}}
    <div class="rv-controls">
      <button class="rv-btn rv-prev-{{ $bid }}" aria-label="Предыдущий">
        <svg viewBox="0 0 14 14" fill="none"><path d="M9 2.5L4.5 7 9 11.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </button>
      <div class="rv-dots" id="rv-dots-{{ $bid }}"></div>
      <button class="rv-btn rv-next-{{ $bid }}" aria-label="Следующий">
        <svg viewBox="0 0 14 14" fill="none"><path d="M5 2.5L9.5 7 5 11.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </button>
    </div>

    @else
      <div style="text-align:center;padding:48px;border:1px dashed rgba(15,46,39,.25);border-radius:16px;">
        <p style="color:rgba(15,46,39,.6);">Отзывов пока нет. Добавьте их в WP Admin → Отзывы.</p>
      </div>
    @endif

  </div>{{-- /rv-inner --}}

</section>

{{-- Video Modal --}}
<div class="rv-modal-overlay" id="rv-modal-{{ $bid }}">
  <div class="rv-modal-inner" id="rv-modal-box-{{ $bid }}">
    <button class="rv-modal-close" id="rv-modal-close-{{ $bid }}" aria-label="Закрыть">✕</button>
    <div id="rv-modal-content-{{ $bid }}"></div>
  </div>
</div>

<script>
(function() {
  const BID   = '{{ $bid }}';
  const root  = document.getElementById('rv-' + BID);
  const stage = document.getElementById('rv-stage-' + BID);
  if (!root || !stage) return;

  /* ── Slider ── */
  const cards = Array.from(stage.querySelectorAll('.rv-card'));
  const dotsEl = document.getElementById('rv-dots-' + BID);
  const N = cards.length;
  let current = 0;

  function render() {
    cards.forEach((c, i) => {
      c.classList.remove('is-active','is-prev','is-next','is-hidden');
      if      (i === current)               c.classList.add('is-active');
      else if (i === (current - 1 + N) % N) c.classList.add('is-prev');
      else if (i === (current + 1)     % N) c.classList.add('is-next');
      else                                   c.classList.add('is-hidden');
    });
    Array.from(dotsEl.children).forEach((d, i) =>
      d.classList.toggle('is-on', i === current));
  }

  function go(i) { current = (i + N) % N; render(); }

  // Build dots
  dotsEl.innerHTML = '';
  cards.forEach((_, i) => {
    const d = document.createElement('button');
    d.className = 'rv-dot';
    d.setAttribute('aria-label', 'Отзыв ' + (i+1));
    d.addEventListener('click', () => go(i));
    dotsEl.appendChild(d);
  });

  root.querySelector('.rv-prev-' + BID)?.addEventListener('click', () => go(current - 1));
  root.querySelector('.rv-next-' + BID)?.addEventListener('click', () => go(current + 1));

  // Touch swipe
  let tx0 = 0;
  stage.addEventListener('touchstart', e => tx0 = e.changedTouches[0].screenX, {passive:true});
  stage.addEventListener('touchend',   e => {
    const dx = e.changedTouches[0].screenX - tx0;
    if (dx < -40) go(current + 1);
    if (dx >  40) go(current - 1);
  }, {passive:true});

  // Keyboard
  document.addEventListener('keydown', e => {
    if (e.key === 'ArrowLeft')  go(current - 1);
    if (e.key === 'ArrowRight') go(current + 1);
  });

  render();

  /* ── Video Modal ── */
  const modal        = document.getElementById('rv-modal-' + BID);
  const modalContent = document.getElementById('rv-modal-content-' + BID);
  const modalClose   = document.getElementById('rv-modal-close-' + BID);

  root.querySelectorAll('.rv-play-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const card    = btn.closest('.rv-card');
      const url     = card?.dataset.embed || '';
      const isMp4   = card?.dataset.isMp4 === '1';

      if (!url) return;
      modalContent.innerHTML = isMp4
        ? `<video src="${url}" autoplay controls></video>`
        : `<iframe src="${url}" allow="autoplay; fullscreen" allowfullscreen></iframe>`;

      modal.classList.add('open');
      document.body.style.overflow = 'hidden';
    });
  });

  function closeModal() {
    modal.classList.remove('open');
    modalContent.innerHTML = '';
    document.body.style.overflow = '';
  }

  modalClose?.addEventListener('click', closeModal);
  modal?.addEventListener('click', e => { if (e.target === modal) closeModal(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

  /* ── Scroll-driven arc ── */
  const path = document.getElementById('rv-path-' + BID);
  const arcEl = document.getElementById('rv-arc-' + BID);
  if (!path || !arcEl) return;

  let total = 0, maxP = 0;
  function setLen() {
    total = path.getTotalLength();
    path.style.strokeDasharray = '4 9';
    arcEl.style.setProperty('--arc-len', total);
  }
  setLen();
  window.addEventListener('resize', setLen);

  const wps = Array.from(arcEl.querySelectorAll('.wp, .wp-ring'));

  function onScroll() {
    const rect = root.getBoundingClientRect();
    const vh   = window.innerHeight;
    const raw  = (vh - rect.top) / vh;
    const p    = Math.max(0, Math.min(1, raw));
    if (p > maxP) maxP = p;
    path.style.strokeDashoffset = total * (1 - maxP);
    wps.forEach(el => {
      const at = parseFloat(el.dataset.at || '0');
      el.classList.toggle('on', maxP >= at);
    });
  }

  onScroll();
  window.addEventListener('scroll', onScroll, {passive:true});
})();
</script>
