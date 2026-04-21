<a href="{{ $item['url'] }}" class="consult-card">
  @if(!empty($item['badge']))
  <div class="cc-rec">{{ $item['badge'] }}</div>
  @endif
  <div class="cc-doc">
    <div class="cc-avatar">
      @if(!empty($item['author']['avatar']))
        <img src="{{ $item['author']['avatar'] }}" alt="{{ $item['author']['name'] }}">
      @else
        <span>👨‍⚕️</span>
      @endif
    </div>
    <div>
      <div class="cc-name">{{ !empty($item['author']['name']) ? $item['author']['name'] : $item['title'] }}</div>
      <div class="cc-spec">{{ $item['author']['specialty'] ?? 'Специалист' }}</div>
      <div class="cc-exp">{{ $item['author']['experience'] ?? '' }}</div>
    </div>
  </div>
  
  <p class="mpo-card-desc" style="font-size: 11.5px; color: var(--sub); margin: 6px 0 0 0;">{{ wp_trim_words($item['excerpt'], 10) }}</p>

  <div class="cc-tags">
    @foreach($item['features'] as $f)
      <span class="cc-tag">{{ $f['text'] ?? $f['title'] ?? 'Консультация' }}</span>
    @endforeach
  </div>
  <div class="cc-foot">
    <div style="position: relative;">
      @if(!empty($item['price_old']))
        <div style="font-size: 11.5px; text-decoration: line-through; color: #94A3B8; margin-bottom: -2px;">{{ $item['price_old'] }} ₽</div>
      @endif
      <div class="cc-price">{{ $item['price'] ? $item['price'] . ' ₽' : 'Бесплатно' }}</div>
      <div class="cc-price-sub">{{ $item['delivery'] ?: 'Онлайн / Zoom / Telegram' }}</div>
    </div>
    <button class="cc-btn">Записаться</button>
  </div>
</a>
