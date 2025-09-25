<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Karyawan')</title>
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
                <div
                    class="bg-white rounded-2xl shadow-lg {{ request()->routeIs('pegawai.katalog.index') ? '' : 'p-6' }} mb-6">
                    <!-- Header -->
                    <div class="flex justify-between items-start mb-6">
                        <div class="text-center flex-1">

                            @hasSection('header-subtitle')
                                <div
                                    class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-sm">
                                    @yield('header-subtitle')
                                </div>
                            @endif
                        </div>
                        <!-- Date Display -->
                        <div class="text-right">
                            <p class="text-sm text-gray-500 font-medium" id="current-date">
                                {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}
                            </p>
                            <p class="text-xs text-gray-400" id="current-time">
                                {{ \Carbon\Carbon::now()->locale('id')->isoFormat('HH:mm:ss') }}
                            </p>
                        </div>
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

    <script>
        // Get timezone from Laravel config
        const appTimezone = '{{ config("app.timezone") }}';

        // Real-time clock with seconds using configured timezone
        function updateClock() {
            const now = new Date();

            // Convert to the configured timezone
            const options = {
                timeZone: appTimezone,
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                weekday: 'long',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            };

            const formatter = new Intl.DateTimeFormat('id-ID', options);
            const parts = formatter.formatToParts(now);

            // Extract date and time parts
            let day = '', date = '', month = '', year = '', hours = '', minutes = '', seconds = '';

            parts.forEach(part => {
                switch (part.type) {
                    case 'weekday':
                        day = part.value;
                        break;
                    case 'day':
                        date = part.value;
                        break;
                    case 'month':
                        month = part.value;
                        break;
                    case 'year':
                        year = part.value;
                        break;
                    case 'hour':
                        hours = part.value;
                        break;
                    case 'minute':
                        minutes = part.value;
                        break;
                    case 'second':
                        seconds = part.value;
                        break;
                }
            });

            // Update date display
            document.getElementById('current-date').textContent = `${day}, ${date} ${month} ${year}`;

            // Update time display
            document.getElementById('current-time').textContent = `${hours}:${minutes}:${seconds}`;
        }

        // Update clock immediately and then every second
        updateClock();
        setInterval(updateClock, 1000);
    </script>

    @stack('js')
</body>

</html>