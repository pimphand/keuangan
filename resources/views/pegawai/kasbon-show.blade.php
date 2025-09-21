@extends('pegawai.layout')

@section('title', 'Detail Kasbon')
@section('header-title', 'Detail Kasbon')
@section('header-icon', 'eye')

@section('content')
    <div class="space-y-6">
        <!-- Back Button -->
        <div class="flex items-center space-x-3">
            <a href="{{ route('pegawai.kasbon') }}"
                class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left text-gray-600"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-gray-800">Detail Kasbon</h1>
                <p class="text-sm text-gray-600">Informasi lengkap pengajuan kasbon Anda</p>
            </div>
        </div>

        <!-- Status Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Status Pengajuan</h2>
                <span class="px-3 py-1 rounded-full text-sm font-medium
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

            @if($kasbon->status === 'pending')
                <div class="flex items-center space-x-2 text-yellow-600">
                    <i class="fas fa-clock"></i>
                    <span class="text-sm">Menunggu persetujuan admin</span>
                </div>
            @elseif($kasbon->status === 'disetujui')
                <div class="flex items-center space-x-2 text-blue-600">
                    <i class="fas fa-check-circle"></i>
                    <span class="text-sm">Pengajuan telah disetujui, menunggu proses</span>
                </div>
            @elseif($kasbon->status === 'di proses')
                <div class="flex items-center space-x-2 text-purple-600">
                    <i class="fas fa-cogs"></i>
                    <span class="text-sm">Kasbon sedang diproses</span>
                </div>
            @elseif($kasbon->status === 'selesai')
                <div class="flex items-center space-x-2 text-green-600">
                    <i class="fas fa-check-double"></i>
                    <span class="text-sm">Kasbon telah selesai diproses</span>
                </div>
            @else
                <div class="flex items-center space-x-2 text-red-600">
                    <i class="fas fa-times-circle"></i>
                    <span class="text-sm">Pengajuan ditolak</span>
                </div>
            @endif
        </div>

        <!-- Detail Information -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Kasbon</h2>

            <div class="space-y-4">
                <!-- Nominal -->
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="text-gray-600 font-medium">Nominal</span>
                    <span class="text-lg font-bold text-gray-800">Rp
                        {{ number_format($kasbon->nominal, 0, ',', '.') }}</span>
                </div>

                <!-- Keterangan -->
                <div class="py-3 border-b border-gray-100">
                    <span class="text-gray-600 font-medium block mb-2">Keterangan</span>
                    <p class="text-gray-800">{{ $kasbon->keterangan }}</p>
                </div>

                <!-- Tanggal Pengajuan -->
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="text-gray-600 font-medium">Tanggal Pengajuan</span>
                    <span class="text-gray-800">{{ $kasbon->created_at->format('d M Y, H:i') }}</span>
                </div>

                @if($kasbon->disetujui)
                    <!-- Disetujui Oleh -->
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">Disetujui Oleh</span>
                        <span class="text-gray-800">{{ $kasbon->disetujui->name }}</span>
                    </div>

                    <!-- Tanggal Persetujuan -->
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">Tanggal Persetujuan</span>
                        <span class="text-gray-800">{{ $kasbon->updated_at->format('d M Y, H:i') }}</span>
                    </div>
                @endif

                @if($kasbon->alasan)
                    <!-- Alasan Penolakan -->
                    <div class="py-3 border-b border-gray-100">
                        <span class="text-gray-600 font-medium block mb-2">Alasan Penolakan</span>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                            <p class="text-red-800 text-sm">{{ $kasbon->alasan }}</p>
                        </div>
                    </div>
                @endif

                @if($kasbon->bukti)
                    <!-- Bukti Pengiriman -->
                    <div class="py-3 border-b border-gray-100">
                        <span class="text-gray-600 font-medium block mb-2">Bukti Pengiriman</span>
                        <div class="flex items-start space-x-4">
                            <img src="{{ asset('gambar/kasbon/' . $kasbon->bukti) }}" alt="Bukti Pengiriman"
                                class="rounded-lg shadow-sm cursor-pointer hover:shadow-md transition-shadow"
                                style="max-width: 200px; max-height: 150px; object-fit: cover;"
                                onclick="window.open('{{ asset('gambar/kasbon/' . $kasbon->bukti) }}', '_blank')">
                            <div class="flex flex-col space-y-2">
                                <a href="{{ asset('gambar/kasbon/' . $kasbon->bukti) }}" target="_blank"
                                    class="inline-flex items-center px-3 py-2 bg-blue-100 text-blue-800 rounded-lg hover:bg-blue-200 transition-colors">
                                    <i class="fas fa-download mr-2"></i>
                                    <span class="text-sm font-medium">Download</span>
                                </a>
                                <span class="text-xs text-gray-500">File: {{ $kasbon->bukti }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                @if($kasbon->tanggal_pengiriman)
                    <!-- Tanggal Pengiriman -->
                    <div class="py-3">
                        <span class="text-gray-600 font-medium block mb-1">Tanggal Pengiriman</span>
                        <span
                            class="text-gray-800">{{ \Carbon\Carbon::parse($kasbon->tanggal_pengiriman)->format('d M Y') }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex space-x-3">
            <a href="{{ route('pegawai.kasbon') }}"
                class="flex-1 bg-gray-100 text-gray-700 py-3 px-4 rounded-lg text-center font-medium hover:bg-gray-200 transition-colors">
                Kembali ke Daftar
            </a>
        </div>

        <!-- Info Card -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start space-x-3">
                <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                <div>
                    <h3 class="text-sm font-medium text-blue-800 mb-1">Informasi Penting</h3>
                    <ul class="text-xs text-blue-700 space-y-1">
                        @if($kasbon->isPending())
                            <li>• Pengajuan kasbon Anda sedang dalam proses review</li>
                            <li>• Admin akan memproses pengajuan dalam waktu 1-2 hari kerja</li>
                        @elseif($kasbon->isApproved())
                            <li>• Pengajuan kasbon Anda telah disetujui</li>
                            <li>• Kasbon akan segera diproses oleh admin</li>
                            <li>• Hubungi admin jika ada pertanyaan</li>
                        @elseif($kasbon->isProcessing())
                            <li>• Kasbon Anda sedang dalam proses</li>
                            <li>• Admin sedang memproses pengiriman dana</li>
                            <li>• Anda akan mendapat notifikasi ketika selesai</li>
                        @elseif($kasbon->isCompleted())
                            <li>• Kasbon Anda telah selesai diproses</li>
                            <li>• Bukti pengiriman tersedia di atas</li>
                            <li>• Hubungi admin jika ada pertanyaan</li>
                        @else
                            <li>• Pengajuan kasbon Anda ditolak</li>
                            <li>• Silakan periksa alasan penolakan di atas</li>
                            <li>• Anda dapat mengajukan ulang dengan informasi yang benar</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection