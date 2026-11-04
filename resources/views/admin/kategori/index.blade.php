<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Kelola Kategori</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
            @endif

            <a href="{{ route('admin.kategori.create') }}"
               class="inline-block mb-4 px-4 py-2 bg-blue-600 text-white rounded">+ Tambah Kategori</a>

            <div class="bg-white shadow-sm rounded-lg p-6 overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="border-b">
                        <tr>
                            <th class="py-2">Nama</th>
                            <th class="py-2">Slug</th>
                            <th class="py-2">Jumlah berita</th>
                            <th class="py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr class="border-b">
                                <td class="py-2">{{ $category->nama }}</td>
                                <td class="py-2 text-gray-500">{{ $category->slug }}</td>
                                <td class="py-2">{{ $category->posts_count }}</td>
                                <td class="py-2 flex gap-3">
                                    <a href="{{ route('admin.kategori.edit', $category) }}"
                                       class="text-blue-600">Ubah</a>
                                    <form method="POST" action="{{ route('admin.kategori.destroy', $category) }}"
                                          onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600" type="submit">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-4 text-gray-500">Belum ada kategori.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $categories->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>