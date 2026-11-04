<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Dashboard</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <p class="mb-6 text-gray-700">
                Halo, <strong>{{ auth()->user()->name }}</strong>.
                Peran Anda: <span class="capitalize font-semibold">{{ auth()->user()->role }}</span>.
            </p>

            <div class="grid gap-4 md:grid-cols-4">
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Total berita</p>
                    <p class="text-3xl font-bold">{{ $jumlahBerita }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Berita terbit</p>
                    <p class="text-3xl font-bold">{{ $jumlahTerbit }}</p>
                </div>
                @if (auth()->user()->isAdmin())
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <p class="text-sm text-gray-500">Kategori</p>
                        <p class="text-3xl font-bold">{{ $jumlahKategori }}</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <p class="text-sm text-gray-500">Pengguna</p>
                        <p class="text-3xl font-bold">{{ $jumlahPengguna }}</p>
                    </div>
                @endif
            </div>

            <div class="mt-8 flex flex-wrap gap-3">
                @if (auth()->user()->role !== 'pembaca')
                    <a href="{{ route('admin.berita.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">+ Tulis Berita</a>
                    <a href="{{ route('admin.berita.index') }}" class="px-4 py-2 bg-white border rounded">Kelola Berita</a>
                @endif

                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.kategori.index') }}" class="px-4 py-2 bg-white border rounded">Kelola Kategori</a>
                    <a href="{{ route('admin.pengguna.index') }}" class="px-4 py-2 bg-white border rounded">Kelola Pengguna</a>
                @endif

                <a href="{{ route('home') }}" class="px-4 py-2 text-blue-700">Lihat situs publik &rarr;</a>
            </div>
        </div>
    </div>
</x-app-layout>