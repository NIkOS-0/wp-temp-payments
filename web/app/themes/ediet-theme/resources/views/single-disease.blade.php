@extends('layouts.app')

@section('content')
@php
  // GET ACF FIELDS
  $icon = get_field('hero_icon') ?: '🫁';
  $eyebrow = get_field('hero_eyebrow') ?: 'Отрасль медицины';
  $hero_title = get_field('hero_title') ?: get_the_title();
  $desc = get_field('hero_desc') ?: get_the_excerpt();
  $stats = get_field('hero_stats') ?: [];
  $docs = get_field('recommended_doctors') ?: [];
  $clinics = get_field('recommended_clinics') ?: [];
  $lead_magnet = get_field('lead_magnet_file');
  
  $disease_id = get_the_ID();
  
  $mpos_assigned = function_exists('get_field') ? (get_field('products', $disease_id) ?: []) : [];
  $mpos = is_array($mpos_assigned) ? $mpos_assigned : [];
  $mpos_ids = array_map(function($m) { return is_object($m) ? $m->ID : (is_array($m) ? ($m['ID'] ?? null) : $m); }, $mpos);

  // GET RELATED PRODUCTS (Books) via robust array filtering
  $all_mpos = get_posts([
     'post_type' => 'book',
     'posts_per_page' => -1,
  ]);
  
  foreach($all_mpos as $m) {
      if (in_array($m->ID, $mpos_ids)) continue;
      
      $assigned = function_exists('get_field') ? get_field('related_diseases', $m->ID) : [];
      if (is_array($assigned)) {
          foreach($assigned as $a) {
              $a_id = is_object($a) ? $a->ID : (is_array($a) ? ($a['ID'] ?? null) : $a);
              if ($a_id == $disease_id) {
                  $mpos[] = $m;
                  $mpos_ids[] = $m->ID;
                  break;
              }
          }
      }
  }
  
  // GET RELATED DISEASES for sidebar
  $other_diseases = get_posts([
     'post_type' => 'disease',
     'posts_per_page' => 5,
     'post__not_in' => [get_the_ID()]
  ]);
@endphp

<style>
/* SCOPED CSS FOR DISEASE HUB */
.ds-hub {
  --bg:        #eef2f7;
  --surface:   #ffffff;
  --border:    #dde4ef;
  --border-s:  rgba(221,228,239,0.6);
  --text:      #0d1526;
  --sub:       #5b6a82;
  --muted:     #8fa0bb;
  --blue:      #2563eb;
  --blue-l:    #eff6ff;
  --blue-b:    #bfdbfe;
  --green:     #15803d;
  --green-l:   #f0fdf4;
  --orange:    #c2410c;
  --orange-l:  #fff7ed;
  --purple:    #6d28d9;
  --purple-l:  #f5f3ff;
  --r:         12px;
  --r-lg:      18px;
  --r-xl:      24px;
}
.ds-page { max-width: 1240px; margin: 0 auto; padding: 0 40px 100px; color: var(--text); font-family: 'Inter', sans-serif; }
.ds-breadcrumb { display: flex; align-items: center; gap: 6px; padding: 18px 0 0; font-size: 11.5px; color: var(--muted); }
.ds-breadcrumb a { color: var(--muted); transition: color 0.15s; }
.ds-breadcrumb a:hover { color: var(--blue); }
.ds-breadcrumb .sep { font-size: 10px; }
.ds-breadcrumb .cur { color: var(--text); font-weight: 500; }

.ds-sec-head { display: flex; align-items: baseline; justify-content: space-between; margin-bottom: 16px; }
.ds-sec-title { font-size: 18px; font-weight: 800; letter-spacing: -0.025em; }
.ds-sec-sub { font-size: 13px; color: var(--sub); margin-top: 2px; }
.ds-sec-sep { height: 1px; background: var(--border); margin: 36px 0; }

