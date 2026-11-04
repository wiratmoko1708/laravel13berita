@extends('layouts.publik')

@section('title', $post->judul)

@section('content')
    <article class="bg-white rounded-lg shadow-sm p-6 md:p-10 max-w-3xl mx-auto">
        <a href="{{ route('kategori.show', $post->category->slug) }}"
           class="text-sm text-blue-700 uppercase font-semibold">{{ $post->category->nama }}</a>

        <h1 class="text-3xl font-bold mt-2">{{ $post->judul }}</h1>

        <p class="text-sm text-gray-500 mt-2">
            Ditulis oleh {{ $post->user->name }} &middot;
            {{ $post->published_at->translatedFormat('d F Y, H:i') }}
        </p>

        @if ($post->gambar)
            <img src="{{ asset('storage/' . $post->gambar) }}" alt="{{ $post->judul }}"
                 class="w-full rounded my-6">
        @endif

        <p class="italic text-gray-700 border-l-4 border-blue-600 pl-4 mb-6">{{ $post->ringkasan }}</p>

        {{-- nl2br mengubah baris baru menjadi paragraf sederhana --}}
        <div class="leading-relaxed space-y-4">
            {!! nl2br(e($post->isi)) !!}
        </div>

        <a href="{{ route('home') }}" class="inline-block mt-8 text-blue-700 hover:underline">
            &larr; Kembali ke beranda
        </a>
    </article>
@endsection