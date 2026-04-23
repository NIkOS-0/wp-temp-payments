<a href="{{ $item['url'] }}" class="dtc-card dtc-card--wide">

  {{-- ── IMAGE ── --}}
  <div class="dtc-card__img">
    @if(!empty($item['author']['avatar']))
      <img src="{{ $item['author']['avatar'] }}" alt="{{ $item['author']['name'] ?? $item['title'] }}">
    @elseif(!empty($item['image']))
      <img src="{{ $item['image'] }}" alt="{{ esc_attr($item['title']) }}">
    @else
      <div style="width:100%;height:100%;background:linear-gradient(135deg,#EBE3D2 0%,#ddd2be 100%);display:flex;align-items:center;justify-content:center;color:rgba(42,26,16,0.2);">
        <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="0.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
      </div>
    @endif

    @if(!empty($item['badge']))
      <span class="dtc-card__badge">{{ $item['badge'] }}</span>
    @endif

    @if(!empty($item['id']))
      <div class="absolute top-3 right-3 z-20" onclick="event.preventDefault();">
        @include('partials.favorite-btn', ['post_id' => $item['id'], 'class' => '!w-8 !h-8 bg-white/80 backdrop-blur-sm rounded-full shadow-sm'])
      </div>
    @endif
  </div>

  {{-- ── BODY ── --}}
  <div class="dtc-card__body">

    {{-- Tags --}}
    <div class="dtc-card__tags">
      <span class="dtc-card__tag dtc-card__tag--doctor">Консультация</span>
      @if(!empty($item['price_old']) && !empty($item['price']))
        @php
          $p  = (float) str_replace([' ', ','], ['', '.'], $item['price']);
          $po = (float) str_replace([' ', ','], ['', '.'], $item['price_old']);
          $d  = ($po > 0 && $p < $po) ? round((1 - $p / $po) * 100) : 0;
        @endphp
        @if($d > 0)
          <span class="dtc-card__tag dtc-card__tag--sale">−{{ $d }}%</span>
        @endif
      @endif
    </div>

    {{-- Doctor info or title --}}
    @if(!empty($item['author']['name']))
      <div>
        <h3 class="dtc-card__title">{{ $item['author']['name'] }}</h3>
        <p style="font-size:12px;color:#EF945B;font-weight:600;margin-top:3px;">{{ $item['author']['specialty'] ?? 'Специалист' }}</p>
        @if(!empty($item['author']['experience']))
          <p style="font-size:11px;color:#A89F8B;margin-top:2px;">{{ $item['author']['experience'] }}</p>
        @endif
      </div>
    @else
      <h3 class="dtc-card__title">{{ $item['title'] }}</h3>
    @endif

    {{-- Features --}}
    @if(!empty($item['features']))
      <div class="dtc-card__features">
        @foreach(array_slice($item['features'], 0, 3) as $f)
          <div class="dtc-card__feature">
            <div class="dtc-card__check">
              <svg viewBox="0 0 9 9" fill="none">
                <path d="M1.5 4.5L3.5 6.5L7.5 2.5" stroke="#16A34A" stroke-width="1.5" stroke-linecap="round"/>
              </svg>
            </div>
            {{ $f['text'] ?? $f['title'] ?? '' }}
          </div>
        @endforeach
      </div>
    @endif

    {{-- Footer --}}
    <div class="dtc-card__footer">
      <div class="dtc-card__price-block">
        @if(!empty($item['price_old']))
          <span class="dtc-card__price-old">{{ $item['price_old'] }} ₽</span>
        @endif
        @if(!empty($item['price']))
          <span class="dtc-card__price">
            {{ is_numeric(str_replace(' ', '', $item['price'])) ? number_format((float) $item['price'], 0, '.', ' ') . ' ₽' : $item['price'] }}
          </span>
        @endif
        <span class="dtc-card__delivery">{{ $item['delivery'] ?: 'Онлайн / Zoom / Telegram' }}</span>
      </div>
      <button class="dtc-card__btn">Записаться</button>
    </div>

  </div>

</a>
