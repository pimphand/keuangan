@extends('pegawai.layout')

@section('title', $pengumuman->judul)
@section('header-title', 'Pengumuman')
@section('header-icon', 'bullhorn')

@section('content')
    <div
        class="bg-gray-50 rounded-xl p-6 border border-gray-200 {{ $pengumuman->prioritas === 'tinggi' ? 'border-l-4 border-l-red-500' : '' }}">
        <!-- Header dengan judul dan tanggal -->
        <div class="mb-6">
            <div class="flex justify-between items-start mb-3">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        @if($pengumuman->prioritas === 'tinggi')
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $pengumuman->priority_color }}">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                {{ $pengumuman->priority_text }}
                            </span>
                        @elseif($pengumuman->prioritas === 'sedang')
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $pengumuman->priority_color }}">
                                {{ $pengumuman->priority_text }}
                            </span>
                        @endif
                    </div>
                    <h1 class="font-bold text-gray-800 text-xl leading-tight">{{ $pengumuman->judul }}</h1>
                </div>
                <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full ml-2">
                    {{ $pengumuman->tanggal_formatted }}
                </span>
            </div>

            <!-- Author info dan stats -->
            <div class="flex justify-between items-center text-sm text-gray-600 mb-4">
                <div class="flex items-center">
                    <i class="fas fa-user-circle mr-2"></i>
                    <span>Dibuat oleh:
                        <strong>{{ $pengumuman->creator ? $pengumuman->creator->name : 'Sistem' }}</strong></span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-eye mr-1"></i>
                    <span>{{ $pengumuman->views_count }} dilihat</span>
                </div>
            </div>
        </div>

        <!-- Gambar jika ada -->
        @if($pengumuman->gambar)
            <div class="mb-6">
                <img src="{{ asset($pengumuman->gambar) }}" alt="{{ $pengumuman->judul }}"
                    class="w-full h-48 object-cover rounded-lg shadow-sm">
            </div>
        @endif

        <!-- Konten pengumuman -->
        <div class="text-gray-700 leading-relaxed mb-6">
            {!! nl2br(e($pengumuman->isi)) !!}
        </div>

        <!-- Link eksternal jika ada -->
        @if($pengumuman->link)
            <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <h4 class="font-semibold text-blue-800 mb-2">
                    <i class="fas fa-link mr-1"></i>
                    Link Terkait
                </h4>
                <a href="{{ $pengumuman->link }}" target="_blank"
                    class="text-blue-600 hover:text-blue-800 transition-colors duration-200 underline">
                    {{ $pengumuman->link }}
                    <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                </a>
            </div>
        @endif

        <!-- Action buttons -->
        <div class="flex justify-between items-center pt-4 border-t border-gray-200">
            <a href="{{ route('pegawai.pengumuman') }}"
                class="flex items-center text-gray-600 hover:text-gray-800 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Daftar Pengumuman
            </a>

            <div class="flex space-x-2">
                @if($pengumuman->link)
                    <a href="{{ $pengumuman->link }}" target="_blank"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-external-link-alt mr-1"></i>
                        Buka Link
                    </a>
                @endif

                <button onclick="window.location.reload()"
                    class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 transition-colors duration-200">
                    <i class="fas fa-sync-alt mr-1"></i>
                    Refresh
                </button>
            </div>
        </div>
    </div>
@endsection
