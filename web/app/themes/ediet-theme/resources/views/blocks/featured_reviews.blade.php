<section class="py-24 bg-[#F8F9FA] overflow-hidden">
  <div class="max-w-[1400px] mx-auto px-4 relative">
    <div class="flex items-center justify-between mb-16">
      @if(!empty($block['title']))
        <h2 class="text-4xl md:text-5xl font-black tracking-tight text-slate-900">{{ $block['title'] }}</h2>
      @else
        <h2 class="text-4xl md:text-5xl font-black tracking-tight text-slate-900">Истории, которые дают надежду</h2>
      @endif
      
      <a href="/reviews" class="hidden md:flex items-center gap-2 font-black text-slate-900 hover:text-blue-600 transition-colors uppercase tracking-widest text-sm">
        Смотреть отзывы <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
      </a>
    </div>

    <!-- Reviews Slider UI (Representational from Mockup) -->
    <div class="relative w-full h-[600px] md:h-[500px] flex items-center justify-center">
       
       <!-- Left Inactive Card -->
       <div class="hidden lg:flex absolute left-0 w-[280px] h-[400px] bg-white rounded-[2rem] shadow-sm transform -translate-x-10 scale-90 opacity-60 border border-slate-100 items-center justify-center blur-[2px]">
         <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center"><svg class="w-8 h-8 text-slate-300 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg></div>
       </div>
       
       <!-- Center Active Card -->
       <div class="relative z-10 w-full max-w-[800px] bg-white rounded-[2rem] shadow-2xl flex flex-col md:flex-row shadow-slate-200 border border-slate-100 p-3 border-l-8 border-l-blue-600 hover:scale-[1.01] transition-transform duration-500">
         
         <!-- Video Left Box -->
         <div class="w-full md:w-1/2 h-[250px] md:h-auto min-h-[400px] bg-[#e9ecef] rounded-[1.5rem] relative flex items-center justify-center overflow-hidden">
            <div class="absolute inset-0 w-full h-full bg-slate-100"></div>
            <div class="w-20 h-20 bg-white/90 backdrop-blur-md rounded-full shadow-lg flex items-center justify-center cursor-pointer hover:bg-white hover:scale-110 transition-all relative z-10 duration-300 group">
               <svg class="w-8 h-8 text-slate-800 ml-1 group-hover:text-blue-600 transition-colors" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
            </div>
            
            <!-- User Identifier overlay -->
            <div class="absolute bottom-6 left-6 right-6 flex items-center gap-4 bg-white/95 backdrop-blur-md p-3 px-4 rounded-2xl shadow-xl">
               <div class="w-10 h-10 bg-[#ced4da] rounded-full shrink-0 flex items-center justify-center overflow-hidden">
                 <svg class="w-6 h-6 text-slate-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.2 0 4-1.8 4-4s-1.8-4-4-4-4 1.8-4 4 1.8 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
               </div>
               <div>
                  <h4 class="font-bold text-slate-900 text-sm leading-tight">Елена Смирнова</h4>
                  <p class="text-xs font-bold text-slate-500 mt-0.5">Диагноз: Гипотиреоз</p>
               </div>
            </div>
         </div>
         
         <!-- Text Right Box -->
         <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center relative">
            <span class="absolute top-4 left-6 text-7xl font-serif text-slate-100 leading-none pointer-events-none">"</span>
            <div class="relative z-10 pt-4">
               <div class="inline-block px-3 py-1 bg-gradient-to-r from-[#fff3cd] to-[#ffecb3] text-[#856404] text-[10px] font-black uppercase tracking-widest rounded-md mb-8 border border-[#ffeeba] shadow-[0_2px_10px_rgba(255,236,179,0.5)]">РЕЗУЛЬТАТЫ ПРОГРАММЫ ДО / ПОСЛЕ</div>
               
               <div class="space-y-8">
                 <div>
                   <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 flex items-center gap-2">
                     <span class="w-2 h-2 rounded-full bg-slate-300"></span>БЫЛО
                   </span>
                   <p class="text-sm text-slate-500 font-medium italic pr-4 leading-relaxed">"Постоянная усталость, вес стоял, жесткая депрессия, ТТГ выше 15. Эндокринологи прописывали только гормоны, но состояние даже ухудшалось..."</p>
                 </div>
                 
                 <div class="relative">
                   <div class="absolute -top-4 -left-3 border-l-2 border-b-2 border-slate-100 w-4 h-8 rounded-bl-xl pointer-events-none"></div>
                   
                   <span class="block text-[10px] font-black text-blue-500 uppercase tracking-widest mb-2 flex items-center gap-2">
                     <span class="w-2 h-2 rounded-full bg-blue-500"></span>СТАЛО
                   </span>
                   <p class="text-sm text-slate-900 font-bold leading-relaxed bg-[#f8f9fa] p-4 rounded-xl">"ТТГ спустился до 2. Идеальное самочувствие. Полностью ушла отечность тела и вернулась энергия для регулярных тренировок!"</p>
                 </div>
               </div>
            </div>
         </div>
         
       </div>
       
       <!-- Right Inactive Card -->
       <div class="hidden lg:flex absolute right-0 w-[280px] h-[400px] bg-white rounded-[2rem] shadow-sm transform translate-x-10 scale-90 opacity-60 border border-slate-100 items-center justify-center blur-[2px]">
         <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center"><svg class="w-8 h-8 text-slate-300 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg></div>
       </div>

    </div>

    <!-- Slider Controls -->
    <div class="flex justify-center items-center gap-6 mt-12 mb-4">
      <button class="w-14 h-14 rounded-full border-2 border-slate-200 flex items-center justify-center text-slate-500 hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-colors group"><svg class="w-6 h-6 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></button>
      <div class="flex items-center gap-3 bg-white px-4 py-2 rounded-full shadow-sm border border-slate-100">
         <div class="w-8 h-2.5 bg-slate-900 rounded-full cursor-pointer"></div>
         <div class="w-2.5 h-2.5 bg-slate-200 rounded-full cursor-pointer hover:bg-slate-400 transition-colors"></div>
         <div class="w-2.5 h-2.5 bg-slate-200 rounded-full cursor-pointer hover:bg-slate-400 transition-colors"></div>
      </div>
      <button class="w-14 h-14 rounded-full border-2 border-slate-200 flex items-center justify-center text-slate-500 hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-colors group"><svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></button>
    </div>

  </div>
</section>
