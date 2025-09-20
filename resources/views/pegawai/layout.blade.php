<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pegawai')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @stack('styles')
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'spin-slow': 'spin 3s linear infinite',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50 min-h-screen font-sans">
    <div class="container mx-auto px-4 py-6 pb-24">
        <div class="flex justify-center">
            <div class="w-full max-w-2xl">
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <!-- Header -->
                    <div class="text-center mb-6">
                        <h2 class="font-bold text-blue-600 mb-2 text-2xl">
                            <i class="fas fa-@yield('header-icon', 'user') mr-2"></i>@yield('header-title', 'Pegawai')
                        </h2>
                        @if(Auth::check())
                            <p class="text-gray-600 mb-3">{{ Auth::user()->name }}</p>
                        @endif
                        @hasSection('header-subtitle')
                            <div class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-sm">
                                @yield('header-subtitle')
                            </div>
                        @endif
                    </div>

                    <!-- Page Content -->
                    @yield('content')
                </div>
            </div>
        </div>

        <!-- Navigation Bar -->
        <nav
            class="fixed bottom-0 left-0 right-0 p-4 bg-white border-t border-gray-200 flex justify-around items-center z-50">
            <a href="{{ route('pegawai.beranda') }}" id="home-nav"
                class="nav-item flex flex-col items-center cursor-pointer {{ request()->routeIs('pegawai.beranda') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6 nav-icon {{ request()->routeIs('pegawai.beranda') ? 'text-indigo-600' : 'text-gray-400' }} transition-colors"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-10l-2-2m2 2v10a1 1 0 01-1 1h-3m-6 0V9a1 1 0 011-1h2a1 1 0 011 1v3" />
                </svg>
                <span
                    class="nav-text text-xs mt-1 {{ request()->routeIs('pegawai.beranda') ? 'text-indigo-600 font-semibold' : 'text-gray-400 font-semibold' }} transition-colors">Beranda</span>
            </a>
            <a href="{{ route('pegawai.index') }}" id="transactions-nav"
                class="nav-item flex flex-col items-center cursor-pointer {{ request()->routeIs('pegawai.index') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6 nav-icon {{ request()->routeIs('pegawai.index') ? 'text-indigo-600' : 'text-gray-400' }} transition-colors"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span
                    class="nav-text text-xs mt-1 {{ request()->routeIs('pegawai.index') ? 'text-indigo-600 font-semibold' : 'text-gray-400 font-semibold' }} transition-colors">Absensi</span>
            </a>
            <a href="{{ route('pegawai.riwayat') }}" id="history-nav"
                class="nav-item flex flex-col items-center cursor-pointer {{ request()->routeIs('pegawai.riwayat') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6 nav-icon {{ request()->routeIs('pegawai.riwayat') ? 'text-indigo-600' : 'text-gray-400' }} transition-colors"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.592 1M12 8a1.5 1.5 0 011.5 1.5c0 .193-.038.381-.11.558M12 8a1.5 1.5 0 00-1.5 1.5c0 .193-.038.381-.11.558" />
                </svg>
                <span
                    class="nav-text text-xs mt-1 {{ request()->routeIs('pegawai.riwayat') ? 'text-indigo-600 font-semibold' : 'text-gray-400 font-semibold' }} transition-colors">Histori</span>
            </a>
            <a href="{{ route('pegawai.kasbon') }}" id="kasbon-nav"
                class="nav-item flex flex-col items-center cursor-pointer {{ request()->routeIs('pegawai.kasbon.*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6 nav-icon {{ request()->routeIs('pegawai.kasbon.*') ? 'text-indigo-600' : 'text-gray-400' }} transition-colors"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                <span
                    class="nav-text text-xs mt-1 {{ request()->routeIs('pegawai.kasbon.*') ? 'text-indigo-600 font-semibold' : 'text-gray-400 font-semibold' }} transition-colors">Kasbon</span>
            </a>
            <a href="{{ route('pegawai.profil') }}" id="profile-nav"
                class="nav-item flex flex-col items-center cursor-pointer {{ request()->routeIs('pegawai.profil*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6 nav-icon {{ request()->routeIs('pegawai.profil*') ? 'text-indigo-600' : 'text-gray-400' }} transition-colors"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span
                    class="nav-text text-xs mt-1 {{ request()->routeIs('pegawai.profil*') ? 'text-indigo-600 font-semibold' : 'text-gray-400 font-semibold' }} transition-colors">Profil</span>
            </a>
        </nav>
    </div>

    @stack('scripts')
</body>

</html>