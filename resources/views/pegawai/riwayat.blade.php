@extends('pegawai.layout')

@section('title', 'Riwayat Absensi')
@section('header-title', 'Riwayat Absensi')
@section('header-icon', 'history')
@section('header-subtitle', 'Lihat riwayat absensi Anda')

@section('content')

    <!-- Filter Options -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div>
            <label for="filter-jenis" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-filter mr-2 text-blue-500"></i>Filter Jenis Absensi
            </label>
            <select id="filter-jenis" onchange="filterByJenis()"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                <option value="">Semua Jenis</option>
                <option value="masuk">Masuk</option>
                <option value="keluar">Keluar</option>
                <option value="izin">Izin</option>
                <option value="sakit">Sakit</option>
            </select>
        </div>
        <div>
            <label for="filter-status" class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-check-circle mr-2 text-blue-500"></i>Filter Status
            </label>
            <select id="filter-status" onchange="filterByStatus()"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                <option value="">Semua Status</option>
                <option value="valid">Valid</option>
                <option value="invalid">Invalid</option>
                <option value="pending">Pending</option>
            </select>
        </div>
    </div>

    <!-- Attendance List -->
    @if($absensi->count() > 0)
        <div id="attendance-list" class="space-y-4">
            @foreach($absensi as $absen)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200"
                    data-jenis="{{ $absen->jenis }}" data-status="{{ $absen->status }}">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div class="flex-1">
                            <!-- Header with badges -->
                            <div class="flex flex-wrap items-center gap-3 mb-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $absen->jenis == 'masuk' ? 'bg-green-100 text-green-800' :
                        ($absen->jenis == 'keluar' ? 'bg-red-100 text-red-800' :
                            ($absen->jenis == 'izin' ? 'bg-yellow-100 text-yellow-800' :
                                'bg-blue-100 text-blue-800')) }}">
                                    <i class="fas fa-{{ $absen->jenis == 'masuk' ? 'sign-in-alt' :
                        ($absen->jenis == 'keluar' ? 'sign-out-alt' :
                            ($absen->jenis == 'izin' ? 'user-clock' : 'user-injured')) }} mr-2"></i>
                                    {{ ucwords(str_replace('_', ' ', $absen->jenis)) }}
                                </span>

                                @if($absen->terlambat && $absen->jenis == 'masuk')
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>Terlambat
                                    </span>
                                @endif

                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                                                                                                    {{ $absen->status == 'valid' ? 'bg-green-100 text-green-800' :
                        ($absen->status == 'invalid' ? 'bg-red-100 text-red-800' :
                            'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($absen->status) }}
                                </span>
                            </div>

                            <!-- Date and time -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-3">
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-calendar mr-2 text-blue-500"></i>
                                    <span class="text-sm">{{ $absen->waktu_absen->format('d M Y') }}</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-clock mr-2 text-blue-500"></i>
                                    <span class="text-sm">{{ $absen->waktu_absen->format('H:i:s') }}</span>
                                </div>
                            </div>

                            <!-- Address -->
                            @if($absen->alamat)
                                <div class="flex items-start text-gray-600 mb-2">
                                    <i class="fas fa-map-marker-alt mr-2 text-blue-500 mt-0.5"></i>
                                    <span class="text-sm">{{ $absen->alamat }}</span>
                                </div>
                            @endif

                            <!-- Notes -->
                            @if($absen->keterangan)
                                <div class="flex items-start text-gray-600 mb-2">
                                    <i class="fas fa-comment mr-2 text-blue-500 mt-0.5"></i>
                                    <span class="text-sm">{{ $absen->keterangan }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Actions and coordinates -->
                        <div class="flex flex-col items-end gap-3">
                            @if($absen->foto)
                                <button onclick="viewPhoto('{{ $absen->foto }}')"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-image mr-2"></i>Lihat Foto
                                </button>
                            @endif

                            <div class="text-right">
                                <div class="flex items-center text-gray-500 text-xs">
                                    <i class="fas fa-location-arrow mr-1"></i>
                                    <span>{{ $absen->latitude }}, {{ $absen->longitude }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-center mt-6">
            {{ $absensi->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                <i class="fas fa-inbox text-2xl text-gray-400"></i>
            </div>
            <h5 class="text-gray-600 font-medium mb-2">Belum ada riwayat absensi</h5>
            <p class="text-gray-500 text-sm">Mulai absensi untuk melihat riwayat di sini</p>
        </div>
    @endif
@endsection

@push('scripts')
    <!-- Photo Modal -->
    <div id="photoModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50"
        onclick="closePhotoModal()">
        <div class="bg-white rounded-xl max-w-4xl max-h-[90vh] overflow-hidden" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Foto Absensi</h3>
                <button onclick="closePhotoModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-4 text-center">
                <img id="modal-photo" src="" alt="Foto Absensi" class="max-w-full max-h-[70vh] rounded-lg mx-auto">
            </div>
        </div>
    </div>

    <script>
        function filterByJenis() {
            const filter = document.getElementById('filter-jenis').value;
            const items = document.querySelectorAll('#attendance-list > div');

            items.forEach(item => {
                if (filter === '' || item.dataset.jenis === filter) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function filterByStatus() {
            const filter = document.getElementById('filter-status').value;
            const items = document.querySelectorAll('#attendance-list > div');

            items.forEach(item => {
                if (filter === '' || item.dataset.status === filter) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function viewPhoto(photoPath) {
            document.getElementById('modal-photo').src = '/storage/' + photoPath;
            document.getElementById('photoModal').classList.remove('hidden');
            document.getElementById('photoModal').classList.add('flex');
        }

        function closePhotoModal() {
            document.getElementById('photoModal').classList.add('hidden');
            document.getElementById('photoModal').classList.remove('flex');
        }
    </script>
@endpush