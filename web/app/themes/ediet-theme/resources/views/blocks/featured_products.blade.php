@php
  $bid = $block['id'] ?? uniqid();
  $items = !empty($block['items']) ? $block['items'] : [];
@endphp

<section class="section-canvas">
  <div class="container-editorial">
    <!-- Header row -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-10">
      <div>
        <h2 class="heading-display text-balance">{!! !empty($block['title']) ? nl2br(esc_html($block['title'])) : 'Готовые планы оздоровления<br>(МПО) — Ваш личный «врач» в PDF' !!}</h2>
        <p class="text-body mt-2">Доказательная медицина и биохакинг. Готовые инструкции, протоколы питания и БАДов.</p>
      </div>
      <!-- Stats -->
      <div class="flex items-stretch bg-[var(--surface)] rounded-2xl overflow-hidden border border-[var(--border)] shadow-sm shrink-0 flex-wrap">
        <div class="card p-5 text-center flex-1 min-w-[110px]">
          <span class="block text-2xl font-bold text-bark-900 leading-none mb-1">99</span>
          <span class="text-overline">планов МПО</span>
        </div>
        <div class="card p-5 text-center flex-1 min-w-[110px] border-l border-[var(--border)]">
          <span class="block text-2xl font-bold text-bark-900 leading-none mb-1">12 400+</span>
          <span class="text-overline">скачиваний</span>
        </div>
        <div class="card p-5 text-center flex-1 min-w-[130px] border-l border-[var(--border)]">
          <span class="block text-2xl font-bold text-bark-900 leading-none mb-1">до 50 000 ₽</span>
          <span class="text-overline">экономии на анализах</span>
        </div>
      </div>
    </div>

    <!-- Carousel -->
    @if(count($items) > 0)
    <div class="relative w-full" id="mpo-wrap-{{ $bid }}">
      <div class="overflow-hidden rounded-sm -mx-2 px-2 pb-3" id="overflow-{{ $bid }}">
        <div class="flex gap-4 transition-transform duration-500" id="track-{{ $bid }}" style="will-change:transform;">

          @foreach($items as $i => $item)
          <div class="card-hover flex flex-col p-5 gap-3 flex-none w-[252px] cursor-pointer opacity-50 scale-[0.96] transition-all duration-300" data-i="{{ $i }}" id="mpo-card-{{ $bid }}-{{ $i }}">
            <!-- Cover image -->
            <div class="rounded-lg aspect-square overflow-hidden relative bg-cream-100">
              <a href="{{ get_permalink($item->ID) }}" class="absolute inset-0 z-20" aria-label="{{ $item->post_title }}"></a>
              <div class="absolute top-2 right-2 z-30">
                @include('partials.favorite-btn', ['post_id' => $item->ID])
              </div>
              @if(has_post_thumbnail($item->ID))
                {!! get_the_post_thumbnail($item->ID, 'large', ['class' => 'w-full h-full object-cover']) !!}
              @else
                <div class="w-full h-full flex items-center justify-center">
                  <svg class="w-12 h-10 text-bark-300 opacity-45" viewBox="0 0 52 40" fill="none"><rect x="1" y="1" width="50" height="38" rx="4" stroke="currentColor" stroke-width="1.5"/><path d="M8 30 L18 18 L26 25 L34 14 L44 30" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" fill="none"/><circle cx="16" cy="13" r="4" stroke="currentColor" stroke-width="1.5"/></svg>
                </div>
              @endif
              @php $badge = function_exists('get_field') ? get_field('ps_card_badge', $item->ID) : ''; @endphp
              @if($badge)
                <span class="badge-terra absolute top-3 left-3 z-10">{{ $badge }}</span>
              @endif
              <span class="badge-cream absolute bottom-3 left-3 z-10">{{ get_post_type_object($item->post_type)->labels->singular_name ?? 'Гайд' }}</span>
            </div>

            <!-- Title -->
            <h3 class="heading-card truncate">{{ $item->post_title }}</h3>

            <!-- Benefits list -->
            <ul class="flex flex-col gap-1.5 flex-1">
              @php
                $benefits = function_exists('get_field') ? get_field('benefits', $item->ID) : null;
              @endphp
              @if(!empty($benefits) && is_array($benefits))
                @foreach(array_slice($benefits, 0, 3) as $benefit)
                  <li class="flex items-start gap-2 text-caption text-bark-600">
                    <span class="w-4 h-4 rounded-full bg-cream-100 border border-[var(--border)] flex items-center justify-center shrink-0 mt-0.5">
                      <svg viewBox="0 0 8 8" fill="none" class="w-2 h-2"><polyline points="1,4 3,6 7,2" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </span>
                    {{ $benefit['text'] ?? $benefit['title'] ?? 'Преимущество' }}
                  </li>
                @endforeach
              @else
                <li class="flex items-start gap-2 text-caption text-bark-600"><span class="w-4 h-4 rounded-full bg-cream-100 border border-[var(--border)] flex items-center justify-center shrink-0 mt-0.5"><svg viewBox="0 0 8 8" fill="none" class="w-2 h-2"><polyline points="1,4 3,6 7,2" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Индивидуальное меню питания</li>
                <li class="flex items-start gap-2 text-caption text-bark-600"><span class="w-4 h-4 rounded-full bg-cream-100 border border-[var(--border)] flex items-center justify-center shrink-0 mt-0.5"><svg viewBox="0 0 8 8" fill="none" class="w-2 h-2"><polyline points="1,4 3,6 7,2" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Схема приема нутрицевтиков</li>
                <li class="flex items-start gap-2 text-caption text-bark-600"><span class="w-4 h-4 rounded-full bg-cream-100 border border-[var(--border)] flex items-center justify-center shrink-0 mt-0.5"><svg viewBox="0 0 8 8" fill="none" class="w-2 h-2"><polyline points="1,4 3,6 7,2" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>Авторские протоколы восстановления</li>
              @endif
            </ul>

            <!-- Footer: price + button -->
            <div class="flex items-center justify-between pt-3 border-t border-[var(--border)] gap-2 mt-auto">
              @php
                $price_old = function_exists('get_field') ? get_field('book_price_old', $item->ID) : '';
                $price     = function_exists('get_field') ? get_field('price', $item->ID) : '';
              @endphp
              <div>
                @if(!empty($price_old))
                  <div class="text-caption line-through text-bark-400">{{ $price_old }} ₽</div>
                @endif
                <div class="text-xl font-bold text-bark-900 leading-none">{{ $price }} ₽</div>
                <div class="text-caption text-bark-400 mt-0.5">PDF · Доступ навсегда</div>
              </div>
              <a href="{{ get_permalink($item->ID) }}" class="btn-dark btn-sm whitespace-nowrap">КУПИТЬ</a>
            </div>
          </div>
          @endforeach

        </div>
      </div>

      <!-- Bottom controls -->
      <div class="flex items-center justify-between mt-7 flex-wrap gap-5">
        <div class="flex items-center gap-3">
          <button class="btn-ghost p-2 rounded-full" id="prev-{{ $bid }}" aria-label="Назад">
            <svg viewBox="0 0 14 14" fill="none" class="w-4 h-4"><path d="M9 2.5L4.5 7 9 11.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
          <div class="flex gap-1.5 items-center" id="dots-{{ $bid }}"></div>
          <button class="btn-ghost p-2 rounded-full" id="next-{{ $bid }}" aria-label="Вперёд">
            <svg viewBox="0 0 14 14" fill="none" class="w-4 h-4"><path d="M5 2.5L9.5 7 5 11.5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
        </div>
        <a href="/products" class="btn-secondary">Смотреть все МПО</a>
      </div>
    </div>

    <script>
      (function() {
        const track   = document.getElementById('track-{{ $bid }}');
        if(!track) return;

        const dotsEl  = document.getElementById('dots-{{ $bid }}');
        const prevBtn = document.getElementById('prev-{{ $bid }}');
        const nextBtn = document.getElementById('next-{{ $bid }}');
        const cards   = Array.from(track.querySelectorAll('[data-i]'));
        const N       = cards.length;
        const CARD_W  = 252;
        const GAP     = 16;
        const PEEK    = 28;

        let current = 0;

        function updateClasses() {
          cards.forEach((c, i) => {
            c.classList.remove('opacity-100', 'scale-100', 'opacity-[0.78]', 'scale-[0.97]', 'opacity-50', 'scale-[0.96]');
            if (i === current) {
              c.classList.add('opacity-100', 'scale-100');
            } else if (i === current - 1 || i === current + 1) {
              c.classList.add('opacity-[0.78]', 'scale-[0.97]');
            } else {
              c.classList.add('opacity-50', 'scale-[0.96]');
            }
          });
        }

        function buildDots() {
          dotsEl.innerHTML = '';
          for (let i = 0; i < N; i++) {
            const d = document.createElement('button');
            d.className = i === current
              ? 'w-5 h-1.5 bg-bark-900 rounded-full border-0 cursor-pointer transition-all p-0'
              : 'w-1.5 h-1.5 bg-bark-300 rounded-full border-0 cursor-pointer transition-all p-0';
            d.addEventListener('click', () => goTo(i));
            dotsEl.appendChild(d);
          }
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

          Array.from(dotsEl.children).forEach((d, i) => {
            if (i === current) {
              d.className = 'w-5 h-1.5 bg-bark-900 rounded-full border-0 cursor-pointer transition-all p-0';
            } else {
              d.className = 'w-1.5 h-1.5 bg-bark-300 rounded-full border-0 cursor-pointer transition-all p-0';
            }
          });
        }

        if(prevBtn) prevBtn.addEventListener('click', () => goTo(current - 1));
        if(nextBtn) nextBtn.addEventListener('click', () => goTo(current + 1));

        cards.forEach((c, i) => {
          c.addEventListener('click', (e) => {
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
      <div class="p-8 border-2 border-dashed border-[var(--border)] rounded-2xl text-center">
        <p class="text-body">В этом блоке пока нет продуктов. Выберите их в редакторе страницы.</p>
      </div>
    @endif
  </div>
</section>
