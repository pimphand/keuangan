@extends('pegawai.layout')

@section('title', 'Beranda')
@section('header-title', 'Beranda')
@section('header-icon', 'home')

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Halo, {{ $user->name }}!</h1>
            </div>
            <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center shadow-sm">
                <i class="fas fa-user text-gray-600"></i>
            </div>
        </div>

        <!-- Main Card with Purple Background -->
        <div
            class="relative bg-gradient-to-br from-purple-600 to-purple-700 rounded-2xl p-6 text-white shadow-lg overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="grid grid-cols-8 gap-2 h-full">
                    @for($i = 0; $i < 32; $i++)
                        <div class="bg-white rounded-sm"></div>
                    @endfor
                </div>
            </div>

            <!-- Card Content -->
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h2 class="text-sm font-medium opacity-90 mb-1">PT MATARAM DIGITAL TEKNOLOGI</h2>
                        <div class="flex justify-content-between">
                            <span>
                                <p class="text-xs opacity-75">Saldo Tersedia</p>
                                <p class="text-2xl font-bold">Rp {{ number_format($user->saldo, 0, ',', '.') }}</p>
                            </span>
                        </div>


                    </div>
                    <div class="text-right">
                        <h3 class="text-lg font-bold">MDTPay</h3>
                        <p class="text-xs opacity-75">12/25</p>
                    </div>
                </div>

                <div class="mt-4">
                    <p class="text-xs opacity-75">Pemegang Kartu</p>
                    <p class="text-lg font-semibold">{{ strtoupper($user->name) }}</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="grid grid-cols-4 gap-4">
            <a href="{{ route('pegawai.pengumuman') }}"
                class="flex flex-col items-center space-y-2 cursor-pointer hover:opacity-80 transition-opacity">
                <div
                    class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center border-2 border-purple-300 hover:bg-purple-200 transition-colors">
                    <i class="fas fa-bell text-purple-600"></i>
                </div>
                <span class="text-xs font-medium text-gray-700">Pengumuman</span>
            </a>

            <div class="flex flex-col items-center space-y-2 cursor-pointer hover:opacity-80 transition-opacity">
                <div
                    class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center border-2 border-purple-300 hover:bg-purple-200 transition-colors">
                    <i class="fas fa-calendar text-purple-600"></i>
                </div>
                <span class="text-xs font-medium text-gray-700">Absen</span>
            </div>

            <a href="{{ route('pegawai.kasbon') }}"
                class="flex flex-col items-center space-y-2 cursor-pointer hover:opacity-80 transition-opacity">
                <div
                    class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center border-2 border-purple-300 hover:bg-purple-200 transition-colors">
                    <i class="fas fa-money-bill text-purple-600"></i>
                </div>
                <span class="text-xs font-medium text-gray-700">Kasbon</span>
            </a>

            <div class="flex flex-col items-center space-y-2 cursor-pointer hover:opacity-80 transition-opacity">
                <div
                    class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center border-2 border-purple-300 hover:bg-purple-200 transition-colors">
                    <i class="fas fa-arrow-down text-purple-600"></i>
                </div>
                <span class="text-xs font-medium text-gray-700">Withdraw</span>
            </div>
        </div>

        <!-- Transaction History Section -->
        <div class="space-y-4">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Transaksi Terkini</h3>
                <a href="#" class="text-blue-600 text-sm font-medium">Lihat Semua</a>
            </div>

            <div class="space-y-3">
                @forelse($transaksiTerkini as $transaksi)
                    <div class="flex items-center space-x-3 p-3 bg-white rounded-lg shadow-sm">
                        <div
                            class="w-8 h-8 {{ $transaksi->jenis == 'masuk' ? 'bg-green-100' : 'bg-red-100' }} rounded-full flex items-center justify-center">
                            <i
                                class="fas fa-clock {{ $transaksi->jenis == 'masuk' ? 'text-green-600' : 'text-red-600' }} text-xs"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">{{ $transaksi->type_transaksi }}</p>
                            <p class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($transaksi->created_at)->format('d F Y h:i') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p
                                class="text-sm font-semibold {{ $transaksi->type_transaksi == 'masuk' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaksi->jenis == 'masuk' ? '+' : '-' }} Rp
                                {{ number_format($transaksi->nominal, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-receipt text-4xl mb-2"></i>
                        <p>Belum ada transaksi</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pengumuman Section -->
        @if($pengumumanTerkini->count() > 0)
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800">
                        <i class="fas fa-bullhorn mr-2"></i>
                        Pengumuman Terkini
                    </h3>
                    <a href="{{ route('pegawai.pengumuman') }}" class="text-blue-600 text-sm font-medium">Lihat Semua</a>
                </div>

                <div class="space-y-3">
                    @foreach($pengumumanTerkini as $pengumuman)
                        <div
                            class="p-3 bg-white rounded-lg shadow-sm border-l-4 {{ $pengumuman->prioritas === 'tinggi' ? 'border-l-red-500' : ($pengumuman->prioritas === 'sedang' ? 'border-l-yellow-500' : 'border-l-blue-500') }}">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        @if($pengumuman->prioritas === 'tinggi')
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                Prioritas Tinggi
                                            </span>
                                        @elseif($pengumuman->prioritas === 'sedang')
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Prioritas Sedang
                                            </span>
                                        @endif
                                    </div>
                                    <h4 class="text-sm font-semibold text-gray-800 mb-1">{{ $pengumuman->judul }}</h4>
                                    <p class="text-xs text-gray-600 line-clamp-2">{{ $pengumuman->excerpt }}</p>
                                    <div class="flex items-center justify-between mt-2">
                                        <span class="text-xs text-gray-500">
                                            <i class="fas fa-user mr-1"></i>
                                            {{ $pengumuman->creator ? $pengumuman->creator->name : 'Sistem' }}
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            {{ $pengumuman->created_at->format('d M Y h:i') }}
                                        </span>
                                    </div>
                                </div>
                                <a href="{{ route('pegawai.pengumuman.show', $pengumuman->id) }}"
                                    class="ml-2 text-blue-600 hover:text-blue-800 transition-colors">
                                    <i class="fas fa-arrow-right text-sm"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        // Add click handlers for action buttons
        document.addEventListener('DOMContentLoaded', function () {
            // Pengumuman button
            document.querySelector('.grid .flex.flex-col:nth-child(1)').addEventListener('click', function () {
                // Handle pengumuman click
                console.log('Pengumuman clicked');
            });

            // Absen button
            document.querySelector('.grid .flex.flex-col:nth-child(2)').addEventListener('click', function () {
                window.location.href = '{{ route("pegawai.index") }}';
            });

            // Kasbon button
            document.querySelector('.grid .flex.flex-col:nth-child(3)').addEventListener('click', function () {
                // Handle kasbon click
                console.log('Kasbon clicked');
            });

            // Withdraw button
            document.querySelector('.grid .flex.flex-col:nth-child(4)').addEventListener('click', function () {
                // Handle withdraw click
                console.log('Withdraw clicked');
            });
        });
    </script>
@endpush