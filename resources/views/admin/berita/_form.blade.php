@csrf

<div class="mb-4">
    <x-input-label for="judul" value="Judul berita" />
    <x-text-input id="judul" name="judul" type="text" class="mt-1 block w-full"
                  :value="old('judul', $post->judul)" required />
    <x-input-error :messages="$errors->get('judul')" class="mt-2" />
</div>

<div class="mb-4">
    <x-input-label for="category_id" value="Kategori" />
    <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-300 rounded-md" required>
        <option value="">-- pilih kategori --</option>
        @foreach ($categories as $kategori)
            <option value="{{ $kategori->id }}" @selected(old('category_id', $post->category_id) == $kategori->id)>
                {{ $kategori->nama }}
            </option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
</div>

<div class="mb-4">
    <x-input-label for="ringkasan" value="Ringkasan (maks. 300 karakter)" />
    <textarea id="ringkasan" name="ringkasan" rows="2" maxlength="300"
              class="mt-1 block w-full border-gray-300 rounded-md" required>{{ old('ringkasan', $post->ringkasan) }}</textarea>
    <x-input-error :messages="$errors->get('ringkasan')" class="mt-2" />
</div>

<div class="mb-4">
    <x-input-label for="isi" value="Isi berita" />
    <textarea id="isi" name="isi" rows="12"
              class="mt-1 block w-full border-gray-300 rounded-md" required>{{ old('isi', $post->isi) }}</textarea>
    <x-input-error :messages="$errors->get('isi')" class="mt-2" />
</div>

<div class="mb-4">
    <x-input-label for="gambar" value="Gambar utama (JPG/PNG/WEBP, maks. 2 MB)" />
    @if ($post->gambar)
        <img src="{{ asset('storage/' . $post->gambar) }}" alt="Gambar saat ini" class="mt-2 mb-2 h-32 rounded">
    @endif
    <input id="gambar" name="gambar" type="file" accept="image/*" class="mt-1 block w-full">
    <x-input-error :messages="$errors->get('gambar')" class="mt-2" />
</div>

<div class="mb-6">
    <x-input-label for="status" value="Status" />
    <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md">
        <option value="draft" @selected(old('status', $post->status) === 'draft')>Draft (belum tampil)</option>
        <option value="terbit" @selected(old('status', $post->status) === 'terbit')>Terbit (tampil di publik)</option>
    </select>
    <x-input-error :messages="$errors->get('status')" class="mt-2" />
</div>

<div class="flex gap-3">
    <x-primary-button>{{ $tombol }}</x-primary-button>
    <a href="{{ route('admin.berita.index') }}" class="px-4 py-2 text-gray-600">Batal</a>
</div>