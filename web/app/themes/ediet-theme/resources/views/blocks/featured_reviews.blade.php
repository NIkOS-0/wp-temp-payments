<section class="py-24 bg-[#F8F9FA] overflow-hidden" id="rev-block-{{ $bid ?? uniqid() }}">
  @php 
    $revs = $custom_reviews ?? [];
    if(empty($revs)) {
        $revs = get_posts([
            'post_type' => 'review',
            'posts_per_page' => 5,
        ]);
    }
  @endphp
  
  <div class="max-w-[1400px] mx-auto px-4 relative">
    @if(empty($hide_controls))
    <div class="flex items-center justify-between mb-16">
      @if(!empty($block['title']))
        <h2 class="text-4xl md:text-5xl font-black tracking-tight text-slate-900">{{ $block['title'] }}</h2>
      @else
        <h2 class="text-4xl md:text-5xl font-black tracking-tight text-slate-900">Истории, которые дают надежду</h2>
      @endif
      
      <a href="/reviews" class="hidden md:flex items-center gap-2 font-black text-slate-900 hover:text-blue-600 transition-colors uppercase tracking-widest text-sm">
        Смотреть отзывы <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
      </a>
    </div>
    @else
      <h2 class="text-3xl font-bold mb-10 text-center">Отзывы</h2>
    @endif

    @if(count($revs) > 0)
    <!-- Reviews Slider UI -->
    <div class="relative w-full h-[650px] md:h-[500px] flex items-center justify-center review-slider-container">
      
      @foreach($revs as $i => $rev)
      @php
          $type = get_field('type', $rev->ID) ?: 'text';
          $before_text = get_field('before_text', $rev->ID);
          $after_text = get_field('after_text', $rev->ID);
          $media = get_field('media', $rev->ID);
          $author_name = get_the_title($rev->ID);
          $diagnosis = '';
          $rel_diseases = get_field('related_diseases', $rev->ID);
          if(!empty($rel_diseases)) {
              $diagnosis = is_array($rel_diseases) ? get_the_title($rel_diseases[0]->ID) : get_the_title($rel_diseases->ID);
          }
      @endphp
       
       <div class="review-card absolute transition-all duration-500 ease-in-out w-full max-w-[800px] flex flex-col md:flex-row bg-white rounded-[2rem] shadow-2xl border border-slate-100 p-3 border-l-8 border-l-blue-600" data-idx="{{ $i }}">
         
         <!-- Left Media/Identifier -->
         <div class="w-full md:w-1/2 h-[250px] md:h-auto min-h-[350px] md:min-h-[400px] bg-[#e9ecef] rounded-[1.5rem] relative flex items-center justify-center overflow-hidden">
            @if($media)
              <img src="{{ is_array($media) ? $media['url'] : wp_get_attachment_url($media) }}" class="absolute inset-0 w-full h-full object-cover">
            @else
              <div class="absolute inset-0 w-full h-full bg-slate-100 flex items-center justify-center">
                <svg class="w-20 h-20 text-slate-200" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.2 0 4-1.8 4-4s-1.8-4-4-4-4 1.8-4 4 1.8 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
              </div>
            @endif
            
            @if($type === 'video')
            <div class="w-20 h-20 bg-white/90 backdrop-blur-md rounded-full shadow-lg flex items-center justify-center cursor-pointer hover:bg-white hover:scale-110 transition-all relative z-10 duration-300 group">
               <svg class="w-8 h-8 text-slate-800 ml-1 group-hover:text-blue-600 transition-colors" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
            </div>
            @endif
            
            <!-- User Identifier overlay -->
            <div class="absolute bottom-6 left-6 right-6 flex items-center gap-4 bg-white/95 backdrop-blur-md p-3 px-4 rounded-2xl shadow-xl">
               <div class="w-10 h-10 bg-[#ced4da] rounded-full shrink-0 flex items-center justify-center overflow-hidden">
                 <svg class="w-6 h-6 text-slate-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.2 0 4-1.8 4-4s-1.8-4-4-4-4 1.8-4 4 1.8 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
               </div>
               <div>
                  <h4 class="font-bold text-slate-900 text-sm leading-tight">{{ $author_name }}</h4>
                  @if($diagnosis)
                  <p class="text-xs font-bold text-slate-500 mt-0.5">Диагноз: {{ $diagnosis }}</p>
                  @endif
               </div>
            </div>
         </div>
         
         <!-- Right Content Box -->
         <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center relative">
            <span class="absolute top-4 left-6 text-7xl font-serif text-slate-100 leading-none pointer-events-none">"</span>
            <div class="relative z-10 pt-4">
               @if($type === 'before_after')
               <div class="inline-block px-3 py-1 bg-gradient-to-r from-[#fff3cd] to-[#ffecb3] text-[#856404] text-[10px] font-black uppercase tracking-widest rounded-md mb-6 border border-[#ffeeba] shadow-[0_2px_10px_rgba(255,236,179,0.5)]">РЕЗУЛЬТАТЫ ДО / ПОСЛЕ</div>
               
               <div class="space-y-6">
                 <div>
                   <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 flex items-center gap-2">
                     <span class="w-2 h-2 rounded-full bg-slate-300"></span>БЫЛО
                   </span>
                   <div class="text-sm text-slate-500 font-medium italic pr-4 leading-relaxed line-clamp-3">{!! $before_text !!}</div>
                 </div>
                 
                 <div class="relative">
                   <div class="absolute -top-4 -left-3 border-l-2 border-b-2 border-slate-100 w-4 h-8 rounded-bl-xl pointer-events-none"></div>
                   <span class="block text-[10px] font-black text-blue-500 uppercase tracking-widest mb-2 flex items-center gap-2">
                     <span class="w-2 h-2 rounded-full bg-blue-500"></span>СТАЛО
                   </span>
                   <div class="text-sm text-slate-900 font-bold leading-relaxed bg-[#f8f9fa] p-4 rounded-xl line-clamp-4">{!! $after_text !!}</div>
                 </div>
               </div>
               @else
               <div class="text-sm text-slate-700 font-medium italic leading-relaxed line-clamp-[10]">{!! $before_text ?: get_post_field('post_content', $rev->ID) !!}</div>
               @endif
            </div>
         </div>
         
       </div>
       @endforeach

    </div>

    <!-- Slider Controls -->
    <div class="flex justify-center items-center gap-6 mt-12 mb-4 rev-controls">
      <button class="w-14 h-14 rounded-full border-2 border-slate-200 flex items-center justify-center text-slate-500 hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-colors group rev-prev"><svg class="w-6 h-6 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></button>
      <div class="flex items-center gap-3 bg-white px-4 py-2 rounded-full shadow-sm border border-slate-100 rev-dots">
         <!-- dots injected via js -->
      </div>
      <button class="w-14 h-14 rounded-full border-2 border-slate-200 flex items-center justify-center text-slate-500 hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-colors group rev-next"><svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></button>
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
                 // reset classes
                 c.className = 'review-card absolute transition-all duration-500 ease-in-out w-full max-w-[800px] flex flex-col md:flex-row bg-white rounded-[2rem] shadow-2xl border border-slate-100 p-3 border-l-8 border-l-blue-600';
                 
                 if (i === current) {
                     c.classList.add('z-20', 'scale-[1.01]', 'opacity-100');
                     c.style.transform = 'translateX(0)';
                     c.style.filter = 'blur(0)';
                 } else if (i === current - 1 || (current === 0 && i === total - 1)) {
                     // Prev card (Left)
                     c.className = 'review-card absolute transition-all duration-500 ease-in-out w-full hidden lg:flex left-0 w-[280px] h-[400px] bg-white rounded-[2rem] shadow-sm border border-slate-100 items-center justify-center';
                     c.style.transform = 'translateX(-15%) scale(0.9)';
                     c.style.filter = 'blur(2px)';
                     c.classList.add('opacity-60', 'z-10', 'pointer-events-none');
                 } else if (i === current + 1 || (current === total - 1 && i === 0)) {
                     // Next card (Right)
                     c.className = 'review-card absolute transition-all duration-500 ease-in-out w-full hidden lg:flex right-0 w-[280px] h-[400px] bg-white rounded-[2rem] shadow-sm border border-slate-100 items-center justify-center';
                     c.style.transform = 'translateX(15%) scale(0.9)';
                     c.style.filter = 'blur(2px)';
                     c.classList.add('opacity-60', 'z-10', 'pointer-events-none');
                 } else {
                     // Hidden
                     c.classList.add('opacity-0', 'scale-75', '-z-10');
                     c.style.transform = 'translateX(0)';
                 }
             });

             Array.from(dotsContainer.children).forEach((d, i) => {
                 d.className = i === current 
                     ? 'w-8 h-2.5 bg-slate-900 rounded-full cursor-pointer transition-all'
                     : 'w-2.5 h-2.5 bg-slate-200 rounded-full cursor-pointer hover:bg-slate-400 transition-all';
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
      <div class="text-center p-8 border border-dashed border-slate-300 rounded-xl text-slate-500">Отзывов пока нет.</div>
    @endif

  </div>
</section>
