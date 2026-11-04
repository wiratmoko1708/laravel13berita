<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Admin melihat semua berita, penulis hanya miliknya sendiri
        $posts = Post::with(['category', 'user'])
            ->when(! $user->isAdmin(), fn ($q) => $q->where('user_id', $user->id))
            ->latest()
            ->paginate(10);

        return view('admin.berita.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.berita.create', [
            'post' => new Post,
            'categories' => Category::orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request);

        $data['user_id'] = $request->user()->id;
        $data['slug'] = $this->buatSlug($data['judul']);
        $data['published_at'] = $data['status'] === 'terbit' ? now() : null;

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('berita', 'public');
        }

        Post::create($data);

        return redirect()->route('admin.berita.index')
            ->with('success', 'Berita berhasil disimpan.');
    }

    public function edit(Request $request, Post $berita)
    {
        $this->cekAkses($request, $berita);

        return view('admin.berita.edit', [
            'post' => $berita,
            'categories' => Category::orderBy('nama')->get(),
        ]);
    }

    public function update(Request $request, Post $berita)
    {
        $this->cekAkses($request, $berita);

        $data = $this->validasi($request);

        // Slug hanya berubah jika judul berubah
        if ($berita->judul !== $data['judul']) {
            $data['slug'] = $this->buatSlug($data['judul'], $berita->id);
        }

        // Tanggal terbit dicatat saat pertama kali berita diterbitkan
        if ($data['status'] === 'terbit' && $berita->published_at === null) {
            $data['published_at'] = now();
        }

        if ($data['status'] === 'draft') {
            $data['published_at'] = null;
        }

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama agar tidak menumpuk
            if ($berita->gambar) {
                Storage::disk('public')->delete($berita->gambar);
            }

            $data['gambar'] = $request->file('gambar')->store('berita', 'public');
        }

        $berita->update($data);

        return redirect()->route('admin.berita.index')
            ->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(Request $request, Post $berita)
    {
        $this->cekAkses($request, $berita);

        if ($berita->gambar) {
            Storage::disk('public')->delete($berita->gambar);
        }

        $berita->delete();

        return redirect()->route('admin.berita.index')
            ->with('success', 'Berita berhasil dihapus.');
    }

    // ----- Fungsi pembantu -----

    private function validasi(Request $request): array
    {
        return $request->validate([
            'judul' => ['required', 'string', 'max:200'],
            'category_id' => ['required', 'exists:categories,id'],
            'ringkasan' => ['required', 'string', 'max:300'],
            'isi' => ['required', 'string'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', Rule::in(['draft', 'terbit'])],
        ]);
    }

    private function buatSlug(string $judul, ?int $abaikanId = null): string
    {
        $dasar = Str::slug($judul);
        $slug = $dasar;
        $nomor = 1;

        while (
            Post::where('slug', $slug)
                ->when($abaikanId, fn ($q) => $q->where('id', '!=', $abaikanId))
                ->exists()
        ) {
            $slug = $dasar.'-'.$nomor++;
        }

        return $slug;
    }

    private function cekAkses(Request $request, Post $post): void
    {
        $user = $request->user();

        // Hanya admin, atau penulis pemilik berita, yang boleh mengubah
        abort_unless($user->isAdmin() || $post->user_id === $user->id, 403);
    }
}
