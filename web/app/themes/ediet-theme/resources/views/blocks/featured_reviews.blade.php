<section class="section-canvas overflow-hidden" id="rev-block-{{ $bid ?? uniqid() }}">
  @php
    $revs = $custom_reviews ?? [];
    if(empty($revs)) {
        $revs = get_posts([
            'post_type' => 'review',
            'posts_per_page' => 5,
        ]);
    }
  @endphp

  <div class="container-editorial relative">
    @if(empty($hide_controls))
    <div class="flex items-center justify-between mb-12">
      @if(!empty($block['title']))
        <h2 class="heading-display">{{ $block['title'] }}</h2>
      @else
        <h2 class="heading-display">Истории, которые дают надежду</h2>
      @endif

      <a href="/reviews" class="hidden md:flex items-center gap-2 btn-ghost text-sm uppercase tracking-widest">
        Смотреть отзывы
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
      </a>
    </div>
    @else
      <h2 class="heading-section mb-10 text-center">Отзывы</h2>
    @endif

    @if(count($revs) > 0)
    <!-- Reviews Slider UI -->
    <div class="relative w-full h-[650px] md:h-[500px] flex items-center justify-center review-slider-container">

      @foreach($revs as $i => $rev)
      @php
          $type        = get_field('type', $rev->ID) ?: 'text';
          $before_text = get_field('before_text', $rev->ID);
          $after_text  = get_field('after_text', $rev->ID);
          $media       = get_field('media', $rev->ID);
          $author_name = get_the_title($rev->ID);
          $diagnosis   = '';
          $rel_diseases = get_field('related_diseases', $rev->ID);
          if(!empty($rel_diseases)) {
              $diagnosis = is_array($rel_diseases) ? get_the_title($rel_diseases[0]->ID) : get_the_title($rel_diseases->ID);
          }
      @endphp

      <div class="review-card absolute transition-all duration-500 ease-in-out w-full max-w-[800px] flex flex-col md:flex-row card border-l-4 border-l-terra-400 p-3" data-idx="{{ $i }}">

        <!-- Left Media/Identifier -->
        <div class="w-full md:w-1/2 h-[250px] md:h-auto min-h-[350px] md:min-h-[400px] bg-cream-100 rounded-2xl relative flex items-center justify-center overflow-hidden">
          @if($media)
            <img src="{{ is_array($media) ? $media['url'] : wp_get_attachment_url($media) }}" class="absolute inset-0 w-full h-full object-cover">
          @else
            <div class="absolute inset-0 w-full h-full bg-cream-50 flex items-center justify-center">
              <svg class="w-20 h-20 text-bark-200" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.2 0 4-1.8 4-4s-1.8-4-4-4-4 1.8-4 4 1.8 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
            </div>
          @endif

          @if($type === 'video')
          <div class="w-20 h-20 bg-cream-100/90 backdrop-blur-md rounded-full shadow-lg flex items-center justify-center cursor-pointer hover:bg-cream-100 hover:scale-110 transition-all relative z-10 duration-300 group">
            <svg class="w-8 h-8 text-bark-800 ml-1 group-hover:text-terra-500 transition-colors" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
          </div>
          @endif

          <!-- User Identifier overlay -->
          <div class="absolute bottom-4 left-4 right-4 flex items-center gap-3 bg-[var(--surface)]/95 backdrop-blur-md p-3 px-4 rounded-2xl shadow-lg">
            <div class="w-12 h-12 rounded-full bg-cream-200 shrink-0 flex items-center justify-center overflow-hidden">
              <svg class="w-6 h-6 text-bark-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.2 0 4-1.8 4-4s-1.8-4-4-4-4 1.8-4 4 1.8 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
            </div>
            <div>
              <h4 class="font-semibold text-bark-900 text-sm leading-tight">{{ $author_name }}</h4>
              @if($diagnosis)
              <p class="text-caption text-bark-500 mt-0.5">Диагноз: {{ $diagnosis }}</p>
              @endif
            </div>
          </div>
        </div>

        <!-- Right Content Box -->
        <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center relative">
          <span class="absolute top-4 left-6 text-7xl font-serif text-cream-200 leading-none pointer-events-none">"</span>
          <div class="relative z-10 pt-4">
            @if($type === 'before_after')
            <div class="badge-peach mb-6">РЕЗУЛЬТАТЫ ДО / ПОСЛЕ</div>

            <div class="space-y-6">
              <div>
                <span class="text-overline text-bark-400 mb-2 flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-bark-300"></span>БЫЛО
                </span>
                <div class="text-body text-sm text-bark-500 italic pr-4 leading-relaxed line-clamp-3">{!! $before_text !!}</div>
              </div>

              <div class="relative">
                <div class="absolute -top-4 -left-3 border-l-2 border-b-2 border-[var(--border)] w-4 h-8 rounded-bl-xl pointer-events-none"></div>
                <span class="text-overline text-terra-500 mb-2 flex items-center gap-2">
                  <span class="w-2 h-2 rounded-full bg-terra-500"></span>СТАЛО
                </span>
                <div class="text-body text-sm text-bark-900 font-bold leading-relaxed bg-cream-50 p-4 rounded-xl line-clamp-4">{!! $after_text !!}</div>
              </div>
            </div>
            @else
            <div class="text-body text-sm leading-relaxed line-clamp-[10]">{!! $before_text ?: get_post_field('post_content', $rev->ID) !!}</div>
            @endif
          </div>
        </div>

      </div>
      @endforeach

    </div>

    <!-- Slider Controls -->
    <div class="flex justify-center items-center gap-6 mt-10 mb-4 rev-controls">
      <button class="btn-ghost p-2 rounded-full w-14 h-14 flex items-center justify-center rev-prev">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      </button>
      <div class="flex items-center gap-3 bg-[var(--surface)] px-4 py-2 rounded-full shadow-sm border border-[var(--border)] rev-dots">
        <!-- dots injected via js -->
      </div>
      <button class="btn-ghost p-2 rounded-full w-14 h-14 flex items-center justify-center rev-next">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      </button>
    </div>

    <script>
      (function() {
         const container = document.currentScript.closest('section');
         const cards = Array.from(container.querySelectorAll('.review-card'));
         const dotsContainer = container.querySelector('.rev-dots');
         const prevBtn = container.querySelector('.rev-prev');
         const nextBtn = container.querySelector('.rev-next');
         const total = cards.length;
         if(!total) return container.style.display = 'none';

         let current = 0;

         function render() {
             cards.forEach((c, i) => {
                 c.className = 'review-card absolute transition-all duration-500 ease-in-out w-full max-w-[800px] flex flex-col md:flex-row card border-l-4 border-l-terra-400 p-3';

                 if (i === current) {
                     c.classList.add('z-20', 'scale-[1.01]', 'opacity-100');
                     c.style.transform = 'translateX(0)';
                     c.style.filter = 'blur(0)';
                 } else if (i === current - 1 || (current === 0 && i === total - 1)) {
                     c.className = 'review-card absolute transition-all duration-500 ease-in-out w-full hidden lg:flex left-0 w-[280px] h-[400px] card items-center justify-center';
                     c.style.transform = 'translateX(-15%) scale(0.9)';
                     c.style.filter = 'blur(2px)';
                     c.classList.add('opacity-60', 'z-10', 'pointer-events-none');
                 } else if (i === current + 1 || (current === total - 1 && i === 0)) {
                     c.className = 'review-card absolute transition-all duration-500 ease-in-out w-full hidden lg:flex right-0 w-[280px] h-[400px] card items-center justify-center';
                     c.style.transform = 'translateX(15%) scale(0.9)';
                     c.style.filter = 'blur(2px)';
                     c.classList.add('opacity-60', 'z-10', 'pointer-events-none');
                 } else {
                     c.classList.add('opacity-0', 'scale-75', '-z-10');
                     c.style.transform = 'translateX(0)';
                 }
             });

             Array.from(dotsContainer.children).forEach((d, i) => {
                 d.className = i === current
                     ? 'w-8 h-2.5 bg-bark-900 rounded-full cursor-pointer transition-all'
                     : 'w-2.5 h-2.5 bg-bark-200 rounded-full cursor-pointer hover:bg-bark-400 transition-all';
             });
         }

         dotsContainer.innerHTML = '';
         for (let i = 0; i < total; i++) {
             let d = document.createElement('div');
             d.addEventListener('click', () => { current = i; render(); });
             dotsContainer.appendChild(d);
         }

         prevBtn.addEventListener('click', () => { current = (current - 1 + total) % total; render(); });
         nextBtn.addEventListener('click', () => { current = (current + 1) % total; render(); });

         /* Touch Swipe Logic */
         let touchstartX = 0;
         let touchendX = 0;
         const sliderContent = container.querySelector('.review-slider-container');
         sliderContent.addEventListener('touchstart', e => { touchstartX = e.changedTouches[0].screenX; }, {passive: true});
         sliderContent.addEventListener('touchend', e => {
           touchendX = e.changedTouches[0].screenX;
           if (touchendX < touchstartX - 40) { current = (current + 1) % total; render(); }
           if (touchendX > touchstartX + 40) { current = (current - 1 + total) % total; render(); }
         }, {passive: true});

         render();
      })();
    </script>
    @else
      <div class="text-center p-8 border border-dashed border-[var(--border)] rounded-xl">
        <p class="text-body">Отзывов пока нет.</p>
      </div>
    @endif

  </div>
</section>
