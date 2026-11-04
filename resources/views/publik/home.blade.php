@extends('layouts.publik')

@section('title', 'Beranda')

@section('content')
    <form method="GET" action="{{ route('home') }}" class="flex gap-2 mb-6">
        <input type="text" name="q" value="{{ $q }}" placeholder="Cari berita..."
               class="flex-1 border-gray-300 rounded-md">
        <button class="px-4 py-2 bg-blue-600 text-white rounded-md">Cari</button>
    </form>

    <div class="flex flex-wrap gap-2 mb-8">
        @foreach ($categories as $kategori)
            <a href="{{ route('kategori.show', $kategori->slug) }}"
               class="px-3 py-1 bg-white border rounded-full text-sm hover:bg-blue-50">
                {{ $kategori->nama }}
            </a>
        @endforeach
    </div>

    @if ($posts->isEmpty())
        <p class="text-gray-500">Belum ada berita yang cocok.</p>
    @endif

    <div class="grid gap-6 md:grid-cols-3">
        @foreach ($posts as $post)
            <article class="bg-white rounded-lg shadow-sm overflow-hidden flex flex-col">
                @if ($post->gambar)
                    <img src="{{ asset('storage/' . $post->gambar) }}" alt="{{ $post->judul }}"
                         class="h-40 w-full object-cover">
                @endif
                <div class="p-4 flex flex-col flex-1">
                    <span class="text-xs text-blue-700 uppercase font-semibold">{{ $post->category->nama }}</span>
                    <h2 class="font-bold text-lg mt-1">
                        <a href="{{ route('berita.show', $post->slug) }}" class="hover:underline">{{ $post->judul }}</a>
                    </h2>
                    <p class="text-sm text-gray-600 mt-2 flex-1">{{ $post->ringkasan }}</p>
                    <p class="text-xs text-gray-400 mt-3">{{ $post->published_at->translatedFormat('d F Y') }}</p>
                </div>
            </article>
        @endforeach
    </div>

    <div class="mt-8">{{ $posts->links() }}</div>
@endsection