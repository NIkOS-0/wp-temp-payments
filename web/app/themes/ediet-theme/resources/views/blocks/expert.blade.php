<section class="section-warm">
  <div class="container-editorial">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12 px-2">
      <h2 class="heading-display">{{ !empty($block['main_title']) ? $block['main_title'] : 'Почему нам доверяют?' }}</h2>
      @if(!empty($block['main_link']))
        <a href="{{ $block['main_link']['url'] }}" target="{{ $block['main_link']['target'] }}" class="btn-ghost inline-flex items-center gap-2 uppercase tracking-widest text-sm">
          {{ $block['main_link']['title'] }}
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </a>
      @endif
    </div>

    <!-- Main Expert Card -->
    <div class="card p-8 md:p-12 mb-6">
      <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-12 xl:gap-20 items-center">

        <div class="flex flex-col items-center">
          <div class="relative mb-8 w-48 h-48">
            <div class="w-full h-full bg-cream-100 rounded-[2rem] flex items-center justify-center shadow-inner ring-1 ring-[var(--border)] overflow-hidden">
              @if(!empty($block['photo']['url']))
                <img src="{{ $block['photo']['url'] }}" class="w-24 h-24 rounded-full object-cover border-2 border-terra-200 w-full h-full" alt="{{ !empty($block['name']) ? $block['name'] : 'Expert' }}">
              @else
                <svg class="w-20 h-20 text-bark-300" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
              @endif
            </div>

            <div class="absolute -bottom-3 -right-3 w-10 h-10 bg-green-500 rounded-full border-4 border-[var(--surface)] flex items-center justify-center shadow-lg z-20">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
            </div>
          </div>

          <h3 class="heading-card text-center mb-2">{{ !empty($block['name']) ? $block['name'] : 'Имя Эксперта' }}</h3>
          @if(!empty($block['role']))
            <p class="text-overline text-bark-400 mb-6 text-center">{{ $block['role'] }}</p>
          @endif

          <div class="flex flex-col gap-2 w-full">
            @if(!empty($block['tag1']))
              <span class="badge-cream text-center">{{ $block['tag1'] }}</span>
            @endif
            @if(!empty($block['tag2']))
              <span class="badge-cream text-center">{{ $block['tag2'] }}</span>
            @endif
          </div>
        </div>

        <div class="flex flex-col h-full justify-center">
          @if(!empty($block['content_title']))
            <h4 class="heading-section mb-6">{{ $block['content_title'] }}</h4>
          @endif

          <div class="prose prose-bark max-w-none mb-10">
            <div class="text-body font-semibold leading-loose">
              {!! !empty($block['text']) ? $block['text'] : '' !!}
            </div>
            @if(!empty($block['quote']))
              <p class="text-body text-bark-400 italic mt-6 pl-4 border-l-4 border-[var(--border)]">
                "{{ $block['quote'] }}"
              </p>
            @endif
          </div>

          @if(!empty($block['stats']))
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 pt-10 border-t border-[var(--border)] mt-auto">
              @foreach($block['stats'] as $stat)
                <div>
                  <span class="block text-4xl font-black mb-2 text-{{ !empty($stat['color']) ? $stat['color'] : 'bark-900' }}">{{ $stat['value'] }}</span>
                  <span class="text-overline block">{{ $stat['label'] }}</span>
                </div>
              @endforeach
            </div>
          @endif

          @if(!empty($block['cta']))
            <div class="mt-8">
              <a href="{{ $block['cta']['url'] }}" class="btn-primary btn-lg">{{ $block['cta']['title'] }}</a>
            </div>
          @endif
        </div>

      </div>
    </div>
  </div>
</section>
