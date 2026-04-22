<section class="py-12 bg-bark-900 text-cream-100">
  <div class="container-editorial flex items-center justify-between gap-8 flex-wrap">
    <div class="flex items-center gap-6">
      @if(!empty($block['icon']['url']))
        <div class="w-16 h-16 bg-bark-800 rounded-2xl flex items-center justify-center shrink-0 overflow-hidden">
          <img src="{{ $block['icon']['url'] }}" class="w-full h-full object-cover" alt="Icon">
        </div>
      @else
        <div class="w-16 h-16 bg-bark-800 rounded-2xl flex items-center justify-center shrink-0">
          <svg class="w-8 h-8 text-cream-300" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="6" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="2"/></svg>
        </div>
      @endif
      <div>
        @if(!empty($block['subtitle']))
          <p class="text-overline text-cream-400 mb-1.5">{{ $block['subtitle'] }}</p>
        @endif
        @if(!empty($block['title']))
          <h4 class="text-xl md:text-2xl font-bold text-cream-100 text-balance leading-none">{{ $block['title'] }}</h4>
        @endif
      </div>
    </div>

    @if(!empty($block['text']))
      <p class="text-body text-cream-300 flex-1 max-w-xl hidden lg:block px-6">{{ $block['text'] }}</p>
    @endif

    @if(!empty($block['btn']))
      <a href="{{ $block['btn']['url'] }}" target="{{ $block['btn']['target'] }}" class="btn-primary btn-lg w-full lg:w-auto shrink-0 text-center">
        {{ $block['btn']['title'] }}
      </a>
    @endif
  </div>
</section>
