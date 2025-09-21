@extends('pegawai.layout')

@section('title', 'Riwayat Absensi')

@section('header-icon', 'history')
@section('header-title', 'Riwayat Absensi')

@section('content')
    <!-- Filter Options -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div>
            <label for="filter-jenis" class="block text-sm font-semibold text-gray-700 mb-2">Filter Jenis Absensi</label>
            <select
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                id="filter-jenis" onchange="filterByJenis()">
                <option value="">Semua Jenis</option>
                <option value="masuk">Masuk</option>
                <option value="keluar">Keluar</option>
                <option value="izin">Izin</option>
                <option value="sakit">Sakit</option>
            </select>
        </div>
        <div>
            <label for="filter-status" class="block text-sm font-semibold text-gray-700 mb-2">Filter Status</label>
            <select
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                id="filter-status" onchange="filterByStatus()">
                <option value="">Semua Status</option>
                <option value="valid">Valid</option>
                <option value="invalid">Invalid</option>
                <option value="pending">Pending</option>
            </select>
        </div>
    </div>

    <!-- Attendance List -->
    @if($absensi->count() > 0)
        <div id="attendance-list">
            @foreach($absensi as $absen)
                <div class="bg-gray-50 rounded-xl p-4 mb-3 border-l-4 border-blue-500 history-item" data-jenis="{{ $absen->jenis }}"
                    data-status="{{ $absen->status }}">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                        <div class="flex-1">
                            <div class="flex items-center mb-2 flex-wrap gap-2">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium jenis-{{ $absen->jenis }} {{ $absen->jenis == 'masuk' ? 'bg-green-100 text-green-800' : ($absen->jenis == 'keluar' ? 'bg-red-100 text-red-800' : ($absen->jenis == 'izin' ? 'bg-yellow-100 text-yellow-800' : 'bg-cyan-100 text-cyan-800')) }}">
                                    <i
                                        class="fas fa-{{ $absen->jenis == 'masuk' ? 'sign-in-alt' : ($absen->jenis == 'keluar' ? 'sign-out-alt' : ($absen->jenis == 'izin' ? 'calendar-times' : 'thermometer-half')) }} mr-1"></i>
                                    {{ ucwords(str_replace('_', ' ', $absen->jenis)) }}
                                </span>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $absen->status == 'valid' ? 'bg-green-100 text-green-800' : ($absen->status == 'invalid' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($absen->status) }}
                                </span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-2">
                                <div class="text-sm text-gray-600">
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ $absen->waktu_absen->format('d M Y') }}
                                </div>
                                <div class="text-sm text-gray-600">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $absen->waktu_absen->format('H:i:s') }}
                                </div>
                            </div>
                            @if($absen->alamat)
                                <div class="mb-2">
                                    <small class="text-gray-500">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        {{ $absen->alamat }}
                                    </small>
                                </div>
                            @endif
                            @if($absen->keterangan)
                                <div class="mb-2">
                                    <small class="text-gray-500">
                                        <i class="fas fa-comment mr-1"></i>
                                        {{ $absen->keterangan }}
                                    </small>
                                </div>
                            @endif
                        </div>
                        <div class="flex flex-col items-end mt-4 md:mt-0">
                            @if($absen->foto)
                                <button
                                    class="mb-2 bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-sm transition-colors"
                                    onclick="viewPhoto('{{ $absen->foto }}')">
                                    <i class="fas fa-image mr-1"></i>Lihat Foto
                                </button>
                            @endif
                            <div class="text-xs text-gray-500">
                                <i class="fas fa-location-arrow mr-1"></i>
                                {{ $absen->latitude }}, {{ $absen->longitude }}
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
            <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
            <h5 class="text-gray-500 text-lg mb-2">Belum ada riwayat absensi</h5>
            <p class="text-gray-400">Mulai absensi untuk melihat riwayat di sini</p>
        </div>
    @endif
@endsection

@section('navigation-links')
    <a href="{{ route('pegawai.index') }}"
        class="inline-flex items-center px-4 py-2 border border-blue-500 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
        <i class="fas fa-arrow-left mr-1"></i>Kembali ke Absensi
    </a>
@endsection

<!-- Photo Modal -->
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden" id="photoModal">
    <div class="bg-white rounded-2xl p-6 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h5 class="text-lg font-semibold">Foto Absensi</h5>
            <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closePhotoModal()">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="text-center">
            <img id="modal-photo" src="" alt="Foto Absensi" class="w-full rounded-lg">
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function filterByJenis() {
            const filter = document.getElementById('filter-jenis').value;
            const items = document.querySelectorAll('.history-item');

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
            const items = document.querySelectorAll('.history-item');

            items.forEach(item => {
                if (filter === '' || item.dataset.status === filter) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function viewPhoto(photoPath) {
            document.getElementById('modal-photo').src = '/' + photoPath;
            document.getElementById('photoModal').classList.remove('hidden');
        }

        function closePhotoModal() {
            document.getElementById('photoModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('photoModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closePhotoModal();
            }
        });
    </script>
@endpush