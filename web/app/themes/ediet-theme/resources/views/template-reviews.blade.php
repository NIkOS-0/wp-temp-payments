{{--
  Template Name: Отзывы
--}}
@extends('layouts.app')

@section('content')
<div class="min-h-[calc(100vh-80px)] bg-[--app-bg]">

  {{-- Hero --}}
  <div class="section-canvas border-b border-[--border]">
    <div class="container-editorial">
      <p class="text-overline mb-3">Реальные результаты</p>
      <h1 class="heading-display text-balance mb-4 max-w-2xl">
        Отзывы клиентов
      </h1>
      <p class="text-body max-w-xl mb-8">
        Истории людей, которые изменили своё здоровье с e-diet
      </p>

      {{-- Ratings summary --}}
      @if($ratings_summary['total'] > 0)
      <div class="flex flex-wrap items-center gap-6">
        <div class="flex items-baseline gap-2">
          <span class="text-4xl font-bold text-bark-900">{{ $ratings_summary['avg'] }}</span>
          <div>
            <div class="flex gap-0.5 text-terra-500">
              @for($i = 1; $i <= 5; $i++)
                <svg class="w-4 h-4 {{ $i <= round($ratings_summary['avg']) ? 'fill-current' : 'fill-bark-200' }}"
                     viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
              @endfor
            </div>
            <p class="text-xs text-bark-400 mt-0.5">{{ $ratings_summary['total'] }} отзывов</p>
          </div>
        </div>
      </div>
      @endif
    </div>
  </div>

  {{-- Filter tabs + grid --}}
  <div class="container-editorial py-8">
    <div class="flex gap-1 bg-cream-200 p-1 rounded-xl w-fit overflow-x-auto mb-8">
      @foreach(['all' => 'Все', 'text' => 'Текст', 'photo' => 'С фото', 'before_after' => 'До → После'] as $filterKey => $label)
        <button class="reviews-filter px-5 py-2.5 rounded-lg text-sm font-semibold whitespace-nowrap
                       transition-all {{ $filterKey === 'all' ? 'bg-white text-bark-900 shadow-sm' : 'text-bark-500 hover:text-bark-800' }}"
                data-filter="{{ $filterKey }}">
          {{ $label }}
        </button>
      @endforeach
    </div>

    {{-- Reviews grid --}}
    @if(count($reviews) > 0)
    <div id="reviews-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
      @foreach($reviews as $review)
      <div class="review-card card card-hover rounded-2xl overflow-hidden" data-type="{{ $review['type'] }}">

        {{-- === TEXT review === --}}
        @if($review['type'] === 'text')
        <div class="p-6 flex flex-col h-full">
          {{-- Stars --}}
          <div class="flex gap-0.5 text-terra-500 mb-4">
            @for($i = 1; $i <= 5; $i++)
              <svg class="w-4 h-4 {{ $i <= $review['rating'] ? 'fill-current' : 'fill-bark-200 text-bark-200' }}" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
              </svg>
            @endfor
          </div>
          {{-- Text --}}
          <div class="text-body text-sm leading-relaxed flex-1 mb-5 text-bark-700">
            {!! $review['text'] !!}
          </div>
          {{-- Author --}}
          <div class="flex items-center gap-3 pt-4 border-t border-[--border]">
            @if($review['avatar_url'])
              <img src="{{ $review['avatar_url'] }}" alt="{{ $review['author'] }}"
                   class="w-9 h-9 rounded-full object-cover">
            @else
              <div class="w-9 h-9 rounded-full bg-cream-200 flex items-center justify-center">
                <span class="text-sm font-bold text-bark-600">{{ mb_substr($review['author'], 0, 1) }}</span>
              </div>
            @endif
            <div>
              <p class="text-sm font-semibold text-bark-900">{{ $review['author'] }}</p>
              <p class="text-xs text-bark-400">{{ $review['date'] }}</p>
            </div>
          </div>
        </div>

        {{-- === PHOTO review === --}}
        @elseif($review['type'] === 'photo')
        <div class="flex flex-col h-full">
          @if($review['image_url'])
            <div class="aspect-[4/3] overflow-hidden">
              <img src="{{ $review['image_url'] }}" alt="Фото отзыв"
                   class="w-full h-full object-cover">
            </div>
          @endif
          <div class="p-5 flex flex-col flex-1">
            <div class="flex gap-0.5 text-terra-500 mb-3">
              @for($i = 1; $i <= 5; $i++)
                <svg class="w-3.5 h-3.5 {{ $i <= $review['rating'] ? 'fill-current' : 'fill-bark-200 text-bark-200' }}" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
              @endfor
            </div>
            @if($review['text'])
              <div class="text-xs text-bark-600 leading-relaxed flex-1 mb-4">{!! $review['text'] !!}</div>
            @endif
            <div class="flex items-center gap-2.5">
              @if($review['avatar_url'])
                <img src="{{ $review['avatar_url'] }}" alt="{{ $review['author'] }}"
                     class="w-8 h-8 rounded-full object-cover">
              @else
                <div class="w-8 h-8 rounded-full bg-cream-200 flex items-center justify-center">
                  <span class="text-xs font-bold text-bark-600">{{ mb_substr($review['author'], 0, 1) }}</span>
                </div>
              @endif
              <span class="text-sm font-semibold text-bark-900">{{ $review['author'] }}</span>
            </div>
          </div>
        </div>

        {{-- === BEFORE → AFTER review === --}}
        @else
        <div class="p-5 flex flex-col h-full">
          <div class="flex gap-0.5 text-terra-500 mb-3">
            @for($i = 1; $i <= 5; $i++)
              <svg class="w-3.5 h-3.5 {{ $i <= $review['rating'] ? 'fill-current' : 'fill-bark-200 text-bark-200' }}" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
              </svg>
            @endfor
          </div>
          {{-- Before/After split --}}
          <div class="grid grid-cols-2 gap-2 mb-4 flex-1">
            <div class="bg-cream-200 rounded-lg p-3">
              <p class="text-[10px] font-bold text-bark-400 uppercase tracking-wider mb-2">До</p>
              @if($review['before_img'])
                <img src="{{ $review['before_img'] }}" alt="До" class="w-full aspect-square object-cover rounded-md mb-2">
              @endif
              @if($review['before_text'])
                <div class="text-xs text-bark-600 leading-relaxed">{!! $review['before_text'] !!}</div>
              @endif
            </div>
            <div class="bg-terra-100 rounded-lg p-3 border border-terra-500/20">
              <p class="text-[10px] font-bold text-terra-500 uppercase tracking-wider mb-2">После</p>
              @if($review['after_img'])
                <img src="{{ $review['after_img'] }}" alt="После" class="w-full aspect-square object-cover rounded-md mb-2">
              @endif
              @if($review['after_text'])
                <div class="text-xs text-bark-600 leading-relaxed">{!! $review['after_text'] !!}</div>
              @endif
            </div>
          </div>
          {{-- Author --}}
          <div class="flex items-center gap-2.5 pt-3 border-t border-[--border]">
            @if($review['avatar_url'])
              <img src="{{ $review['avatar_url'] }}" alt="{{ $review['author'] }}"
                   class="w-8 h-8 rounded-full object-cover">
            @else
              <div class="w-8 h-8 rounded-full bg-cream-200 flex items-center justify-center">
                <span class="text-xs font-bold text-bark-600">{{ mb_substr($review['author'], 0, 1) }}</span>
              </div>
            @endif
            <div>
              <p class="text-sm font-semibold text-bark-900">{{ $review['author'] }}</p>
              <p class="text-xs text-bark-400">{{ $review['date'] }}</p>
            </div>
          </div>
        </div>
        @endif

      </div>
      @endforeach
    </div>
    @else
    <div class="text-center py-20">
      <p class="text-bark-400 text-sm">Отзывов пока нет</p>
    </div>
    @endif
  </div>
</div>

<script>
(function () {
  const btns  = document.querySelectorAll('.reviews-filter');
  const cards = document.querySelectorAll('.review-card');

  btns.forEach(function (btn) {
    btn.addEventListener('click', function () {
      const filter = this.dataset.filter;

      // Update active tab styles
      btns.forEach(function (b) {
        const isActive = b.dataset.filter === filter;
        b.classList.toggle('bg-white',      isActive);
        b.classList.toggle('text-bark-900', isActive);
        b.classList.toggle('shadow-sm',     isActive);
        b.classList.toggle('text-bark-500', !isActive);
      });

      // Show / hide cards
      cards.forEach(function (card) {
        card.style.display = (filter === 'all' || card.dataset.type === filter) ? '' : 'none';
      });
    });
  });
}());
</script>
@endsection
