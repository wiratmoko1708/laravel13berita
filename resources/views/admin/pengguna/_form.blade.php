@csrf

<div class="mb-4">
    <x-input-label for="name" value="Nama" />
    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                  :value="old('name', $user->name)" required autofocus />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<div class="mb-4">
    <x-input-label for="email" value="Email" />
    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                  :value="old('email', $user->email)" required />
    <x-input-error :messages="$errors->get('email')" class="mt-2" />
</div>

<div class="mb-4">
    <x-input-label for="role" value="Peran" />
    <select id="role" name="role" class="mt-1 block w-full border-gray-300 rounded-md">
        @foreach (['pembaca', 'penulis', 'admin'] as $peran)
            <option value="{{ $peran }}" @selected(old('role', $user->role ?? 'pembaca') === $peran)>
                {{ ucfirst($peran) }}
            </option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('role')" class="mt-2" />
</div>

<div class="mb-4">
    <x-input-label for="password"
                   :value="$user->exists ? 'Password baru (kosongkan jika tidak diubah)' : 'Password'" />
    <x-text-input id="password" name="password" type="password" class="mt-1 block w-full"
                  autocomplete="new-password" :required="! $user->exists" />
    <x-input-error :messages="$errors->get('password')" class="mt-2" />
</div>

<div class="mb-6">
    <x-input-label for="password_confirmation" value="Ulangi password" />
    <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                  class="mt-1 block w-full" autocomplete="new-password" :required="! $user->exists" />
</div>

<div class="flex gap-3">
    <x-primary-button>{{ $tombol }}</x-primary-button>
    <a href="{{ route('admin.pengguna.index') }}" class="px-4 py-2 text-gray-600">Batal</a>
</div>