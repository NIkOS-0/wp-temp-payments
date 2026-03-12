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
?>

@extends('layouts.app')

@section('content')
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 text-slate-800">Менеджер предложений</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <!-- Create Offer Form -->
      <div class="bg-white p-6 rounded-xl shadow-lg border border-slate-100">
        <h2 class="text-xl font-semibold mb-6 text-slate-700">Создать новое предложение</h2>
        
        <form id="create-offer-form" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Название предложения (для менеджера)</label>
            <input type="text" name="offer_title" placeholder="Напр. Скидка для Ивана" class="w-full p-2 border border-slate-300 rounded-md focus:ring-2 focus:ring-blue-500 transition-all">
          </div>

          @if ($products->have_posts())
            <div>
              <label class="block text-sm font-medium text-slate-600 mb-1">Выберите товар</label>
              <select name="product_id" id="product_id" class="w-full p-2 border border-slate-300 rounded-md focus:ring-2 focus:ring-blue-500 transition-all" required>
                <option value="">-- Выбрать товар --</option>
                @while ($products->have_posts()) @php $products->the_post() @endphp
                  <option value="{{ get_the_ID() }}" data-prices='@json(get_field("price_options"))'>
                    {{ get_the_title() }}
                  </option>
                @endwhile
              </select>
            </div>
          @endif

          <div id="price-variants" class="hidden">
             <label class="block text-sm font-medium text-slate-600 mb-1">Вариант цены</label>
             <div class="space-y-2" id="price-list"></div>
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Срок действия (часов)</label>
            <input type="number" name="expiry_hours" value="24" class="w-full p-2 border border-slate-300 rounded-md" required>
          </div>

          <div class="flex items-center space-x-2 py-2">
            <input type="checkbox" name="use_cookie_security" id="use_cookie_security" checked class="w-4 h-4 text-blue-600 border-slate-300 rounded">
            <label for="use_cookie_security" class="text-sm font-medium text-slate-600">Привязать к браузеру клиента (Cookie Security)</label>
          </div>

          <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition-colors shadow-md">
            Сгенерировать ссылку
          </button>
        </form>

        <div id="offer-result" class="mt-6 hidden p-4 bg-green-50 border border-green-200 rounded-lg">
           <p class="text-sm text-green-800 font-medium mb-2">Ссылка создана:</p>
           <div class="flex items-center space-x-2">
             <input type="text" id="generated-link" readonly class="flex-1 p-2 bg-white border border-green-300 rounded text-sm overflow-ellipsis">
             <button onclick="copyLink()" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded text-sm transition-colors">Copy</button>
           </div>
        </div>
      </div>

      <!-- Instructions or Recent Offers could go here -->
      <div class="hidden md:block">
        <div class="bg-slate-50 p-6 rounded-xl border border-slate-200">
           <h3 class="font-bold text-slate-700 mb-4 italic">Как это работает:</h3>
           <ul class="space-y-3 text-slate-600 text-sm">
             <li class="flex items-start">
               <span class="bg-blue-100 text-blue-600 rounded-full w-5 h-5 flex items-center justify-center text-xs mt-0.5 mr-2">1</span>
               <span>Выберите товар из списка.</span>
             </li>
             <li class="flex items-start">
               <span class="bg-blue-100 text-blue-600 rounded-full w-5 h-5 flex items-center justify-center text-xs mt-0.5 mr-2">2</span>
               <span>Выберите подходящую цену (скидка, рассрочка и т.д.).</span>
             </li>
             <li class="flex items-start">
               <span class="bg-blue-100 text-blue-600 rounded-full w-5 h-5 flex items-center justify-center text-xs mt-0.5 mr-2">3</span>
               <span>Установите время в часах, за которое клиент должен принять решение.</span>
             </li>
             <li class="flex items-start">
               <span class="bg-blue-100 text-blue-600 rounded-full w-5 h-5 flex items-center justify-center text-xs mt-0.5 mr-2">4</span>
               <span>Отправьте ссылку. Если включена защита, ссылка откроется только в браузере клиента!</span>
             </li>
           </ul>
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

    productSelect.addEventListener('change', function() {
      const selected = this.options[this.selectedIndex];
      const prices = JSON.parse(selected.dataset.prices || '[]');
      
      priceList.innerHTML = '';
      if (prices.length > 0) {
        priceVariants.classList.remove('hidden');
        prices.forEach((item, index) => {
          const div = document.createElement('div');
          div.className = 'flex items-center space-x-2 p-2 bg-slate-50 rounded border border-slate-200 hover:bg-slate-100 cursor-pointer transition-colors';
          div.innerHTML = `
            <input type="radio" name="price" value="${item.price}" id="price-${index}" ${index === 0 ? 'checked' : ''} class="w-4 h-4 text-blue-600">
            <label for="price-${index}" class="flex-1 cursor-pointer text-sm">
              <span class="font-bold">${item.label}:</span> 
              <span class="text-blue-600">${item.price} руб.</span>
            </label>
          `;
          div.addEventListener('click', () => div.querySelector('input').checked = true);
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
          alert('Error: ' + data.data.message);
        }
      } catch (err) {
        console.error(err);
        alert('Network error occurred.');
      }
    });

    function copyLink() {
      generatedLink.select();
      document.execCommand('copy');
      const btn = event.target;
      const originalText = btn.innerText;
      btn.innerText = 'Copied!';
      btn.classList.replace('bg-green-600', 'bg-blue-600');
      setTimeout(() => {
        btn.innerText = originalText;
        btn.classList.replace('bg-blue-600', 'bg-green-600');
      }, 2000);
    }
  </script>
@endsection
