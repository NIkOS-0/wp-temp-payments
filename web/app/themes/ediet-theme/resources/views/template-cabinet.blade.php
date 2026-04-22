{{--
  Template Name: Личный кабинет
--}}

@extends('layouts.app')

@section('content')

@php
  // Redirect guests to auth
  if (!is_user_logged_in()) {
    wp_safe_redirect(add_query_arg('redirect', home_url('/cabinet'), home_url('/auth')));
    exit;
  }

  $user      = wp_get_current_user();
  $tab       = sanitize_key($_GET['tab'] ?? 'dashboard');
  $avatar    = get_avatar_url($user->ID, ['size' => 96]);
  $full_name = trim($user->first_name . ' ' . $user->last_name) ?: $user->display_name;

  // Orders: ediet_order CPT matched by user email
  $orders_query = new WP_Query([
    'post_type'      => 'ediet_order',
    'posts_per_page' => -1,
    'meta_query'     => [[
      'key'     => '_order_email',
      'value'   => $user->user_email,
      'compare' => '=',
    ]],
    'orderby' => 'date',
    'order'   => 'DESC',
  ]);
  $orders = $orders_query->posts;

  // Also: personal_offer posts with status 'paid' by this user
  $offers_query = new WP_Query([
    'post_type'      => 'personal_offer',
    'post_status'    => 'paid',
    'posts_per_page' => -1,
    'author'         => $user->ID,
    'orderby'        => 'date',
    'order'          => 'DESC',
  ]);
  $paid_offers = $offers_query->posts;

  // Favorites
  $fav_ids   = get_user_meta($user->ID, '_ediet_favorites', true);
  $fav_ids   = is_array($fav_ids) ? array_map('intval', $fav_ids) : [];
  $fav_posts = !empty($fav_ids) ? get_posts([
      'post__in'       => $fav_ids,
      'post_type'      => ['post', 'page', 'book', 'doctor', 'clinic', 'laboratory', 'course', 'disease', 'consultation'],
      'posts_per_page' => -1,
      'post_status'    => ['publish', 'private', 'inherit'] // Include statuses typical for saved CPTs
  ]) : [];
@endphp

