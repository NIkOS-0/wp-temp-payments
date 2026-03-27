<?php
/*
Template Name: Manager Dashboard
*/

namespace App;

use WP_Query;

if (!current_user_can('manage_offers')) {
    wp_redirect(home_url());
    exit;
}

global $wpdb;

// Generator Products
$products = new WP_Query([
    'post_type' => 'product_offer',
    'posts_per_page' => -1,
    'post_status' => 'publish',
]);

// Dashboard Stats
$counts = $wpdb->get_results("SELECT post_status, COUNT(*) as cc FROM {$wpdb->posts} WHERE post_type = 'personal_offer' GROUP BY post_status");
$total_offers = 0;
$paid_offers = 0;

if ($counts) {
    foreach ($counts as $row) {
        if (!in_array($row->post_status, ['trash', 'auto-draft'])) {
            $total_offers += (int) $row->cc;
        }
        if ($row->post_status === 'paid') {
            $paid_offers = (int) $row->cc;
        }
    }
}
$conversion = ($total_offers > 0) ? round(($paid_offers / $total_offers) * 100, 1) : 0;

// Recent Offers
$recent_offers = get_posts([
    'post_type' => 'personal_offer',
    'numberposts' => 5,
    'post_status' => ['publish', 'paid'],
]);
?>

@extends('layouts.app')

