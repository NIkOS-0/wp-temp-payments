@php
  $bid = $block['id'] ?? uniqid();
  $items = !empty($block['items']) ? $block['items'] : [];
  $total = count($items);
@endphp

<style>
/* ── Ambient Blobs ── */
@keyframes fp-float-a {
  0%,100% { transform: translate(0,0) scale(1); }
  33%      { transform: translate(40px,-30px) scale(1.06); }
  66%      { transform: translate(-20px,20px) scale(0.96); }
}
@keyframes fp-float-b {
  0%,100% { transform: translate(0,0) scale(1); }
  40%      { transform: translate(-50px,25px) scale(1.08); }
  70%      { transform: translate(30px,-15px) scale(0.94); }
}
@keyframes fp-float-c {
  0%,100% { transform: translate(0,0) scale(1); }
  50%      { transform: translate(20px,40px) scale(1.04); }
}
@keyframes fp-float-d {
  0%,100% { transform: translate(0,0) scale(1); }
  45%      { transform: translate(-30px,-20px) scale(1.07); }
  80%      { transform: translate(15px,10px) scale(0.97); }
}
.fp-blob { position:absolute; border-radius:50%; filter:blur(90px); pointer-events:none; }
.fp-blob-1 { width:540px; height:540px; top:-160px; left:-120px;
  background:radial-gradient(circle, rgba(239,148,91,0.22) 0%, transparent 65%);
  animation: fp-float-a 18s ease-in-out infinite; }
.fp-blob-2 { width:420px; height:420px; bottom:-80px; right:8%;
  background:radial-gradient(circle, rgba(239,148,91,0.16) 0%, transparent 65%);
  animation: fp-float-b 22s ease-in-out infinite; }
.fp-blob-3 { width:320px; height:320px; top:40%; left:38%;
  background:radial-gradient(circle, rgba(47,61,42,0.13) 0%, transparent 65%);
  animation: fp-float-c 26s ease-in-out infinite; }
.fp-blob-4 { width:280px; height:280px; bottom:10%; left:20%;
  background:radial-gradient(circle, rgba(239,148,91,0.10) 0%, transparent 65%);
  animation: fp-float-d 20s ease-in-out infinite; }
.fp-blob-br { width:360px; height:360px; bottom:-60px; right:-60px;
  background:radial-gradient(circle, rgba(239,148,91,0.18) 0%, transparent 65%);
  animation: fp-float-b 24s ease-in-out infinite reverse; }
