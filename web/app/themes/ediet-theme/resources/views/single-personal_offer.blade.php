@extends('layouts.app')

@section('content')
  <div class="min-h-screen bg-linear-to-b from-slate-50 to-slate-200 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
      <!-- Main Card -->
      <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-white/20 backdrop-blur-xs flex flex-col lg:flex-row transition-all duration-500 hover:shadow-blue-500/10 hover:border-blue-500/20">
        
        <!-- Images Section -->
        <div class="lg:w-1/2 bg-slate-100 relative group overflow-hidden">
          @if($gallery)
            <div class="relative h-[400px] lg:h-full overflow-hidden">
              @foreach($gallery as $img_id)
                <img src="{{ wp_get_attachment_image_url($img_id, 'large') }}" 
                     class="absolute inset-0 w-full h-full object-cover transition-opacity duration-700 {{ $loop->first ? 'opacity-100' : 'opacity-0' }}" 
                     data-gallery-item="{{ $loop->index }}" 
                     alt="Product Image">
              @endforeach
              
              @if(count($gallery) > 1)
                <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex space-x-2 z-10">
                  @foreach($gallery as $img_id)
                    <button class="w-2.5 h-2.5 rounded-full transition-all duration-300 {{ $loop->first ? 'bg-blue-600 w-6' : 'bg-white/50 hover:bg-white' }}"
                            onclick="setActiveSlide({{ $loop->index }})"
                            data-gallery-dot="{{ $loop->index }}"></button>
                  @endforeach
                </div>
              @endif
            </div>
          @elseif($thumbnail)
            <img src="{{ $thumbnail }}" class="w-full h-full object-cover aspect-square lg:aspect-auto" alt="{{ $product->post_title }}">
          @else
            <div class="h-[400px] lg:h-full flex items-center justify-center text-slate-300">
               <svg class="w-32 h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
          @endif

          <div class="absolute top-6 left-6 z-10">
            <span class="bg-blue-600 text-white px-5 py-2 rounded-full text-sm font-bold shadow-lg flex items-center gap-2">
              <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-300"></span>
              </span>
              Эксклюзивное предложение
            </span>
          </div>
        </div>

        <!-- Content Section -->
        <div class="lg:w-1/2 p-8 lg:p-14 flex flex-col">
          <header class="mb-8">
            <h1 class="text-4xl lg:text-5xl font-black text-slate-900 leading-tight mb-4">
              {{ $product->post_title }}
            </h1>
            <div class="flex items-center gap-4 mb-6">
              @if($short_description)
                <p class="text-lg text-slate-600 leading-relaxed font-medium italic border-l-4 border-blue-500 pl-4 py-1">
                  {{ $short_description }}
                </p>
              @endif
            </div>
          </header>

          <div class="space-y-10 mb-12">
            <!-- Features Grid -->
            @if($features)
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @foreach($features as $feature)
                  <div class="flex items-start gap-4 group">
                    <div class="flex-shrink-0 w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center transition-all group-hover:bg-blue-600 group-hover:text-white group-hover:shadow-lg group-hover:shadow-blue-200">
                      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                      <h4 class="font-bold text-slate-900 mb-0.5">{{ $feature['title'] }}</h4>
                      <p class="text-sm text-slate-500 line-clamp-2">{{ $feature['description'] }}</p>
                    </div>
                  </div>
                @endforeach
              </div>
            @endif

            <!-- Tabs/Specs accordion -->
            <div class="border-t border-slate-100 pt-8">
              <h3 class="text-sm font-bold uppercase tracking-widest text-slate-400 mb-6 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Характеристики
              </h3>
              @if($specs)
                <div class="grid grid-cols-1 gap-y-3">
                  @foreach($specs as $spec)
                    <div class="flex justify-between items-end border-b border-dashed border-slate-200 pb-2 hover:border-blue-300 transition-colors">
                      <span class="text-slate-500 text-sm font-medium">{{ $spec['name'] }}</span>
                      <span class="text-slate-900 text-sm font-bold">{{ $spec['value'] }}</span>
                    </div>
                  @endforeach
                </div>
              @endif
            </div>
          </div>

          <!-- Price & Action Section -->
          <div class="mt-auto bg-slate-50 -mx-8 -mb-8 lg:-mx-14 lg:-mb-14 p-8 lg:p-14 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-8 group">
            <div class="text-center sm:text-left">
              <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Финальная стоимость</div>
              <div class="flex items-baseline gap-2">
                <span class="text-5xl font-black text-slate-900 tracking-tighter">{{ number_format($price, 0, '.', ' ') }}</span>
                <span class="text-2xl font-bold text-slate-400">₽</span>
              </div>
            </div>

            <button id="checkout-button" class="relative group/btn w-full sm:w-auto px-10 py-5 bg-slate-900 text-white rounded-2xl font-black text-xl tracking-wide shadow-2xl transition-all hover:bg-blue-600 hover:shadow-blue-500/30 overflow-hidden cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
              <span class="relative z-10 flex items-center justify-center gap-3">
                <span id="checkout-text">Оформить сейчас</span>
                <svg id="checkout-icon" class="w-6 h-6 transform transition-transform group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
              </span>
              <div class="absolute inset-0 w-full h-full bg-linear-to-r from-blue-600 to-indigo-600 opacity-0 group-hover/btn:opacity-100 transition-opacity duration-500"></div>
            </button>
          </div>
        </div>
      </div>

      <!-- Trust Badges -->
      <div class="mt-12 flex flex-wrap justify-center gap-8 lg:gap-16">
        <div class="flex items-center gap-3 text-slate-500 grayscale opacity-70 hover:grayscale-0 hover:opacity-100 transition-all duration-300">
          <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
          <span class="text-sm font-bold uppercase tracking-wider">Защищенная сделка</span>
        </div>
        <div class="flex items-center gap-3 text-slate-500 grayscale opacity-70 hover:grayscale-0 hover:opacity-100 transition-all duration-300">
          <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
          <span class="text-sm font-bold uppercase tracking-wider">
            Спеццена истекает через: 
            <span id="countdown" class="font-black text-orange-600 tabular-nums">--:--:--</span>
          </span>
        </div>
        <div class="flex items-center gap-3 text-slate-500 grayscale opacity-70 hover:grayscale-0 hover:opacity-100 transition-all duration-300">
          <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg>
          <span class="text-sm font-bold uppercase tracking-wider">Гарантия качества</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Expiry Popup -->
  <div id="expiry-popup" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-3xl p-8 max-w-sm w-full mx-4 shadow-2xl transform scale-95 transition-transform duration-300 text-center border-t-4 border-red-500">
      <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
      </div>
      <h3 class="text-2xl font-black text-slate-900 mb-2">Ссылка истекла</h3>
      <p class="text-slate-500 mb-8">Время действия этого персонального предложения подошло к концу. Пожалуйста, запросите новую ссылку.</p>
      <button onclick="closeExpiryPopup()" class="w-full py-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-colors">Понятно</button>
    </div>
  </div>

  <!-- Success/Error Popup (Robokassa Test) -->
  <div id="payment-popup" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-3xl p-8 max-w-md w-full mx-4 shadow-2xl transform scale-95 transition-transform duration-300 text-center">
      <div id="payment-status-icon" class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6"></div>
      <h3 id="payment-status-title" class="text-2xl font-black text-slate-900 mb-2">Статус платежа</h3>
      <p id="payment-status-message" class="text-slate-500 mb-8">Сообщение платежа</p>
      <button onclick="closePaymentPopup()" class="w-full py-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-colors">Закрыть</button>
    </div>
  </div>

  <script>
    function setActiveSlide(index) {
      const items = document.querySelectorAll('[data-gallery-item]');
      const dots = document.querySelectorAll('[data-gallery-dot]');
      
      items.forEach((item, i) => {
        item.classList.toggle('opacity-100', i === index);
        item.classList.toggle('opacity-0', i !== index);
      });
      
      dots.forEach((dot, i) => {
        dot.classList.toggle('bg-blue-600', i === index);
        dot.classList.toggle('w-6', i === index);
        dot.classList.toggle('bg-white/50', i !== index);
        dot.classList.toggle('w-2.5', i !== index);
      });
    }

    const expiryTimestamp = {{ $expiry_timestamp }} * 1000;
    const countdownEl = document.getElementById('countdown');
    const checkoutBtn = document.getElementById('checkout-button');
    const checkoutText = document.getElementById('checkout-text');
    const expiryPopup = document.getElementById('expiry-popup');
    
    let isExpired = false;

    function showExpiryPopup() {
      expiryPopup.classList.remove('hidden');
      setTimeout(() => {
        expiryPopup.classList.remove('opacity-0');
        expiryPopup.querySelector('div').classList.remove('scale-95');
      }, 10);
    }

    function closeExpiryPopup() {
      expiryPopup.classList.add('opacity-0');
      expiryPopup.querySelector('div').classList.add('scale-95');
      setTimeout(() => {
        expiryPopup.classList.add('hidden');
      }, 300);
    }

    function updateCountdown() {
      if (isExpired) return;

      const now = new Date().getTime();
      const distance = expiryTimestamp - now;

      if (distance < 0) {
        isExpired = true;
        countdownEl.innerHTML = "ИСТЕКЛО";
        countdownEl.classList.replace('text-orange-600', 'text-red-600');
        
        // Deactivate checkout button
        checkoutBtn.disabled = true;
        checkoutText.innerText = "Предложение недоступно";
        document.getElementById('checkout-icon').classList.add('hidden');
        
        // Show popup
        showExpiryPopup();
        return;
      }

      const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      const seconds = Math.floor((distance % (1000 * 60)) / 1000);

      const pad = (num) => String(num).padStart(2, '0');
      countdownEl.innerHTML = `${pad(hours)}:${pad(minutes)}:${pad(seconds)}`;
    }

    // Robokassa Demo Logic
    /**
     * Helper to show the dynamic payment status popup
     */
    function showPaymentStatus(isSuccess, title, message) {
      const popup = document.getElementById('payment-popup');
      const iconContainer = document.getElementById('payment-status-icon');
      const titleEl = document.getElementById('payment-status-title');
      const messageEl = document.getElementById('payment-status-message');

      if (isSuccess) {
        iconContainer.className = 'w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6';
        iconContainer.innerHTML = '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
        titleEl.className = 'text-2xl font-black text-green-700 mb-2';
      } else {
        iconContainer.className = 'w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-6';
        iconContainer.innerHTML = '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
        titleEl.className = 'text-2xl font-black text-red-700 mb-2';
      }

      titleEl.innerText = title;
      messageEl.innerHTML = message;

      popup.classList.remove('hidden');
      setTimeout(() => {
        popup.classList.remove('opacity-0');
        popup.querySelector('div').classList.remove('scale-95');
      }, 10);
    }

    function closePaymentPopup() {
      const popup = document.getElementById('payment-popup');
      popup.classList.add('opacity-0');
      popup.querySelector('div').classList.add('scale-95');
      setTimeout(() => {
        popup.classList.add('hidden');
        // If it was a success, you might want to redirect them or update UI further here.
      }, 300);
    }

    // Handle incoming return from Robokassa
    document.addEventListener('DOMContentLoaded', () => {
      const urlParams = new URLSearchParams(window.location.search);
      
      // If we returned from Robokassa with parameters
      if (urlParams.has('InvId')) {
        const outSum = urlParams.get('OutSum');
        const invId = urlParams.get('InvId');
        
        // This is a naive frontend check based on URL parameters ONLY for demonstration.
        // In reality, you MUST verify the SignatureValue on the backend via the ResultURL webhook.
        // And use the backend to verify the SuccessURL parameters.
        if (urlParams.has('SignatureValue')) {
          // Assuming success for demo if SignatureValue is present
          showPaymentStatus(
            true, 
            'Оплата прошла успешно!', 
            `Спасибо за покупку! Заказ #${invId} оплачен на сумму ${outSum} руб.<br><br><span class="text-sm">В ближайшее время менеджер свяжется с вами или вы получите доступ на почту.</span>`
          );
        } else {
            // Fail scenario, maybe they clicked cancel.
            showPaymentStatus(
                false, 
                'Ошибка оплаты', 
                `Оплата заказа #${invId} не была завершена.<br><span class="text-sm">Пожалуйста, попробуйте снова или обратитесь в поддержку.</span>`
            );
        }
        
        // Clean URL parameters
        window.history.replaceState({}, document.title, window.location.pathname);
      }
    });

    checkoutBtn.addEventListener('click', (e) => {
      e.preventDefault();
      if (isExpired) return;

      checkoutBtn.disabled = true;
      checkoutText.innerText = "Переход к оплате...";

      // Prepare data for Robokassa test mode
      const mrh_login = "demo"; // Note: 'demo' login is usually used for Robokassa testing
      const out_summ = "{{ $price }}";
      const inv_id = "{{ get_the_ID() }}99"; // Faked invoice ID for uniqueness in test mode
      const inv_desc = "Оплата персонального предложения: {{ $product->post_title }}";
      
      // We must generate the MD5 signature. Since this is purely frontend demo logic for test mode, 
      // we are breaking security rules by hashing on the frontend or passing dummy data.
      // Usually, you MUST fetch the signature and URL from your PHP backend.
      
      // For this temporary solution, we'll route the user directly to the demo page
      // In a real scenario, this form submission must be generated securely by backend.
      
      const currentUrl = encodeURIComponent(window.location.href);
      
      // Note: testing environment for Robokassa usually expects properly md5 signed parameters.
      // Since we don't have the backend endpoint ready, we'll simulate the merchant request
      // Note: without a valid password 1, Robokassa will reject it even in test mode if IsTest is strictly enforced
      // against a real live merchant login. 
      // For pure dummy link behavior, as requested ("переход на оплату в демо-режиме"):
      
      const robokassaForm = document.createElement('form');
      robokassaForm.action = "https://auth.robokassa.ru/Merchant/Index.aspx";
      robokassaForm.method = "POST";
      
      // Add hidden fields
      const params = {
          MerchantLogin: 'demo', // Using demo
          OutSum: out_summ,
          InvId: inv_id,
          Description: inv_desc,
          SignatureValue: '11111111111111111111111111111111', // Dummy signature for UI testing
          IsTest: '1',
          Culture: 'ru',
      };

      for (const [key, value] of Object.entries(params)) {
          const input = document.createElement('input');
          input.type = 'hidden';
          input.name = key;
          input.value = value;
          robokassaForm.appendChild(input);
      }
      
      // Fallback for UI if signature fails (which it will without backend integration)
      // To fulfill the requirement "either show popup success or failure", we can simply mock the flow if you strictly want a mockup,
      // But let's attempt to redirect first. Because Robokassa will reject the dummy signature, let's actually
      // just simulate the flow locally so you can see the popups.

      // IF YOU WANT TO ACTUALLY REDIRECT, UNCOMMENT BELOW:
      document.body.appendChild(robokassaForm);
      robokassaForm.submit();
      
      // IF YOU WANT TO JUST SIMULATE THE POPUP DIRECTLY (since real redirect requires backend):
      /*
      setTimeout(() => {
          // Simulating a random success/failure
          if (Math.random() > 0.5) {
              window.location.href = window.location.href + (window.location.href.includes('?') ? '&' : '?') + `InvId=${inv_id}&OutSum=${out_summ}&SignatureValue=MOCKED`;
          } else {
              window.location.href = window.location.href + (window.location.href.includes('?') ? '&' : '?') + `InvId=${inv_id}&OutSum=${out_summ}`;
          }
      }, 1000);
      */
    });

    setInterval(updateCountdown, 1000);
    updateCountdown();
  </script>
@endsection
