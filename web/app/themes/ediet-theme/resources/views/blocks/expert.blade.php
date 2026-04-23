<style>
/* ── Expert: Ambient Blobs ── */
@keyframes ex-float-a {
  0%,100% { transform: translate(0,0) scale(1); }
  33%      { transform: translate(40px,-30px) scale(1.06); }
  66%      { transform: translate(-20px,20px) scale(0.96); }
}
@keyframes ex-float-b {
  0%,100% { transform: translate(0,0) scale(1); }
  40%      { transform: translate(-50px,25px) scale(1.08); }
  70%      { transform: translate(30px,-15px) scale(0.94); }
}
@keyframes ex-float-c {
  0%,100% { transform: translate(0,0) scale(1); }
  50%      { transform: translate(20px,40px) scale(1.04); }
}
@keyframes ex-float-d {
  0%,100% { transform: translate(0,0) scale(1); }
  45%      { transform: translate(-30px,-20px) scale(1.07); }
  80%      { transform: translate(15px,10px) scale(0.97); }
}
.ex-blob { position:absolute; border-radius:50%; filter:blur(90px); pointer-events:none; }
.ex-blob-1 { width:540px; height:540px; top:-160px; left:-120px;
  background:radial-gradient(circle, rgba(239,148,91,0.22) 0%, transparent 65%);
  animation: ex-float-a 18s ease-in-out infinite; }
.ex-blob-2 { width:420px; height:420px; bottom:-80px; right:8%;
  background:radial-gradient(circle, rgba(239,148,91,0.16) 0%, transparent 65%);
  animation: ex-float-b 22s ease-in-out infinite; }
.ex-blob-3 { width:320px; height:320px; top:40%; left:38%;
  background:radial-gradient(circle, rgba(47,61,42,0.13) 0%, transparent 65%);
  animation: ex-float-c 26s ease-in-out infinite; }
.ex-blob-4 { width:280px; height:280px; bottom:10%; left:20%;
  background:radial-gradient(circle, rgba(239,148,91,0.10) 0%, transparent 65%);
  animation: ex-float-d 20s ease-in-out infinite; }
.ex-blob-br { width:360px; height:360px; bottom:-60px; right:-60px;
  background:radial-gradient(circle, rgba(239,148,91,0.18) 0%, transparent 65%);
  animation: ex-float-b 24s ease-in-out infinite reverse; }
.ex-ring { position:absolute; border-radius:50%; border:1px solid rgba(239,148,91,0.09); pointer-events:none; }
.ex-ring-1 { width:680px; height:680px; top:50%; left:50%; transform:translate(-50%,-50%); }
.ex-ring-2 { width:860px; height:860px; top:50%; left:50%; transform:translate(-50%,-50%); opacity:.5; }
.ex-grain {
  position:absolute; inset:0; pointer-events:none; opacity:.03;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='200'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='200' height='200' filter='url(%23n)'/%3E%3C/svg%3E");
  background-size:200px 200px;
}
/* Shell */
.ex-shell {
  background: rgba(245,239,226,0.78);
  backdrop-filter: blur(18px);
  -webkit-backdrop-filter: blur(18px);
  border: 1px solid rgba(239,148,91,0.15);
  border-radius: 32px;
  box-shadow: 0 8px 40px rgba(42,26,16,0.07), 0 1px 0 rgba(255,255,255,0.7) inset;
  padding: 3rem 2.5rem;
}
@media(min-width:1024px){ .ex-shell { padding: 3.5rem 3.5rem; } }

