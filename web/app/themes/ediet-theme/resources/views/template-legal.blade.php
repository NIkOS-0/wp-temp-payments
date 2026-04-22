{{--
  Template Name: Юридическая страница
--}}
@extends('layouts.app')
@section('content')
<div class="min-h-[calc(100vh-80px)] bg-[--app-bg]">
  <div class="container-narrow py-16 md:py-24">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs text-bark-400 mb-8">
      <a href="{{ home_url('/') }}" class="hover:text-bark-700 transition-colors">Главная</a>
      <span class="text-bark-300">›</span>
      <span class="text-bark-600 font-medium">{{ get_the_title() }}</span>
    </nav>

    {{-- Title --}}
    <h1 class="heading-display mb-3 text-balance">{{ get_the_title() }}</h1>

    @if(get_the_modified_date())
      <p class="text-caption mb-10">
        Последнее обновление: {{ get_the_modified_date('d.m.Y') }}
      </p>
    @endif

    <div class="divider mb-10"></div>

    {{-- Content --}}
    <div class="prose prose-slate max-w-none
                prose-headings:font-bold prose-headings:text-bark-900 prose-headings:tracking-tight
                prose-p:text-bark-600 prose-p:leading-relaxed
                prose-a:text-terra-600 prose-a:no-underline hover:prose-a:underline
                prose-li:text-bark-600">
      {!! the_content() !!}
    </div>

  </div>
</div>
@endsection
