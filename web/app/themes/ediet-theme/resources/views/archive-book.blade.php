@extends('layouts.app')

@section('content')
@php
  global $wp_query;
  $archiveHasMore = $wp_query->max_num_pages > 1;
  $archiveTotal   = (int) $wp_query->found_posts;
@endphp

<div style="background:#F5EFE2; min-height:100vh;">
  <div class="archive-page">

    <nav class="archive-breadcrumb">
      <a href="/">Главная</a>
      <span class="sep">/</span>
      <a href="/products">Продукты</a>
      <span class="sep">/</span>
      <span class="current">Книги</span>
    </nav>

    <h1 class="archive-title">Книги</h1>

    @if(count($books) > 0)

      @include('partials.archive-controls', [
        'archivePostType' => 'book',
        'archiveHasMore'  => $archiveHasMore,
        'archiveTotal'    => $archiveTotal,
      ])

      <div class="dtc-grid" id="archive-grid">
        @foreach($books as $item)
          @include('partials.dtc-card')
        @endforeach
      </div>

    @else
      <div style="padding:48px;text-align:center;border:2px dashed rgba(42,26,16,0.15);border-radius:20px;color:#A89F8B;font-size:16px;">
        Пока нет доступных книг.
      </div>
    @endif

  </div>
</div>
@endsection
