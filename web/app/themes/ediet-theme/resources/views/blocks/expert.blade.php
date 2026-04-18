<section class="py-24 bg-[#F8F9FA]">
  <div class="max-w-[1200px] mx-auto px-4">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12 px-2">
      <h2 class="text-4xl md:text-5xl font-black tracking-tight text-slate-900 leading-[1.1]">{{ !empty($block['main_title']) ? $block['main_title'] : 'Почему нам доверяют?' }}</h2>
      @if(!empty($block['main_link']))
        <a href="{{ $block['main_link']['url'] }}" target="{{ $block['main_link']['target'] }}" class="inline-flex items-center gap-2 font-black text-slate-900 hover:text-blue-600 transition-colors uppercase tracking-widest text-sm bg-white px-6 py-3 rounded-full shadow-sm border border-slate-200">
          {{ $block['main_link']['title'] }} <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </a>
      @endif
    </div>

    <!-- Main Expert Card -->
    <div class="bg-white rounded-[2rem] p-8 md:p-12 shadow-2xl shadow-slate-200/40 mb-6 border border-slate-100">
       <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-12 xl:gap-20 items-center">
         
         <div class="flex flex-col items-center">
            <div class="relative mb-8 w-48 h-48">
               <div class="w-full h-full bg-gradient-to-br from-[#f1f3f5] to-[#e9ecef] rounded-[2rem] flex items-center justify-center shadow-inner ring-1 ring-slate-200/50 overflow-hidden">
                 @if(!empty($block['photo']['url']))
                   <img src="{{ $block['photo']['url'] }}" class="w-full h-full object-cover" alt="{{ !empty($block['name']) ? $block['name'] : 'Expert' }}">
                 @else
                   <svg class="w-20 h-20 text-[#adb5bd]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                 @endif
               </div>
               
               <div class="absolute -bottom-3 -right-3 w-10 h-10 bg-green-500 rounded-full border-4 border-white flex items-center justify-center shadow-[0_4px_10px_rgba(34,197,94,0.4)] z-20">
                 <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
               </div>
            </div>
            
            <h3 class="text-2xl font-black text-slate-900 mb-2 text-center">{{ !empty($block['name']) ? $block['name'] : 'Имя Эксперта' }}</h3>
            @if(!empty($block['role']))
              <p class="text-xs font-bold text-slate-400 tracking-widest uppercase mb-6 text-center leading-relaxed">{{ $block['role'] }}</p>
            @endif
            
            <div class="flex flex-col gap-2 w-full">
              @if(!empty($block['tag1']))
                <span class="px-5 py-2.5 bg-[#f8f9fa] rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-500 text-center border border-slate-100">{{ $block['tag1'] }}</span>
              @endif
              @if(!empty($block['tag2']))
                <span class="px-5 py-2.5 bg-[#f8f9fa] rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-500 text-center border border-slate-100">{{ $block['tag2'] }}</span>
              @endif
            </div>
         </div>
         
         <div class="flex flex-col h-full justify-center">
            @if(!empty($block['content_title']))
              <h4 class="text-2xl md:text-3xl font-black text-slate-900 mb-6 leading-tight">{{ $block['content_title'] }}</h4>
            @endif
            
            <div class="prose prose-slate max-w-none mb-10">
               <div class="text-slate-600 font-semibold leading-loose text-lg">
                 {!! !empty($block['text']) ? $block['text'] : '' !!}
               </div>
               @if(!empty($block['quote']))
                 <p class="text-slate-400 font-medium italic text-base mt-6 pl-4 border-l-4 border-slate-200">
                   "{{ $block['quote'] }}"
                 </p>
               @endif
            </div>
            
            @if(!empty($block['stats']))
              <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 pt-10 border-t border-slate-100 mt-auto">
                 @foreach($block['stats'] as $stat)
                   <div>
                     <span class="block text-4xl font-black mb-2 text-{{ !empty($stat['color']) ? $stat['color'] : 'slate-900' }}">{{ $stat['value'] }}</span>
                     <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-snug block">{{ $stat['label'] }}</span>
                   </div>
                 @endforeach
              </div>
            @endif
         </div>
         
       </div>
    </div>
  </div>
</section>
