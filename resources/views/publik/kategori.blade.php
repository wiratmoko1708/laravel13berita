@extends('layouts.publik')

@section('title', 'Kategori ' . $category->nama)

@section('content')
    <h1 class="text-2xl font-bold mb-6">Kategori: {{ $category->nama }}</h1>

    <div class="grid gap-6 md:grid-cols-3">
        @forelse ($posts as $post)
            <article class="bg-white rounded-lg shadow-sm p-4">
                <h2 class="font-bold text-lg">
                    <a href="{{ route('berita.show', $post->slug) }}" class="hover:underline">{{ $post->judul }}</a>
                </h2>
                <p class="text-sm text-gray-600 mt-2">{{ $post->ringkasan }}</p>
                <p class="text-xs text-gray-400 mt-3">
                    {{ $post->user->name }} &middot; {{ $post->published_at->translatedFormat('d F Y') }}
                </p>
            </article>
        @empty
            <p class="text-gray-500">Belum ada berita di kategori ini.</p>
        @endforelse
    </div>

    <div class="mt-8">{{ $posts->links() }}</div>
@endsection