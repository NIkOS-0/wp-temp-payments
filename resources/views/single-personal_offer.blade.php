@extends('layouts.app')

@section('content')
  <div class="min-h-screen bg-slate-50 flex flex-col items-center justify-center p-4">
    <div class="max-w-4xl w-full bg-white rounded-2xl shadow-2xl overflow-hidden border border-slate-200">
      <div class="md:flex">
        <!-- Product Image -->
        <div class="md:w-1/2 bg-slate-100 flex items-center justify-center relative">
          @if($thumbnail)
            <img src="{{ $thumbnail }}" alt="{{ $product->post_title }}" class="w-full h-full object-cover">
          @else
            <div class="p-20 text-slate-400">
              <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
          @endif
          
          <!-- Special Offer Badge -->
          <div class="absolute top-4 left-4 bg-orange-500 text-white px-4 py-1 rounded-full text-sm font-bold shadow-lg transform -rotate-3">
            Персональное предложение
          </div>
        </div>

        <!-- Product Info -->
        <div class="md:w-1/2 p-8 md:p-12 flex flex-col justify-between">
          <div>
            <h1 class="text-3xl font-extrabold text-slate-900 mb-4">{{ $product->post_title }}</h1>
            
            <div class="prose prose-slate mb-8 max-w-none">
              {!! $content !!}
            </div>
          </div>

          <div class="mt-auto pt-8 border-t border-slate-100">
            <div class="flex items-baseline mb-6">
              <span class="text-4xl font-black text-blue-600">{{ $price }}</span>
              <span class="text-xl font-bold text-blue-600 ml-1">₽</span>
            </div>

            <button class="w-full bg-blue-600 hover:bg-blue-700 text-white text-xl font-bold py-4 px-8 rounded-xl transition-all transform hover:scale-[1.02] active:scale-95 shadow-xl">
              Воспользоваться предложением
            </button>
            
            <p class="text-center text-slate-400 text-xs mt-4">
              Это предложение создано специально для вас и ограничено по времени.
            </p>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Footer/Trust info -->
    <div class="mt-8 text-slate-500 text-sm flex items-center space-x-6">
      <div class="flex items-center"><svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h.01a1 1 0 100-2H10zm3 0a1 1 0 000 2h.01a1 1 0 100-2H13zm-6 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h.01a1 1 0 100-2H10zm3 0a1 1 0 000 2h.01a1 1 0 100-2H13z" clip-rule="evenodd"></path></svg>Безопасная оплата</div>
      <div class="flex items-center"><svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>Ограниченное время</div>
    </div>
  </div>
@endsection
