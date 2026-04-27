@php
  $item_id    = $item['id'] ?? null;
  $type_label = $item['type_label'] ?? 'МПО';
  $variant    = $variant ?? '';

  // Extra HTML attributes (e.g. data-i, id for carousel)
  $attrs_str = '';
  if (!empty($attrs) && is_array($attrs)) {
    foreach ($attrs as $_k => $_v) {
      $attrs_str .= ' ' . esc_attr($_k) . '="' . esc_attr($_v) . '"';
    }
  }

  $tagClass = match(true) {
    $type_label === 'Курс'                                   => 'dtc-card__tag--course',
    in_array($type_label, ['Консультация', 'Врач', 'Клиника']) => 'dtc-card__tag--doctor',
    default                                                  => 'dtc-card__tag--plan',
  };

  $btn_text = match(true) {
    $type_label === 'Курс'                            => 'Участвовать',
    in_array($type_label, ['Консультация', 'Врач'])   => 'Записаться',
    $type_label === 'Клиника'                         => 'Перейти',
    default                                           => 'Купить',
  };

  $p        = (float)str_replace([' ', ','], ['', '.'], $item['price'] ?? 0);
  $po       = (float)str_replace([' ', ','], ['', '.'], $item['price_old'] ?? 0);
  $discount = ($po > 0 && $p < $po) ? round((1 - $p / $po) * 100) : 0;

  $cover_gradient = $type_label === 'Курс'
    ? 'background:linear-gradient(135deg,#EF945B 0%,#D87A4A 100%)'
    : 'background:linear-gradient(144deg,#5BB8E8 0%,#3A9BD5 100%)';
@endphp

<a href="{{ $item['url'] }}" class="dtc-card {{ $variant }}"{!! $attrs_str !!}>

  {{-- ── IMAGE ── --}}
  <div class="dtc-card__img">
    @if(!empty($item['image']))
      <img src="{{ $item['image'] }}" alt="{{ esc_attr($item['title']) }}">
    @else
      <div class="dtc-card__cover" style="{{ $cover_gradient }}">
        <div class="dtc-card__cover-title">{!! nl2br(esc_html($item['title'])) !!}</div>
      </div>
    @endif

    @if(!empty($item['badge']))
      <span class="dtc-card__badge">{{ $item['badge'] }}</span>
    @endif

    @if($item_id)
      <div class="absolute top-3 right-3 z-20" onclick="event.preventDefault(); event.stopPropagation();">
        @include('partials.favorite-btn', [
          'post_id' => $item_id,
          'class'   => '!w-8 !h-8 bg-white/80 backdrop-blur-sm rounded-full shadow-sm',
        ])
      </div>
    @endif
  </div>

  {{-- ── TAGS ── --}}
  <div class="dtc-card__tags">
    <span class="dtc-card__tag {{ $tagClass }}">{{ $type_label }}</span>
    @if($discount > 0)
      <span class="dtc-card__tag dtc-card__tag--sale">−{{ $discount }}%</span>
    @endif
  </div>

  {{-- ── BODY ── --}}
  <div class="dtc-card__body">
    <h3 class="dtc-card__title">{{ $item['title'] }}</h3>

    @if(!empty($item['features']))
      <div class="dtc-card__features">
        @foreach(array_slice($item['features'], 0, 3) as $f)
          <div class="dtc-card__feature">
            <div class="dtc-card__check">
              <svg viewBox="0 0 9 9" fill="none">
                <path d="M1.5 4.5L3.5 6.5L7.5 2.5" stroke="#16A34A" stroke-width="1.5" stroke-linecap="round"/>
              </svg>
            </div>
            <span>
              @if(is_array($f))
                {{ $f['text'] ?? $f['title'] ?? '' }}
              @elseif(is_object($f))
                {{ $f->name ?? $f->post_title ?? '' }}
              @else
                {{ $f }}
              @endif
            </span>
          </div>
        @endforeach
      </div>
    @endif

    <div class="dtc-card__footer">
      <div class="dtc-card__price-block">
        @if(!empty($item['price_old']))
          <span class="dtc-card__price-old">{{ $item['price_old'] }} ₽</span>
        @endif
        @if(!empty($item['price']))
          <span class="dtc-card__price">
            {{ is_numeric(str_replace(' ', '', $item['price'])) ? $item['price'].' ₽' : $item['price'] }}
          </span>
        @endif
        @if(!empty($item['delivery']))
          <span class="dtc-card__delivery">{{ $item['delivery'] }}</span>
        @endif
      </div>
      <button class="dtc-card__btn">{{ $btn_text }}</button>
    </div>
  </div>

</a>
