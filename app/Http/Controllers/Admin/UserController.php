<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);

        return view('admin.pengguna.index', compact('users'));
    }

    public function create()
    {
        return view('admin.pengguna.create', ['user' => new User]);
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request);

        User::create($data);

        return redirect()->route('admin.pengguna.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.pengguna.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $this->validasi($request, $user);

        // Admin tidak boleh menurunkan peran akunnya sendiri, agar tidak terkunci dari sistem
        if ($user->id === $request->user()->id && $data['role'] !== 'admin') {
            return back()->withInput()
                ->with('error', 'Anda tidak bisa menurunkan peran akun Anda sendiri.');
        }

        // Password hanya diubah jika kolomnya diisi
        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.pengguna.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user)
    {
        // Jangan sampai admin menghapus akunnya sendiri
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        // Jangan sampai tidak ada admin yang tersisa
        if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Admin terakhir tidak bisa dihapus.');
        }

        $user->delete();

        return redirect()->route('admin.pengguna.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }

    // ----- Fungsi pembantu -----

    private function validasi(Request $request, ?User $user = null): array
    {
        // Samakan huruf email supaya tidak ada duplikat beda kapitalisasi
        $request->merge(['email' => Str::lower((string) $request->input('email'))]);

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($user?->id),
            ],
            'role' => ['required', Rule::in(['pembaca', 'penulis', 'admin'])],
            'password' => [
                $user ? 'nullable' : 'required',
                'confirmed',
                Password::min(8),
            ],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah dipakai pengguna lain.',
            'role.required' => 'Peran wajib dipilih.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Ulangi password tidak sama.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);
    }
}
