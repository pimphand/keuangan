@extends('pegawai.layout')

@section('title', 'Detail Slip Gaji')
@section('header-title', 'Detail Slip Gaji')
@section('header-icon', 'file-invoice-dollar')

@section('content')
    <div class="space-y-6">
        <!-- Back Button -->
        <div class="flex items-center space-x-3">
            <a href="{{ route('pegawai.slip-gaji') }}"
                class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left text-gray-600"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-gray-800">Detail Slip Gaji</h1>
                <p class="text-sm text-gray-600">Periode {{ $gajian->formatted_periode_gaji }}</p>
            </div>
        </div>

        <!-- Salary Slip Detail Card -->
        <div class="bg-white rounded-lg shadow-sm border p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">SLIP GAJI KARYAWAN</h2>
                <p class="text-gray-600">Periode Gaji: {{ $gajian->formatted_periode_gaji }}</p>
            </div>

            <!-- Employee Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Karyawan</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Nama:</span>
                            <span class="font-medium">{{ $gajian->nama }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Jabatan:</span>
                            <span class="font-medium">{{ $gajian->jabatan }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'approved' => 'bg-blue-100 text-blue-800',
                                    'paid' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800'
                                ];
                                $statusLabels = [
                                    'pending' => 'Pending',
                                    'approved' => 'Disetujui',
                                    'paid' => 'Dibayar',
                                    'rejected' => 'Ditolak'
                                ];
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$gajian->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusLabels[$gajian->status] ?? ucfirst($gajian->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Pembayaran</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tanggal Pembayaran:</span>
                            <span class="font-medium">
                                {{ $gajian->tanggal_pembayaran ? $gajian->tanggal_pembayaran->format('d M Y') : '-' }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Periode:</span>
                            <span class="font-medium">{{ $gajian->formatted_periode_gaji }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Salary Breakdown -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Rincian Gaji</h3>

                <!-- Earnings Section -->
                <div class="mb-6">
                    <h4 class="text-md font-semibold text-gray-700 mb-3">Penghasilan</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between py-2">
                            <span class="text-gray-600">Gaji Pokok:</span>
                            <span class="font-medium">{{ $gajian->formatted_gaji_pokok }}</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-gray-600">Tunjangan:</span>
                            <span class="font-medium">{{ $gajian->formatted_tunjangan }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-t pt-2">
                            <span class="font-semibold text-gray-800">Total Penghasilan:</span>
                            <span class="font-bold text-gray-800">Rp.{{ number_format((int) $gajian->gaji_pokok + (int) $gajian->tunjangan, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Deductions Section -->
                <div class="mb-6">
                    <h4 class="text-md font-semibold text-gray-700 mb-3">Potongan</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between py-2">
                            <span class="text-gray-600">Potongan:</span>
                            <span class="font-medium text-red-600">- {{ $gajian->formatted_potongan }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-t pt-2">
                            <span class="font-semibold text-gray-800">Total Potongan:</span>
                            <span class="font-bold text-red-600">- {{ $gajian->formatted_potongan }}</span>
                        </div>
                    </div>
                </div>

                <!-- Net Salary -->
                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-blue-800">GAJI BERSIH:</span>
                        <span class="text-2xl font-bold text-blue-800">{{ $gajian->formatted_gaji_bersih }}</span>
                    </div>
                </div>

                <!-- Additional Information -->
                @if($gajian->keterangan)
                    <div class="mt-6">
                        <h4 class="text-md font-semibold text-gray-700 mb-2">Keterangan</h4>
                        <p class="text-gray-600 bg-gray-50 p-3 rounded-lg">{{ $gajian->keterangan }}</p>
                    </div>
                @endif
            </div>

            <!-- Thank You Message -->
            <div class="text-center mt-8 pt-6 border-t">
                <p class="text-gray-600 italic">Terima kasih atas kerja keras Anda.</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-center space-x-4">
            <a href="{{ route('pegawai.slip-gaji.print', $gajian->id) }}"
               class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors flex items-center space-x-2"
               target="_blank">
                <i class="fas fa-print"></i>
                <span>Cetak Slip Gaji</span>
            </a>
            <a href="{{ route('pegawai.slip-gaji') }}"
               class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors flex items-center space-x-2">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Daftar</span>
            </a>
        </div>
    </div>
@endsection