@section('content')
  <div class="container mx-auto px-4 py-8 max-w-6xl">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 border-b border-slate-100 pb-8 gap-4">
      <div>
        <h1 class="text-4xl font-black text-slate-900 tracking-tighter uppercase leading-none mb-2">Генератор офферов</h1>
        <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">Дашборд Менеджера</p>
      </div>
      <a href="/wp/wp-admin/edit.php?post_type=personal_offer" class="px-6 py-3 bg-slate-100 text-slate-600 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-900 hover:text-white transition-all shadow-sm hover:shadow-xl whitespace-nowrap">
        Все предложения &rarr;
      </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-12">
      <div class="bg-white p-8 rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 flex flex-col justify-center items-center text-center transition-all hover:-translate-y-1">
        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Всего предложений</div>
        <div class="text-5xl font-black text-slate-900">{{ $total_offers }}</div>
      </div>
      <div class="bg-white p-8 rounded-3xl shadow-xl shadow-emerald-200/50 border border-emerald-100 flex flex-col justify-center items-center text-center transition-all hover:-translate-y-1">
        <div class="text-[10px] font-black text-emerald-600/70 uppercase tracking-widest mb-2">Оплачено</div>
        <div class="text-5xl font-black text-emerald-600">{{ $paid_offers }}</div>
      </div>
      <div class="bg-white p-8 rounded-3xl shadow-xl shadow-blue-200/50 border border-blue-100 flex flex-col justify-center items-center text-center transition-all hover:-translate-y-1">
        <div class="text-[10px] font-black text-blue-600/70 uppercase tracking-widest mb-2">Конверсия</div>
        <div class="text-5xl font-black text-blue-600">{{ $conversion }}%</div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
      
      <!-- Generator App -->
      <div class="lg:col-span-7">
        <h2 class="text-xl font-black text-slate-800 tracking-tight mb-6 flex items-center gap-3">
          <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold">+</span>
          Создать предложение
        </h2>
        <div class="bg-white p-8 md:p-10 rounded-3xl shadow-2xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden">
          <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-bl-full opacity-50 -mr-8 -mt-8"></div>
          
          <form id="create-offer-form" class="space-y-8 relative z-10">
            <div class="space-y-6">
              <div class="space-y-2">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Название оффера</label>
                <input type="text" name="offer_title" placeholder="Напр. Скидка для Ивана" class="w-full p-4 bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-blue-100 transition-all font-bold text-slate-800 placeholder:text-slate-300">
              </div>

              @if ($products->have_posts())
                <div class="space-y-2">
                  <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Выбор товара</label>
                  <select name="product_id" id="product_id" class="w-full p-4 bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-blue-100 transition-all font-bold text-slate-800" required>
                    <option value="">-- Выбрать товар --</option>
                    @while ($products->have_posts()) @php $products->the_post() @endphp
                      <option value="{{ get_the_ID() }}" data-prices='@json(get_field("price_options"))'>
                        {{ get_the_title() }}
                      </option>
                    @endwhile
                  </select>
                </div>
              @endif
            </div>

            <div id="price-variants" class="hidden animate-in fade-in slide-in-from-top-4 duration-300 bg-slate-50 p-6 rounded-3xl border border-slate-100">
               <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-4">Выберите ценник</label>
               <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="price-list"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
              <div class="space-y-3">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Срок действия</label>
                <div class="flex items-center space-x-3">
                  <input type="number" name="expiry_hours" value="24" class="w-20 p-4 bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-blue-100 transition-all font-black text-slate-800 text-center">
                  <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">ч.</span>
                  
                  <input type="number" name="expiry_minutes" value="0" class="w-20 p-4 bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-blue-100 transition-all font-black text-slate-800 text-center">
                  <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">м.</span>
                </div>
              </div>

              <div class="flex items-center space-x-3 bg-slate-50 p-4 rounded-2xl border border-transparent hover:border-blue-200 transition-all cursor-pointer group mt-4 md:mt-0" onclick="document.getElementById('use_cookie_security').click()">
                <input type="checkbox" name="use_cookie_security" id="use_cookie_security" checked class="w-5 h-5 text-blue-600 border-none bg-white rounded-md focus:ring-blue-500 shadow-sm pointer-events-none">
                <label class="text-[10px] font-black text-slate-600 uppercase tracking-widest cursor-pointer mt-1"> Привязка к браузеру</label>
              </div>
            </div>

            <button type="submit" class="w-full bg-slate-900 hover:bg-blue-600 text-white font-black py-5 px-8 rounded-2xl transition-all shadow-2xl hover:shadow-blue-500/40 text-[11px] uppercase tracking-widest active:scale-[0.98]">
              Сгенерировать ссылку
            </button>
          </form>

          <div id="offer-result" class="mt-8 hidden p-6 bg-blue-600 rounded-3xl animate-in zoom-in-95 duration-500 shadow-2xl shadow-blue-500/30">
             <p class="text-[10px] text-white/60 font-black uppercase tracking-widest mb-3">Готовая ссылка:</p>
             <div class="flex flex-col sm:flex-row items-center gap-3">
               <input type="text" id="generated-link" readonly class="w-full p-4 bg-white/10 border border-white/20 rounded-xl text-white font-mono text-sm focus:ring-0 placeholder:text-white/30">
               <button onclick="copyLink()" class="w-full sm:w-auto bg-white text-blue-600 px-8 py-4 rounded-xl font-black uppercase tracking-widest text-[11px] hover:bg-slate-900 hover:text-white transition-all shadow-lg active:scale-95">
                 Копия
               </button>
             </div>
          </div>
        </div>
      </div>

      <!-- Recent Offers Sidebar -->
      <div class="lg:col-span-5">
        <h2 class="text-xl font-black text-slate-800 tracking-tight mb-6 flex items-center gap-3">
          <span class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
          </span>
          Последние предложения
        </h2>
        
        <div class="space-y-4">
          @if(empty($recent_offers))
            <div class="bg-slate-50 border border-slate-100 p-8 rounded-3xl text-center">
              <p class="text-slate-400 font-black text-xs uppercase tracking-widest">Пока нет предложений</p>
            </div>
          @else
            @foreach($recent_offers as $offer)
              @php
                $status = get_post_status($offer->ID);
                $is_paid = $status === 'paid';
              @endphp
              <div class="bg-white p-5 rounded-3xl shadow-xl shadow-slate-200/40 border border-slate-100 flex items-center justify-between group hover:border-blue-200 transition-all">
                <div class="overflow-hidden pr-4">
                  <a href="/wp/wp-admin/post.php?post={{ $offer->ID }}&action=edit" class="text-sm font-black text-slate-900 group-hover:text-blue-600 truncate block transition-colors">
                    {{ get_the_title($offer->ID) ?: 'Без названия' }}
                  </a>
                  <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">
                    {{ get_the_date('d.m.Y H:i', $offer->ID) }}
                  </div>
                </div>
                <div>
                  @if($is_paid)
                    <span class="px-3 py-1.5 bg-emerald-100 text-emerald-700 rounded-lg text-[9px] font-black uppercase tracking-widest">Оплачено</span>
                  @else
                    <span class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-[9px] font-black uppercase tracking-widest">Активен</span>
                  @endif
                </div>
              </div>
            @endforeach
          @endif
        </div>
        
        <div class="mt-8 p-6 bg-slate-50 border border-slate-100 rounded-3xl text-center">
          <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Остальное в админке</p>
          <a href="/wp/wp-admin/edit.php?post_type=personal_offer" class="text-blue-600 font-bold text-xs hover:underline uppercase tracking-widest">Перейти к таблице</a>
        </div>
      </div>
      
    </div>
  </div>

  <script>
    const form = document.getElementById('create-offer-form');
    const productSelect = document.getElementById('product_id');
    const priceVariants = document.getElementById('price-variants');
    const priceList = document.getElementById('price-list');
    const resultDiv = document.getElementById('offer-result');
    const generatedLink = document.getElementById('generated-link');

    if(productSelect) {
        productSelect.addEventListener('change', function() {
          const selected = this.options[this.selectedIndex];
          const prices = JSON.parse(selected.dataset.prices || '[]');
          
          priceList.innerHTML = '';
          if (prices.length > 0) {
            priceVariants.classList.remove('hidden');
            prices.forEach((item, index) => {
              const div = document.createElement('div');
              div.className = 'flex items-center space-x-3 p-4 bg-white rounded-2xl border-2 border-slate-100 hover:border-blue-600 hover:shadow-lg cursor-pointer transition-all group';
              div.innerHTML = `
                <input type="radio" name="price" value="${item.price}" id="price-${index}" ${index === 0 ? 'checked' : ''} class="w-5 h-5 text-blue-600 border-none bg-slate-50 shadow-sm">
                <label for="price-${index}" class="flex-1 cursor-pointer">
                  <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest group-hover:text-blue-400 transition-colors">${item.label}</div>
                  <div class="text-lg font-black text-slate-900 font-mono">${item.price} ₽</div>
                </label>
              `;
              div.addEventListener('click', () => {
                div.querySelector('input').checked = true;
              });
              priceList.appendChild(div);
            });
          } else {
            priceVariants.classList.add('hidden');
          }
        });
    }

    if(form) {
        form.addEventListener('submit', async (e) => {
          e.preventDefault();
          const formData = new FormData(form);
          formData.append('action', 'create_personalized_offer');
          formData.append('nonce', '{{ wp_create_nonce("manager_dashboard_nonce") }}');

          const submitBtn = form.querySelector('button[type="submit"]');
          const originalText = submitBtn.innerText;
          submitBtn.innerText = 'Генерация...';
          submitBtn.disabled = true;

          try {
            const response = await fetch('/wp/wp-admin/admin-ajax.php', {
              method: 'POST',
              body: formData
            });
            const data = await response.json();
            
            if (data.success) {
              resultDiv.classList.remove('hidden');
              generatedLink.value = data.data.link;
              resultDiv.scrollIntoView({ behavior: 'smooth' });
            } else {
              alert('Ошибка: ' + data.data.message);
            }
          } catch (err) {
            console.error(err);
            alert('Ошибка сети.');
          } finally {
            submitBtn.innerText = originalText;
            submitBtn.disabled = false;
          }
        });
    }

    function copyLink() {
      generatedLink.select();
      document.execCommand('copy');
      const btn = event.target;
      const originalText = btn.innerText;
      btn.innerText = 'Успешно!';
      btn.classList.add('bg-slate-900', 'text-white');
      setTimeout(() => {
        btn.innerText = originalText;
        btn.classList.remove('bg-slate-900', 'text-white');
      }, 2000);
    }
  </script>
@endsection
