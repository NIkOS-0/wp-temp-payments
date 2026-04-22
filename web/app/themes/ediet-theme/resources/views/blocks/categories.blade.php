<section class="section-warm overflow-hidden">
  <div class="container-editorial">
    @if(!empty($block['title']))
      <h2 class="heading-display text-center text-balance mb-12">{{ $block['title'] }}</h2>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-[1fr_400px] xl:grid-cols-[1fr_500px] gap-12 md:gap-16 items-center">
      <!-- Left: Wheel Interactive -->
      <div id="wheel-container" class="relative w-full aspect-square max-w-[700px] mx-auto flex items-center justify-center">

        @if(!empty($block['items']))
          <canvas id="health-wheel" class="absolute inset-0 w-full h-full cursor-pointer touch-none z-10"></canvas>
          <script>
          document.addEventListener('DOMContentLoaded', () => {
            const cats = [
              @foreach($block['items'] as $index => $item)
                {
                  id: {{ $index + 1 }},
                  short: "{{ addslashes($item->post_title) }}",
                  iconUrl: "{{ get_field('hero_icon', $item->ID) ?: 'https://dev.e-diet.wiki/themes/ediet-theme/resources/images/logo.png' }}",
                  color: "{{ get_field('wheel_color', $item->ID) ?: '#4aaee0' }}",
                  url: "{{ get_permalink($item->ID) }}"
                },
              @endforeach
            ];

            const canvas = document.getElementById('health-wheel');
            const container = document.getElementById('wheel-container');
            const ctx = canvas.getContext('2d');

            let W, H, CX, CY, MIN, R_IN, R_OUT, R_EXP;
            const DPR = window.devicePixelRatio || 1;

            function initCanvas() {
                const rect = container.getBoundingClientRect();
                W = rect.width || 300;
                H = rect.height || 300;
                canvas.width = W * DPR;
                canvas.height = H * DPR;
                canvas.style.width = W + 'px';
                canvas.style.height = H + 'px';
                ctx.scale(DPR, DPR);

                CX = W / 2; CY = H / 2;
                MIN = Math.min(W, H);
                R_IN   = MIN * 0.16;
                R_OUT  = MIN * 0.44;
                R_EXP  = MIN * 0.055;
            }
            window.addEventListener('resize', initCanvas);
            initCanvas();

            const N = cats.length;
            const SLICE = (2 * Math.PI) / N;
            const GAP   = 0.022;

            let hovered = -1;

            function hexRgb(hex) {
              hex = hex || '#4aaee0';
              if (hex.length === 4) {
                hex = '#' + hex[1] + hex[1] + hex[2] + hex[2] + hex[3] + hex[3];
              }
              const r = parseInt(hex.slice(1,3), 16) || 0;
              const g = parseInt(hex.slice(3,5), 16) || 0;
              const b = parseInt(hex.slice(5,7), 16) || 0;
              return [r,g,b];
            }
            function rgba(hex, a) {
              const [r,g,b] = hexRgb(hex);
              return `rgba(${r},${g},${b},${a})`;
            }
            function lighten(hex, amt) {
              let [r,g,b] = hexRgb(hex);
              r = Math.min(255, r + amt); g = Math.min(255, g + amt); b = Math.min(255, b + amt);
              return `rgb(${r},${g},${b})`;
            }

            // Preload images
            cats.forEach(c => {
                const img = new Image();
                img.src = c.iconUrl;
                c.imgObj = img;
            });

            function drawSector(i, expandT) {
              const cat = cats[i];
              const startA = i * SLICE - Math.PI/2 + GAP/2;
              const endA   = startA + SLICE - GAP;
              const midA   = i * SLICE - Math.PI/2 + SLICE/2;

              const ox = Math.cos(midA) * R_EXP * expandT;
              const oy = Math.sin(midA) * R_EXP * expandT;

              ctx.save();
              ctx.translate(ox, oy);

              ctx.beginPath();
              ctx.moveTo(Math.cos(startA) * R_IN, Math.sin(startA) * R_IN);
              ctx.arc(0, 0, R_OUT, startA, endA);
              ctx.arc(0, 0, R_IN,  endA, startA, true);
              ctx.closePath();

              ctx.shadowColor = rgba(cat.color, 0.25);
              ctx.shadowBlur  = 16;
              ctx.shadowOffsetY = 4;

              const grad = ctx.createRadialGradient(
                Math.cos(midA)*(R_IN+R_OUT)*0.38, Math.sin(midA)*(R_IN+R_OUT)*0.38, 0,
                0, 0, R_OUT
              );
              grad.addColorStop(0, lighten(cat.color, 55));
              grad.addColorStop(0.5, cat.color);
              grad.addColorStop(1, rgba(cat.color, 0.75));
              ctx.fillStyle = grad;
              ctx.fill();

              ctx.shadowColor = 'transparent';
              ctx.shadowBlur  = 0;
              ctx.shadowOffsetY = 0;

              ctx.strokeStyle = 'rgba(255,255,255,0.35)';
              ctx.lineWidth = 1.5;
              ctx.stroke();

              const emojiR = R_IN + (R_OUT - R_IN) * 0.58;
              const ex = Math.cos(midA) * emojiR;
              const ey = Math.sin(midA) * emojiR;

              if (cat.imgObj && cat.imgObj.complete && cat.imgObj.naturalHeight !== 0) {
                  const sSize = MIN * 0.14;
                  ctx.drawImage(cat.imgObj, ex - sSize/2, ey - sSize/2, sSize, sSize);
              }

              const numR = R_OUT - MIN * 0.028;
              ctx.font = `300 ${MIN * 0.044}px 'Inter', sans-serif`;
              ctx.fillStyle = 'rgba(255,255,255,0.55)';
              ctx.textAlign = 'center';
              ctx.textBaseline = 'middle';
              ctx.fillText(String(cat.id).padStart(2,'0'), Math.cos(midA)*numR, Math.sin(midA)*numR);

              ctx.restore();
            }

            function drawOuterLabels() {
              const labelR = R_OUT + MIN * 0.045;
              cats.forEach((cat, i) => {
                const midA = i * SLICE - Math.PI/2 + SLICE/2;
                const lx = Math.cos(midA) * labelR;
                const ly = Math.sin(midA) * labelR;

                ctx.save();
                ctx.translate(lx, ly);
                let rot = midA + Math.PI/2;
                if (midA > Math.PI/2 && midA < Math.PI*1.5) rot += Math.PI;
                ctx.rotate(rot);

                ctx.font = `700 ${MIN * 0.018}px 'Inter', sans-serif`;
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillStyle = rgba(cat.color, 0.72);
                ctx.fillText(cat.short.toUpperCase(), 0, 0);
                ctx.restore();
              });
            }

            function drawCenter(cat) {
              ctx.beginPath();
              ctx.arc(0, 0, R_IN * 0.97, 0, Math.PI*2);
              ctx.shadowColor = 'rgba(0,0,0,0.05)';
              ctx.shadowBlur = 20;
              ctx.fillStyle = '#ffffff';
              ctx.fill();
              ctx.shadowBlur = 0;

              ctx.beginPath();
              ctx.arc(0, 0, R_IN * 0.87, 0, Math.PI*2);
              ctx.strokeStyle = cat ? rgba(cat.color, 0.45) : 'rgba(200,210,230,0.5)';
              ctx.lineWidth = 1.5;
              ctx.setLineDash([3,5]);
              ctx.stroke();
              ctx.setLineDash([]);

              if (cat) {
                ctx.beginPath();
                ctx.arc(0, 0, R_IN * 0.80, 0, Math.PI*2);
                ctx.fillStyle = rgba(cat.color, 0.09);
                ctx.fill();
              }

              if (cat && cat.imgObj && cat.imgObj.complete && cat.imgObj.naturalHeight !== 0) {
                  const sSize = R_IN * 0.75;
                  ctx.drawImage(cat.imgObj, -sSize/2, -R_IN * 0.1 - sSize/2, sSize, sSize);
              } else if (!cat) {
                  const sSize = R_IN * 0.6;
                  ctx.font = `800 ${sSize}px 'Inter', sans-serif`;
                  ctx.textAlign = 'center';
                  ctx.textBaseline = 'middle';
                  ctx.globalAlpha = 0.2;
                  ctx.fillText('✚', 0, -R_IN * 0.1);
                  ctx.globalAlpha = 1;
              }

              ctx.font = `800 ${MIN * 0.026}px 'Inter', sans-serif`;
              ctx.textAlign = 'center';
              ctx.textBaseline = 'middle';
              ctx.fillStyle = cat ? cat.color : '#0f172a';
              ctx.fillText(cat ? cat.short.toUpperCase() : 'ЗДОРОВЬЕ', 0, R_IN * 0.40);

              ctx.font = `600 ${MIN * 0.017}px 'Inter', sans-serif`;
              ctx.fillStyle = '#94a3b8';
              ctx.fillText(cat ? `Раздел ${String(cat.id).padStart(2,'0')}` : 'Выберите раздел', 0, R_IN * 0.58);
            }

            const expandStates = new Array(N).fill(0);
            let lastTime = 0;

            function render(now) {
              const dt = Math.min((now - lastTime) / 1000, 0.05);
              lastTime = now;

              for (let i = 0; i < N; i++) {
                const target = i === hovered ? 1 : 0;
                const speed = 8;
                expandStates[i] += (target - expandStates[i]) * speed * dt;
                if (Math.abs(expandStates[i] - target) <= 0.001) expandStates[i] = target;
              }

              ctx.clearRect(0, 0, W, H);

              ctx.save();
              ctx.translate(CX, CY);

              for (let i = 0; i < N; i++) {
                if (i !== hovered) drawSector(i, expandStates[i]);
              }
              if (hovered >= 0) drawSector(hovered, expandStates[hovered]);

              drawOuterLabels();
              drawCenter(hovered >= 0 ? cats[hovered] : null);

              ctx.restore();
              requestAnimationFrame(render);
            }

            function handleHit(mx, my) {
              const dist = Math.sqrt(mx*mx + my*my);
              if (dist < R_IN || dist > R_OUT + R_EXP * 1.2) {
                return -1;
              }
              let angle = Math.atan2(my, mx) + Math.PI/2;
              if (angle < 0) angle += 2 * Math.PI;
              return Math.floor(angle / SLICE) % N;
            }

            canvas.addEventListener('mousemove', e => {
              const rect = canvas.getBoundingClientRect();
              const mx = e.clientX - rect.left - CX;
              const my = e.clientY - rect.top  - CY;

              const hit = handleHit(mx, my);
              hovered = hit;
              canvas.style.cursor = hit >= 0 ? 'pointer' : 'default';
            });

            canvas.addEventListener('mouseleave', () => { hovered = -1; });

            canvas.addEventListener('click', e => {
               const rect = canvas.getBoundingClientRect();
               const mx = e.clientX - rect.left - CX;
               const my = e.clientY - rect.top  - CY;
               const hit = handleHit(mx, my);
               if (hit >= 0) {
                   window.location.href = cats[hit].url;
               }
            });

            canvas.addEventListener('touchstart', e => {
                if (e.touches.length > 0) {
                    const rect = canvas.getBoundingClientRect();
                    const mx = e.touches[0].clientX - rect.left - CX;
                    const my = e.touches[0].clientY - rect.top  - CY;
                    const hit = handleHit(mx, my);

                    if (hit >= 0) {
                       e.preventDefault();
                       if (hovered === hit) {
                           window.location.href = cats[hit].url;
                       } else {
                           hovered = hit;
                       }
                    } else {
                       hovered = -1;
                    }
                }
            }, {passive: false});

            requestAnimationFrame(render);
          });
          </script>
        @endif
      </div>

      <!-- Right: Content -->
      <div class="flex flex-col gap-6 w-full">
        <div class="card-warm p-8 sm:p-12 relative overflow-hidden">
          <div class="absolute -top-10 -right-10 w-40 h-40 bg-cream-100 rounded-full blur-3xl opacity-50 pointer-events-none"></div>
          <svg class="absolute top-6 left-6 w-12 h-12 text-bark-300 opacity-50 pointer-events-none" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>

          <p class="text-body font-semibold relative z-10 pt-6">
            Для начала найдите ваше направление на колесе слева. Кликнув по нему, вы перейдёте на страницу с детальными гайдами, протоколами питания и подборкой МПО, разработанными врачами интегративной медицины индивидуально под ваш запрос.
          </p>
        </div>

        @if(empty($block['items']))
          <div class="card p-4 border-l-4 border-terra-400">
            <p class="text-sm font-bold text-bark-900">Справка для редактора:</p>
            <p class="text-body text-sm mt-1">Добавьте нужные "Заболевания" в настройках секции в админке, чтобы они отобразились в виде интерактивных долек на колесе!</p>
          </div>
        @endif
      </div>
    </div>
  </div>
</section>
