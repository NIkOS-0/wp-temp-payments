@php 
  $bid = $block['id'] ?? uniqid(); 
  $items = !empty($block['items']) ? $block['items'] : [];
@endphp

<section class="py-16 md:py-24 bg-[#F8F9FA]">
<style>
/* ════════════════════════
   MPO SECTION SCOPED CSS
════════════════════════ */
.mpo-section-{{ $bid }} {
  --bg:      #edf1f7;
  --white:   #ffffff;
  --text:    #111827;
  --sub:     #6b7280;
  --border:  #e2e8f0;
  --accent:  #111827;
  --card-r:  16px;
  
  width: 100%;
  max-width: 1200px;
  padding: 0 20px;
  margin: 0 auto;
}

@media (min-width: 768px) {
  .mpo-section-{{ $bid }} { padding: 0 40px; }
}

.mpo-section-{{ $bid }} * { box-sizing: border-box; }

.mpo-header-row {
  display: grid;
  grid-template-columns: 1fr;
  align-items: center;
  gap: 20px;
  margin-bottom: 40px;
}
@media (min-width: 1024px) {
  .mpo-header-row { grid-template-columns: 1fr auto; gap: 40px; }
}

.mpo-section-title {
  font-size: clamp(22px, 2.8vw, 34px);
  font-weight: 800;
  letter-spacing: -0.025em;
  line-height: 1.18;
  color: var(--text);
  margin-bottom: 12px;
}

.mpo-section-desc {
  font-size: 13.5px;
  font-weight: 400;
  color: var(--sub);
  line-height: 1.65;
  max-width: 360px;
}

.mpo-stats-row {
  display: flex;
  align-items: stretch;
  background: var(--white);
  border-radius: 14px;
  overflow: hidden;
  border: 1px solid var(--border);
  box-shadow: 0 1px 4px rgba(0,0,0,0.04);
  flex-shrink: 0;
  flex-wrap: wrap;
}

.mpo-stat-item {
  padding: 18px 28px;
  text-align: center;
  min-width: 120px;
  flex: 1;
}
@media (min-width: 640px) {
  .mpo-stat-item + .mpo-stat-item { border-left: 1px solid var(--border); }
}

.mpo-stat-n {
  font-size: 26px;
  font-weight: 800;
  letter-spacing: -0.03em;
  color: var(--text);
  line-height: 1;
  display: block;
  margin-bottom: 4px;
}
.mpo-stat-n .rub { font-size: 18px; }

.mpo-stat-l {
  font-size: 10.5px;
  font-weight: 400;
  color: var(--sub);
  line-height: 1.3;
  display: block;
}

/* CAROUSEL */
.mpo-carousel-wrap {
  position: relative;
  width: 100%;
}

.mpo-carousel-overflow {
  overflow: hidden;
  border-radius: 4px;
  margin: 0 -8px;
  padding: 8px 8px 12px;
}

.mpo-carousel-track {
  display: flex;
  gap: 16px;
  transition: transform 0.5s cubic-bezier(0.77, 0, 0.175, 1);
  will-change: transform;
}

/* CARD */
.mpo-card {
  flex: 0 0 252px;
  background: var(--white);
  border-radius: var(--card-r);
  border: 1px solid var(--border);
  overflow: hidden;
  box-shadow: 0 1px 3px rgba(0,0,0,0.05), 0 4px 16px rgba(0,0,0,0.05);
  transition: box-shadow 0.25s ease, transform 0.25s ease, opacity 0.35s ease;
  cursor: pointer;
  opacity: 0.5;
  transform: scale(0.96);
  display: flex;
  flex-direction: column;
}

.mpo-card.active {
  opacity: 1;
  transform: scale(1);
  box-shadow: 0 2px 6px rgba(0,0,0,0.06), 0 12px 36px rgba(0,0,0,0.09);
}
.mpo-card.adj {
  opacity: 0.78;
  transform: scale(0.97);
}
.mpo-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.1); }

