<section class="py-20 bg-[#F8F9FA] overflow-hidden">
  <div class="max-w-[1400px] mx-auto px-4">
    @if(!empty($block['title']))
      <h2 class="text-4xl md:text-5xl font-black mb-16 tracking-tight text-slate-900 border-l-4 border-slate-900 pl-6 leading-tight">{{ $block['title'] }}</h2>
    @endif
    
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_400px] xl:grid-cols-[1fr_500px] gap-12 md:gap-16 items-center">
      <!-- Left: Wheel Interactive -->
      <div class="relative w-full aspect-square max-w-[700px] mx-auto rounded-full bg-white shadow-xl shadow-slate-200/50 flex items-center justify-center border-[16px] border-slate-50 group">
         
         @if(!empty($block['items']))
           @php $count = count($block['items']); @endphp
           <!-- Orbiting Terms dynamically rendered in a circle! -->
           <div class="absolute inset-0 w-full h-full rounded-full">
             @foreach($block['items'] as $index => $item)
               @php 
                 // Calculate angle for circular orbit starting from top
                 $deg = ($index * (360 / $count)) - 90; 
                 // Define orbit radius. Must be strictly larger than core_radius + item_radius
                 $radius = "clamp(160px, 25vw, 260px)"; 
               @endphp
               <a href="{{ get_permalink($item->ID) }}" class="absolute top-1/2 left-1/2 w-[90px] h-[90px] sm:w-[110px] sm:h-[110px] -mt-[45px] -ml-[45px] sm:-mt-[55px] sm:-ml-[55px] bg-white shadow-xl rounded-[1.5rem] flex flex-col items-center justify-center hover:scale-110 hover:bg-blue-600 hover:text-white transition-all duration-300 z-10 group/term" style="transform: rotate({{ $deg }}deg) translateX({{ $radius }}) rotate({{ -$deg }}deg);">
                  <!-- Dynamic term icon placeholder -->
                  <svg class="w-8 h-8 text-slate-400 group-hover/term:text-white mb-1.5 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                  <span class="text-[9px] sm:text-[10px] font-black text-center leading-tight tracking-widest uppercase px-2">{{ $item->post_title }}</span>
               </a>
             @endforeach
           </div>
         @endif
         
         <!-- Center Core -->
         <div class="absolute inset-0 flex flex-col items-center justify-center rounded-full pointer-events-none">
           <div class="w-40 h-40 sm:w-56 sm:h-56 bg-slate-900 shadow-2xl rounded-full flex flex-col items-center justify-center text-white ring-[10px] ring-white">
             <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1 sm:mb-2">E-DIET</span>
             <span class="text-xl sm:text-2xl font-black text-center leading-none">КОЛЕСО<br>ЗДОРОВЬЯ</span>
           </div>
         </div>
      </div>
      
      <!-- Right: Content -->
      <div class="flex flex-col gap-6 w-full">
         <div class="bg-[#e9ecef] rounded-[2rem] p-8 sm:p-12 relative overflow-hidden">
           <div class="absolute -top-10 -right-10 w-40 h-40 bg-white rounded-full blur-3xl opacity-50"></div>
           <!-- Stylized Quotes -->
           <svg class="absolute top-6 left-6 w-12 h-12 text-slate-300 opacity-50" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
           
           <p class="text-slate-600 leading-relaxed font-semibold text-lg sm:text-xl relative z-10 pt-6">
             Для начала найдите ваше направление на колесе слева. Кликнув по нему, вы перейдёте на страницу с детальными гайдами, протоколами питания и подборкой МПО, разработанными врачами интегративной медицины индивидуально под ваш запрос.
           </p>
         </div>
         
         @if(empty($block['items']))
           <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-xl">
             <p class="text-blue-700 text-sm font-bold">Справка для редактора:</p>
             <p class="text-blue-600 text-sm mt-1">Добавьте нужные "Заболевания" в настройках секции в админке, чтобы они отобразились в виде интерактивных долек на колесе!</p>
           </div>
         @endif
      </div>
    </div>
  </div>
</section>
