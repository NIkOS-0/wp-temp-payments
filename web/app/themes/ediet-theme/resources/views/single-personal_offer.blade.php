@extends('layouts.app')

@section('content')
  <div class="min-h-screen bg-[#fff5d4] py-6 sm:py-12 px-2 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
      <!-- Main Card -->
      <div class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl overflow-hidden border border-[#ffc671]/20 backdrop-blur-xs flex flex-col lg:flex-row transition-all duration-500 hover:shadow-[#ffc671]/20 hover:border-[#ffc671]/40">
        <!-- Images Section -->
        <div class="lg:w-1/2 bg-[#fffcf0] relative group flex flex-col justify-center p-6 sm:p-8 lg:p-12 gap-4">
          @if($gallery)
            <div class="relative">
              <!-- Navigation Arrows -->
              @if(count($gallery) > 1)
                <button onclick="prevSlide()" class="absolute -left-4 sm:-left-6 lg:-left-10 bottom-[22px] w-10 sm:w-12 h-10 sm:h-12 bg-white shadow-2xl rounded-full flex items-center justify-center text-[#0a2633] hover:text-[#ffc671] transition-all z-30 hover:scale-110 active:scale-95 border border-[#fff5d4]">
                  <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <button onclick="nextSlide()" class="absolute -right-4 sm:-right-6 lg:-right-10 bottom-[22px] w-10 sm:w-12 h-10 sm:h-12 bg-white shadow-2xl rounded-full flex items-center justify-center text-[#0a2633] hover:text-[#ffc671] transition-all z-30 hover:scale-110 active:scale-95 border border-[#fff5d4]">
                  <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
              @endif

              <div class="relative overflow-hidden aspect-square rounded-2xl sm:rounded-3xl shadow-lg bg-white">
                @foreach($gallery as $img_id)
                  <img src="{{ wp_get_attachment_image_url($img_id, 'large') }}" 
                       class="absolute inset-0 w-full h-full object-cover transition-opacity duration-700 {{ $loop->first ? 'opacity-100' : 'opacity-0' }}" 
                       data-gallery-item="{{ $loop->index }}" 
                       alt="Product Image">
                @endforeach
                
                @if(count($gallery) > 1)
                  <!-- Dots -->
                  <div class="absolute bottom-4 sm:bottom-6 left-1/2 -translate-x-1/2 flex space-x-2 z-10">
                    @foreach($gallery as $img_id)
                      <button class="w-2.5 h-2.5 rounded-full transition-all duration-300 {{ $loop->first ? 'bg-[#0a2633] w-6' : 'bg-white/70 hover:bg-white' }}"
                              onclick="setActiveSlide({{ $loop->index }})"
                              data-gallery-dot="{{ $loop->index }}"></button>
                    @endforeach
                  </div>
                @endif
              </div>
            </div>

            <!-- Thumbnails Gallery -->
            @if(count($gallery) > 1)
              <div class="flex gap-2 p-3 sm:p-4 backdrop-blur-sm overflow-x-auto no-scrollbar snap-x snap-mandatory">
                @foreach($gallery as $img_id)
                  <button onclick="setActiveSlide({{ $loop->index }})" 
                          data-gallery-thumb="{{ $loop->index }}"
                          class="snap-center relative flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 rounded-xl overflow-hidden border transition-all {{ $loop->first ? 'border-[#ffc671] scale-105' : 'border-transparent opacity-60 hover:opacity-100' }}">
                    <img src="{{ wp_get_attachment_image_url($img_id, 'thumbnail') }}" class="w-full h-full object-cover" alt="Thumbnail {{ $loop->iteration }}">
                  </button>
                @endforeach
              </div>
            @endif
          @elseif($thumbnail)
            <div class="relative aspect-square rounded-2xl sm:rounded-3xl shadow-lg overflow-hidden bg-white">
              <img src="{{ $thumbnail }}" class="absolute inset-0 w-full h-full object-cover" alt="{{ $product->post_title }}">
            </div>
          @else
            <div class="aspect-square rounded-2xl sm:rounded-3xl bg-white flex items-center justify-center text-slate-200 border-2 border-dashed border-[#fff5d4]">
               <svg class="w-24 h-24 sm:w-32 sm:h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
          @endif

          <div class="absolute top-4 sm:top-6 left-4 sm:left-6 z-10">
            <span class="bg-[#ffc671] text-[#0a2633] px-3 sm:px-5 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-bold shadow-lg flex items-center gap-2">
              <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-white/80"></span>
              </span>
              Эксклюзивное предложение
            </span>
          </div>
        </div>

        <!-- Content Section -->
        <div class="lg:w-1/2 p-6 sm:p-8 lg:p-14 flex flex-col">
          <header class="mb-6 sm:mb-8">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-[#0a2633] leading-tight mb-4">
              {{ $product->post_title }}
            </h1>
            <div class="flex items-center gap-4 mb-6">
              @if($short_description)
                <p class="text-base sm:text-lg text-[#0a2633]/80 leading-relaxed font-medium italic border-l-4 border-[#ffc671] pl-4 py-1">
                  {{ $short_description }}
                </p>
              @endif
            </div>
          </header>

          <div class="space-y-8 sm:space-y-10 mb-10 sm:mb-12">
            <!-- Features Grid -->
            @if($features)
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                @foreach($features as $feature)
                  <div class="flex items-start gap-4 group">
                    <div class="flex-shrink-0 w-12 h-12 bg-[#ffc671]/20 text-[#0a2633] rounded-2xl flex items-center justify-center transition-all group-hover:bg-[#ffc671] group-hover:shadow-lg group-hover:shadow-[#ffc671]/40">
                      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                      <h4 class="font-bold text-[#0a2633] mb-0.5">{{ $feature['title'] }}</h4>
                      <p class="text-sm text-[#0a2633]/60 line-clamp-2">{{ $feature['description'] }}</p>
                    </div>
                  </div>
                @endforeach
              </div>
            @endif

            <!-- Tabs/Specs accordion -->
            <div class="border-t border-[#ffc671]/20 pt-6 sm:pt-8">
              <h3 class="text-xs sm:text-sm font-bold uppercase tracking-widest text-[#0a2633]/60 mb-4 sm:mb-6 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Характеристики
              </h3>
              @if($specs)
                <div class="grid grid-cols-1 gap-y-3">
                  @foreach($specs as $spec)
                    <div class="flex justify-between items-end border-b border-dashed border-[#ffc671]/30 pb-2 hover:border-[#ffc671] transition-colors">
                      <span class="text-[#0a2633]/70 text-sm font-medium">{{ $spec['name'] }}</span>
                      <span class="text-[#0a2633] text-sm font-bold">{{ $spec['value'] }}</span>
                    </div>
                  @endforeach
                </div>
              @endif
            </div>
          </div>

          <!-- Price & Action Section -->
          <div class="mt-auto -mx-6 sm:-mx-8 lg:-mx-14 -mb-6 sm:-mb-8 lg:-mb-14 p-6 sm:p-8 lg:p-14 border-t border-[#ffc671]/20 flex flex-col sm:flex-row items-center justify-between gap-6 sm:gap-8 group bg-[#fffcf0] lg:bg-transparent">
            <div class="text-center sm:text-left">
              <div class="text-xs font-bold text-[#0a2633]/60 uppercase tracking-widest mb-1">Итого к оплате:</div>
              <div class="flex items-baseline gap-2 whitespace-nowrap justify-center sm:justify-start">
                <span class="text-4xl sm:text-5xl font-black text-[#0a2633] tracking-tighter">{{ number_format($price, 0, '.', ' ') }}</span>
                <span class="text-xl sm:text-2xl font-bold text-[#0a2633]/60">₽</span>
              </div>
            </div>
            
            <div class="flex flex-col gap-3 w-full sm:w-auto">
              <!-- Robokassa -->
              @if(get_post_status() === 'paid')
                <div class="px-8 py-4 bg-[#0a2633]/10 text-[#0a2633] rounded-2xl font-black text-lg tracking-wide shadow-xl text-center w-full sm:w-auto">
                  ОПЛАЧЕНО
                </div>
              @else
                @if($offer_quantity > 1)
                  <div class="text-xs font-bold text-[#ffc671] uppercase tracking-widest mb-1 text-center lg:text-right px-1">
                    Количество: {{ $offer_quantity }} шт.
                  </div>
                @endif
                <button id="checkout-button" class="relative group/btn px-8 py-4 bg-[#0a2633] text-white rounded-2xl font-black text-base sm:text-lg tracking-wide shadow-xl transition-all hover:bg-[#ffc671] hover:text-[#0a2633] hover:shadow-[#ffc671]/40 overflow-hidden cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed w-full sm:w-auto">
                  <span class="relative z-10 flex items-center justify-center gap-3">
                    <span id="checkout-text">Оплатить (Robokassa)</span>
                    <svg id="checkout-icon" class="w-5 h-5 transform transition-transform group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                  </span>
                </button>
              @endif
            </div>
          </div>
        </div>
      </div>

      <!-- Trust Badges -->
      <div class="mt-8 sm:mt-12 flex flex-col sm:flex-row flex-wrap justify-center items-center gap-6 sm:gap-8 lg:gap-16 px-4">
        <div class="flex items-center gap-3 text-[#0a2633]/70 grayscale opacity-70 hover:grayscale-0 hover:opacity-100 transition-all duration-300 w-full sm:w-auto justify-center">
          <svg class="w-8 h-8 text-[#0a2633]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
          <span class="text-xs sm:text-sm font-bold uppercase tracking-wider text-center sm:text-left">
            Спеццена истекает: <br class="sm:hidden">
            <span id="countdown" class="font-black text-[#0a2633] tabular-nums mt-1 sm:mt-0 block sm:inline">--:--:--</span>
          </span>
        </div>
        <div class="flex items-center gap-3 text-[#0a2633]/70 grayscale opacity-70 hover:grayscale-0 hover:opacity-100 transition-all duration-300 w-full sm:w-auto justify-center">
          <svg class="w-8 h-8 text-[#0a2633]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
          <span class="text-xs sm:text-sm font-bold uppercase tracking-wider text-center sm:text-left">Защищенная сделка</span>
        </div>
        <div class="flex items-center gap-3 text-[#0a2633]/70 grayscale opacity-70 hover:grayscale-0 hover:opacity-100 transition-all duration-300 w-full sm:w-auto justify-center">
          <svg class="w-8 h-8 text-[#0a2633]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg>
          <span class="text-xs sm:text-sm font-bold uppercase tracking-wider text-center sm:text-left">Гарантия качества</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Expiry Popup -->
  <div id="expiry-popup" class="fixed inset-0 z-50 flex items-center justify-center bg-[#0a2633]/80 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-sm w-full mx-4 shadow-2xl transform scale-95 transition-transform duration-300 text-center border-t-4 border-[#0a2633]">
      <div class="w-16 h-16 bg-[#0a2633]/20 text-[#0a2633] rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
      </div>
      <h3 class="text-2xl font-black text-[#0a2633] mb-2">Ссылка истекла</h3>
      <p class="text-[#0a2633]/60 mb-8">Время действия этого персонального предложения подошло к концу. Пожалуйста, запросите новую ссылку у менеджера.</p>
      <button onclick="closeExpiryPopup()" class="w-full py-4 bg-[#fffcf0] hover:bg-[#fff5d4] text-[#0a2633] font-bold rounded-xl transition-colors">Понятно</button>
    </div>
  </div>

  <!-- Success/Error Popup (Robokassa Test) -->
  <div id="payment-popup" class="fixed inset-0 z-50 flex items-center justify-center bg-[#0a2633]/80 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full mx-4 shadow-2xl transform scale-95 transition-transform duration-300 text-center">
      <div id="payment-status-icon" class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6"></div>
      <h3 id="payment-status-title" class="text-2xl font-black text-[#0a2633] mb-2">Статус платежа</h3>
      <p id="payment-status-message" class="text-[#0a2633]/60 mb-8">Сообщение платежа</p>
      <button onclick="closePaymentPopup()" class="w-full py-4 bg-[#fffcf0] hover:bg-[#fff5d4] text-[#0a2633] font-bold rounded-xl transition-colors">Закрыть</button>
    </div>
  </div>

  <script>
    let currentSlide = 0;
    const totalSlides = {{ $gallery ? count($gallery) : 0 }};

    function setActiveSlide(index) {
      currentSlide = index;
      const items = document.querySelectorAll('[data-gallery-item]');
      const dots = document.querySelectorAll('[data-gallery-dot]');
      const thumbs = document.querySelectorAll('[data-gallery-thumb]');
      
      items.forEach((item, i) => {
        item.classList.toggle('opacity-100', i === index);
        item.classList.toggle('opacity-0', i !== index);
      });
      
      dots.forEach((dot, i) => {
        dot.classList.toggle('bg-[#0a2633]', i === index);
        dot.classList.toggle('w-6', i === index);
        dot.classList.toggle('bg-white/70', i !== index);
        dot.classList.toggle('w-2.5', i !== index);
      });

      thumbs.forEach((thumb, i) => {
        thumb.classList.toggle('border-[#ffc671]', i === index);
        thumb.classList.toggle('scale-105', i === index);
        thumb.classList.toggle('border-transparent', i !== index);
        thumb.classList.toggle('opacity-60', i !== index);
      });
    }

    function nextSlide() {
      let next = (currentSlide + 1) % totalSlides;
      setActiveSlide(next);
    }

    function prevSlide() {
      let prev = (currentSlide - 1 + totalSlides) % totalSlides;
      setActiveSlide(prev);
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
        countdownEl.classList.replace('text-[#0a2633]', 'text-red-600');
        
        // Deactivate checkout button if it exists
        if (checkoutBtn) {
          checkoutBtn.disabled = true;
          checkoutText.innerText = "Предложение недоступно";
          document.getElementById('checkout-icon').classList.add('hidden');
        }
        
        // Show popup
        showExpiryPopup();
        return;
      }

      const hours = Math.floor(distance / (1000 * 60 * 60));
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
      
      // Check payment status from redirect
      const paymentStatus = urlParams.get('payment');
      if (paymentStatus === 'success') {
        showPaymentStatus(
          true, 
          'Оплата прошла успешно!', 
          `
<div class="text-left bg-slate-50 p-4 rounded-xl mb-4 border border-slate-100">
  <div class="text-xs text-[#0a2633]/60 uppercase tracking-widest font-bold mb-1">Детали заказа</div>
  <div class="font-bold text-[#0a2633]">{{ $product->post_title }}</div>
  <div class="text-sm text-[#0a2633]/70 mt-1">Сумма: {{ number_format($price, 0, '.', ' ') }} ₽</div>
  <div class="text-sm text-[#0a2633]/70">Заказ №{{ get_the_ID() }}</div>
</div>
<div class="text-sm text-[#0a2633]/80 mb-6">
  Свяжитесь с менеджером, который выставил вам счёт и сообщите номер заказа.
</div>
<div class="flex flex-col sm:flex-row gap-3 justify-center mb-2">
  <a href="https://wa.me/79000000000" target="_blank" style="background-color: #25d366;" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 text-white rounded-xl font-bold hover:opacity-90 transition-opacity">
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
    WhatsApp
  </a>
  <a href="https://t.me/your_telegram" target="_blank" style="background-color: #0088cc;" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 text-white rounded-xl font-bold hover:opacity-90 transition-opacity">
    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M11.944 0A12 12 0 000 12a12 12 0 0012 12 12 12 0 0012-12A12 12 0 0012 0a12 12 0 00-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 01.171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.892-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
    Telegram
  </a>
</div>
          `
        );
        window.history.replaceState({}, document.title, window.location.pathname);
      } else if (paymentStatus === 'fail') {
        showPaymentStatus(
            false, 
            'Ошибка оплаты', 
            `Оплата заказа не была завершена.<br><span class="text-sm">Пожалуйста, попробуйте снова или обратитесь в поддержку.</span>`
        );
        window.history.replaceState({}, document.title, window.location.pathname);
      }
    });

    function initCheckoutButton(btnElement, btnTextElement, provider, originalText) {
      btnElement.addEventListener('click', (e) => {
        e.preventDefault();
        if (isExpired) return;

        btnElement.disabled = true;
        btnTextElement.innerText = "Создание сессии...";

        fetch('/api/payment/robokassa/checkout', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ offer_id: '{{ get_the_ID() }}' })
        })
        .then(res => res.json())
        .then(res => {
          if (res.url) {
            window.location.href = res.url;
          } else {
            btnElement.disabled = false;
            btnTextElement.innerText = originalText;
            showPaymentStatus(false, 'Ошибка платежной системы', res.message || 'Не удалось создать сессию оплаты. Подробности в консоли.');
            console.error(res);
          }
        })
        .catch(err => {
          btnElement.disabled = false;
          btnTextElement.innerText = originalText;
          showPaymentStatus(false, 'Ошибка соединения', 'Не удалось связаться с сервером.');
          console.error(err);
        });
      });
    }

    if (checkoutBtn) {
      initCheckoutButton(checkoutBtn, checkoutText, 'robokassa', 'Оплатить (Robokassa)');
    }

    setInterval(updateCountdown, 1000);
    updateCountdown();
  </script>
@endsection