.ds-hero { margin-top: 20px; background: var(--surface); border: 1px solid var(--border); border-radius: var(--r-xl); padding: 36px 40px; display: grid; grid-template-columns: 1fr 340px; gap: 40px; align-items: center; position: relative; overflow: hidden; box-shadow: 0 1px 0 rgba(255,255,255,0.9) inset, 0 4px 24px rgba(0,0,0,0.04); }
.ds-hero::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, var(--orange) 0%, #f97316 40%, var(--blue) 100%); }
.ds-hero-bg-icon { position: absolute; right: 360px; top: 50%; transform: translateY(-50%); font-size: 160px; opacity: 0.04; pointer-events: none; user-select: none; line-height: 1; }
.ds-hero-eyebrow { display: inline-flex; align-items: center; gap: 6px; background: var(--orange-l); border: 1px solid #fed7aa; color: var(--orange); font-size: 10.5px; font-weight: 700; letter-spacing: 0.13em; text-transform: uppercase; padding: 4px 11px; border-radius: 50px; margin-bottom: 14px; }
.ds-hero-title { font-size: clamp(26px, 3vw, 38px); font-weight: 900; letter-spacing: -0.035em; line-height: 1.08; margin-bottom: 14px; }
.ds-hero-title em { font-style: italic; color: var(--blue); }
.ds-hero-desc { font-size: 13.5px; color: var(--sub); line-height: 1.75; max-width: 500px; margin-bottom: 22px; }
.ds-hero-stats { display: flex; gap: 20px; margin-bottom: 24px; padding: 14px 18px; background: var(--bg); border: 1px solid var(--border); border-radius: var(--r); width: fit-content; }
.ds-h-stat + .ds-h-stat { padding-left: 20px; border-left: 1px solid var(--border); }
.ds-h-stat-n { font-size: 20px; font-weight: 800; letter-spacing: -0.03em; line-height: 1; }
.ds-h-stat-l { font-size: 10.5px; color: var(--muted); margin-top: 1px; }

.ds-btn-primary { display: inline-flex; align-items: center; justify-content:center; gap: 7px; background: var(--text); color: #fff; padding: 11px 22px; border-radius: 10px; font-weight: 700; border: none; cursor: pointer; transition: background 0.18s; text-decoration:none !important; }
.ds-btn-primary:hover { background: var(--blue); color:#fff; }
.ds-btn-ghost { display: inline-flex; align-items: center; gap: 7px; background: var(--surface); color: var(--text); padding: 10px 18px; border-radius: 10px; font-weight: 600; border: 1.5px solid var(--border); cursor: pointer; transition: all 0.18s; }
.ds-btn-ghost:hover { border-color: var(--blue); color: var(--blue); }

.ds-hero-right { background: var(--bg); border: 1px solid var(--border); border-radius: var(--r-lg); padding: 20px; display: flex; flex-direction: column; gap: 8px; }
.ds-subcat-row { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: var(--r); background: var(--surface); border: 1px solid var(--border); cursor: pointer; transition: all 0.18s; text-decoration:none !important; color:inherit; }
.ds-subcat-row:hover { border-color: var(--blue); background: var(--blue-l); }
.ds-subcat-row:hover .sc-label { color: var(--blue); }
.ds-sc-icon { font-size: 20px; flex-shrink: 0; }
.ds-sc-label { font-size: 12.5px; font-weight: 600; }

/* Grid MPO */
.ds-mpo-grid { display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 21px; }
.ds-mpo-card { background: var(--surface); border: 1.5px solid var(--border); border-radius: var(--r-lg); overflow: hidden; cursor: pointer; transition: transform 0.25s, box-shadow 0.25s ease; display: flex; flex-direction: column; box-shadow: 0 1px 3px rgba(0,0,0,0.04); text-decoration:none !important; color:inherit; }
.ds-mpo-card:hover { transform: translateY(-5px); box-shadow: 0 12px 32px rgba(0,0,0,0.1); border-color: rgba(200,210,230,0.8); }
.ds-mpo-cover { height: 160px; display: flex; flex-direction: column; justify-content: flex-end; align-items: flex-start; padding: 14px 16px; position: relative; overflow: hidden; }
.ds-mpo-cover::after { content: ''; position: absolute; inset: 0; background: linear-gradient(160deg, transparent 30%, rgba(0,0,0,0.35) 100%); }
.ds-mpo-cover-title { position: relative; z-index: 2; font-size: 17px; font-weight: 800; color: #fff; line-height: 1.18; }
.ds-mpo-body { padding: 14px 16px 16px; flex: 1; display: flex; flex-direction: column; }
.ds-mpo-desc { font-size: 12px; color: var(--sub); line-height: 1.55; margin-bottom: 12px; }
.ds-mpo-footer { display: flex; align-items: center; justify-content: space-between; padding-top: 12px; border-top: 1px solid var(--border); margin-top:auto; }

/* Book Product Card from Single Book */
.book-product-card { background: var(--surface); border: 0.5px solid #B1B5C4; border-radius: 20px; overflow: hidden; display: flex; flex-direction: column; transition: box-shadow 0.2s; text-decoration: none; color: inherit; height: 100%; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
.book-product-card:hover { box-shadow: 0 8px 24px rgba(100,120,180,0.18); transform: translateY(-5px); border-color: rgba(200,210,230,0.8); }
.book-card-img { height: 260px; background: rgba(112,152,223,0.41); position: relative; display: flex; align-items: center; justify-content: center; overflow: hidden; }
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
.book-card-delivery { font-weight: 200; font-size: 12px; color: var(--sub); margin-top: 2px; }
.book-btn-card-buy { background: #000; color: #fff; border: none; border-radius: 10px; padding: 11.5px 18px; font-family: 'Inter', sans-serif; font-weight: 700; font-size: 12.5px; cursor: pointer; transition: opacity 0.15s; }

/* Consult / Clinics */
.ds-consult-row { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 12px; }
.ds-consult-card { background: var(--surface); border: 1.5px solid var(--border); border-radius: var(--r-lg); padding: 18px; display: flex; flex-direction: column; gap: 11px; box-shadow: 0 1px 3px rgba(0,0,0,0.03); }
.ds-cc-avatar { width: 44px; height: 44px; border-radius: 12px; background: var(--bg); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; font-size: 20px; overflow:hidden; }

/* Lead Magnet */
.ds-lead-block { background: var(--surface); border: 1px solid var(--border); border-radius: var(--r-xl); padding: 30px 34px; display: grid; grid-template-columns: 1fr 280px; gap: 36px; align-items: center; position: relative; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,0.03); }
</style>

<div class="ds-hub bg-[#F8F9FA] min-h-screen">
  <div class="ds-page">
    
    <!-- ── BREADCRUMB ── -->
    <div class="ds-breadcrumb">
      <a href="/">Главная</a><span class="sep">›</span>
      <span class="cur">{{ get_the_title() }}</span>
    </div>
    
    <!-- ── HERO ── -->
    <div class="ds-hero">
      <div class="ds-hero-bg-icon">
        <img src="{{ $icon }}" class="w-full h-full object-contain" alt="">
      </div>
      <div>
        <div class="ds-hero-eyebrow">
          <img src="{{ $icon }}" class="w-3.5 h-3.5 object-contain mr-1 inline-block -mt-0.5" alt="">
          {{ $eyebrow }}
        </div>
        <h1 class="ds-hero-title">{!! $hero_title !!}</h1>
        <div class="ds-hero-desc">{!! $desc !!}</div>
        
        @if(!empty($stats))
        <div class="ds-hero-stats">
          @foreach($stats as $stat)
          <div class="ds-h-stat">
            <div class="ds-h-stat-n" style="color:{{ $stat['color'] ?? 'var(--text)' }}">{{ $stat['value'] }}</div>
            <div class="ds-h-stat-l">{{ $stat['label'] }}</div>
          </div>
          @endforeach
        </div>
        @endif
        
        <div class="flex gap-3 mt-4">
          @php
             $btn1 = get_field('hero_primary_link', get_the_ID());
             $btn2 = get_field('hero_secondary_link', get_the_ID());
          @endphp
          @if($btn1)
             <a href="{{ $btn1['url'] }}" target="{{ $btn1['target'] }}" class="ds-btn-primary">{{ $btn1['title'] }}</a>
          @else
             <a href="#mpo-catalog" class="ds-btn-primary">📋 Смотреть планы МПО</a>
          @endif

          @if($btn2)
             <a href="{{ $btn2['url'] }}" target="{{ $btn2['target'] }}" class="ds-btn-ghost">{{ $btn2['title'] }}</a>
          @else
             <button class="ds-btn-ghost" onclick="alert('Форма записи в разработке')">💬 Консультация</button>
          @endif
        </div>
      </div>
      
      <div class="ds-hero-right">
        <div class="text-[11px] font-bold tracking-widest uppercase text-[#8fa0bb] mb-1">Связанные Диагнозы</div>
        @foreach($other_diseases as $od)
          <a href="{{ get_permalink($od->ID) }}" class="ds-subcat-row">
            <span class="ds-sc-icon">{{ function_exists('get_field') ? (get_field('hero_icon', $od->ID) ?: '🧬') : '🧬' }}</span>
            <div>
              <div class="ds-sc-label">{{ $od->post_title }}</div>
            </div>
          </a>
        @endforeach
      </div>
    </div>
    
    <!-- ── CATALOG MPO ── -->
    <div class="ds-sec-head mt-12" id="mpo-catalog">
      <div>
        <div class="ds-sec-title">Доступные продукты</div>
        <div class="ds-sec-sub">Протоколы специально для: {{ get_the_title() }}</div>
      </div>
    </div>
    
    <div class="ds-mpo-grid">
       @if(count($mpos) > 0)
         @foreach($mpos as $mpo)
           @php 
             $price = function_exists('get_field') ? get_field('price', $mpo->ID) : '2 490';
             $price_old = function_exists('get_field') ? get_field('book_price_old', $mpo->ID) : '';
             $image = get_the_post_thumbnail_url($mpo->ID, 'medium') ?: '';
             $badge = function_exists('get_field') ? get_field('ps_card_badge', $mpo->ID) : '';
             $features = function_exists('get_field') ? get_field('book_features', $mpo->ID) : [];
             $delivery = function_exists('get_field') ? get_field('book_delivery_note', $mpo->ID) : '';
             $type_label = $mpo->post_type === 'course' ? 'Курс' : 'Книга';
           @endphp

          <a href="{{ get_permalink($mpo->ID) }}" class="book-product-card" style="position: relative;">
            <div class="book-card-img" style="{{ !empty($image) ? 'background-image: url('.$image.'); background-size: cover; background-position: center;' : '' }}">
              @if(empty($image))
                <div class="book-card-img-overlay"></div>
                <div class="book-card-book" @if($type_label === 'Курс') style="background: linear-gradient(135deg, #10B981 0%, #047857 100%)" @endif>
                  <div class="book-card-book-title">{!! nl2br(esc_html($mpo->post_title)) !!}</div>
                </div>
              @endif
              
              @if(!empty($badge))
                <span style="position: absolute; top: 12px; left: 12px; background: #EF4444; color: #fff; padding: 4px 12px; border-radius: 8px; font-weight: 700; font-size: 11px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); z-index: 10;">{{ $badge }}</span>
              @endif
            </div>
            
            <div class="book-card-body">
              <div class="book-card-features">
                @if(!empty($features))
                  @foreach(array_slice($features, 0, 4) as $f)
                    <div class="book-card-feature"><div class="book-card-check"><svg viewBox="0 0 9 9" fill="none"><path d="M1.5 4.5L3.5 6.5L7.5 2.5" stroke="#16A34A" stroke-width="1.5" stroke-linecap="round"/></svg></div>{{ is_array($f) ? ($f['text'] ?? $f['title'] ?? 'Полезный материал') : 'Полезный материал' }}</div>
                  @endforeach
                @else
                  <div class="book-card-feature"><div class="book-card-check"><svg viewBox="0 0 9 9" fill="none"><path d="M1.5 4.5L3.5 6.5L7.5 2.5" stroke="#16A34A" stroke-width="1.5" stroke-linecap="round"/></svg></div>Полезный материал</div>
                @endif
              </div>
              <div class="book-card-footer">
                <div style="position: relative;">
                  @if(!empty($price_old))
                    <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 2px;">
                      <span style="font-size: 13.5px; text-decoration: line-through; color: #94A3B8;">{{ $price_old }} ₽</span>
                      @php 
                        $p = (float)str_replace([' ', ','], ['', '.'], $price);
                        $po = (float)str_replace([' ', ','], ['', '.'], $price_old);
                      @endphp
                      @if($po > 0 && $p < $po)
                        <span style="background: #FEE2E2; border-radius: 4px; padding: 2px 6px; font-weight: 700; font-size: 10px; color: #991B1B;">−{{ round((1 - ($p / $po)) * 100) }}%</span>
                      @endif
                    </div>
                  @endif
                  <div class="book-card-price">{{ $price }} ₽</div>
                  <div class="book-card-delivery">{{ $delivery }}</div>
                </div>
                <button class="book-btn-card-buy">{{ $type_label === 'Курс' ? 'Участвовать' : 'Купить' }}</button>
              </div>
            </div>
          </a>
         @endforeach
       @else
         <div class="col-span-3 p-8 border-2 border-dashed border-slate-300 rounded-2xl text-center">
            <p class="text-slate-500 font-bold">Для этого диагноза пока нет доступных планов МПО.</p>
         </div>
       @endif
    </div>

    <div class="ds-sec-sep"></div>
    
    <!-- ── CONSULTS ── -->
    <div class="ds-sec-head">
      <div>
        <div class="ds-sec-title">Реестр специалистов</div>
        <div class="ds-sec-sub">Врачи интегративной медицины, курирующие данное направление</div>
      </div>
    </div>
    
    <div class="ds-consult-row">
      @if(!empty($docs))
        @foreach($docs as $doc)
          @php $spec = function_exists('get_field') ? get_field('specialization', $doc->ID) : 'Врач-нутрициолог'; @endphp
          <div class="ds-consult-card">
            <div class="flex items-center gap-3">
              <div class="ds-cc-avatar">
                @if(has_post_thumbnail($doc->ID))
                  {!! get_the_post_thumbnail($doc->ID, 'thumbnail', ['class' => 'w-full h-full object-cover']) !!}
                @else
                  👨⚕️
                @endif
              </div>
              <div>
                <div class="text-[13.5px] font-bold">{{ $doc->post_title }}</div>
                <div class="text-[11px] text-blue-600 font-medium">{{ $spec }}</div>
              </div>
            </div>
            <div class="flex items-center justify-between pt-2 border-t border-slate-100 mt-1">
              <div class="text-[15px] font-black">4 900 ₽</div>
              <button class="bg-slate-900 text-white rounded-lg px-3 py-1.5 text-xs font-bold hover:bg-blue-600 transition-colors">Записаться</button>
            </div>
          </div>
        @endforeach
      @else
        <div class="col-span-3 p-6 bg-white border border-slate-200 rounded-xl text-center text-slate-500 text-sm">Врачи временно не прикреплены к этому диагнозу.</div>
      @endif
    </div>
    
    <!-- ── CLINICS ── -->
    @if(!empty($clinics))
      <div class="ds-sec-sep"></div>
      <div class="ds-sec-head">
        <div><div class="ds-sec-title">🏥 Реестр клиник</div><div class="ds-sec-sub">Рекомендованные учреждения и партнеры</div></div>
      </div>
      <div class="ds-consult-row">
        @foreach($clinics as $clinic)
          <div class="ds-consult-card">
            <div class="flex items-center gap-2">
              <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-lg shrink-0">🏥</div>
              <div>
                <div class="text-sm font-bold leading-tight">{{ $clinic->post_title }}</div>
              </div>
            </div>
            <a href="{{ get_permalink($clinic->ID) }}" class="mt-2 block w-full text-center bg-slate-50 border border-slate-200 rounded-lg py-2 text-xs font-bold hover:border-blue-500 hover:text-blue-600 transition-colors">Перейти в карточку →</a>
          </div>
        @endforeach
      </div>
    @endif
    
    <!-- ── LEAD MAGNET ── -->
    @if(!empty($lead_magnet))
      <div class="ds-sec-sep"></div>
      <div class="ds-lead-block">
        <div class="absolute right-0 top-1/2 -translate-y-1/2 w-[200px] h-[200px] bg-green-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div>
          <div class="text-[10.5px] font-bold tracking-widest uppercase text-green-700 mb-2">🎁 Бесплатно</div>
          <div class="text-lg font-extrabold tracking-tight mb-2">Гайд по самодиагностике и анализам</div>
          <p class="text-[13px] text-slate-500 leading-relaxed">Скачайте бесплатный справочник, который поможет вам разобраться с базовыми показателями без медицинского образования.</p>
        </div>
        <div class="flex flex-col gap-2">
          <input type="email" placeholder="Ваш e-mail" class="px-3 py-2.5 border-2 border-slate-200 rounded-lg text-sm outline-none focus:border-blue-500 transition-colors">
          <button class="bg-green-600 hover:bg-green-700 text-white rounded-lg px-4 py-2.5 text-sm font-bold transition-colors">📥 Получить гайд</button>
          <div class="text-[10px] text-center text-slate-400">Только польза, никакого спама</div>
        </div>
      </div>
    @endif

  </div>
</div>
@endsection