.fp-ring { position:absolute; border-radius:50%; border:1px solid rgba(239,148,91,0.09); pointer-events:none; }
.fp-ring-1 { width:680px; height:680px; top:50%; left:50%; transform:translate(-50%,-50%); }
.fp-ring-2 { width:860px; height:860px; top:50%; left:50%; transform:translate(-50%,-50%); opacity:.5; }
.fp-grain {
  position:absolute; inset:0; pointer-events:none; opacity:.03;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='200'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='200' height='200' filter='url(%23n)'/%3E%3C/svg%3E");
  background-size:200px 200px;
}
/* Shell */
.fp-shell {
  background: rgba(245,239,226,0.78);
  backdrop-filter: blur(18px);
  -webkit-backdrop-filter: blur(18px);
  border: 1px solid rgba(239,148,91,0.15);
  border-radius: 32px;
  box-shadow: 0 8px 40px rgba(42,26,16,0.07), 0 1px 0 rgba(255,255,255,0.7) inset;
  padding: 3rem 2.5rem;
}
@media(min-width:1024px){ .fp-shell { padding: 3.5rem 3.5rem; } }
/* Counter */
.fp-counter { font-family:'Playfair Display',serif; font-size:1rem; color:var(--color-bark-400,#7a6550); letter-spacing:.04em; }
.fp-counter strong { color:var(--color-bark-900,#2A1A10); font-size:1.25rem; }
/* Nav arrow */
.fp-arrow { display:inline-flex; align-items:center; justify-content:center;
  width:44px; height:44px; border-radius:50%;
  border:1.5px solid rgba(42,26,16,0.18); background:rgba(255,255,255,0.6);
  cursor:pointer; transition:all .2s; flex-shrink:0; }
.fp-arrow:hover { background:#2A1A10; border-color:#2A1A10; color:#F5EFE2; }
.fp-arrow svg { transition: inherit; }
.fp-arrow:hover svg { stroke:#F5EFE2; }
/* Dots */
.fp-dot { display:inline-block; border-radius:50px; border:none; padding:0; cursor:pointer; transition:all .3s; }
.fp-dot--active { width:20px; height:6px; background:#2A1A10; }
.fp-dot--idle   { width:6px;  height:6px; background:rgba(42,26,16,0.2); }
/* Mobile compact shell */
@media (max-width: 640px) {
  .fp-shell { padding: 1.5rem 1rem; border-radius: 20px; }
  .fp-shell .fp-stats-row { overflow-x: auto; flex-wrap: nowrap; -ms-overflow-style: none; scrollbar-width: none; }
  .fp-shell .fp-stats-row::-webkit-scrollbar { display: none; }
}
/* Drag cursor */
.fp-carousel-drag { cursor: grab; user-select: none; -webkit-user-select: none; }
.fp-carousel-drag.fp-grabbing { cursor: grabbing; }
/* Scroll reveal */
.fp-reveal {
  opacity: 0;
  transform: translateY(40px);
  transition: opacity 0.8s cubic-bezier(0.2, 0.8, 0.2, 1), transform 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
  will-change: opacity, transform;
}
.fp-reveal.fp-in-view {
  opacity: 1;
  transform: translateY(0);
}
</style>

<!-- ── Ambient canvas ── -->
<div class="relative overflow-hidden" style="background:linear-gradient(135deg,#f9f3e8 0%,#f0e8d4 50%,#ede0cc 100%);">
  <!-- Blobs -->
  <div class="fp-blob fp-blob-1"></div>
  <div class="fp-blob fp-blob-2"></div>
  <div class="fp-blob fp-blob-3"></div>
  <div class="fp-blob fp-blob-4"></div>
  <div class="fp-blob fp-blob-br"></div>
  <!-- Rings -->
  <div class="fp-ring fp-ring-1"></div>
  <div class="fp-ring fp-ring-2"></div>
  <!-- Grain -->
  <div class="fp-grain"></div>

  <div class="container-editorial relative z-10 py-20 lg:py-28">
    <div class="fp-shell" id="fp-shell-{{ $bid }}">

      <!-- ── Header ── -->
      <div class="mb-10 lg:mb-12">
        <div class="text-xs font-semibold uppercase tracking-[0.18em] text-terra-500 mb-3 fp-reveal">
          Доказательная медицина · Биохакинг
        </div>
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 relative">
          <!-- Ambient Abstract Doctor Silhouette -->
           
          <!-- <div class="absolute right-[-10px] lg:right-[-20px] bottom-[-30px] lg:bottom-[-20px] w-[240px] lg:w-[300px] h-[300px] lg:h-[380px] pointer-events-none z-0 mix-blend-multiply" style="animation: fp-doc-drift 9s ease-in-out infinite alternate; opacity: 0.85;">
            <svg viewBox="0 0 200 250" width="100%" height="100%">
              <defs>
                <filter id="fpDocBlur" x="-50%" y="-50%" width="200%" height="200%">
                  <feGaussianBlur stdDeviation="1" />
                </filter>
              </defs>
              <g filter="url(#fpDocBlur)">
                <circle cx="100" cy="55" r="38" fill="rgba(120, 210, 210, 0.65)" />
                <path d="M25 250 C 25 145, 65 115, 100 115 C 135 115, 175 145, 175 250 Z" fill="rgba(120, 210, 210, 0.5)" />
                <path d="M 65 120 C 65 185, 135 185, 135 120" fill="none" stroke="rgba(255, 255, 255, 0.7)" stroke-width="6" stroke-linecap="round" />
                <path d="M 100 170 L 100 220" fill="none" stroke="rgba(255, 255, 255, 0.7)" stroke-width="6" stroke-linecap="round" />
              </g>
            </svg>
          </div> 
          --> 
          <style>
            @keyframes fp-doc-drift {
              0%   { transform: translateY(15px) translateX(10px) rotate(-4deg) scale(0.95); }
              100% { transform: translateY(-15px) translateX(-10px) rotate(4deg) scale(1.05); }
            }
          </style> 

          <div class="max-w-xl fp-reveal relative z-10">
            <h2 class="text-[2.25rem] lg:text-[2.75rem] font-bold leading-[1.15] text-bark-900 font-serif mb-3" style="font-family:'Playfair Display',serif;">
              {!! !empty($block['title'])
                ? nl2br(esc_html($block['title']))
                : 'Готовые планы оздоровления — ваш личный <em style="font-style:italic;color:var(--color-terra-500,#EF945B);">«врач» в PDF</em>'
              !!}
            </h2>
            <p class="text-[15px] text-bark-600 leading-relaxed">
              Протоколы питания и приёма БАДов, разработанные врачами интегративной медицины под конкретный запрос.
            </p>
          </div>
          <!-- Stats -->
          <div class="relative z-10 fp-stats-row fp-reveal flex items-stretch rounded-2xl overflow-hidden border border-[rgba(42,26,16,0.12)] bg-[rgba(255,255,255,0.7)] backdrop-blur-lg shrink-0 flex-wrap shadow-md">
            <div class="px-5 py-4 text-center flex-1 min-w-[120px]">
              <span class="block text-2xl font-bold text-bark-900 leading-none mb-1">17</span>
              <span class="text-[11px] font-semibold uppercase tracking-wider text-bark-400">книг</span>
            </div>
            <div class="px-5 py-4 text-center flex-1 min-w-[150px] border-l border-[rgba(42,26,16,0.08)]">
              <span class="block text-2xl font-bold text-bark-900 leading-none mb-1">12 400+</span>
              <span class="text-[11px] font-semibold uppercase tracking-wider text-bark-400">скачиваний</span>
            </div>
            <div class="px-5 py-4 text-center flex-1 min-w-[120px] border-l border-[rgba(42,26,16,0.08)]">
              <span class="block text-2xl font-bold text-bark-900 leading-none mb-1">11%</span>
              <span class="text-[11px] font-semibold uppercase tracking-wider text-bark-400">скидка</span>
            </div>
          </div>
        </div>
      </div>

      <!-- ── Carousel ── -->
      @if($total > 0)
      <div class="relative w-full fp-reveal" id="mpo-wrap-{{ $bid }}">
        <div class="overflow-hidden -mx-2 px-2 pb-3 fp-carousel-drag" id="overflow-{{ $bid }}">
          <div class="flex gap-4 transition-transform duration-500" id="track-{{ $bid }}" style="will-change:transform;">

            @foreach($items as $i => $item)
            @php
              $c_benefits = function_exists('get_field')
                ? ($item->post_type === 'book'
                    ? (get_field('book_features', $item->ID) ?: [])
                    : (get_field('benefits', $item->ID) ?: []))
                : [];
              $c_features = array_map(fn($b) => ['text' => is_array($b) ? ($b['text'] ?? $b['title'] ?? '') : (is_object($b) ? ($b->name ?? $b->post_title ?? '') : $b)], array_slice($c_benefits, 0, 3));
              if (empty($c_features)) {
                $c_features = [
                  ['text' => 'Индивидуальное меню питания'],
                  ['text' => 'Схема приема нутрицевтиков'],
                  ['text' => 'Авторские протоколы восстановления'],
                ];
              }
              $c_item = [
                'id'         => $item->ID,
                'title'      => $item->post_title,
                'url'        => get_permalink($item->ID),
                'type_label' => get_post_type_object($item->post_type)->labels->singular_name ?? 'МПО',
                'image'      => get_the_post_thumbnail_url($item->ID, 'large') ?: '',
                'badge'      => function_exists('get_field') ? (get_field('ps_card_badge', $item->ID) ?: '') : '',
                'features'   => $c_features,
                'price'      => function_exists('get_field') ? (get_field('price', $item->ID) ?: '') : '',
                'price_old'  => function_exists('get_field') ? (get_field('ps_price_old', $item->ID) ?: get_field('book_price_old', $item->ID) ?: '') : '',
                'delivery'   => function_exists('get_field') ? (get_field('ps_delivery_method', $item->ID) ?: get_field('book_delivery_note', $item->ID) ?: '') : '',
              ];
            @endphp
            @include('partials.dtc-card', [
              'item'    => $c_item,
              'variant' => 'dtc-card--carousel opacity-50 scale-[0.96] transition-all duration-300 cursor-pointer',
              'attrs'   => ['data-i' => $i, 'id' => "mpo-card-{$bid}-{$i}"],
            ])
            @endforeach

          </div>
        </div>

        <!-- ── Controls ── -->
        <div class="flex items-center justify-between mt-8 flex-wrap gap-4">
          <!-- Left: arrows + dots + counter -->
          <div class="flex items-center gap-3">
            <button class="fp-arrow" id="prev-{{ $bid }}" aria-label="Назад">
              <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="#2A1A10" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10 3L5 8L10 13"/>
              </svg>
            </button>

            <div class="flex items-center gap-1.5" id="dots-{{ $bid }}"></div>

            <button class="fp-arrow" id="next-{{ $bid }}" aria-label="Вперёд">
              <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="#2A1A10" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 3L11 8L6 13"/>
              </svg>
            </button>

            <div class="fp-counter ml-2" id="counter-{{ $bid }}">
              <strong>01</strong> / {{ str_pad($total, 2, '0', STR_PAD_LEFT) }}
            </div>
          </div>

          <!-- Right: all plans -->
          <a href="/products"
             class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full border-2 border-[rgba(42,26,16,0.25)] text-bark-900 text-sm font-semibold hover:bg-bark-900 hover:text-cream-50 hover:border-bark-900 transition-all duration-200">
            Все продукты
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path d="M3 7h8M8 4l3 3-3 3"/>
            </svg>
          </a>
        </div>
      </div>

      <script>
        (function() {
          const track   = document.getElementById('track-{{ $bid }}');
          if(!track) return;

          const dotsEl   = document.getElementById('dots-{{ $bid }}');
          const counterEl= document.getElementById('counter-{{ $bid }}');
          const prevBtn  = document.getElementById('prev-{{ $bid }}');
          const nextBtn  = document.getElementById('next-{{ $bid }}');
          const cards    = Array.from(track.querySelectorAll('[data-i]'));
          const N        = cards.length;
          const TOTAL_PAD= '{{ str_pad($total, 2, "0", STR_PAD_LEFT) }}';
          const CARD_W   = 252;
          const GAP      = 16;
          const PEEK     = 28;

          let current = 0;

          function pad(n) { return String(n+1).padStart(2,'0'); }

          function updateClasses() {
            cards.forEach((c, i) => {
              c.classList.remove('opacity-100','scale-100','opacity-[0.78]','scale-[0.97]','opacity-50','scale-[0.96]');
              if (i === current)                              c.classList.add('opacity-100','scale-100');
              else if (i === current-1 || i === current+1)   c.classList.add('opacity-[0.78]','scale-[0.97]');
              else                                            c.classList.add('opacity-50','scale-[0.96]');
            });
          }

          function buildDots() {
            dotsEl.innerHTML = '';
            for (let i = 0; i < N; i++) {
              const d = document.createElement('button');
              d.className = i === current ? 'fp-dot fp-dot--active' : 'fp-dot fp-dot--idle';
              d.addEventListener('click', () => goTo(i));
              dotsEl.appendChild(d);
            }
          }

          function updateCounter() {
            if (counterEl) counterEl.innerHTML = `<strong>${pad(current)}</strong> / ${TOTAL_PAD}`;
          }

          function goTo(idx, instant) {
            if (idx < 0) idx = N - 1;
            else if (idx >= N) idx = 0;
            current = idx;

            let maxOffset = (N * (CARD_W + GAP)) - track.parentElement.offsetWidth + PEEK;
            if (maxOffset < 0) maxOffset = 0;
            let offset = current * (CARD_W + GAP) - PEEK;
            if (offset < 0) offset = 0;
            if (offset > maxOffset) offset = maxOffset;

            track.style.transition = instant ? 'none' : 'transform 0.5s cubic-bezier(0.77,0,0.175,1)';
            track.style.transform = `translateX(-${offset}px)`;

            updateClasses();
            updateCounter();

            Array.from(dotsEl.children).forEach((d, i) => {
              d.className = i === current ? 'fp-dot fp-dot--active' : 'fp-dot fp-dot--idle';
            });
          }

          if(prevBtn) prevBtn.addEventListener('click', () => goTo(current - 1));
          if(nextBtn) nextBtn.addEventListener('click', () => goTo(current + 1));

          cards.forEach((c, i) => {
            c.addEventListener('click', (e) => {
              if (i !== current) {
                e.preventDefault();
                goTo(i);
              }
            });
          });

          buildDots();
          goTo(0, true);

          // ── Touch swipe ──
          const overflowEl = document.getElementById('overflow-{{ $bid }}');
          let tsX = 0;
          overflowEl.addEventListener('touchstart', e => {
            tsX = e.changedTouches[0].clientX;
          }, {passive: true});
          overflowEl.addEventListener('touchend', e => {
            const dx = e.changedTouches[0].clientX - tsX;
            if (Math.abs(dx) > 36) { dx < 0 ? goTo(current + 1) : goTo(current - 1); }
          }, {passive: true});

          // ── Mouse drag ──
          let mdX = 0, isDrag = false, hasDragged = false;
          overflowEl.addEventListener('mousedown', e => {
            if (e.button !== 0) return;
            mdX = e.clientX; isDrag = true; hasDragged = false;
            overflowEl.classList.add('fp-grabbing');
            e.preventDefault();
          });
          overflowEl.addEventListener('mousemove', e => {
            if (!isDrag) return;
            if (Math.abs(e.clientX - mdX) > 6) hasDragged = true;
          });
          window.addEventListener('mouseup', e => {
            if (!isDrag) return;
            isDrag = false;
            overflowEl.classList.remove('fp-grabbing');
            const dx = e.clientX - mdX;
            if (hasDragged && Math.abs(dx) > 36) { dx < 0 ? goTo(current + 1) : goTo(current - 1); }
          });
          overflowEl.addEventListener('click', e => {
            if (hasDragged) { e.preventDefault(); e.stopPropagation(); hasDragged = false; }
          }, true);
          overflowEl.addEventListener('mouseleave', () => {
            if (isDrag) { isDrag = false; overflowEl.classList.remove('fp-grabbing'); }
          });

          // ── Scroll Reveal ──
          const shellEl = document.getElementById('fp-shell-{{ $bid }}');
          if (shellEl) {
            const reveals = shellEl.querySelectorAll('.fp-reveal');
            const observer = new IntersectionObserver((entries) => {
              entries.forEach(entry => {
                if (entry.isIntersecting) {
                  reveals.forEach((el, index) => {
                    setTimeout(() => {
                      el.classList.add('fp-in-view');
                    }, index * 100);
                  });
                } else {
                  reveals.forEach(el => el.classList.remove('fp-in-view'));
                }
              });
            }, { threshold: 0.15 });
            observer.observe(shellEl);
          }
        })();
      </script>

      @else
        <div class="p-8 border-2 border-dashed border-[rgba(42,26,16,0.12)] rounded-2xl text-center">
          <p class="text-body">В этом блоке пока нет продуктов. Выберите их в редакторе страницы.</p>
        </div>
      @endif

    </div><!-- /.fp-shell -->
  </div><!-- /.container-editorial -->
</div><!-- /.ambient canvas -->
