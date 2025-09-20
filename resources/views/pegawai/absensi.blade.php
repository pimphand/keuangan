@extends('pegawai.layout')

@section('title', 'Absensi Pegawai')

@section('header-icon', 'clock')
@section('header-title', 'Absensi Pegawai')
@section('header-subtitle')
    <i class="fas fa-calendar mr-1"></i>
    {{ \Carbon\Carbon::now()->format('d M Y, H:i') }}
@endsection

@section('content')
    <!-- Location Info -->
    @include('pegawai.components.location-info')

    <!-- Camera Section -->
    @include('pegawai.components.camera-section')

    <!-- Work Hours Info -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 mb-6 border border-blue-200">
        <h6 class="font-semibold text-gray-700 mb-3">
            <i class="fas fa-clock mr-2"></i>Jam Kerja
        </h6>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div class="text-center">
                <div class="text-gray-600 mb-1">Jam Masuk</div>
                <div class="font-semibold text-green-600">{{ config('app.work_hours.start_time') }}</div>
                <div class="text-xs text-gray-500">Terlambat setelah {{ config('app.work_hours.start_time') }} +
                    {{ config('app.work_hours.late_threshold_minutes') }} menit
                </div>
            </div>
            <div class="text-center">
                <div class="text-gray-600 mb-1">Jam Pulang</div>
                <div class="font-semibold text-red-600">{{ config('app.work_hours.end_time') }}</div>
                <div class="text-xs text-gray-500">Bisa pulang setelah jam ini</div>
            </div>
        </div>
    </div>

    <!-- Attendance Buttons -->
    <div class="mb-6">

        <!-- Additional Notes -->
        <div class="mb-6">
            <label for="keterangan" class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-sticky-note mr-2"></i>Keterangan (Opsional)
            </label>
            <textarea
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                id="keterangan" rows="3" placeholder="Tambahkan keterangan jika diperlukan..."></textarea>
        </div>
        <h6 class="font-semibold text-gray-700 mb-4">
            <i class="fas fa-tasks mr-2"></i>Pilih Jenis Absensi
        </h6>
        <button
            class="w-full py-4 px-6 rounded-2xl font-semibold text-lg my-2 transition-all duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-xl {{ $sudahMasuk ? 'bg-gray-500 text-white cursor-not-allowed opacity-60' : 'bg-gradient-to-r from-green-500 to-teal-500 text-white hover:from-green-600 hover:to-teal-600' }}"
            onclick="absen('masuk')" {{ $sudahMasuk ? 'disabled' : '' }}>
            <i class="fas fa-sign-in-alt mr-2"></i>
            {{ $sudahMasuk ? 'Sudah Masuk' : 'Absen Masuk' }}
        </button>

        <button
            class="w-full py-4 px-6 rounded-2xl font-semibold text-lg my-2 transition-all duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-xl {{ $sudahIzin ? 'bg-gray-500 text-white cursor-not-allowed opacity-60' : 'bg-gradient-to-r from-yellow-500 to-orange-500 text-white hover:from-yellow-600 hover:to-orange-600' }}"
            onclick="absen('izin')" {{ $sudahIzin ? 'disabled' : '' }}>
            <i class="fas fa-calendar-times mr-2"></i>
            {{ $sudahIzin ? 'Sudah Izin' : 'Izin' }}
        </button>

        <button
            class="w-full py-4 px-6 rounded-2xl font-semibold text-lg my-2 transition-all duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-xl {{ $sudahSakit ? 'bg-gray-500 text-white cursor-not-allowed opacity-60' : 'bg-gradient-to-r from-cyan-500 to-purple-600 text-white hover:from-cyan-600 hover:to-purple-700' }}"
            onclick="absen('sakit')" {{ $sudahSakit ? 'disabled' : '' }}>
            <i class="fas fa-thermometer-half mr-2"></i>
            {{ $sudahSakit ? 'Sudah Sakit' : 'Sakit' }}
        </button>

        <button
            class="w-full py-4 px-6 rounded-2xl font-semibold text-lg my-2 transition-all duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-xl {{ $sudahKeluar ? 'bg-gray-500 text-white cursor-not-allowed opacity-60' : 'bg-gradient-to-r from-red-500 to-orange-500 text-white hover:from-red-600 hover:to-orange-600' }}"
            onclick="absen('keluar')" {{ $sudahKeluar ? 'disabled' : '' }}>
            <i class="fas fa-sign-out-alt mr-2"></i>
            {{ $sudahKeluar ? 'Sudah Pulang' : 'Absen Pulang' }}
        </button>
    </div>

    <!-- Loading Indicator -->
    @include('pegawai.components.loading-indicator')

    <!-- Today's Attendance History -->
    @if($absensiHariIni->count() > 0)
        <div class="mt-6">
            <h6 class="font-semibold text-gray-700 mb-4">
                <i class="fas fa-history mr-2"></i>Riwayat Hari Ini
            </h6>
            <div class="max-h-[300px] overflow-y-auto">
                @foreach($absensiHariIni as $absen)
                    <div class="bg-gray-50 rounded-xl p-4 mb-3 border-l-4 border-blue-500">
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="flex items-center">
                                    <strong class="text-gray-800 capitalize">{{ $absen->jenis }}</strong>
                                    @if($absen->terlambat && $absen->jenis == 'masuk')
                                        <span
                                            class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Terlambat
                                        </span>
                                    @endif
                                </div>
                                <br>
                                <small class="text-gray-500">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $absen->waktu_absen->format('H:i:s') }}
                                </small>
                            </div>
                            <div class="text-right">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $absen->status == 'valid' ? 'bg-green-100 text-green-800' : ($absen->status == 'invalid' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($absen->status) }}
                                </span>
                            </div>
                        </div>
                        @if($absen->keterangan)
                            <div class="mt-2">
                                <small class="text-gray-500">
                                    <i class="fas fa-comment mr-1"></i>{{ $absen->keterangan }}
                                </small>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Navigation Links -->
    @include('pegawai.components.navigation-links')
