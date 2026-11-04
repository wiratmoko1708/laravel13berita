<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    // Beranda: daftar berita terbaru, dengan pencarian sederhana
    public function home(Request $request)
    {
        $q = $request->string('q')->toString();

        $posts = Post::terbit()
            ->with('category')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('judul', 'like', "%{$q}%")
                        ->orWhere('ringkasan', 'like', "%{$q}%");
                });
            })
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        $categories = Category::orderBy('nama')->get();

        return view('publik.home', compact('posts', 'q', 'categories'));
    }

    // Halaman baca satu berita
    public function show(string $slug)
    {
        $post = Post::terbit()
            ->with(['category', 'user'])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('publik.show', compact('post'));
    }

    // Daftar berita dalam satu kategori
    public function kategori(string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $posts = $category->posts()
            ->terbit()
            ->with('user')
            ->latest('published_at')
            ->paginate(9);

        return view('publik.kategori', compact('category', 'posts'));
    }
}
