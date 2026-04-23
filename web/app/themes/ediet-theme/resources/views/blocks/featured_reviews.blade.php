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
  $rv_theme = $rv_theme ?? 'teal'; // 'teal' (default) or 'light'
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

/* Light theme overrides (for product pages) */
@if($rv_theme === 'light')
#rv-{{ $bid }} {
  --rv-bg-0: #fdfcf9;
  --rv-bg-1: #F5EFE2;
  --rv-bg-2: #EBE3D2;
  --rv-bg-3: #F5EFE2;
  --rv-deep: #2A1A10;
  --rv-ink:  #2A1A10;
  --rv-lime: #F5EFE2;
  --rv-border: rgba(42,26,16,0.12);
  background: linear-gradient(180deg, #fdfcf9 0%, #F5EFE2 50%, #fdfcf9 100%);
  padding: 40px 32px 60px;
}
#rv-{{ $bid }} .rv-blob.a { background:radial-gradient(circle,rgba(239,148,91,.18),transparent 65%); }
#rv-{{ $bid }} .rv-blob.b { background:radial-gradient(circle,rgba(239,148,91,.12),transparent 65%); }
#rv-{{ $bid }} .rv-blob.c { background:radial-gradient(circle,rgba(47,61,42,.08),transparent 65%); }
#rv-{{ $bid }} .rv-blob.d { background:radial-gradient(circle,rgba(239,148,91,.10),transparent 65%); }
#rv-{{ $bid }} .rv-grain { opacity:.03; }
#rv-{{ $bid }} .rv-arc { display:none; }
#rv-{{ $bid }} .rv-eyebrow { color:var(--rv-deep); }
#rv-{{ $bid }} .rv-title { color:var(--rv-ink); }
#rv-{{ $bid }} .rv-title em { color:#8a7060; }
#rv-{{ $bid }} .rv-card { border-left-color:#2A1A10; }
#rv-{{ $bid }} .rv-arrow { border-color:rgba(42,26,16,.18);background:rgba(255,255,255,.6); }
#rv-{{ $bid }} .rv-arrow:hover { background:#2A1A10;border-color:#2A1A10; }
#rv-{{ $bid }} .rv-arrow svg { stroke:#2A1A10; }
#rv-{{ $bid }} .rv-arrow:hover svg { stroke:#F5EFE2; }
#rv-{{ $bid }} .rv-dot--active { background:#2A1A10 !important; }
#rv-{{ $bid }} .rv-dot--idle { background:rgba(42,26,16,.25) !important; }
@endif

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
#rv-{{ $bid }} .rv-card { position:absolute;width:min(100%,920px);display:flex;background:rgba(255,255,255,.92);backdrop-filter:blur(16px);border:1px solid rgba(255,255,255,.7);border-radius:28px;padding:14px;overflow:hidden;box-shadow:0 1px 2px rgba(15,46,39,.04),0 40px 80px -40px rgba(15,46,39,.35);transition:transform .65s cubic-bezier(.2,.8,.2,1),opacity .65s ease,filter .65s ease;will-change:transform,opacity,filter; }
#rv-{{ $bid }} .rv-card.is-active { transform:translateX(0) scale(1);opacity:1;filter:blur(0);z-index:20; }
#rv-{{ $bid }} .rv-card.is-prev { transform:translateX(-42%) scale(.86);opacity:.55;filter:blur(2px);z-index:10;pointer-events:none; }
#rv-{{ $bid }} .rv-card.is-next { transform:translateX(42%) scale(.86);opacity:.55;filter:blur(2px);z-index:10;pointer-events:none; }
#rv-{{ $bid }} .rv-card.is-hidden { transform:translateX(0) scale(.7);opacity:0;z-index:1;pointer-events:none; }

/* Media panel */
#rv-{{ $bid }} .rv-media { position:relative;flex:0 0 42%;min-height:420px;border-radius:20px;overflow:hidden;background:linear-gradient(135deg,var(--rv-bg-1),var(--rv-bg-3)); }
#rv-{{ $bid }} .rv-media > img { position:absolute;inset:0;width:100%;height:100%;object-fit:cover; }
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
#rv-{{ $bid }} .rv-badge { display:inline-flex;align-items:center;gap:8px;align-self:flex-start;background:#DCFCE7;border:1px solid rgba(45,90,79,.2);color:var(--rv-deep);font-size:10.5px;font-weight:600;letter-spacing:.18em;text-transform:uppercase;padding:6px 12px;border-radius:9999px;margin-bottom:4px; }
#rv-{{ $bid }} .rv-row-label { display:flex;align-items:center;gap:8px;font-size:10.5px;letter-spacing:.22em;text-transform:uppercase;font-weight:600;color:var(--rv-deep);opacity:.7;margin-bottom:8px; }
#rv-{{ $bid }} .rv-row-label .d { width:6px;height:6px;border-radius:50%;background:var(--rv-deep);opacity:.4; }
#rv-{{ $bid }} .after-label { opacity:1; }
#rv-{{ $bid }} .after-label .d { background:var(--rv-gold);opacity:1; }
#rv-{{ $bid }} .rv-text-before { font-size:14.5px;line-height:1.55;color:rgba(15,46,39,.72);font-style:italic;padding-right:8px; }
#rv-{{ $bid }} .rv-text-after { font-style:normal;color:var(--rv-ink);font-weight:500;background:#DCFCE7;border:1px solid rgba(45,90,79,.12);padding:14px 16px;border-radius:12px;font-size:14.5px;line-height:1.55; }
#rv-{{ $bid }} .rv-text-plain { font-size:15px;line-height:1.6;color:rgba(15,46,39,.82);position:relative;z-index:2;margin-top:18px;max-width:440px; }

