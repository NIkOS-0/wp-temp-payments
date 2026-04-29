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
  
  // SIDEBAR BANNER
  $hero_banner = function_exists('get_field') ? get_field('hero_banner_image') : null;
@endphp

<style>
/* SCOPED CSS FOR DISEASE HUB */
.ds-hub {
  --bg:        #F5EFE2;
  --surface:   #ffffff;
  --border:    rgba(42,26,16,0.10);
  --border-s:  rgba(42,26,16,0.06);
  --text:      #2A1A10;
  --sub:       #6a5040;
  --muted:     #A89F8B;
  --terra:     #EF945B;
  --terra-d:   #D87A4A;
  --terra-l:   #FDFBF7;
  --r:         12px;
  --r-lg:      20px;
  --r-xl:      24px;
}
.ds-page { max-width: 1240px; margin: 0 auto; color: var(--text); font-family: 'Instrument Sans', 'Inter', sans-serif; }

.ds-sec-head { display: flex; align-items: baseline; justify-content: space-between; margin-bottom: 20px; }
.ds-sec-title { font-family: 'Playfair Display', serif; font-size: 28px; font-weight: 700; letter-spacing: -0.02em; }
.ds-sec-sub { font-size: 14px; color: var(--sub); margin-top: 4px; }
.ds-sec-sep { height: 1px; background: var(--border); margin: 40px 0; }

