<a href="{{ $item['url'] }}" class="book-product-card" style="position: relative;">
  <div class="book-card-img" style="{{ !empty($item['image']) ? 'background-image: url('.$item['image'].'); background-size: cover; background-position: center;' : '' }}">
    @if(empty($item['image']))
      <div class="book-card-img-overlay"></div>
      <div class="book-card-book">
        <div class="book-card-book-title">{!! nl2br(esc_html($item['title'])) !!}</div>
      </div>
    @endif
    
    @if(!empty($item['badge']))
      <span style="position: absolute; top: 12px; left: 12px; background: #EF4444; color: #fff; padding: 4px 12px; border-radius: 8px; font-weight: 700; font-size: 11px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); z-index: 10;">{{ $item['badge'] }}</span>
    @endif
  </div>
  
  <div class="book-card-body">
    <div style="font-size:10px; font-weight:700; color:var(--muted); letter-spacing:0.1em; text-transform:uppercase; margin-bottom:4px; margin-top: 14px;">МПО (План)</div>
    <h3 style="font-size: 14px; font-weight: 700; margin-bottom: 4px; line-height: 1.35;">{{ $item['title'] }}</h3>

    <div class="book-card-features">
      @foreach($item['features'] as $f)
        <div class="book-card-feature">
          <div class="book-card-check">
            <svg viewBox="0 0 9 9" fill="none"><path d="M1.5 4.5L3.5 6.5L7.5 2.5" stroke="#16A34A" stroke-width="1.5" stroke-linecap="round"/></svg>
          </div>
          {{ $f['text'] ?? $f['title'] ?? 'Полезный материал' }}
        </div>
      @endforeach
    </div>
    <div class="book-card-footer">
      <div style="position: relative;">
        @if(!empty($item['price_old']))
          <div style="font-size: 12px; text-decoration: line-through; color: #94A3B8; margin-bottom: -2px;">{{ $item['price_old'] }} ₽</div>
        @endif
        @if($item['price'])
        <div class="book-card-price">{{ $item['price'] }} ₽</div>
        @endif
        @if($item['delivery'])
        <div class="book-card-delivery">{{ $item['delivery'] }}</div>
        @endif
      </div>
      <button class="book-btn-card-buy">{{ $item['type_label'] === 'Курс' ? 'Участвовать' : 'Купить' }}</button>
    </div>
  </div>
</a>
