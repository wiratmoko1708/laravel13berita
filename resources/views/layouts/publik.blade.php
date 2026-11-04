<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Portal Berita Sekolah')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen flex flex-col">
    <header class="bg-blue-900 text-white">
        <div class="max-w-6xl mx-auto px-4 py-4 flex flex-wrap items-center justify-between gap-3">
            <a href="{{ route('home') }}" class="text-xl font-bold">📰 Portal Berita Sekolah</a>

            <nav class="flex gap-4 text-sm">
                <a href="{{ route('home') }}" class="hover:underline">Beranda</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="hover:underline">Login</a>
                    <a href="{{ route('register') }}" class="hover:underline">Daftar</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-8 flex-1 w-full">
        @yield('content')
    </main>

    <footer class="bg-gray-200 text-center text-sm text-gray-600 py-4">
        &copy; {{ date('Y') }} Portal Berita Sekolah &middot; SMA Kalam Kudus Jayapura
    </footer>
</body>
</html>