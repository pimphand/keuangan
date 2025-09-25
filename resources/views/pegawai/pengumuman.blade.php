@extends('pegawai.layout')

@section('title', 'Pengumuman')
@section('header-title', 'Pengumuman')
@section('header-icon', 'bullhorn')

@section('content')
    <div class="space-y-4">
        @if($pengumuman->count() > 0)
            @foreach($pengumuman as $item)
                <div
                    class="bg-gray-50 rounded-xl p-4 border border-gray-200 hover:shadow-md transition-shadow duration-200 {{ $item->prioritas === 'tinggi' ? 'border-l-4 border-l-red-500' : '' }} flex flex-col">
                    <!-- Header dengan judul dan tanggal -->
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                @if($item->prioritas === 'tinggi')
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $item->priority_color }}">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        {{ $item->priority_text }}
                                    </span>
                                @elseif($item->prioritas === 'sedang')
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $item->priority_color }}">
                                        {{ $item->priority_text }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full ml-2">
                            {{ $item->tanggal_formatted }}
                        </span>
                    </div>
                    <h6 class="font-bold text-gray-800  leading-tight">{{ $item->judul }}</h6>

                    <!-- Konten pengumuman -->
                    <div class="text-gray-700 text-sm leading-relaxed mb-3"
                        style="display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;min-height:68px;">
                        {!! nl2br(e($item->excerpt)) !!}
                        @if(strlen(strip_tags($item->isi)) > 150)
                            <span class="text-blue-600 font-medium">...</span>
                        @endif
                    </div>

                    <!-- Gambar jika ada -->
                    @if($item->gambar)
                        <div class="mb-3">
                            <img src="{{ asset('gambar/pengumuman/' . $item->gambar) }}" alt="{{ $item->judul }}"
                                class="w-full h-32 object-cover rounded-lg">
                        </div>
                    @endif

                    <!-- Footer dengan link dan author -->
                    <div class="flex justify-between items-center pt-3 border-t border-gray-200 mt-auto">
                        <div class="flex items-center text-xs text-gray-500">
                            <i class="fas fa-user-circle mr-1"></i>
                            <span>{{ $item->creator ? $item->creator->name : 'Sistem' }}</span>
                        </div>

                        <a href="{{ route('pegawai.pengumuman.show', $item->id) }}"
                            class="bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-medium hover:bg-blue-700 transition-colors duration-200">
                            Baca Selengkapnya
                        </a>
                    </div>

                    <!-- Link eksternal jika ada -->
                    @if($item->link)
                        <div class="mt-2 pt-2 border-t border-gray-200">
                            <a href="{{ $item->link }}" target="_blank"
                                class="text-blue-600 text-xs hover:text-blue-800 transition-colors duration-200">
                                <i class="fas fa-external-link-alt mr-1"></i>
                                Link Terkait
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach
        @else
            <!-- Empty state -->
            <div class="text-center py-12">
                <div class="mb-4">
                    <i class="fas fa-bullhorn text-gray-300 text-6xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-600 mb-2">Belum Ada Pengumuman</h3>
                <p class="text-gray-500 text-sm">Saat ini belum ada pengumuman yang tersedia. Silakan kembali lagi nanti.</p>
            </div>
        @endif
    </div>

    <!-- Floating action button untuk refresh -->
    <div class="fixed bottom-20 right-4 z-40">
        <button onclick="window.location.reload()"
            class="bg-blue-600 text-white p-3 rounded-full shadow-lg hover:bg-blue-700 transition-colors duration-200">
            <i class="fas fa-sync-alt"></i>
        </button>
    </div>
@endsection