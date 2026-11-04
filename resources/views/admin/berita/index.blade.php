<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Daftar Berita</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif

            <a href="{{ route('admin.berita.create') }}"
               class="inline-block mb-4 px-4 py-2 bg-blue-600 text-white rounded">+ Tulis Berita</a>

            <div class="bg-white shadow-sm rounded-lg p-6 overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="border-b">
                        <tr>
                            <th class="py-2">Judul</th>
                            <th class="py-2">Kategori</th>
                            <th class="py-2">Penulis</th>
                            <th class="py-2">Status</th>
                            <th class="py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($posts as $post)
                            <tr class="border-b">
                                <td class="py-2">{{ $post->judul }}</td>
                                <td class="py-2">{{ $post->category->nama }}</td>
                                <td class="py-2">{{ $post->user->name }}</td>
                                <td class="py-2">
                                    <span class="px-2 py-1 rounded text-sm {{ $post->status === 'terbit' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $post->status }}
                                    </span>
                                </td>
                                <td class="py-2 flex gap-3">
                                    <a href="{{ route('admin.berita.edit', $post) }}" class="text-blue-600">Ubah</a>
                                    <form method="POST" action="{{ route('admin.berita.destroy', $post) }}"
                                          onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600" type="submit">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-4 text-gray-500">Belum ada berita.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $posts->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>