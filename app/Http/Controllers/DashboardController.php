<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $jumlahBerita = Post::query()
            ->when(! $user->isAdmin(), fn ($q) => $q->where('user_id', $user->id))
            ->count();

        $jumlahTerbit = Post::query()
            ->when(! $user->isAdmin(), fn ($q) => $q->where('user_id', $user->id))
            ->where('status', 'terbit')
            ->count();

        // Statistik ini hanya untuk admin
        $jumlahKategori = $user->isAdmin() ? Category::count() : null;
        $jumlahPengguna = $user->isAdmin() ? User::count() : null;

        return view('dashboard', compact(
            'jumlahBerita', 'jumlahTerbit', 'jumlahKategori', 'jumlahPengguna'
        ));
    }
}
