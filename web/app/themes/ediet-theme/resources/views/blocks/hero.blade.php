<section class="section gradient-canvas overflow-hidden relative" id="hero-section">
  <!-- Ambient Gradient Spots Center / Parallax -->
  <div class="absolute inset-0 z-0 flex items-center justify-center pointer-events-none mix-blend-multiply opacity-80">
      <!-- Blob 1: Yellow -->
      <div class="parallax-blob absolute w-[400px] h-[400px] md:w-[600px] md:h-[600px] rounded-full bg-[#fde68a] filter blur-[100px] opacity-70 transition-transform duration-700 ease-out" style="transform: translate(-10%, -15%);" data-orig-x="-10%" data-orig-y="-15%" data-speed="-0.03"></div>
      
      <!-- Blob 2: Light Blue -->
      <div class="parallax-blob absolute w-[450px] h-[450px] md:w-[650px] md:h-[650px] rounded-full bg-[#bae6fd] filter blur-[120px] opacity-60 transition-transform duration-700 ease-out" style="transform: translate(15%, 10%);" data-orig-x="15%" data-orig-y="10%" data-speed="0.05"></div>
      
      <!-- Blob 3: Cyan -->
      <div class="parallax-blob absolute w-[300px] h-[300px] md:w-[450px] md:h-[450px] rounded-full bg-[#e0f2fe] filter blur-[90px] opacity-90 transition-transform duration-700 ease-out" style="transform: translate(-20%, 25%);" data-orig-x="-20%" data-orig-y="25%" data-speed="-0.06"></div>
  </div>

  <div class="container-wide grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center py-20 md:py-32 relative z-10">
    <!-- Left: Content -->
    <div class="max-w-2xl pt-8">
      @if(!empty($block['title']))
        <h1 class="heading-hero mb-6">
          {!! nl2br(esc_html($block['title'])) !!}
        </h1>
      @endif

      @if(!empty($block['subtitle']))
        <div class="flex items-start gap-4 mb-10">
          <div class="w-12 h-12 bg-peach-100 rounded-xl flex items-center justify-center shrink-0 mt-1 shadow-sm border border-peach-200">
            <svg class="w-6 h-6 text-cream-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
          </div>
          <p class="text-body text-bark-700 font-medium leading-snug">{!! nl2br(esc_html($block['subtitle'])) !!}</p>
        </div>
      @endif

      <div class="flex flex-col sm:flex-row gap-4">
        @if(!empty($block['cta_1']))
          <a href="{{ $block['cta_1']['url'] }}" class="btn-primary btn-lg">
            {{ $block['cta_1']['title'] }}
          </a>
        @endif
        @if(!empty($block['cta_2']))
          <a href="{{ $block['cta_2']['url'] }}" class="btn-soft btn-lg">
            {{ $block['cta_2']['title'] }}
          </a>
        @endif
      </div>
    </div>

    <!-- Right: Video Placeholder / Image -->
    <div class="relative bg-cream-200 rounded-2xl aspect-[4/3] flex items-center justify-center overflow-hidden group shadow-lg border border-cream-300">

      <!-- Image Cover Layout -->
      <div id="hero-cover" class="absolute inset-0 w-full h-full flex items-center justify-center z-10 cursor-pointer"
           onclick="document.getElementById('hero-cover').style.display='none'; document.getElementById('hero-video-wrapper').style.display='block';">
          @if(!empty($block['background']['url']))
            <img src="{{ $block['background']['url'] }}" class="absolute inset-0 w-full h-full object-cover mix-blend-overlay hover:scale-105 transition-transform duration-700" alt="Hero Media">
          @endif

          @if(!empty($block['video']))
            <!-- Play Button Wrapper -->
            <div class="w-24 h-24 bg-cream-100/70 backdrop-blur-md rounded-full flex items-center justify-center cursor-pointer hover:bg-cream-100 transition-colors relative z-20 shadow-xl group-hover:scale-110 duration-300">
              <svg class="w-10 h-10 text-bark-900 ml-1.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"></path></svg>
            </div>
          @endif

          <div class="absolute bottom-6 left-6 right-6 text-center z-20 bg-cream-100/40 backdrop-blur-md p-4 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity">
            <p class="text-sm font-bold text-bark-900 leading-tight">"Мы не лечим симптомы. Мы ищем причину"<br><span class="font-semibold text-xs text-bark-700">— E-DIET</span></p>
          </div>
      </div>

      <!-- Hidden Video Embed -->
      @if(!empty($block['video']))
        <div id="hero-video-wrapper" class="absolute inset-0 w-full h-full bg-cream-200 z-20" style="display: none;">
          <div class="w-full h-full [&>iframe]:w-full [&>iframe]:h-full [&>iframe]:absolute [&>iframe]:inset-0">
            {!! $block['video'] !!}
          </div>
        </div>
      @endif

    </div>
  </div>
</section>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const hero = document.getElementById("hero-section");
    const blobs = document.querySelectorAll(".parallax-blob");
    if(!hero || blobs.length === 0) return;

    hero.addEventListener("mousemove", (e) => {
      const rect = hero.getBoundingClientRect();
      const x = e.clientX - rect.left - rect.width / 2;
      const y = e.clientY - rect.top - rect.height / 2;

      blobs.forEach(blob => {
        const speed = parseFloat(blob.getAttribute("data-speed"));
        const xOffset = x * speed;
        const yOffset = y * speed;
        const origX = blob.getAttribute("data-orig-x");
        const origY = blob.getAttribute("data-orig-y");
        
        blob.style.transform = `translate(calc(${origX} + ${xOffset}px), calc(${origY} + ${yOffset}px))`;
      });
    });
  });
</script>
