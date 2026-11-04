<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Pengguna</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
            @endif

            <a href="{{ route('admin.pengguna.create') }}"
               class="inline-block mb-4 px-4 py-2 bg-blue-600 text-white rounded">+ Tambah Pengguna</a>

            <div class="bg-white shadow-sm rounded-lg p-6 overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="border-b">
                        <tr>
                            <th class="py-2">Nama</th>
                            <th class="py-2">Email</th>
                            <th class="py-2">Peran</th>
                            <th class="py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr class="border-b">
                                <td class="py-2">
                                    {{ $user->name }}
                                    @if ($user->id === auth()->id())
                                        <span class="text-xs text-gray-400">(Anda)</span>
                                    @endif
                                </td>
                                <td class="py-2">{{ $user->email }}</td>
                                <td class="py-2">
                                    <span class="px-2 py-1 rounded text-sm
                                        {{ $user->isAdmin() ? 'bg-purple-100 text-purple-800'
                                            : ($user->isPenulis() ? 'bg-blue-100 text-blue-800'
                                            : 'bg-gray-100 text-gray-700') }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="py-2 flex gap-3">
                                    <a href="{{ route('admin.pengguna.edit', $user) }}"
                                       class="text-blue-600 hover:underline">Ubah</a>

                                    @if ($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.pengguna.destroy', $user) }}"
                                              onsubmit="return confirm('Yakin ingin menghapus pengguna {{ $user->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600" type="submit">Hapus</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-4 text-gray-500">Belum ada pengguna.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $users->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>