/* Expert Photo */
.ex-photo-wrap {
  width: 200px; height: 200px;
  border-radius: 2rem;
  overflow: hidden;
  position: relative;
  box-shadow: 0 8px 32px rgba(42,26,16,0.10), 0 1px 0 rgba(255,255,255,0.5) inset;
  border: 2px solid rgba(239,148,91,0.18);
  flex-shrink: 0;
}
.ex-photo-wrap img {
  width: 100%; height: 100%; object-fit: cover;
}
.ex-verified {
  position:absolute; bottom:-4px; right:-4px;
  width:38px; height:38px;
  background: linear-gradient(135deg, #22c55e, #16a34a);
  border-radius:50%;
  border:3.5px solid rgba(245,239,226,0.9);
  display:flex; align-items:center; justify-content:center;
  box-shadow: 0 4px 12px rgba(22,163,74,0.3);
  z-index:5;
}

/* Stats strip */
.ex-stats-strip {
  display: flex; align-items: stretch; border-radius: 1rem; overflow: hidden;
  border: 1px solid rgba(42,26,16,0.10); background: rgba(255,255,255,0.6);
  box-shadow: 0 2px 8px rgba(42,26,16,0.04);
  flex-wrap: wrap;
}
.ex-stat {
  padding: 1rem 1.25rem; text-align: center; flex: 1; min-width: 110px;
}
.ex-stat + .ex-stat {
  border-left: 1px solid rgba(42,26,16,0.08);
}
.ex-stat-value {
  display: block; font-size: 1.5rem; font-weight: 700; color: #2A1A10; line-height: 1; margin-bottom: 4px;
}
.ex-stat-label {
  font-size: 10.5px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: #A89F8B;
}

/* Tag badges */
.ex-tag {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 6px 14px; border-radius: 50px;
  font-size: 12px; font-weight: 600;
  background: rgba(255,255,255,0.7);
  border: 1px solid rgba(42,26,16,0.08);
  color: #4a3020;
  backdrop-filter: blur(6px);
}

/* Quote */
.ex-quote {
  position: relative;
  padding: 1.25rem 1.5rem;
  background: rgba(255,255,255,0.5);
  border-radius: 16px;
  border: 1px solid rgba(42,26,16,0.06);
  font-style: italic;
  color: #6a5040;
  line-height: 1.7;
}
.ex-quote::before {
  content: '\201C';
  position: absolute;
  top: -6px; left: 16px;
  font-family: 'Playfair Display', serif;
  font-size: 48px;
  color: rgba(239,148,91,0.3);
  line-height: 1;
}

/* CTA */
.ex-cta {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 12px 28px; border-radius: 50px;
  background: #2A1A10; color: #F5EFE2;
  font-weight: 700; font-size: 14px;
  border: none; cursor: pointer;
  transition: all 0.2s;
  text-decoration: none;
}
.ex-cta:hover {
  background: #3A2418;
  transform: translateY(-1px);
  box-shadow: 0 6px 20px rgba(42,26,16,0.18);
}

/* Link ghost */
.ex-link-ghost {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 10px 24px; border-radius: 50px;
  border: 2px solid rgba(42,26,16,0.25);
  color: #2A1A10; font-weight: 600; font-size: 13px;
  text-decoration: none; transition: all 0.2s;
}
.ex-link-ghost:hover {
  background: #2A1A10; color: #F5EFE2; border-color: #2A1A10;
}

/* Layout Grid */
.ex-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 2.5rem;
  align-items: start;
}
@media (min-width: 1024px) {
  .ex-grid {
    grid-template-columns: 260px 1fr;
    gap: 4rem;
  }
}
</style>