<div class="min-h-[calc(100vh-80px)] bg-[--app-bg]">
  <div class="container-wide py-8 md:py-12">

    {{-- Profile header --}}
    <div class="flex flex-col sm:flex-row sm:items-center gap-5 mb-8">
      <img src="{{ $avatar }}" alt="{{ $full_name }}"
           class="w-16 h-16 rounded-full object-cover border-2 border-[--border] shadow-sm">
      <div class="flex-1">
        <h1 class="text-xl font-bold text-bark-900 tracking-tight">{{ $full_name ?: 'Мой кабинет' }}</h1>
        <p class="text-sm text-bark-500">{{ $user->user_email }}</p>
      </div>
      <div class="flex gap-3">
        <button id="cabinet-logout-btn"
                class="btn-secondary btn-sm text-sm">
          Выйти
        </button>
      </div>
    </div>

    {{-- Tabs --}}
    <div class="flex gap-1 mb-8 bg-cream-200 p-1 rounded-xl w-fit overflow-x-auto">
      @foreach([
        'dashboard' => 'Кабинет',
        'orders'    => 'Покупки',
        'favorites' => 'Избранное',
        'settings'  => 'Настройки',
      ] as $slug => $label)
        <a href="?tab={{ $slug }}"
           class="px-5 py-2.5 rounded-lg text-sm font-semibold whitespace-nowrap transition-all
                  {{ $tab === $slug
                      ? 'bg-surface text-bark-900 shadow-sm'
                      : 'text-bark-500 hover:text-bark-800' }}">
          {{ $label }}
        </a>
      @endforeach
    </div>

    {{-- ── DASHBOARD ── --}}
    @if($tab === 'dashboard')
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="card p-5">
          <p class="text-overline mb-2">Покупок</p>
          <p class="text-3xl font-bold text-bark-900">{{ count($paid_offers) }}</p>
        </div>
        <div class="card p-5">
          <p class="text-overline mb-2">Избранных</p>
          <p class="text-3xl font-bold text-bark-900">{{ count($fav_ids) }}</p>
        </div>
        <div class="card p-5">
          <p class="text-overline mb-2">Аккаунт</p>
          <p class="text-sm font-semibold text-terra-600 mt-1">Активен</p>
        </div>
      </div>

      @if(count($paid_offers))
        <div class="card">
          <div class="px-6 py-4 border-b border-[--border]">
            <h2 class="font-semibold text-bark-900">Последние покупки</h2>
          </div>
          <div class="divide-y divide-[--border]">
            @foreach(array_slice($paid_offers, 0, 3) as $offer)
              @php
                $product_id = get_post_meta($offer->ID, '_offer_product_id', true);
                $product    = $product_id ? get_post($product_id) : null;
                $price      = get_post_meta($offer->ID, '_offer_selected_price', true);
              @endphp
              <div class="px-6 py-4 flex items-center justify-between gap-4">
                <div>
                  <p class="font-medium text-bark-900 text-sm">
                    {{ $product ? $product->post_title : 'Продукт #' . $offer->ID }}
                  </p>
                  <p class="text-xs text-bark-400 mt-0.5">{{ date('d.m.Y', strtotime($offer->post_date)) }}</p>
                </div>
                <div class="flex items-center gap-3">
                  @if($price)
                    <span class="text-sm font-bold text-bark-900">{{ number_format($price, 0, '.', ' ') }} ₽</span>
                  @endif
                  <a href="{{ get_permalink($offer->ID) }}"
                     class="btn-soft btn-xs text-xs">
                    Скачать
                  </a>
                </div>
              </div>
            @endforeach
          </div>
          @if(count($paid_offers) > 3)
            <div class="px-6 py-4 border-t border-[--border]">
              <a href="?tab=orders" class="text-sm text-terra-600 font-medium hover:text-terra-700">
                Все покупки ({{ count($paid_offers) }}) →
              </a>
            </div>
          @endif
        </div>
      @else
        <div class="card px-6 py-12 text-center">
          <div class="w-14 h-14 rounded-full bg-cream-200 flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7 text-bark-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
          </div>
          <p class="font-semibold text-bark-900 mb-1">Покупок пока нет</p>
          <p class="text-sm text-bark-500 mb-5">Найдите подходящий продукт в каталоге</p>
          <a href="{{ home_url('/products') }}" class="btn-primary btn-sm inline-flex">
            В каталог
          </a>
        </div>
      @endif
    @endif

    {{-- ── ORDERS ── --}}
    @if($tab === 'orders')
      <div class="card overflow-hidden">
        <div class="px-6 py-4 border-b border-[--border]">
          <h2 class="font-semibold text-bark-900">История покупок</h2>
        </div>

        @if(count($paid_offers))
          <div class="divide-y divide-[--border]">
            @foreach($paid_offers as $offer)
              @php
                $product_id = get_post_meta($offer->ID, '_offer_product_id', true);
                $product    = $product_id ? get_post($product_id) : null;
                $price      = get_post_meta($offer->ID, '_offer_selected_price', true);
                $expires    = get_post_meta($offer->ID, '_expiry_timestamp', true);
              @endphp
              <div class="px-6 py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-start gap-4">
                  @if($product && has_post_thumbnail($product->ID))
                    <img src="{{ get_the_post_thumbnail_url($product->ID, 'thumbnail') }}"
                         alt="{{ $product->post_title }}"
                         class="w-14 h-14 rounded-lg object-cover shrink-0 border border-[--border]">
                  @else
                    <div class="w-14 h-14 rounded-lg bg-cream-200 shrink-0 flex items-center justify-center">
                      <svg class="w-6 h-6 text-bark-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                      </svg>
                    </div>
                  @endif
                  <div>
                    <p class="font-semibold text-bark-900 text-sm leading-snug">
                      {{ $product ? $product->post_title : 'Продукт #' . $offer->ID }}
                    </p>
                    <p class="text-xs text-bark-400 mt-1">{{ date('d.m.Y · H:i', strtotime($offer->post_date)) }}</p>
                    @if($expires && time() < $expires)
                      <span class="badge-green mt-1.5 inline-flex">Доступен</span>
                    @elseif($expires)
                      <span class="badge-cream mt-1.5 inline-flex">Срок истёк</span>
                    @else
                      <span class="badge-terra mt-1.5 inline-flex">Оплачено</span>
                    @endif
                  </div>
                </div>
                <div class="flex items-center gap-3 sm:shrink-0">
                  @if($price)
                    <span class="font-bold text-bark-900">{{ number_format($price, 0, '.', ' ') }} ₽</span>
                  @endif
                  <a href="{{ get_permalink($offer->ID) }}" class="btn-dark btn-sm text-sm">
                    Скачать
                  </a>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <div class="px-6 py-16 text-center">
            <p class="text-bark-500 text-sm">Покупок пока нет</p>
          </div>
        @endif
      </div>
    @endif

    {{-- ── FAVORITES ── --}}
    @if($tab === 'favorites')
      <style>
        .dtc-fav-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        @media(max-width: 1024px) { .dtc-fav-grid { grid-template-columns: repeat(2, 1fr); } }
        @media(max-width: 640px)  { .dtc-fav-grid { grid-template-columns: 1fr; } }
      </style>

      @if(count($fav_posts))
        <div class="dtc-fav-grid">
          @foreach($fav_posts as $fp)
            @php
              $fav_img   = get_the_post_thumbnail_url($fp->ID, 'large') ?: '';
              $fav_type  = match($fp->post_type) {
                'course'       => 'Курс',
                'doctor'       => 'Врач',
                'clinic'       => 'Клиника',
                'consultation' => 'Консультация',
                default        => 'МПО',
              };
              if ($fp->post_type === 'doctor') {
                $fav_features = [['text' => 'Специалист']];
                $spec = get_field('doctor_specialty', $fp->ID);
                if ($spec) $fav_features[] = ['text' => $spec];
                $fav_price     = 'От 5 000 ₽';
                $fav_price_old = '';
                $fav_delivery  = 'Онлайн консультация';
              } elseif ($fp->post_type === 'clinic') {
                $fav_features  = [['text' => 'Медицинский центр'], ['text' => 'Лаборатория']];
                $fav_price     = 'Подробности';
                $fav_price_old = '';
                $fav_delivery  = '';
              } else {
                $fav_features  = get_field($fp->post_type === 'book' ? 'book_features' : 'course_features', $fp->ID) ?: [];
                $fav_price     = get_field('price', $fp->ID) ?: '';
                $fav_price_old = get_field('book_price_old', $fp->ID) ?: '';
                $fav_delivery  = get_field($fp->post_type === 'book' ? 'book_delivery_note' : 'course_delivery_note', $fp->ID) ?: '';
              }
              $fav_item = [
                'id'         => $fp->ID,
                'url'        => get_permalink($fp->ID),
                'title'      => $fp->post_title,
                'image'      => $fav_img,
                'type_label' => $fav_type,
                'badge'      => get_field('ps_card_badge', $fp->ID) ?: '',
                'features'   => array_slice($fav_features, 0, 3),
                'price'      => $fav_price,
                'price_old'  => $fav_price_old,
                'delivery'   => $fav_delivery,
              ];
            @endphp
            @include('partials.dtc-card', ['item' => $fav_item])
          @endforeach
        </div>
      @else
        <div class="card px-6 py-16 text-center">
          <p class="font-semibold text-bark-900 mb-1">Избранного пока нет</p>
          <p class="text-sm text-bark-500">Добавляйте понравившиеся товары — они сохранятся здесь</p>
        </div>
      @endif
    @endif

    {{-- ── SETTINGS ── --}}
    @if($tab === 'settings')
      <form id="cabinet-settings-form" class="card max-w-xl p-8">
        <h2 class="font-semibold text-bark-900 mb-6">Личные данные</h2>

        <div class="flex gap-4 mb-6">
          <div class="input-group flex-1">
            <label class="label" for="s-first-name">Имя</label>
            <input id="s-first-name" name="first_name" type="text"
                   class="input" value="{{ esc_attr($user->first_name) }}" placeholder="Имя">
          </div>
          <div class="input-group flex-1">
            <label class="label" for="s-last-name">Фамилия</label>
            <input id="s-last-name" name="last_name" type="text"
                   class="input" value="{{ esc_attr($user->last_name) }}" placeholder="Фамилия">
          </div>
        </div>

        <div class="input-group mb-6">
          <label class="label">Email</label>
          <input type="email" class="input opacity-60 cursor-not-allowed"
                 value="{{ esc_attr($user->user_email) }}" readonly>
          <p class="text-xs text-bark-400 mt-1.5">Email изменить нельзя — он является логином</p>
        </div>

        <button type="submit" class="btn-dark btn-sm">
          Сохранить изменения
        </button>
        <p id="settings-msg" class="text-sm mt-3 hidden"></p>
      </form>

      <script>
      document.getElementById('cabinet-settings-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const fd = new FormData();
        fd.append('action', 'ediet_update_profile');
        fd.append('nonce', '{{ wp_create_nonce('ediet_auth_nonce') }}');
        fd.append('first_name', document.getElementById('s-first-name').value);
        fd.append('last_name',  document.getElementById('s-last-name').value);
        fetch('{{ admin_url('admin-ajax.php') }}', { method: 'POST', body: fd })
          .then(r => r.json())
          .then(res => {
            const msg = document.getElementById('settings-msg');
            msg.textContent = res.success ? 'Сохранено.' : (res.data?.message || 'Ошибка.');
            msg.className = 'text-sm mt-3 ' + (res.success ? 'text-green-700' : 'text-red-600');
            msg.classList.remove('hidden');
          });
      });
      </script>
    @endif

  </div>
</div>

<script>
// Logout
document.getElementById('cabinet-logout-btn')?.addEventListener('click', function() {
  const fd = new FormData();
  fd.append('action', 'ediet_logout');
  fd.append('nonce', '{{ wp_create_nonce('ediet_auth_nonce') }}');
  fetch('{{ admin_url('admin-ajax.php') }}', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(res => { window.location.href = res.data?.redirect || '/'; });
});
</script>

@endsection
