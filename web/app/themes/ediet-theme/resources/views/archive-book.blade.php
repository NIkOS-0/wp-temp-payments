@extends('layouts.app')

@section('content')
  <style>
    /* Add generic styles for archive */
    .books-archive {
      max-width: 1160px;
      margin: 0 auto;
      padding: 40px 20px 100px;
      font-family: 'Inter', sans-serif;
    }
    .books-archive-title {
      font-family: 'Public Sans', sans-serif;
      font-weight: 800;
      font-size: 42px;
      margin-bottom: 32px;
      color: #0F172A;
    }
    
    .book-cards-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 21px; position: relative; }
    @media (max-width: 1024px) {
      .book-cards-row { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 768px) {
      .book-cards-row { grid-template-columns: repeat(2, 1fr); }
      .books-archive-title { font-size: 32px; }
    }
    @media (max-width: 480px) {
      .book-cards-row { grid-template-columns: 1fr; }
    }
    
    .book-product-card { background: #FFFFFF; border: 0.5px solid #B1B5C4; border-radius: 20px; overflow: hidden; display: flex; flex-direction: column; transition: box-shadow 0.2s; text-decoration: none; }
    .book-product-card:hover { box-shadow: 0 8px 24px rgba(100,120,180,0.18); }
    
    .book-card-img { height: 295px; background: rgba(112,152,223,0.41); position: relative; display: flex; align-items: center; justify-content: center; overflow: hidden; }
    .book-card-img-overlay { position: absolute; inset: 0; background: rgba(151,151,151,0.2); box-shadow: inset 0 0 15px rgba(0,0,0,0.25); }
    .book-card-book { width: 130px; height: 182px; background: linear-gradient(144.46deg, #5BB8E8 0%, #3A9BD5 100%); box-shadow: -6px 6px 18px rgba(0,0,0,0.25), 3px 0 0 rgba(0,0,0,0.1); border-radius: 4px 10px 10px 4px; position: relative; z-index: 1; display: flex; flex-direction: column; justify-content: flex-end; padding: 14px 12px; }
    .book-card-book::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 9px; background: rgba(0,0,0,0.15); border-radius: 4px 0 0 4px; }
    .book-card-book-title { font-weight: 700; font-size: 12px; color: #fff; text-shadow: 0 1px 4px rgba(0,0,0,0.2); padding-left: 10px; line-height: 1.3; }
    
    .book-card-body { padding: 0 20px 16px; display: flex; flex-direction: column; flex: 1; }
    .book-card-features { display: flex; flex-direction: column; gap: 6px; padding: 12px 0; flex: 1; }
    .book-card-feature { display: flex; align-items: center; gap: 8px; font-weight: 200; font-size: 13px; color: #000; }
    .book-card-check { width: 18px; height: 18px; background: #DCFCE7; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .book-card-check svg { width: 9px; height: 9px; }
    .book-card-footer { border-top: 0.5px solid #D9D9D9; padding-top: 12px; display: flex; align-items: center; justify-content: space-between; gap: 8px;}
    .book-card-price { font-weight: 600; font-size: 20px; letter-spacing: -0.4px; color: #000; }
    .book-card-delivery { font-weight: 200; font-size: 12px; color: #64748B; margin-top: 2px; }
    .book-btn-card-buy { background: #000; color: #fff; border: none; border-radius: 10px; padding: 11.5px 18px; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 12.5px; cursor: pointer; transition: opacity 0.15s; }
    .book-btn-card-buy:hover { opacity: 0.85; }

    /* Breadcrumbs */
    .books-breadcrumb { display: flex; align-items: center; gap: 8px; margin-bottom: 24px; font-size: 11.5px; color: #757575; }
    .books-breadcrumb a { color: #757575; text-decoration: none; }
    .books-breadcrumb .sep { font-size: 12px; }
    .books-breadcrumb .current { font-weight: 500; color: #000; font-size: 12px; }

    /* Pagination */
    .pagination-books { display: flex; gap: 8px; align-items: center; margin-top: 40px; }
    .pagination-books .page-numbers {
      display: flex; align-items: center; justify-content: center; width: 40px; height: 40px;
      border-radius: 8px; font-weight: 600; font-size: 14px; color: #0F172A; text-decoration: none;
      background: #fff; border: 1px solid #E2E8F0; transition: all 0.2s;
    }
    .pagination-books .page-numbers:hover { background: #F1F5F9; border-color: #CBD5E1; }
    .pagination-books .page-numbers.current { background: #0F172A; color: #fff; border-color: #0F172A; pointer-events: none; }
    .pagination-books .page-numbers.prev, .pagination-books .page-numbers.next { width: auto; padding: 0 16px; }
    .pagination-books h2 { display: none; } /* Hide visually hidden h2 */
  </style>

  <div class="bg-[#EFF2F6] min-h-screen">
    <div class="books-archive">
      
      <!-- BREADCRUMB -->
      <nav class="books-breadcrumb">
        <a href="/">Главная</a>
        <span class="sep">/</span>
        <span class="current">Книги</span>
      </nav>

      <h1 class="books-archive-title">Книги</h1>
      
      @if(count($books) > 0)
        <div class="book-cards-row">
          @foreach($books as $cs)
            <a href="{{ $cs['url'] }}" class="book-product-card" style="position: relative;">
              <div class="book-card-img" style="{{ !empty($cs['image']) ? 'background-image: url('.$cs['image'].'); background-size: cover; background-position: center;' : '' }}">
                @if(empty($cs['image']))
                  <div class="book-card-img-overlay"></div>
                  <div class="book-card-book" @if($cs['type_label'] === 'Курс') style="background: linear-gradient(135deg, #10B981 0%, #047857 100%)" @endif>
                    <div class="book-card-book-title">{!! nl2br(esc_html($cs['title'])) !!}</div>
                  </div>
                @endif
                
                @if(!empty($cs['badge']))
                  <span style="position: absolute; top: 12px; left: 12px; background: #EF4444; color: #fff; padding: 4px 12px; border-radius: 8px; font-weight: 700; font-size: 11px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); z-index: 10;">{{ $cs['badge'] }}</span>
                @endif
              </div>
              
              <div class="book-card-body">
                <div class="book-card-features">
                  @foreach($cs['features'] as $f)
                    <div class="book-card-feature"><div class="book-card-check"><svg viewBox="0 0 9 9" fill="none"><path d="M1.5 4.5L3.5 6.5L7.5 2.5" stroke="#16A34A" stroke-width="1.5" stroke-linecap="round"/></svg></div>{{ $f['text'] ?? $f['title'] ?? 'Полезный материал' }}</div>
                  @endforeach
                </div>
                <div class="book-card-footer">
                  <div style="position: relative;">
                    @if(!empty($cs['price_old']))
                      <div style="font-size: 13.5px; text-decoration: line-through; color: #94A3B8; margin-bottom: -2px;">{{ $cs['price_old'] }} ₽</div>
                    @endif
                    @if($cs['price'])
                    <div class="book-card-price">{{ $cs['price'] }} ₽</div>
                    @endif
                    @if($cs['delivery'])
                    <div class="book-card-delivery">{{ $cs['delivery'] }}</div>
                    @endif
                  </div>
                  <button class="book-btn-card-buy">Купить</button>
                </div>
              </div>
            </a>
          @endforeach
        </div>
        
        <div class="mt-16 flex justify-center">
            {!! get_the_posts_pagination([
                'prev_text' => '&larr; Назад',
                'next_text' => 'Вперёд &rarr;',
                'class' => 'pagination-books'
            ]) !!}
        </div>
      @else
        <div class="p-12 text-center border-2 border-dashed border-slate-300 rounded-2xl text-slate-500 font-medium text-lg">
           Пока нет доступных книг.
        </div>
      @endif

    </div>
  </div>
@endsection
