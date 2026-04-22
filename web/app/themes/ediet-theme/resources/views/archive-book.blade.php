@extends('layouts.app')

@section('content')
<style>
  .books-archive {
    max-width: 1160px;
    margin: 0 auto;
    padding: 40px 20px 100px;
    font-family: 'Instrument Sans', 'Inter', sans-serif;
  }
  .books-archive-title {
    font-weight: 800;
    font-size: 42px;
    margin-bottom: 32px;
    color: #2A1A10;
    letter-spacing: -1.2px;
  }
  .books-breadcrumb { display: flex; align-items: center; gap: 8px; margin-bottom: 24px; font-size: 11.5px; color: #A89F8B; }
  .books-breadcrumb a { color: #A89F8B; text-decoration: none; }
  .books-breadcrumb .sep { font-size: 12px; }
  .books-breadcrumb .current { font-weight: 600; color: #2A1A10; font-size: 12px; }
  .dtc-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
  @media (max-width: 1024px) { .dtc-grid { grid-template-columns: repeat(3, 1fr); } }
  @media (max-width: 768px)  { .dtc-grid { grid-template-columns: repeat(2, 1fr); } .books-archive-title { font-size: 32px; } }
  @media (max-width: 480px)  { .dtc-grid { grid-template-columns: 1fr; } }
  .pagination-books { display: flex; gap: 8px; align-items: center; margin-top: 40px; }
  .pagination-books .page-numbers { display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50px; font-weight: 600; font-size: 14px; color: #2A1A10; text-decoration: none; background: #fff; border: 1px solid rgba(42,26,16,0.12); transition: all 0.2s; }
  .pagination-books .page-numbers:hover { background: #EBE3D2; }
  .pagination-books .page-numbers.current { background: #2A1A10; color: #F5EFE2; border-color: #2A1A10; pointer-events: none; }
  .pagination-books .page-numbers.prev, .pagination-books .page-numbers.next { width: auto; padding: 0 16px; border-radius: 50px; }
  .pagination-books h2 { display: none; }
</style>

<div style="background:#F5EFE2; min-height:100vh;">
  <div class="books-archive">

    <nav class="books-breadcrumb">
      <a href="/">Главная</a>
      <span class="sep">/</span>
      <span class="current">Книги</span>
    </nav>

    <h1 class="books-archive-title">Книги</h1>

    @if(count($books) > 0)
      <div class="dtc-grid">
        @foreach($books as $cs)
          @include('partials.dtc-card', ['item' => $cs])
        @endforeach
      </div>

      <div class="mt-16 flex justify-center">
        {!! get_the_posts_pagination([
          'prev_text' => '&larr; Назад',
          'next_text' => 'Вперёд &rarr;',
          'class'     => 'pagination-books',
        ]) !!}
      </div>
    @else
      <div style="padding:48px;text-align:center;border:2px dashed rgba(42,26,16,0.15);border-radius:20px;color:#A89F8B;font-size:16px;">
        Пока нет доступных книг.
      </div>
    @endif

  </div>
</div>
@endsection
