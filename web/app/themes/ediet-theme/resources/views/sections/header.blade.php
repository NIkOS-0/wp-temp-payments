<header class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 border-b border-transparent" id="site-header">
  <div class="backdrop-blur-md bg-white/70">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-20">
        <!-- Logo -->
        <div class="flex-shrink-0 flex items-center">
          <a href="{{ home_url('/') }}" class="flex items-center gap-2 group transition-transform hover:scale-105">
            <div class="w-10 h-10 bg-linear-to-br from-blue-600 to-indigo-700 rounded-xl flex items-center justify-center shadow-lg transform rotate-3 group-hover:rotate-6 transition-transform">
              <span class="text-white font-black text-xl">E</span>
            </div>
            <span class="text-2xl font-black tracking-tighter text-slate-900 leading-none">
              eDiet<span class="text-blue-600">.</span>
            </span>
          </a>
        </div>

        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center space-x-8">
          @if (has_nav_menu('primary_navigation'))
            {!! wp_nav_menu([
              'theme_location' => 'primary_navigation', 
              'menu_class' => 'flex space-x-8 text-sm font-bold text-slate-600 uppercase tracking-widest', 
              'container' => false,
              'echo' => false,
              'li_class' => 'hover:text-blue-600 transition-colors'
            ]) !!}
          @endif
          
          @if(is_user_logged_in())
            <a href="/cabinet" class="px-6 py-2.5 bg-slate-900 text-white rounded-xl text-sm font-bold hover:bg-blue-600 hover:shadow-xl hover:shadow-blue-500/20 transition-all">
              Личный кабинет
            </a>
          @else
            <button onclick="document.getElementById('otp-modal').classList.remove('hidden')" class="px-6 py-2.5 bg-slate-900 text-white rounded-xl text-sm font-bold hover:bg-blue-600 hover:shadow-xl hover:shadow-blue-500/20 transition-all">
              Войти
            </button>
          @endif
        </nav>

        <!-- Mobile Menu Button -->
        <div class="md:hidden flex items-center">
          <button type="button" class="text-slate-900 p-2" onclick="toggleMobileMenu()">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" id="menu-icon">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Mobile Menu -->
  <div class="hidden md:hidden bg-white border-b border-slate-100" id="mobile-menu">
    <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
      @if (has_nav_menu('primary_navigation'))
        {!! wp_nav_menu([
          'theme_location' => 'primary_navigation', 
          'menu_class' => 'block px-3 py-4 text-base font-bold text-slate-600 uppercase tracking-wider border-b border-slate-50', 
          'container' => false,
          'echo' => false
        ]) !!}
      @endif
      @if(is_user_logged_in())
        <a href="/cabinet" class="block w-full text-center py-4 bg-blue-600 text-white font-bold">
          Личный кабинет
        </a>
      @else
        <button onclick="document.getElementById('otp-modal').classList.remove('hidden'); toggleMobileMenu();" class="block w-full text-center py-4 bg-blue-600 text-white font-bold">
          Войти
        </button>
      @endif
    </div>
  </div>
</header>

<script>
  function toggleMobileMenu() {
    const menu = document.getElementById('mobile-menu');
    menu.classList.toggle('hidden');
  }

  window.addEventListener('scroll', () => {
    const header = document.getElementById('site-header');
    if (window.scrollY > 20) {
      header.classList.add('shadow-sm');
      header.querySelector('.backdrop-blur-md').classList.replace('bg-white/70', 'bg-white/90');
    } else {
      header.classList.remove('shadow-sm');
      header.querySelector('.backdrop-blur-md').classList.replace('bg-white/90', 'bg-white/70');
    }
  });
</script>

@if(!is_user_logged_in())
<div id="otp-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm transition-opacity">
  <div class="bg-white rounded-2xl p-6 shadow-2xl max-w-md w-full relative mx-4">
    <button onclick="document.getElementById('otp-modal').classList.add('hidden')" class="absolute top-4 right-4 text-slate-400 hover:text-slate-900 transition-colors">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
      </svg>
    </button>
    {!! do_shortcode('[ediet_otp_login]') !!}
  </div>
</div>
@endif