<!-- ── Ambient canvas ── -->
<div class="relative overflow-hidden" style="background:linear-gradient(135deg,#f9f3e8 0%,#f0e8d4 50%,#ede0cc 100%);">
  <!-- Blobs -->
  <div class="ex-blob ex-blob-1"></div>
  <div class="ex-blob ex-blob-2"></div>
  <div class="ex-blob ex-blob-3"></div>
  <div class="ex-blob ex-blob-4"></div>
  <div class="ex-blob ex-blob-br"></div>
  <!-- Rings -->
  <div class="ex-ring ex-ring-1"></div>
  <div class="ex-ring ex-ring-2"></div>
  <!-- Grain -->
  <div class="ex-grain"></div>

  <div class="container-editorial relative z-10 py-20 lg:py-28">
    <div class="ex-shell">

      <!-- ── Header ── -->
      <div class="mb-10 lg:mb-12">
        <div class="text-xs font-semibold uppercase tracking-[0.18em] text-terra-500 mb-3">
          Наша команда · Интегративная медицина
        </div>
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
          <div class="max-w-xl">
            <h2 class="text-[2.25rem] lg:text-[2.75rem] font-bold leading-[1.15] text-bark-900 mb-3" style="font-family:'Playfair Display',serif;">
              {!! !empty($block['main_title'])
                ? nl2br(esc_html($block['main_title']))
                : 'Почему нам <em style="font-style:italic;color:var(--color-terra-500,#EF945B);">доверяют?</em>'
              !!}
            </h2>
          </div>
          @if(!empty($block['main_link']))
            <a href="{{ $block['main_link']['url'] }}" target="{{ $block['main_link']['target'] }}" class="ex-link-ghost shrink-0">
              {{ $block['main_link']['title'] }}
              <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 7h8M8 4l3 3-3 3"/>
              </svg>
            </a>
          @endif
        </div>
      </div>

      <!-- ── Main Expert Card ── -->
      <div class="ex-grid">

        <!-- Left: Photo + Name + Tags -->
        <div class="flex flex-col items-center text-center">
          <div class="relative mb-6">
            <div class="ex-photo-wrap">
              @if(!empty($block['photo']['url']))
                <img src="{{ $block['photo']['url'] }}" alt="{{ !empty($block['name']) ? $block['name'] : 'Expert' }}">
              @else
                <div class="w-full h-full bg-cream-200 flex items-center justify-center">
                  <svg class="w-20 h-20 text-bark-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                </div>
              @endif
            </div>
            <div class="ex-verified">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
            </div>
          </div>

          <h3 class="text-lg font-bold text-bark-900 mb-1" style="font-family:'Playfair Display',serif;">
            {{ !empty($block['name']) ? $block['name'] : 'Имя Эксперта' }}
          </h3>
          @if(!empty($block['role']))
            <p class="text-[11px] font-semibold uppercase tracking-[0.12em] text-bark-400 mb-5">{{ $block['role'] }}</p>
          @endif

          <div class="flex flex-col gap-2 w-full">
            @if(!empty($block['tag1']))
              <span class="ex-tag justify-center">{{ $block['tag1'] }}</span>
            @endif
            @if(!empty($block['tag2']))
              <span class="ex-tag justify-center">{{ $block['tag2'] }}</span>
            @endif
          </div>
        </div>

        <!-- Right: Bio + Stats + CTA -->
        <div class="flex flex-col gap-8">
          @if(!empty($block['content_title']))
            <h4 class="text-xl lg:text-2xl font-bold text-bark-900 leading-snug" style="font-family:'Playfair Display',serif;">
              {{ $block['content_title'] }}
            </h4>
          @endif

          <div class="text-[15px] text-bark-600 leading-relaxed font-medium">
            {!! !empty($block['text']) ? $block['text'] : '' !!}
          </div>

          @if(!empty($block['quote']))
            <div class="ex-quote">
              {{ $block['quote'] }}
            </div>
          @endif

          @if(!empty($block['stats']))
            <div class="ex-stats-strip">
              @foreach($block['stats'] as $stat)
                <div class="ex-stat">
                  <span class="ex-stat-value" style="color: {{ !empty($stat['color']) ? $stat['color'] : '#2A1A10' }};">{{ $stat['value'] }}</span>
                  <span class="ex-stat-label">{{ $stat['label'] }}</span>
                </div>
              @endforeach
            </div>
          @endif

          @if(!empty($block['cta']))
            <div class="pt-2">
              <a href="{{ $block['cta']['url'] }}" class="ex-cta">
                {{ $block['cta']['title'] }}
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M3 7h8M8 4l3 3-3 3"/>
                </svg>
              </a>
            </div>
          @endif
        </div>

      </div>
    </div><!-- /.ex-shell -->
  </div><!-- /.container-editorial -->
</div><!-- /.ambient canvas -->
