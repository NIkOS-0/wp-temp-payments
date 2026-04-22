<section class="py-16 gradient-peach">
  <div class="container-editorial text-center">
    <div class="flex flex-col items-center justify-center relative overflow-hidden">

      @if(!empty($block['title']))
        <h2 class="heading-display text-balance mb-4 relative z-10">{{ $block['title'] }}</h2>
      @endif

      @if(!empty($block['subtitle']))
        <p class="text-body mb-10 max-w-2xl mx-auto relative z-10">{{ $block['subtitle'] }}</p>
      @endif

      @if(!empty($block['form_id']))
        <div class="relative z-10 bg-[var(--surface)]/80 backdrop-blur-md p-6 md:p-10 rounded-3xl w-full max-w-3xl mx-auto border border-[var(--border)] shadow-inner">
          <p class="text-overline text-terra-500 mb-4">Интерактивный опросник</p>
          <div class="ediet-quiz-placeholder text-body">
            <!-- Здесь выводится шорткод квиза или форма по ID -->
            [Шорткод Квиза / Формы: {{ $block['form_id'] }}]
          </div>
        </div>
      @else
        @if(!empty($block['cta']))
          <a href="{{ $block['cta']['url'] }}" class="btn-dark btn-lg relative z-10">{{ $block['cta']['title'] }}</a>
        @endif
      @endif

      <!-- Decorative abstract pattern -->
      <svg class="absolute top-0 right-0 w-64 h-64 text-peach-300/20 pointer-events-none" fill="currentColor" viewBox="0 0 100 100"><circle cx="80" cy="20" r="50"/></svg>
      <svg class="absolute bottom-0 left-0 w-64 h-64 text-peach-300/20 pointer-events-none" fill="currentColor" viewBox="0 0 100 100"><circle cx="20" cy="80" r="40"/></svg>
    </div>
  </div>
</section>
