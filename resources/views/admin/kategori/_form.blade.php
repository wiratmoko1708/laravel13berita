@csrf

<div class="mb-4">
    <x-input-label for="nama" value="Nama kategori" />
    <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full"
                  :value="old('nama', $category->nama ?? '')" required />
    <x-input-error :messages="$errors->get('nama')" class="mt-2" />
</div>

<div class="flex gap-3">
    <x-primary-button>{{ $tombol }}</x-primary-button>
    <a href="{{ route('admin.kategori.index') }}" class="px-4 py-2 text-gray-600">Batal</a>
</div>