<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('posts')->orderBy('nama')->paginate(10);

        return view('admin.kategori.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100', 'unique:categories,nama'],
        ]);

        Category::create($data);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $kategori)
    {
        return view('admin.kategori.edit', ['category' => $kategori]);
    }

    public function update(Request $request, Category $kategori)
    {
        $data = $request->validate([
            'nama' => [
                'required', 'string', 'max:100',
                Rule::unique('categories', 'nama')->ignore($kategori->id),
            ],
        ]);

        $kategori->update($data);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $kategori)
    {
        // Kategori yang masih dipakai berita tidak boleh dihapus
        if ($kategori->posts()->exists()) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena masih dipakai berita.');
        }

        $kategori->delete();

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
