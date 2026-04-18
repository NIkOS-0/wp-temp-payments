<section class="py-12 md:py-20 bg-white overflow-hidden">
  <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
    <!-- Left: Content -->
    <div class="max-w-2xl pt-8">
      @if(!empty($block['title']))
        <h1 class="text-4xl sm:text-5xl lg:text-5xl xl:text-[3.5rem] font-black mb-6 tracking-tighter text-slate-900 leading-[1.05] uppercase uppercase-light">
          {!! nl2br(esc_html($block['title'])) !!}
        </h1>
      @endif
      
      @if(!empty($block['subtitle']))
        <div class="flex items-start gap-4 mb-10">
          <div class="w-12 h-12 bg-[#f4f6f8] rounded-xl flex items-center justify-center shrink-0 mt-1 shadow-inner">
             <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
          </div>
          <p class="text-lg text-slate-500 font-semibold leading-snug">{!! nl2br(esc_html($block['subtitle'])) !!}</p>
        </div>
      @endif
      
      <div class="flex flex-col sm:flex-row gap-4">
        @if(!empty($block['cta_1']))
          <a href="{{ $block['cta_1']['url'] }}" class="px-8 py-4 bg-slate-900 text-white rounded-xl font-bold text-center hover:bg-slate-800 transition-colors shadow-lg">
            {{ $block['cta_1']['title'] }}
          </a>
        @endif
        @if(!empty($block['cta_2']))
          <a href="{{ $block['cta_2']['url'] }}" class="px-8 py-4 bg-white text-slate-900 border-2 border-slate-200 rounded-xl font-bold text-center hover:border-slate-900 transition-colors shadow-sm">
            {{ $block['cta_2']['title'] }}
          </a>
        @endif
      </div>
    </div>
    
    <!-- Right: Video Placeholder / Image -->
    <div class="relative bg-[#ebeef1] rounded-[2rem] aspect-[4/3] flex items-center justify-center overflow-hidden group shadow-inner">
       
       <!-- Image Cover Layout -->
       <div id="hero-cover" class="absolute inset-0 w-full h-full flex items-center justify-center z-10 cursor-pointer"
            onclick="document.getElementById('hero-cover').style.display='none'; document.getElementById('hero-video-wrapper').style.display='block';">
           @if(!empty($block['background']['url']))
             <img src="{{ $block['background']['url'] }}" class="absolute inset-0 w-full h-full object-cover mix-blend-overlay hover:scale-105 transition-transform duration-700" alt="Hero Media">
           @endif
           
           @if(!empty($block['video']))
             <!-- Play Button Wrapper -->
             <div class="w-24 h-24 bg-white/70 backdrop-blur-md rounded-full flex items-center justify-center cursor-pointer hover:bg-white transition-colors relative z-20 shadow-xl group-hover:scale-110 duration-300">
               <svg class="w-10 h-10 text-slate-800 ml-1.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"></path></svg>
             </div>
           @endif
           
           <div class="absolute bottom-6 left-6 right-6 text-center z-20 bg-white/40 backdrop-blur-md p-4 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity">
             <p class="text-sm font-bold text-slate-800 leading-tight">"Мы не лечим симптомы. Мы ищем причину"<br><span class="font-semibold text-xs text-slate-600">— E-DIET</span></p>
           </div>
       </div>

       <!-- Hidden Video Embed -->
       @if(!empty($block['video']))
         <div id="hero-video-wrapper" class="absolute inset-0 w-full h-full bg-slate-900 z-20" style="display: none;">
            <div class="w-full h-full [&>iframe]:w-full [&>iframe]:h-full [&>iframe]:absolute [&>iframe]:inset-0">
               {!! $block['video'] !!}
            </div>
         </div>
       @endif
       
    </div>
  </div>
</section>
