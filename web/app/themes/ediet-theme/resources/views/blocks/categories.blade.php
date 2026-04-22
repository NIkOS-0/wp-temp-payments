<section class="section-dark py-24 relative" style="background: var(--color-onyx-900); overflow: clip;">
  <!-- Ambient background glow -->
  <div class="absolute top-0 right-1/4 w-[600px] h-[600px] bg-onyx-800 rounded-full blur-[120px] opacity-30 pointer-events-none"></div>

  <div class="container-editorial relative z-10">
    <div class="text-xs font-semibold uppercase tracking-[0.18em] text-terra-500 mb-2">
      Интерактивный навигатор
    </div>
    @if(!empty($block['title']))
      <h2 class="heading-hero-light mb-12">
        {!! preg_replace('/(своё направление|свое направление)/ui', '<em class="italic text-terra-300 font-serif">$1</em>', $block['title']) !!}
      </h2>
    @else
      <h2 class="heading-hero-light mb-12">найдите <em class="italic text-terra-300 font-serif">своё направление</em></h2>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-[1fr_1fr] gap-12 lg:gap-24 items-center mt-10 lg:mt-16">
      
      <!-- Left: Wheel Interactive SVG -->
      <div id="wheel-container" class="relative w-full aspect-square max-w-[480px] lg:max-w-[640px] xl:max-w-[720px] mx-auto">
        <svg class="w-full h-full overflow-visible" viewBox="0 0 400 400" id="wheelSvg">
          <defs>
            <!-- Global SVG Glow Filter -->
            <filter id="wglow" x="-20%" y="-20%" width="140%" height="140%">
              <feGaussianBlur stdDeviation="5" result="b"/>
              <feMerge>
                <feMergeNode in="b"/>
                <feMergeNode in="SourceGraphic"/>
              </feMerge>
            </filter>
          </defs>
          
          <!-- Dynamic Segments Injected Here -->
          <g id="wheel-segments" class="origin-center"></g>
          
          <!-- Center Hub -->
          <g class="pointer-events-none">
            <circle cx="200" cy="200" r="68" fill="var(--color-onyx-950)" stroke="var(--color-terra-600)" stroke-width="1.5" stroke-opacity="0.4"/>
            <text x="200" y="195" text-anchor="middle" font-family="'Playfair Display', serif" font-size="16" font-weight="700" fill="var(--color-cream-100)" letter-spacing="1">ЗДОРОВЬЕ</text>
            <text x="200" y="213" text-anchor="middle" font-family="'Inter', sans-serif" font-size="10" font-weight="500" fill="var(--color-bark-400)">Выберите раздел</text>
          </g>

          <!-- Dynamic Labels Injected Here -->
          <g id="wheel-labels" class="pointer-events-none"></g>
        </svg>
      </div>

      <!-- Right: Content Info Box -->
      <div class="relative w-full">
        <div class="relative p-10 sm:p-12 border border-onyx-800 rounded-[28px] flex flex-col justify-between overflow-hidden shadow-2xl transition-all duration-300 min-h-[300px]" style="background: rgba(27, 20, 14, 0.45); backdrop-filter: blur(12px);">
          
          <!-- Decorative Glow inside card -->
          <div class="absolute -top-12 -right-12 w-[240px] h-[240px] rounded-full pointer-events-none mix-blend-screen" style="background: radial-gradient(circle, rgba(232,144,104, 0.12) 0%, transparent 65%);"></div>
          
          <div class="relative z-10">
            <div class="font-serif text-[64px] leading-none text-terra-500/20 mb-4 select-none opacity-50">"</div>
            <div class="font-serif text-3xl font-bold text-cream-50 mb-4 tracking-tight" id="wTitle">
              Выберите направление на колесе
            </div>
            <div class="text-[15.5px] text-bark-100/80 leading-[1.75] font-light" id="wText">
              Кликните на любой сегмент колеса слева. Вы перейдёте на страницу с детальными гайдами, протоколами питания и подборкой МПО, разработанными врачами интегративной медицины индивидуально под ваш запрос.
            </div>
          </div>

          <div class="mt-10 relative z-10" id="wCta" style="display:none">
            <a href="#" class="btn-primary" id="wLink" style="background: var(--color-terra-600); color: var(--color-cream-50); box-shadow: 0 4px 14px rgba(173,98,63,0.3);">
              Смотреть раздел
              <svg class="w-4 h-4 ml-1 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const cats = [
    @if(!empty($block['items']))
      @foreach($block['items'] as $index => $item)
        {
          id: {{ $index + 1 }},
          short: "{{ addslashes($item->post_title) }}",
          desc: "{{ addslashes(get_field('hero_desc', $item->ID) ?: '') }}",
          color: "{{ get_field('wheel_color', $item->ID) ?: '#c77b5a' }}", 
          url: "{{ get_permalink($item->ID) }}"
        },
      @endforeach
    @endif
  ];

  // Fixed 10 segments to maintain layout consistency (36 degrees each)
  const N = Math.max(10, cats.length);

  const CX = 200, CY = 200;
  const R_IN = 80, R_OUT = 180;
  const GAP = 0.035; 
  const SLICE = (2 * Math.PI) / N;

  const segmentsGroup = document.getElementById('wheel-segments');
  const labelsGroup = document.getElementById('wheel-labels');
  
  // UI Panels
  const wTitle = document.getElementById('wTitle');
  const wText = document.getElementById('wText');
  const wCta = document.getElementById('wCta');
  const wLink = document.getElementById('wLink');

  function polarToCartesian(cx, cy, r, angle) {
    return {
      x: cx + r * Math.cos(angle),
      y: cy + r * Math.sin(angle)
    };
  }

  function createPath(startA, endA, popDist = 0) {
    const midA = startA + (endA - startA) / 2;
    const ox = Math.cos(midA) * popDist;
    const oy = Math.sin(midA) * popDist;
    
    if(endA < startA) endA += Math.PI * 2;
    
    const p1 = polarToCartesian(CX + ox, CY + oy, R_IN, startA);
    const p2 = polarToCartesian(CX + ox, CY + oy, R_OUT, startA);
    const p3 = polarToCartesian(CX + ox, CY + oy, R_OUT, endA);
    const p4 = polarToCartesian(CX + ox, CY + oy, R_IN, endA);
    
    const largeArc = endA - startA <= Math.PI ? "0" : "1";

    return [
      `M ${p1.x} ${p1.y}`,
      `L ${p2.x} ${p2.y}`,
      `A ${R_OUT} ${R_OUT} 0 ${largeArc} 1 ${p3.x} ${p3.y}`,
      `L ${p4.x} ${p4.y}`,
      `A ${R_IN} ${R_IN} 0 ${largeArc} 0 ${p1.x} ${p1.y}`,
      "Z"
    ].join(" ");
  }

  function rgbObj(hex) {
     hex = hex.replace('#','');
     if(hex.length === 3) hex = hex[0]+hex[0]+hex[1]+hex[1]+hex[2]+hex[2];
     if(hex.length !== 6) return {r:199,g:123,b:90}; 
     return {
       r: parseInt(hex.substring(0,2), 16),
       g: parseInt(hex.substring(2,4), 16),
       b: parseInt(hex.substring(4,6), 16)
     };
  }

  // Generate 10 segments (or cats.length if more than 10)
  for (let i = 0; i < N; i++) {
    const cat = cats[i] || null; // Might be empty segment
    let startA = i * SLICE - Math.PI/2 + GAP/2;
    let endA = startA + SLICE - GAP;
    
    const color = cat ? rgbObj(cat.color) : {r: 30, g: 30, b: 30};

    // Radial Gradient
    const defs = document.querySelector('#wheelSvg defs');
    const grad = document.createElementNS('http://www.w3.org/2000/svg', 'radialGradient');
    grad.id = 'grad-segment-' + i;
    grad.setAttribute('cx', '50%');
    grad.setAttribute('cy', '50%');
    grad.setAttribute('r', '50%');
    
    const stop1 = document.createElementNS('http://www.w3.org/2000/svg', 'stop');
    stop1.setAttribute('offset', '0%');
    stop1.setAttribute('stop-color', cat ? `rgba(${color.r},${color.g},${color.b}, 0.5)` : 'rgba(255,255,255,0.03)');
    
    const stop2 = document.createElementNS('http://www.w3.org/2000/svg', 'stop');
    stop2.setAttribute('offset', '100%');
    stop2.setAttribute('stop-color', cat ? `rgba(${color.r},${color.g},${color.b}, 0.08)` : 'rgba(255,255,255,0.0)');
    
    grad.appendChild(stop1);
    grad.appendChild(stop2);
    defs.appendChild(grad);

    // Path
    const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    path.setAttribute('d', createPath(startA, endA, 0));
    path.setAttribute('fill', `url(#grad-segment-${i})`);
    path.setAttribute('stroke', cat ? `rgba(${color.r}, ${color.g}, ${color.b}, 0.3)` : 'rgba(255,255,255,0.05)');
    path.setAttribute('stroke-width', '1.5');
    path.style.transition = 'all 0.4s cubic-bezier(0.3, 0.8, 0.3, 1)';
    path.style.transformOrigin = '200px 200px';

    if (cat) {
      path.setAttribute('class', 'cursor-pointer');

      function lightUp() {
        path.setAttribute('d', createPath(startA, endA, 8)); 
        path.setAttribute('filter', 'url(#wglow)');
        path.setAttribute('fill', `rgba(${color.r},${color.g},${color.b}, 0.35)`);
        path.setAttribute('stroke', `rgba(${color.r}, ${color.g}, ${color.b}, 0.8)`);
        
        wTitle.innerHTML = cat.short;
        wText.innerHTML  = cat.desc || 'Нажмите, чтобы ознакомиться с персональными протоколами и детальными рекомендациями.';
        wCta.style.display = 'block';
        wLink.href = cat.url;
      }
      
      function lightDown() {
        path.setAttribute('d', createPath(startA, endA, 0));
        path.removeAttribute('filter');
        path.setAttribute('fill', `url(#grad-segment-${i})`);
        path.setAttribute('stroke', `rgba(${color.r}, ${color.g}, ${color.b}, 0.3)`);
      }

      path.addEventListener('mouseenter', lightUp);
      path.addEventListener('mouseleave', lightDown);
      path.addEventListener('click', () => { window.location.href = cat.url; });
    }

    segmentsGroup.appendChild(path);
    
    // Label Placement INSIDE the Petal
    if (cat) {
      const midA = startA + (endA - startA)/2;
      const labelR = R_IN + (R_OUT - R_IN) * 0.5; // Exactly in the middle of the petal
      
      const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
      const lx = CX + labelR * Math.cos(midA);
      const ly = CY + labelR * Math.sin(midA);
      
      // Rotate Text perpendicular to the arc (parallel to the radius line)
      let rot = midA * (180/Math.PI);
      if (rot > 90 && rot < 270) {
         rot += 180;
      }
      
      text.setAttribute('x', lx);
      text.setAttribute('y', ly);
      text.setAttribute('transform', `rotate(${rot}, ${lx}, ${ly})`);
      text.setAttribute('text-anchor', 'middle');
      text.setAttribute('dominant-baseline', 'middle');
      text.setAttribute('fill', `rgba(255,255,255, 0.7)`); // slightly brighter inside
      text.style.fontSize = '10px'; // Made font slightly larger since it has more vertical room when aligned radially
      text.style.fontWeight = '700';
      text.style.letterSpacing = '0.5px';
      text.style.fontFamily = "'Inter', sans-serif";
      text.style.pointerEvents = 'none';
      
      // Breaking string into words if long
      const words = cat.short.toUpperCase().split(' ');
      if (words.length > 1) {
          words.forEach((word, idx) => {
              const tspan = document.createElementNS('http://www.w3.org/2000/svg', 'tspan');
              tspan.setAttribute('x', lx);
              tspan.setAttribute('dy', idx === 0 ? '-0.5em' : '1.2em');
              tspan.textContent = word;
              text.appendChild(tspan);
          });
      } else {
          text.textContent = cat.short.toUpperCase();
      }

      // Tie text brightness to path hover
      path.addEventListener('mouseenter', () => {
         text.setAttribute('fill', `rgba(255,255,255, 1)`);
         text.style.filter = "drop-shadow(0px 2px 4px rgba(0,0,0,0.5))";
      });
      path.addEventListener('mouseleave', () => {
         text.setAttribute('fill', `rgba(255,255,255, 0.7)`);
         text.style.filter = "none";
      });

      labelsGroup.appendChild(text);
    }
  }
});
</script>
