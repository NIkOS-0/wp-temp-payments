<?php
/*
Template Name: Manager Dashboard
*/

namespace App;

use WP_Query;

$products = new WP_Query([
    'post_type' => 'product_offer',
    'posts_per_page' => -1,
    'post_status' => 'publish',
]);

if (!current_user_can('manage_offers')) {
    wp_redirect(home_url());
    exit;
}
?>

@extends('layouts.app')

@section('content')
  <div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="flex justify-between items-center mb-12 border-b border-slate-100 pb-8">
      <div>
        <h1 class="text-4xl font-black text-slate-900 tracking-tighter uppercase leading-none mb-2">Генератор офферов</h1>
        <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">Manager Access Only</p>
      </div>
      <a href="/wp/wp-admin/edit.php?post_type=personal_offer" class="px-6 py-3 bg-slate-100 text-slate-600 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-900 hover:text-white transition-all shadow-sm hover:shadow-xl">
        Все предложения &rarr;
      </a>
    </div>

    <div class="bg-white p-8 md:p-12 rounded-3xl shadow-2xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden">
      <!-- Decoration -->
      <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-bl-full opacity-50 -mr-8 -mt-8"></div>
      
      <form id="create-offer-form" class="space-y-10 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <div class="space-y-2">
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Название оффера</label>
            <input type="text" name="offer_title" placeholder="Напр. Скидка для Ивана" class="w-full p-4 bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-blue-100 transition-all font-bold text-slate-800 placeholder:text-slate-300">
          </div>

          @if ($products->have_posts())
            <div class="space-y-2">
              <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Товар</label>
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

        <div id="price-variants" class="hidden animate-in fade-in slide-in-from-top-4 duration-300">
           <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-3">Выберите цену</label>
           <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" id="price-list"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
          <div class="space-y-3">
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Срок действия</label>
            <div class="flex items-center space-x-6">
              <div class="flex items-center space-x-3">
                <input type="number" name="expiry_hours" value="24" class="w-24 p-4 bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-blue-100 transition-all font-black text-slate-800">
                <span class="text-[10px] font-black text-slate-900 uppercase tracking-widest">час.</span>
              </div>
              <div class="flex items-center space-x-3">
                <input type="number" name="expiry_minutes" value="0" class="w-24 p-4 bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-blue-100 transition-all font-black text-slate-800">
                <span class="text-[10px] font-black text-slate-900 uppercase tracking-widest">мин.</span>
              </div>
            </div>
          </div>

          <div class="flex items-center space-x-3 bg-slate-50 p-4 rounded-2xl border border-transparent hover:border-blue-200 transition-all cursor-pointer group" onclick="document.getElementById('use_cookie_security').click()">
            <input type="checkbox" name="use_cookie_security" id="use_cookie_security" checked class="w-6 h-6 text-blue-600 border-none bg-white rounded-lg focus:ring-blue-500 shadow-sm pointer-events-none">
            <label class="text-xs font-black text-slate-600 uppercase tracking-tight leading-none cursor-pointer"> Привязать к браузеру</label>
          </div>
        </div>

        <button type="submit" class="w-full bg-slate-900 hover:bg-blue-600 text-white font-black py-5 px-8 rounded-2xl transition-all shadow-2xl hover:shadow-blue-500/40 text-lg uppercase tracking-widest active:scale-[0.98]">
          Создать предложение
        </button>
      </form>

      <div id="offer-result" class="mt-12 hidden p-8 bg-blue-600 rounded-3xl animate-in zoom-in-95 duration-500 shadow-2xl shadow-blue-500/30">
         <p class="text-[10px] text-white/60 font-black uppercase tracking-widest mb-4">Предложение готово:</p>
         <div class="flex flex-col sm:flex-row items-center gap-4">
           <input type="text" id="generated-link" readonly class="w-full p-4 bg-white/10 border border-white/20 rounded-2xl text-white font-mono text-sm focus:ring-0 placeholder:text-white/30">
           <button onclick="copyLink()" class="w-full sm:w-auto bg-white text-blue-600 px-10 py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-slate-900 hover:text-white transition-all shadow-lg active:scale-95">
             Копировать
           </button>
         </div>
      </div>
    </div>
    
    <div class="mt-12 text-center">
      <p class="text-slate-400 text-xs font-bold italic uppercase tracking-widest opacity-50">Manage statuses and view reports in the WordPress admin panel.</p>
    </div>
  </div>

  <script>
    const form = document.getElementById('create-offer-form');
    const productSelect = document.getElementById('product_id');
    const priceVariants = document.getElementById('price-variants');
    const priceList = document.getElementById('price-list');
    const resultDiv = document.getElementById('offer-result');
    const generatedLink = document.getElementById('generated-link');

    productSelect.addEventListener('change', function() {
      const selected = this.options[this.selectedIndex];
      const prices = JSON.parse(selected.dataset.prices || '[]');
      
      priceList.innerHTML = '';
      if (prices.length > 0) {
        priceVariants.classList.remove('hidden');
        prices.forEach((item, index) => {
          const div = document.createElement('div');
          div.className = 'flex items-center space-x-3 p-4 bg-slate-50 rounded-2xl border-2 border-transparent hover:border-blue-600 hover:bg-white cursor-pointer transition-all group';
          div.innerHTML = `
            <input type="radio" name="price" value="${item.price}" id="price-${index}" ${index === 0 ? 'checked' : ''} class="w-5 h-5 text-blue-600 border-none bg-white shadow-sm">
            <label for="price-${index}" class="flex-1 cursor-pointer">
              <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest group-hover:text-blue-400 transition-colors">${item.label}</div>
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

    function copyLink() {
      generatedLink.select();
      document.execCommand('copy');
      const btn = event.target;
      const originalText = btn.innerText;
      btn.innerText = 'Готово!';
      btn.classList.add('bg-slate-900', 'text-white');
      setTimeout(() => {
        btn.innerText = originalText;
        btn.classList.remove('bg-slate-900', 'text-white');
      }, 2000);
    }
  </script>
@endsection
