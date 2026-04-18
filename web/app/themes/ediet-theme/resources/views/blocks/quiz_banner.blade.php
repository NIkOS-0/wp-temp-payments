<section class="py-12 bg-white">
  <div class="max-w-7xl mx-auto px-4">
    <div class="bg-linear-to-r from-blue-600 to-indigo-700 rounded-[2rem] p-10 md:p-16 text-center text-white flex flex-col items-center justify-center relative overflow-hidden shadow-2xl shadow-blue-900/20 hover:scale-[1.01] transition-transform duration-500">
      
      @if(!empty($block['title']))
        <h2 class="text-3xl md:text-5xl font-black mb-4 relative z-10 tracking-tight">{{ $block['title'] }}</h2>
      @endif
      
      @if(!empty($block['subtitle']))
        <p class="text-xl text-blue-100 mb-10 max-w-2xl mx-auto relative z-10 font-medium">{{ $block['subtitle'] }}</p>
      @endif
      
      @if(!empty($block['form_id']))
        <div class="relative z-10 bg-white/10 backdrop-blur-md p-6 md:p-10 rounded-3xl w-full max-w-3xl mx-auto border border-white/20 shadow-inner">
          <p class="text-sm font-bold tracking-widest uppercase mb-4 text-blue-200">Интерактивный опросник</p>
          <div class="ediet-quiz-placeholder prose prose-invert text-white text-lg">
             <!-- Здесь выводится шорткод квиза или форма по ID -->
             [Шорткод Квиза / Формы: {{ $block['form_id'] }}]
          </div>
        </div>
      @endif

      <!-- Decorative abstract pattern -->
      <svg class="absolute top-0 right-0 w-64 h-64 text-white/5 pointer-events-none" fill="currentColor" viewBox="0 0 100 100"><circle cx="80" cy="20" r="50"/></svg>
      <svg class="absolute bottom-0 left-0 w-64 h-64 text-white/5 pointer-events-none" fill="currentColor" viewBox="0 0 100 100"><circle cx="20" cy="80" r="40"/></svg>
    </div>
  </div>
</section>