.ds-hero { margin-top: 20px; background: var(--surface); border: 1px solid var(--border); border-radius: 24px; padding: 36px 40px; display: grid; grid-template-columns: 1fr 340px; gap: 40px; align-items: center; position: relative; overflow: hidden; box-shadow: 0 4px 24px rgba(42,26,16,0.06); }
.ds-hero::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px; background: linear-gradient(90deg, #EF945B 0%, #D87A4A 45%, #EBE3D2 100%); }
.ds-hero-bg-icon { position: absolute; right: 360px; top: 50%; transform: translateY(-50%); font-size: 160px; opacity: 0.03; pointer-events: none; user-select: none; line-height: 1; filter: grayscale(1); }
.ds-hero-eyebrow { display: inline-flex; align-items: center; gap: 6px; background: #EBE3D2; border: 1px solid rgba(42,26,16,0.1); color: #6a5040; font-size: 10.5px; font-weight: 700; letter-spacing: 0.13em; text-transform: uppercase; padding: 4px 11px; border-radius: 50px; margin-bottom: 14px; }
.ds-hero-title { font-family: 'Playfair Display', serif; font-size: clamp(26px, 3.5vw, 42px); font-weight: 700; letter-spacing: -0.02em; line-height: 1.15; margin-bottom: 14px; }
.ds-hero-title em { font-style: italic; color: var(--terra); }
.ds-hero-desc { font-size: 14.5px; color: var(--sub); line-height: 1.75; max-width: 500px; margin-bottom: 24px; }
.ds-hero-stats { display: flex; gap: 20px; margin-bottom: 24px; padding: 14px 18px; border: 1.5px solid rgba(42,26,16,0.08); border-radius: var(--r); width: fit-content; background: #faf8f5;}
.ds-h-stat + .ds-h-stat { padding-left: 20px; border-left: 1.5px solid rgba(42,26,16,0.08); }
.ds-h-stat-n { font-size: 20px; font-weight: 700; letter-spacing: -0.03em; line-height: 1; color: var(--text); }
.ds-h-stat-l { font-size: 11px; color: var(--muted); margin-top: 3px; text-transform: uppercase; letter-spacing: 0.05em;}

.ds-btn-primary { display: inline-flex; align-items: center; justify-content:center; gap: 7px; background: var(--text); color: #F5EFE2; padding: 12px 24px; border-radius: 50px; font-weight: 600; font-size:14px; border: none; cursor: pointer; transition: opacity 0.18s; text-decoration:none !important; }
.ds-btn-primary:hover { opacity: 0.85; }
.ds-btn-ghost { display: inline-flex; align-items: center; gap: 7px; background: transparent; color: var(--text); padding: 11px 24px; border-radius: 50px; font-weight: 600; font-size:14px; border: 1.5px solid var(--border); cursor: pointer; transition: all 0.18s; }
.ds-btn-ghost:hover { border-color: var(--text); }

.ds-hero-banner { background: #EBE3D2; border: 1px solid var(--border); border-radius: var(--r-lg); position: relative; overflow: hidden; display: flex; align-items: center; justify-content: center; min-height: 280px; box-shadow: inset 0 2px 14px rgba(42,26,16,0.03); }
.hero-mesh { position: absolute; inset: 0; z-index: 0; pointer-events: none; overflow: hidden; }
.hero-blob { position: absolute; border-radius: 50%; filter: blur(50px); will-change: transform; opacity: 0.65; mix-blend-mode: multiply; }
.hero-blob.a { width: 300px; height: 300px; top: -100px; right: -50px; background: rgba(239, 148, 91, 0.4); animation: hero-dr-a 14s ease-in-out infinite alternate; }
.hero-blob.b { width: 280px; height: 280px; bottom: -80px; left: -80px; background: rgba(139, 167, 127, 0.35); animation: hero-dr-b 18s ease-in-out infinite alternate-reverse; }
@keyframes hero-dr-a { 0% { transform: translate(0, 0) scale(1); } 100% { transform: translate(-30px, 40px) scale(1.1); } }
@keyframes hero-dr-b { 0% { transform: translate(0, 0) scale(1); } 100% { transform: translate(40px, -30px) scale(1.15); } }

/* Grid MPO (Book Card) */
.ds-mpo-grid { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 16px; }
@media (max-width: 900px) { .ds-mpo-grid { grid-template-columns: repeat(2, 1fr) !important; } }
@media (max-width: 600px) { .ds-mpo-grid { grid-template-columns: 1fr !important; } }

.book-product-card { background: var(--surface); border: 1px solid var(--border); border-radius: 20px; overflow: hidden; display: flex; flex-direction: column; transition: box-shadow 0.2s, transform 0.2s; text-decoration: none; color: inherit; height: 100%; box-shadow: 0 4px 12px rgba(42,26,16,0.03); }
.book-product-card:hover { box-shadow: 0 12px 30px rgba(42,26,16,0.08); transform: translateY(-4px); border-color: rgba(42,26,16,0.22); }
.book-card-img { height: 260px; background: #EBE3D2; position: relative; display: flex; align-items: center; justify-content: center; overflow: hidden; border-bottom: 1px solid var(--border); }
.book-card-img-overlay { position: absolute; inset: 0; background: rgba(42,26,16,0.04); }
.book-card-book { width: 130px; height: 182px; background: linear-gradient(144.46deg, #EF945B 0%, #D87A4A 100%); box-shadow: -6px 6px 18px rgba(42,26,16,0.15), 3px 0 0 rgba(0,0,0,0.05); border-radius: 4px 10px 10px 4px; position: relative; z-index: 1; display: flex; flex-direction: column; justify-content: flex-end; padding: 14px 12px; }
.book-card-book::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 9px; background: rgba(0,0,0,0.15); border-radius: 4px 0 0 4px; }
.book-card-book-title { font-weight: 700; font-size: 13px; font-family:'Playfair Display',serif; color: #fff; text-shadow: 0 1px 4px rgba(0,0,0,0.2); padding-left: 10px; line-height: 1.25; }
.book-card-body { padding: 0 20px 16px; display: flex; flex-direction: column; flex: 1; }
.book-card-features { display: flex; flex-direction: column; gap: 6px; padding: 14px 0; flex: 1; }
.book-card-feature { display: flex; align-items: center; gap: 8px; font-weight: 400; font-size: 13px; color: var(--sub); }
.book-card-check { width: 18px; height: 18px; background: rgba(239, 148, 91, 0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.book-card-check svg { width: 9px; height: 9px; }
.book-card-footer { border-top: 1px solid rgba(42,26,16,0.08); padding-top: 14px; display: flex; align-items: center; justify-content: space-between; gap: 8px;}
.book-card-price { font-weight: 700; font-size: 20px; letter-spacing: -0.02em; color: var(--text); }
.book-card-delivery { font-weight: 400; font-size: 12px; color: var(--muted); margin-top: 2px; }
.book-btn-card-buy { background: var(--text); color: #F5EFE2; border: none; border-radius: 50px; padding: 10px 18px; font-family: 'Instrument Sans', sans-serif; font-weight: 600; font-size: 13px; cursor: pointer; transition: opacity 0.15s; }

/* Consult / Clinics */
.ds-consult-row { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 16px; }
.ds-consult-card { background: var(--surface); border: 1px solid var(--border); border-radius: 20px; padding: 20px; display: flex; flex-direction: column; gap: 11px; box-shadow: 0 4px 12px rgba(42,26,16,0.03); transition: transform 0.2s, box-shadow 0.2s;}
.ds-consult-card:hover { transform: translateY(-3px); box-shadow: 0 12px 30px rgba(42,26,16,0.06); border-color: rgba(42,26,16,0.15); }
.ds-cc-avatar { width: 44px; height: 44px; border-radius: 12px; background: #EBE3D2; display: flex; align-items: center; justify-content: center; font-size: 20px; overflow:hidden; }

/* Lead Magnet */
.ds-lead-block { background: var(--surface); border: 1px solid var(--border); border-radius: var(--r-xl); padding: 32px 36px; display: grid; grid-template-columns: 1fr 280px; gap: 36px; align-items: center; position: relative; overflow: hidden; box-shadow: 0 4px 24px rgba(42,26,16,0.04); }
</style>

<div class="ds-hub" style="background:var(--bg);min-height:100vh;">
  <div class="ds-page py-8 pb-24">
    
    <!-- ── BREADCRUMB ── -->
    <nav class="flex items-center gap-1.5 text-xs mb-8" style="color:var(--muted);">
      <a href="/" style="color:var(--muted);" class="hover:text-terra-500 transition-colors duration-150">Главная</a>
      <svg width="10" height="10" viewBox="0 0 10 10" fill="none" style="opacity:.5;"><path d="M3.5 1.5L6.5 5L3.5 8.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
      <span style="color:var(--text);font-weight:500;">{{ get_the_title() }}</span>
    </nav>
    
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
             <a href="#mpo-catalog" class="ds-btn-primary">Смотреть продукты</a>
          @endif

          @if($btn2)
             <a href="{{ $btn2['url'] }}" target="{{ $btn2['target'] }}" class="ds-btn-ghost">{{ $btn2['title'] }}</a>
          @else
             <button class="ds-btn-ghost" onclick="alert('Форма записи в разработке')">Консультация</button>
          @endif
        </div>
      </div>
      
      <div class="ds-hero-banner">
        <!-- Floating blobs ambient background -->
        <div class="hero-mesh">
          <div class="hero-blob a"></div>
          <div class="hero-blob b"></div>
        </div>
        
        @if(!empty($hero_banner))
           <img src="{{ is_array($hero_banner) ? $hero_banner['url'] : $hero_banner }}" class="relative z-10 w-full h-full object-contain p-4" alt="Banner">
        @else
           <div class="relative z-10 m-4 flex flex-col items-center justify-center h-full text-center border-2 border-dashed border-[rgba(42,26,16,0.1)] rounded-2xl w-[calc(100%-32px)]">
             <div class="text-[20px] mb-2 opacity-40">🖼️</div>
             <p style="color:#A89F8B; font-weight:600; font-size: 12.5px;">Место для баннера<br><span style="font-weight:400; font-size:11px;">(загрузите в ACF)</span></p>
           </div>
        @endif
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
                      <div class="book-card-feature"><div class="book-card-check"><svg viewBox="0 0 9 9" fill="none"><path d="M1.5 4.5L3.5 6.5L7.5 2.5" stroke="#D87A4A" stroke-width="1.5" stroke-linecap="round"/></svg></div>{{ is_array($f) ? ($f['text'] ?? $f['title'] ?? 'Полезный материал') : 'Полезный материал' }}</div>
                  @endforeach
                @else
                  <div class="book-card-feature"><div class="book-card-check"><svg viewBox="0 0 9 9" fill="none"><path d="M1.5 4.5L3.5 6.5L7.5 2.5" stroke="#D87A4A" stroke-width="1.5" stroke-linecap="round"/></svg></div>Полезный материал</div>
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
         <div class="col-span-3 p-8 border-2 border-dashed rounded-2xl text-center" style="border-color:rgba(42,26,16,0.12);">
            <p style="color:#A89F8B; font-weight:700;">Для этого диагноза пока нет доступных планов МПО.</p>
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
            <div class="flex items-center justify-between pt-3 border-t mt-1" style="border-color: rgba(42,26,16,0.08);">
              <div class="text-[17px] text-[#2A1A10] font-bold">4 900 ₽</div>
              <button class="ds-btn-primary !px-4 !py-2 !text-[12px]">Записаться</button>
            </div>
          </div>
        @endforeach
      @else
        <div class="col-span-3 p-6 bg-white border rounded-xl text-center text-[14px]" style="border-color:rgba(42,26,16,0.1); color:var(--muted);">Врачи временно не прикреплены к этому диагнозу.</div>
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
              <div class="w-10 h-10 rounded-lg flex items-center justify-center text-lg shrink-0" style="background:#EBE3D2;">🏥</div>
              <div>
                <div class="text-[14px] font-bold leading-tight" style="font-family:'Playfair Display',serif; color:var(--text);">{{ $clinic->post_title }}</div>
              </div>
            </div>
            <a href="{{ get_permalink($clinic->ID) }}" class="mt-2 block w-full text-center border rounded-[50px] py-2 text-[12px] font-semibold transition-colors" style="border-color:rgba(42,26,16,0.1); color:var(--sub);" onmouseover="this.style.color='#EF945B'; this.style.borderColor='#EF945B'" onmouseout="this.style.color='var(--sub)'; this.style.borderColor='rgba(42,26,16,0.1)'">Перейти в карточку →</a>
          </div>
        @endforeach
      </div>
    @endif
    
    <!-- ── LEAD MAGNET ── -->
    @if(!empty($lead_magnet))
      <div class="ds-sec-sep"></div>
      <div class="ds-lead-block">
        <div class="absolute right-0 top-1/2 -translate-y-1/2 w-[240px] h-[240px] rounded-full blur-3xl pointer-events-none" style="background:rgba(239,148,91,0.08);"></div>
        <div class="relative z-10">
          <div class="text-[11px] font-bold tracking-widest uppercase mb-2" style="color:var(--terra-d);">🎁 Бесплатно</div>
          <div class="text-xl font-bold tracking-tight mb-2" style="font-family:'Playfair Display',serif; color:var(--text); line-height:1.2;">Гайд по самодиагностике и анализам</div>
          <p class="text-[14px] leading-relaxed" style="color:var(--sub);">Скачайте бесплатный справочник, который поможет вам разобраться с базовыми показателями без медицинского образования.</p>
        </div>
        <div class="flex flex-col gap-2 relative z-10">
          <input type="email" placeholder="Ваш e-mail" class="px-4 py-3 border-2 rounded-[50px] text-[13.5px] outline-none transition-colors" style="border-color:rgba(42,26,16,0.1); background:#faf8f5;" onfocus="this.style.borderColor='#EF945B'" onblur="this.style.borderColor='rgba(42,26,16,0.1)'">
          <button class="ds-btn-primary w-full">📥 Получить гайд</button>
          <div class="text-[11px] text-center mt-1" style="color:var(--muted);">Только польза, никакого спама</div>
        </div>
      </div>
    @endif

  </div>
</div>
@endsection
