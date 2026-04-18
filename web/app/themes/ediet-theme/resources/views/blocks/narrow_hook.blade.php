<section class="py-8 bg-[#F8F9FA]">
  <div class="max-w-[1200px] mx-auto px-4">
    <div class="bg-white rounded-[2rem] p-6 lg:p-8 shadow-xl shadow-slate-200/30 flex flex-col lg:flex-row items-center justify-between gap-6 border border-transparent hover:border-slate-200 transition-all hover:shadow-2xl relative z-10">
       <div class="flex items-center gap-6 w-full lg:w-auto">
         <div class="w-16 h-16 bg-[#f8f9fa] rounded-2xl flex items-center justify-center shrink-0 border border-slate-100 overflow-hidden">
           @if(!empty($block['icon']['url']))
             <img src="{{ $block['icon']['url'] }}" class="w-full h-full object-cover" alt="Icon">
           @else
             <svg class="w-8 h-8 text-slate-400" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="6" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="2"/></svg>
           @endif
         </div>
         <div>
           @if(!empty($block['subtitle']))
             <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">{{ $block['subtitle'] }}</p>
           @endif
           @if(!empty($block['title']))
             <h4 class="text-xl md:text-2xl font-black text-slate-900 leading-none">{{ $block['title'] }}</h4>
           @endif
         </div>
       </div>
       
       @if(!empty($block['text']))
         <p class="text-slate-500 text-base font-semibold flex-1 max-w-xl hidden lg:block px-6">{{ $block['text'] }}</p>
       @endif
       
       @if(!empty($block['btn']))
         <a href="{{ $block['btn']['url'] }}" target="{{ $block['btn']['target'] }}" class="w-full lg:w-auto px-8 py-5 bg-slate-900 text-white rounded-xl font-bold hover:bg-blue-600 transition-all hover:scale-105 shadow-xl shadow-slate-400/20 shrink-0 text-center">
           {{ $block['btn']['title'] }}
         </a>
       @endif
    </div>
  </div>
</section>