/* Controls — fp-style */
#rv-{{ $bid }} .rv-controls { margin-top:48px;display:flex;align-items:center;justify-content:center;flex-wrap:wrap;gap:16px; }
#rv-{{ $bid }} .rv-controls-left { display:flex;align-items:center;gap:12px; }
#rv-{{ $bid }} .rv-arrow { display:inline-flex;align-items:center;justify-content:center;width:44px;height:44px;border-radius:50%;border:1.5px solid rgba(255,255,255,0.45);background:rgba(255,255,255,0.25);backdrop-filter:blur(10px);cursor:pointer;transition:all .2s;flex-shrink:0; }
#rv-{{ $bid }} .rv-arrow:hover { background:rgba(255,255,255,0.9);border-color:rgba(255,255,255,0.9); }
#rv-{{ $bid }} .rv-arrow svg { transition:inherit; }
#rv-{{ $bid }} .rv-dots-wrap { display:flex;align-items:center;gap:6px; }
#rv-{{ $bid }} .rv-dot { display:inline-block;border-radius:50px;border:none;padding:0;cursor:pointer;transition:all .3s; }
#rv-{{ $bid }} .rv-dot--active { width:20px;height:6px;background:#fff; }
#rv-{{ $bid }} .rv-dot--idle   { width:6px;height:6px;background:rgba(255,255,255,.38); }
#rv-{{ $bid }} .rv-counter { font-family:'Playfair Display',Georgia,serif;font-size:1rem;color:rgba(255,255,255,.7);letter-spacing:.04em;white-space:nowrap; }
#rv-{{ $bid }} .rv-counter strong { color:#fff;font-size:1.2rem; }
#rv-{{ $bid }} .rv-all-link { display:inline-flex;align-items:center;gap:8px;padding:11px 22px;border-radius:9999px;border:1.5px solid rgba(255,255,255,.45);color:#fff;font-size:12px;font-weight:600;letter-spacing:.18em;text-transform:uppercase;text-decoration:none;background:rgba(255,255,255,.1);backdrop-filter:blur(10px);transition:all .25s; }
#rv-{{ $bid }} .rv-all-link:hover { background:rgba(255,255,255,.9);color:var(--rv-ink);border-color:rgba(255,255,255,.9); }

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

  @if($rv_theme !== 'light')
  {{-- Blobs --}}
  <div class="rv-blobs" aria-hidden="true">
    <div class="rv-blob a"></div>
    <div class="rv-blob b"></div>
    <div class="rv-blob c"></div>
    <div class="rv-blob d"></div>
  </div>

  {{-- Grain --}}
  <div class="rv-grain" aria-hidden="true"></div>
  @endif

  {{-- Scroll arc --}}
  <div class="rv-arc" id="rv-arc-{{ $bid }}" aria-hidden="true">
    <svg id="rv-svg-{{ $bid }}" viewBox="0 0 1600 900" preserveAspectRatio="none">
      <path id="rv-path-{{ $bid }}" d="M -40 560 Q 360 180, 820 230 T 1660 120"/>
      {{-- Waypoints injected dynamically by JS via getPointAtLength() --}}
      <g id="rv-wps-{{ $bid }}"></g>
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
              <!--<div class="rv-avatar">{{ $initials }}</div>-->
              <div class="rv-avatar">
                <img src="{{ wp_get_upload_dir()['baseurl'] }}/2026/03/e-diet_min_white.png" alt="{{ $author_name }}" loading="lazy">
              </div>
              <div>
                <div class="rv-author-name">{{ $author_name }}</div>
                @if($diagnosis)
                  <!--<div class="rv-author-diag">Диагноз: {{ $diagnosis }}</div>-->
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
      <div class="rv-controls-left">
        <button class="rv-arrow rv-prev-{{ $bid }}" aria-label="Предыдущий">
          <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M10 3L5 8L10 13"/>
          </svg>
        </button>
        <div class="rv-dots-wrap" id="rv-dots-{{ $bid }}"></div>
        <button class="rv-arrow rv-next-{{ $bid }}" aria-label="Следующий">
          <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 3L11 8L6 13"/>
          </svg>
        </button>
      </div>
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

  const counterEl = document.getElementById('rv-counter-' + BID);
  const TOTAL_PAD = String(N).padStart(2, '0');
  function pad(n) { return String(n + 1).padStart(2, '0'); }

  function render() {
    cards.forEach((c, i) => {
      c.classList.remove('is-active','is-prev','is-next','is-hidden');
      if      (i === current)               c.classList.add('is-active');
      else if (i === (current - 1 + N) % N) c.classList.add('is-prev');
      else if (i === (current + 1)     % N) c.classList.add('is-next');
      else                                   c.classList.add('is-hidden');
    });
    Array.from(dotsEl.children).forEach((d, i) =>
      d.className = 'rv-dot ' + (i === current ? 'rv-dot--active' : 'rv-dot--idle'));
    if (counterEl) counterEl.innerHTML = `<strong>${pad(current)}</strong> / ${TOTAL_PAD}`;
  }

  function go(i) { current = (i + N) % N; render(); }

  // Build dots
  dotsEl.innerHTML = '';
  cards.forEach((_, i) => {
    const d = document.createElement('button');
    d.className = 'rv-dot ' + (i === 0 ? 'rv-dot--active' : 'rv-dot--idle');
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
  const path   = document.getElementById('rv-path-' + BID);
  const arcEl  = document.getElementById('rv-arc-' + BID);
  const wpsG   = document.getElementById('rv-wps-' + BID);
  if (!path || !arcEl || !wpsG) return;

  const NS = 'http://www.w3.org/2000/svg';

  // Waypoint definitions: progress 0-1 along the path
  const WP_ATS  = [0.10, 0.22, 0.36, 0.50, 0.65, 0.80, 0.93];
  const WP_RINNER = [5, 4, 5.5, 4, 5, 4.5, 5];
  const WP_ROUTER = [13, 10, 14, 10, 12, 11, 13];

  let total = 0, maxP = 0;
  let wpDots = []; // {ring, dot, at}

  function buildWaypoints() {
    wpsG.innerHTML = '';
    wpDots = [];
    if (!total) return;

    WP_ATS.forEach((at, i) => {
      const pt = path.getPointAtLength(at * total);

      // Outer ring with pulse
      const ring = document.createElementNS(NS, 'circle');
      ring.setAttribute('cx', pt.x);
      ring.setAttribute('cy', pt.y);
      ring.setAttribute('r',  WP_ROUTER[i]);
      ring.setAttribute('fill', 'none');
      ring.setAttribute('stroke', '#EF945B');
      ring.setAttribute('stroke-width', '1');
      ring.style.opacity = '0';
      ring.style.transition = 'opacity .4s ease, transform .5s ease';
      ring.style.transformOrigin = `${pt.x}px ${pt.y}px`;
      ring.style.transformBox = 'fill-box';
      wpsG.appendChild(ring);

      // Inner filled dot
      const dot = document.createElementNS(NS, 'circle');
      dot.setAttribute('cx', pt.x);
      dot.setAttribute('cy', pt.y);
      dot.setAttribute('r',  WP_RINNER[i]);
      dot.setAttribute('fill', '#EF945B');
      dot.style.opacity = '0';
      dot.style.transform = 'scale(0)';
      dot.style.transition = 'opacity .35s ease, transform .4s cubic-bezier(.34,1.56,.64,1)';
      dot.style.transformOrigin = `${pt.x}px ${pt.y}px`;
      dot.style.transformBox = 'fill-box';
      wpsG.appendChild(dot);

      wpDots.push({ ring, dot, at });
    });
  }

  function setLen() {
    total = path.getTotalLength();
    path.style.strokeDasharray = '4 9';
    arcEl.style.setProperty('--arc-len', total);
    buildWaypoints();
    onScroll();
  }

  setLen();
  window.addEventListener('resize', setLen);

  function onScroll() {
    const rect = root.getBoundingClientRect();
    const vh   = window.innerHeight;
    const raw  = (vh - rect.top) / vh;
    const p    = Math.max(0, Math.min(1, raw));
    if (p > maxP) maxP = p;

    // Draw arc
    path.style.strokeDashoffset = total * (1 - maxP);

    // Reveal each waypoint as draw passes it
    wpDots.forEach(({ ring, dot, at }) => {
      const show = maxP >= at;
      if (show) {
        ring.style.opacity = '0.55';
        ring.style.transform = 'scale(1)';
        dot.style.opacity  = '1';
        dot.style.transform = 'scale(1)';
      } else {
        ring.style.opacity = '0';
        ring.style.transform = 'scale(0.6)';
        dot.style.opacity  = '0';
        dot.style.transform = 'scale(0)';
      }
    });
  }

  onScroll();
  window.addEventListener('scroll', onScroll, {passive:true});

})();
</script>
