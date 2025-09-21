@extends('pegawai.layout')

@section('title', 'Kasbon')
@section('header-title', 'Kasbon')
@section('header-icon', 'credit-card')

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Kasbon</h1>
                <p class="text-gray-600">Kelola pengajuan kasbon Anda</p>
            </div>
            <a href="{{ route('pegawai.kasbon.create') }}"
                class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors flex items-center space-x-2">
                <i class="fas fa-plus"></i>
                <span>Ajukan Kasbon</span>
            </a>
        </div>

        <!-- Saldo Kasbon Card -->
        <div class="bg-gradient-to-br from-purple-600 to-purple-700 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-sm font-medium opacity-90 mb-1">Saldo Kasbon Tersedia</h2>
                    <p class="text-3xl font-bold">Rp
                        {{ number_format($user->kasbon - $user->kasbon_terpakai, 0, ',', '.') }}
                    </p>
                </div>
                <div class="text-right">
                    <i class="fas fa-wallet text-4xl opacity-50"></i>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
            <form method="GET" action="{{ route('pegawai.kasbon') }}" id="filterForm">
                <!-- Filter Tabs - Scrollable -->
                <div class="relative mb-4">
                    <div class="flex space-x-1 bg-gray-100 p-1 rounded-lg overflow-x-auto scrollbar-hide touch-pan-x"
                        id="filterTabs">
                        <button type="button" onclick="filterKasbon('all')"
                            class="filter-tab px-4 py-2 rounded-md text-sm font-medium transition-colors active whitespace-nowrap flex-shrink-0 min-w-max"
                            data-status="all">
                            Semua
                        </button>
                        <button type="button" onclick="filterKasbon('pending')"
                            class="filter-tab px-4 py-2 rounded-md text-sm font-medium transition-colors whitespace-nowrap flex-shrink-0 min-w-max"
                            data-status="pending">
                            Pending
                        </button>
                        <button type="button" onclick="filterKasbon('disetujui')"
                            class="filter-tab px-4 py-2 rounded-md text-sm font-medium transition-colors whitespace-nowrap flex-shrink-0 min-w-max"
                            data-status="disetujui">
                            Disetujui
                        </button>
                        <button type="button" onclick="filterKasbon('di proses')"
                            class="filter-tab px-4 py-2 rounded-md text-sm font-medium transition-colors whitespace-nowrap flex-shrink-0 min-w-max"
                            data-status="di proses">
                            Di Proses
                        </button>
                        <button type="button" onclick="filterKasbon('selesai')"
                            class="filter-tab px-4 py-2 rounded-md text-sm font-medium transition-colors whitespace-nowrap flex-shrink-0 min-w-max"
                            data-status="selesai">
                            Selesai
                        </button>
                        <button type="button" onclick="filterKasbon('ditolak')"
                            class="filter-tab px-4 py-2 rounded-md text-sm font-medium transition-colors whitespace-nowrap flex-shrink-0 min-w-max"
                            data-status="ditolak">
                            Ditolak
                        </button>
                    </div>

                    <!-- Scroll indicators -->
                    <div class="absolute left-0 top-0 bottom-0 w-8 bg-gradient-to-r from-white to-transparent pointer-events-none rounded-l-lg scroll-indicator hidden"
                        id="leftIndicator"></div>
                    <div class="absolute right-0 top-0 bottom-0 w-8 bg-gradient-to-l from-white to-transparent pointer-events-none rounded-r-lg scroll-indicator hidden"
                        id="rightIndicator"></div>
                </div>

                <!-- Date Range Filter -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">
                            Dari Tanggal
                        </label>
                        <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">
                            Sampai Tanggal
                        </label>
                        <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    <div class="flex items-end space-x-2">
                        <button type="submit"
                            class="flex-1 bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700 transition-colors flex items-center justify-center space-x-2">
                            <i class="fas fa-search"></i>
                            <span>Filter</span>
                        </button>
                        <a href="{{ route('pegawai.kasbon') }}"
                            class="flex-1 bg-gray-100 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-200 transition-colors flex items-center justify-center space-x-2">
                            <i class="fas fa-times"></i>
                            <span>Reset</span>
                        </a>
                    </div>
                </div>

                <!-- Hidden status input -->
                <input type="hidden" id="status_filter" name="status" value="{{ request('status', 'all') }}">
            </form>
        </div>

        <!-- Active Filters Info -->
        @if(request('status') || request('date_from') || request('date_to'))
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-filter text-blue-500"></i>
                        <span class="text-sm font-medium text-blue-800">Filter Aktif:</span>
                        <div class="flex flex-wrap gap-2">
                            @if(request('status') && request('status') !== 'all')
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                    Status: {{ ucfirst(request('status')) }}
                                </span>
                            @endif
                            @if(request('date_from'))
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                    Dari: {{ \Carbon\Carbon::parse(request('date_from'))->format('d M Y') }}
                                </span>
                            @endif
                            @if(request('date_to'))
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                    Sampai: {{ \Carbon\Carbon::parse(request('date_to'))->format('d M Y') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('pegawai.kasbon') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                        <i class="fas fa-times mr-1"></i>Hapus Filter
                    </a>
                </div>
            </div>
        @endif

        <!-- Kasbon List -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800">Daftar Kasbon</h2>
                <p class="text-sm text-gray-600">Total: {{ $kasbons->total() }} pengajuan</p>
            </div>

            <div class="relative">
                <div class="max-h-96 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100"
                    id="kasbonList">
                    <div class="space-y-4 p-4">
                        @forelse($kasbons as $kasbon)
                            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4 kasbon-item hover:bg-gray-100 transition-colors"
                                data-status="{{ $kasbon->status }}">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <h3 class="font-semibold text-gray-800">
                                                Rp {{ number_format($kasbon->nominal, 0, ',', '.') }}
                                            </h3>
                                            <span
                                                class="px-2 py-1 rounded-full text-xs font-medium
                                                                                                                                                            @if($kasbon->status === 'pending') bg-yellow-100 text-yellow-800
                                                                                                                                                            @elseif($kasbon->status === 'disetujui') bg-blue-100 text-blue-800
                                                                                                                                                            @elseif($kasbon->status === 'di proses') bg-purple-100 text-purple-800
                                                                                                                                                            @elseif($kasbon->status === 'selesai') bg-green-100 text-green-800
                                                                                                                                                            @else bg-red-100 text-red-800 @endif">
                                                @if($kasbon->status === 'pending') Pending
                                                @elseif($kasbon->status === 'disetujui') Disetujui
                                                @elseif($kasbon->status === 'di proses') Di Proses
                                                @elseif($kasbon->status === 'selesai') Selesai
                                                @else Ditolak @endif
                                            </span>
                                        </div>
                                        <p class="text-gray-600 text-sm mb-2 line-clamp-2">{{ $kasbon->keterangan }}</p>
                                        <div class="flex items-center space-x-4 text-xs text-gray-500">
                                            <span>
                                                <i class="fas fa-calendar mr-1"></i>
                                                {{ $kasbon->created_at->format('d M Y, H:i') }}
                                            </span>
                                            @if($kasbon->disetujui)
                                                <span>
                                                    <i class="fas fa-user-check mr-1"></i>
                                                    {{ $kasbon->disetujui->name }}
                                                </span>
                                            @endif
                                        </div>
                                        @if($kasbon->alasan)
                                            <div class="mt-2 p-2 bg-red-50 rounded text-sm text-red-700">
                                                <strong>Alasan:</strong> {{ $kasbon->alasan }}
                                            </div>
                                        @endif
                                        @if($kasbon->status === 'selesai' && $kasbon->bukti)
                                            <div class="mt-2 p-2 bg-green-50 rounded text-sm text-green-700">
                                                <div class="flex items-center space-x-2">
                                                    <i class="fas fa-file-alt"></i>
                                                    <span><strong>Bukti tersedia</strong></span>
                                                    <a href="{{ asset('gambar/kasbon/' . $kasbon->bukti) }}" target="_blank"
                                                        class="text-green-600 hover:text-green-800 underline">
                                                        Lihat
                                                    </a>
                                                </div>
                                                @if($kasbon->tanggal_pengiriman)
                                                    <div class="text-xs mt-1">
                                                        Dikirim:
                                                        {{ \Carbon\Carbon::parse($kasbon->tanggal_pengiriman)->format('d M Y') }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('pegawai.kasbon.show', $kasbon) }}"
                                            class="text-blue-600 hover:text-blue-800 p-2 rounded-lg hover:bg-blue-50 transition-colors"
                                            title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-500 mb-2">Belum ada pengajuan kasbon</h3>
                                <p class="text-gray-400 mb-4">Mulai ajukan kasbon pertama Anda</p>
                                <a href="{{ route('pegawai.kasbon.create') }}"
                                    class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition-colors inline-flex items-center space-x-2">
                                    <i class="fas fa-plus"></i>
                                    <span>Ajukan Kasbon</span>
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Scroll indicator -->
                <div class="absolute bottom-0 left-0 right-0 h-8 bg-gradient-to-t from-white to-transparent pointer-events-none scroll-indicator-bottom hidden"
                    id="scrollIndicatorBottom"></div>
            </div>
        </div>

        <!-- Pagination -->
        @if($kasbons->hasPages())
            <div class="flex justify-center">
                {{ $kasbons->links() }}
            </div>
        @endif
    </div>

    <style>
        .filter-tab.active {
            background-color: white;
            color: #7c3aed;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .kasbon-item.hidden {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .filter-tab {
            scroll-snap-align: start;
        }

        #filterTabs {
            scroll-behavior: smooth;
            scroll-snap-type: x mandatory;
        }

        .scroll-indicator {
            transition: opacity 0.3s ease;
        }

        .scroll-indicator.hidden {
            opacity: 0;
        }

        .touch-pan-x {
            -webkit-overflow-scrolling: touch;
            overscroll-behavior-x: contain;
        }

        .min-w-max {
            min-width: max-content;
        }

        /* Custom scrollbar */
        .scrollbar-thin {
            scrollbar-width: thin;
        }

        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
        }

        .scrollbar-thin::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Line clamp for text truncation */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>

    <script>
        function filterKasbon(status) {
            // Update active tab
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelector(`[data-status="${status}"]`).classList.add('active');

            // Update hidden status input
            document.getElementById('status_filter').value = status;

            // Scroll to active tab
            const activeTab = document.querySelector(`[data-status="${status}"]`);
            activeTab.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest',
                inline: 'center'
            });

            // Submit form to apply server-side filtering
            document.getElementById('filterForm').submit();
        }

        // Initialize active tab based on current status
        document.addEventListener('DOMContentLoaded', function () {
            const currentStatus = '{{ request("status", "all") }}';
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelector(`[data-status="${currentStatus}"]`).classList.add('active');

            // Initialize scroll indicators
            updateScrollIndicators();
            updateScrollIndicatorBottom();
        });

        // Scroll indicators functionality
        function updateScrollIndicators() {
            const container = document.getElementById('filterTabs');
            const leftIndicator = document.getElementById('leftIndicator');
            const rightIndicator = document.getElementById('rightIndicator');

            const scrollLeft = container.scrollLeft;
            const scrollWidth = container.scrollWidth;
            const clientWidth = container.clientWidth;

            // Show/hide left indicator
            if (scrollLeft > 0) {
                leftIndicator.classList.remove('hidden');
            } else {
                leftIndicator.classList.add('hidden');
            }

            // Show/hide right indicator
            if (scrollLeft < scrollWidth - clientWidth - 1) {
                rightIndicator.classList.remove('hidden');
            } else {
                rightIndicator.classList.add('hidden');
            }
        }

        // Scroll indicator for kasbon list
        function updateScrollIndicatorBottom() {
            const kasbonList = document.getElementById('kasbonList');
            const scrollIndicator = document.getElementById('scrollIndicatorBottom');

            if (!kasbonList || !scrollIndicator) return;

            const scrollTop = kasbonList.scrollTop;
            const scrollHeight = kasbonList.scrollHeight;
            const clientHeight = kasbonList.clientHeight;

            // Show/hide bottom indicator
            if (scrollTop < scrollHeight - clientHeight - 1) {
                scrollIndicator.classList.remove('hidden');
            } else {
                scrollIndicator.classList.add('hidden');
            }
        }

        // Touch gesture support
        let startX = 0;
        let startY = 0;
        let isScrolling = false;

        function handleTouchStart(e) {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
            isScrolling = false;
        }

        function handleTouchMove(e) {
            if (!startX || !startY) return;

            const currentX = e.touches[0].clientX;
            const currentY = e.touches[0].clientY;
            const diffX = Math.abs(startX - currentX);
            const diffY = Math.abs(startY - currentY);

            if (diffX > diffY) {
                isScrolling = true;
            }
        }

        function handleTouchEnd(e) {
            if (!isScrolling) return;

            const endX = e.changedTouches[0].clientX;
            const diffX = startX - endX;

            if (Math.abs(diffX) > 50) { // Minimum swipe distance
                const container = document.getElementById('filterTabs');
                const scrollAmount = 200; // Scroll amount

                if (diffX > 0) {
                    // Swipe left - scroll right
                    container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
                } else {
                    // Swipe right - scroll left
                    container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
                }
            }

            startX = 0;
            startY = 0;
            isScrolling = false;
        }

        // Initialize scroll indicators and touch support
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('filterTabs');
            const kasbonList = document.getElementById('kasbonList');

            // Add scroll event listener for filter tabs
            container.addEventListener('scroll', updateScrollIndicators);

            // Add scroll event listener for kasbon list
            kasbonList.addEventListener('scroll', updateScrollIndicatorBottom);

            // Add touch event listeners
            container.addEventListener('touchstart', handleTouchStart, { passive: true });
            container.addEventListener('touchmove', handleTouchMove, { passive: true });
            container.addEventListener('touchend', handleTouchEnd, { passive: true });

            // Initial check
            updateScrollIndicators();
            updateScrollIndicatorBottom();

            // Check on resize
            window.addEventListener('resize', function () {
                updateScrollIndicators();
                updateScrollIndicatorBottom();
            });
        });
    </script>
@endsection