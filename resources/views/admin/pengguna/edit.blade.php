<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ubah Pengguna: {{ $user->name }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8 bg-white p-6 shadow-sm rounded-lg">
            @if (session('error'))
                <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.pengguna.update', $user) }}">
                @method('PUT')
                @include('admin.pengguna._form', ['tombol' => 'Simpan Perubahan'])
            </form>
        </div>
    </div>
</x-app-layout>