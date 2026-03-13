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

            <button class="relative group/btn w-full sm:w-auto px-10 py-5 bg-slate-900 text-white rounded-2xl font-black text-xl tracking-wide shadow-2xl transition-all hover:bg-blue-600 hover:shadow-blue-500/30 overflow-hidden">
              <span class="relative z-10 flex items-center justify-center gap-3">
                Оформить сейчас
                <svg class="w-6 h-6 transform transition-transform group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
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

    function updateCountdown() {
      const now = new Date().getTime();
      const distance = expiryTimestamp - now;

      if (distance < 0) {
        countdownEl.innerHTML = "ИСТЕКЛО";
        countdownEl.classList.replace('text-orange-600', 'text-red-600');
        // Immediately reload the page to trigger server-side 404/expiry check
        setTimeout(() => location.reload(), 1000);
        return;
      }

      const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      const seconds = Math.floor((distance % (1000 * 60)) / 1000);

      const pad = (num) => String(num).padStart(2, '0');
      countdownEl.innerHTML = `${pad(hours)}:${pad(minutes)}:${pad(seconds)}`;
    }

    setInterval(updateCountdown, 1000);
    updateCountdown();
  </script>
@endsection