.mpo-card-cover {
  height: 156px;
  background: linear-gradient(135deg, #d8dee8 0%, #c8d0dc 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
}
.mpo-card-cover img {
  width: 100%; height: 100%; object-fit: cover;
}

.mpo-placeholder-icon { opacity: 0.45; }
.mpo-placeholder-icon svg { width: 52px; height: 40px; }

.mpo-card-badge {
  position: absolute;
  bottom: 12px; left: 12px;
  background: rgba(17,24,39,0.75);
  backdrop-filter: blur(6px);
  color: #fff;
  font-size: 8.5px;
  font-weight: 700;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  padding: 3px 9px;
  border-radius: 4px;
}

.mpo-card-body {
  padding: 14px 16px 16px;
  flex: 1;
  display: flex;
  flex-direction: column;
}

.mpo-card-title {
  font-size: 15px;
  font-weight: 700;
  letter-spacing: -0.015em;
  color: var(--text);
  margin-bottom: 6px;
  line-height: 1.3;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.mpo-card-desc {
  font-size: 11.5px;
  font-weight: 400;
  color: var(--sub);
  line-height: 1.55;
  margin-bottom: 10px;
  height: 35px; /* approx 2 lines */
  overflow: hidden;
}

.mpo-card-benefits {
  list-style: none;
  display: flex;
  flex-direction: column;
  gap: 5px;
  margin-bottom: 14px;
  flex: 1;
}
.mpo-card-benefits li {
  display: flex;
  align-items: flex-start;
  gap: 7px;
  font-size: 11.5px;
  font-weight: 400;
  color: var(--sub);
  line-height: 1.35;
}
.mpo-bullet {
  width: 16px; height: 16px;
  border-radius: 50%;
  background: var(--bg);
  border: 1px solid var(--border);
  flex-shrink: 0;
  display: flex; align-items: center; justify-content: center;
  margin-top: 1px;
}
.mpo-bullet svg { width: 8px; height: 8px; }

.mpo-card-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding-top: 12px;
  border-top: 1px solid var(--border);
  gap: 8px;
  margin-top: auto;
}

.mpo-price-main {
  font-size: 17px;
  font-weight: 800;
  letter-spacing: -0.02em;
  color: var(--text);
  line-height: 1;
}
.mpo-price-sub {
  font-size: 10px;
  font-weight: 400;
  color: #9ca3af;
  margin-top: 2px;
}

.mpo-buy-btn {
  background: var(--text);
  color: #fff;
  border: none;
  border-radius: 8px;
  padding: 8px 16px;
  font-family: inherit;
  font-size: 12px;
  font-weight: 700;
  cursor: pointer;
  transition: background 0.18s, transform 0.15s;
  letter-spacing: 0.01em;
  white-space: nowrap;
  display: inline-block;
}
.mpo-buy-btn:hover { background: #374151; transform: scale(1.03); color: #fff; }

/* BOTTOM ROW */
.mpo-bottom-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-top: 28px;
  flex-wrap: wrap;
  gap: 20px;
}

.mpo-nav-left {
  display: flex;
  align-items: center;
  gap: 12px;
}

.mpo-arrow {
  width: 38px; height: 38px;
  border-radius: 50%;
  background: var(--white);
  border: 1.5px solid var(--border);
  display: flex; align-items: center; justify-content: center;
  cursor: pointer;
  transition: all 0.18s;
  color: var(--text);
  box-shadow: 0 1px 3px rgba(0,0,0,0.06);
}
.mpo-arrow svg { width: 14px; height: 14px; }
.mpo-arrow:hover:not(:disabled) { background: var(--text); color: #fff; border-color: var(--text); }
.mpo-arrow:disabled { opacity: 0.3; cursor: default; }

.mpo-dots { display: flex; gap: 6px; align-items: center; }
.mpo-dot {
  width: 7px; height: 7px;
  border-radius: 50%;
  background: #d1d5db;
  border: none;
  cursor: pointer;
  transition: all 0.28s;
  padding: 0;
}
.mpo-dot.on {
  background: var(--text);
  width: 22px;
  border-radius: 4px;
}

.mpo-view-all-btn {
  background: transparent;
  border: 1.5px solid var(--border);
  border-radius: 10px;
  padding: 11px 24px;
  font-family: inherit;
  font-size: 13px;
  font-weight: 600;
  color: var(--text);
  cursor: pointer;
  transition: all 0.18s;
  background: var(--white);
  box-shadow: 0 1px 3px rgba(0,0,0,0.05);
  display: inline-block;
}
.mpo-view-all-btn:hover { background: var(--text); color: #fff; border-color: var(--text); }
</style>

  <div class="mpo-section-{{ $bid }}">
    <!-- Header row -->
    <div class="mpo-header-row">
      <div class="mpo-header-left">
        <h2 class="mpo-section-title">{!! !empty($block['title']) ? nl2br(esc_html($block['title'])) : 'Готовые планы оздоровления<br>(МПО) — Ваш личный «врач» в PDF' !!}</h2>
        <p class="mpo-section-desc">Доказательная медицина и биохакинг. Готовые инструкции, протоколы питания и БАДов.</p>
      </div>
      <div class="mpo-stats-row">
        <div class="mpo-stat-item">
          <span class="mpo-stat-n">99</span>
          <span class="mpo-stat-l">планов МПО</span>
        </div>
        <div class="mpo-stat-item">
          <span class="mpo-stat-n">12 400+</span>
          <span class="mpo-stat-l">скачиваний</span>
        </div>
        <div class="mpo-stat-item">
          <span class="mpo-stat-n">до 50 000 <span class="rub">₽</span></span>
          <span class="mpo-stat-l">экономии на анализах</span>
        </div>
      </div>
    </div>

    <!-- Carousel -->
    @if(count($items) > 0)
    <div class="mpo-carousel-wrap">
      <div class="mpo-carousel-overflow" id="overflow-{{ $bid }}">
        <div class="mpo-carousel-track" id="track-{{ $bid }}">

          @foreach($items as $i => $item)
          <div class="mpo-card" data-i="{{ $i }}">
            <div class="mpo-card-cover">
              @if(has_post_thumbnail($item->ID))
                {!! get_the_post_thumbnail($item->ID, 'large') !!}
              @else
                <div class="mpo-placeholder-icon">
                  <svg viewBox="0 0 52 40" fill="none"><rect x="1" y="1" width="50" height="38" rx="4" stroke="#6b7280" stroke-width="1.5"/><path d="M8 30 L18 18 L26 25 L34 14 L44 30" stroke="#6b7280" stroke-width="1.5" stroke-linejoin="round" fill="none"/><circle cx="16" cy="13" r="4" stroke="#6b7280" stroke-width="1.5"/></svg>
                </div>
              @endif
              <div class="mpo-card-badge">{{ get_post_type_object($item->post_type)->labels->singular_name ?? 'Гайд' }}</div>
            </div>
            
            <div class="mpo-card-body">
              <div class="mpo-card-title">{{ $item->post_title }}</div>
              <p class="mpo-card-desc">{{ has_excerpt($item->ID) ? get_the_excerpt($item->ID) : strip_tags(wp_trim_words($item->post_content, 10)) }}</p>
              
              <ul class="mpo-card-benefits">
                 @php
                   // Optionally pull dynamic benefits from ACF group_product_service, or fallback to default benefits
                   $benefits = function_exists('get_field') ? get_field('benefits', $item->ID) : null;
                 @endphp
                 
                 @if(!empty($benefits) && is_array($benefits))
                   @foreach(array_slice($benefits, 0, 3) as $benefit)
                     <li><div class="mpo-bullet"><svg viewBox="0 0 8 8" fill="none"><polyline points="1,4 3,6 7,2" stroke="#9ca3af" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg></div>{{ $benefit['text'] ?? $benefit['title'] ?? 'Преимущество' }}</li>
                   @endforeach
                 @else
                    <li><div class="mpo-bullet"><svg viewBox="0 0 8 8" fill="none"><polyline points="1,4 3,6 7,2" stroke="#9ca3af" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg></div>Индивидуальное меню питания</li>
                    <li><div class="mpo-bullet"><svg viewBox="0 0 8 8" fill="none"><polyline points="1,4 3,6 7,2" stroke="#9ca3af" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg></div>Схема приема нутрицевтиков</li>
                    <li><div class="mpo-bullet"><svg viewBox="0 0 8 8" fill="none"><polyline points="1,4 3,6 7,2" stroke="#9ca3af" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg></div>Авторские протоколы восстановления</li>
                 @endif
              </ul>
              
              <div class="mpo-card-footer">
                <div class="mpo-price-block">
                  <div class="mpo-price-main">{{ function_exists('get_field') ? get_field('price', $item->ID) : '' }} ₽</div>
                  <div class="mpo-price-sub">PDF · Доступ навсегда</div>
                </div>
                <a href="{{ get_permalink($item->ID) }}" class="mpo-buy-btn text-center hover:text-white">КУПИТЬ</a>
              </div>
            </div>
          </div>
          @endforeach

        </div>
      </div>

      <!-- Bottom controls -->
      <div class="mpo-bottom-row">
        <div class="mpo-nav-left">
          <button class="mpo-arrow" id="prev-{{ $bid }}" aria-label="Назад">
            <svg viewBox="0 0 14 14" fill="none"><path d="M9 2.5L4.5 7 9 11.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
          <div class="mpo-dots" id="dots-{{ $bid }}"></div>
          <button class="mpo-arrow" id="next-{{ $bid }}" aria-label="Вперёд">
            <svg viewBox="0 0 14 14" fill="none"><path d="M5 2.5L9.5 7 5 11.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
        </div>
        <a href="/products" class="mpo-view-all-btn text-center hover:text-white">Смотреть все МПО</a>
      </div>
    </div>
    
    <script>
      (function() {
        const track   = document.getElementById('track-{{ $bid }}');
        if(!track) return;
        
        const dotsEl  = document.getElementById('dots-{{ $bid }}');
        const prevBtn = document.getElementById('prev-{{ $bid }}');
        const nextBtn = document.getElementById('next-{{ $bid }}');
        const cards   = Array.from(track.querySelectorAll('.mpo-card'));
        const N       = cards.length;
        const CARD_W  = 252;
        const GAP     = 16;
        const PEEK    = 28; 

        let current = 0;

        function updateClasses() {
          cards.forEach((c, i) => {
            c.classList.remove('active', 'adj');
            if (i === current) c.classList.add('active');
            else if (i === current - 1 || i === current + 1) c.classList.add('adj');
          });
        }

        function buildDots() {
          dotsEl.innerHTML = '';
          for (let i = 0; i < N; i++) {
            const d = document.createElement('button');
            d.className = 'mpo-dot' + (i === current ? ' on' : '');
            d.addEventListener('click', () => goTo(i));
            dotsEl.appendChild(d);
          }
        }

        function goTo(idx, instant) {
          current = Math.max(0, Math.min(idx, N - 1));
          
          let maxOffset = (N * (CARD_W + GAP)) - track.parentElement.offsetWidth + PEEK;
          if (maxOffset < 0) maxOffset = 0;
          
          let offset = current * (CARD_W + GAP) - PEEK;
          if (offset < 0) offset = 0;
          if (offset > maxOffset) offset = maxOffset;
          
          track.style.transition = instant ? 'none' : 'transform 0.5s cubic-bezier(0.77,0,0.175,1)';
          track.style.transform = `translateX(-${offset}px)`;
          
          updateClasses();
          
          Array.from(dotsEl.children).forEach((d, i) => {
            if(i === current) d.classList.add('on');
            else d.classList.remove('on');
          });
          
          if(prevBtn) prevBtn.disabled = current === 0;
          if(nextBtn) nextBtn.disabled = current === N - 1;
        }

        if(prevBtn) prevBtn.addEventListener('click', () => goTo(current - 1));
        if(nextBtn) nextBtn.addEventListener('click', () => goTo(current + 1));

        cards.forEach((c, i) => {
          c.addEventListener('click', (e) => { 
             // Allow clicks on links to pass through
             if(e.target.tagName.toLowerCase() !== 'a' && i !== current) {
                 goTo(i); 
                 e.preventDefault();
             }
          });
        });

        buildDots();
        goTo(0, true);
      })();
    </script>
    @else
      <div class="p-8 border-2 border-dashed border-slate-300 rounded-2xl text-center">
         <p class="text-slate-500 font-bold">В этом блоке пока нет продуктов. Выберите их в редакторе страницы.</p>
      </div>
    @endif
  </div>

</section>