@endsection

@push('scripts')
    <!-- Geolocation Script -->
    @include('pegawai.components.geolocation-script')

    <!-- Camera Script -->
    @include('pegawai.components.camera-script')

    <!-- Attendance Script -->
    <script>
        function absen(jenis) {
            if (!selectedFile) {
                alert('Silakan ambil foto terlebih dahulu!');
                return;
            }

            // Check if location is available, but don't block attendance if not
            if (!currentLocation) {
                if (!confirm('Lokasi tidak dapat dideteksi. Apakah Anda ingin melanjutkan absensi tanpa lokasi?')) {
                    return;
                }
            }

            // Create a File object from the blob with proper filename
            const file = new File([selectedFile], 'attendance_photo.jpg', { type: 'image/jpeg' });

            const formData = new FormData();
            formData.append('jenis', jenis);
            formData.append('foto', file);

            // Add location if available, otherwise send null
            if (currentLocation) {
                formData.append('latitude', currentLocation.latitude);
                formData.append('longitude', currentLocation.longitude);
            } else {
                formData.append('latitude', '');
                formData.append('longitude', '');
            }

            formData.append('keterangan', document.getElementById('keterangan').value);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            // Show loading
            document.getElementById('loading').classList.remove('hidden');
            document.querySelectorAll('button[onclick^="absen"]').forEach(btn => btn.disabled = true);

            fetch('{{ route("pegawai.absen") }}', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('loading').classList.add('hidden');
                    document.querySelectorAll('button[onclick^="absen"]').forEach(btn => btn.disabled = false);

                    if (data.success) {
                        let message = data.message;
                        if (data.terlambat) {
                            message += '\n\n⚠️ Anda terlambat! Jam masuk kerja adalah ' + '{{ config("app.work_hours.start_time") }}' + ' + {{ config("app.work_hours.late_threshold_minutes") }} menit.';
                        }
                        alert(message);
                        location.reload();
                    } else {
                        // Show specific error messages with better formatting
                        let errorMessage = data.message;
                        if (errorMessage.includes('Belum waktunya pulang')) {
                            errorMessage = '🚫 ' + errorMessage + '\n\n💡 Silakan tunggu hingga jam ' + '{{ config("app.work_hours.end_time") }}' + ' untuk melakukan absen pulang.';
                        } else if (errorMessage.includes('luar radius kantor')) {
                            errorMessage = '📍 ' + errorMessage;
                        } else if (errorMessage.includes('sudah melakukan absensi')) {
                            errorMessage = '✅ ' + errorMessage;
                        } else {
                            errorMessage = '❌ ' + errorMessage;
                        }
                        alert(errorMessage);
                    }
                })
                .catch(error => {
                    document.getElementById('loading').classList.add('hidden');
                    document.querySelectorAll('button[onclick^="absen"]').forEach(btn => btn.disabled = false);
                    alert('Terjadi kesalahan: ' + error.message);
                });
        }
    </script>
@endpush