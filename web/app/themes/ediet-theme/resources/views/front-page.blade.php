@extends('layouts.app')

@section('content')
  @if(!empty($home_blocks))
    @foreach($home_blocks as $block)
      @if(view()->exists("blocks.{$block['acf_fc_layout']}"))
        @include("blocks.{$block['acf_fc_layout']}", ['block' => $block])
      @else
        <div class="py-4 bg-red-50 text-red-500 text-center border border-red-200">
          [Отсутствует шаблон блока: <strong>views/blocks/{{ $block['acf_fc_layout'] }}.blade.php</strong>]
        </div>
      @endif
    @endforeach
  @else
    <section class="py-32 text-center bg-slate-50 min-h-screen flex items-center justify-center">
      <div class="max-w-2xl mx-auto px-4">
        <h1 class="text-5xl font-black text-slate-800 mb-6 tracking-tight">Главная страница пуста</h1>
        <p class="text-xl text-slate-500 mb-8">Чтобы собрать Главную страницу (P-01), перейдите в админ-панель WordPress, создайте страницу с названием "Главная" и добавьте на неё секции в Конструкторе блоков.</p>
        <a href="/wp/wp-admin/post-new.php?post_type=page" class="inline-block px-8 py-4 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition">Перейти в Редактор</a>
      </div>
    </section>
  @endif
@endsection
