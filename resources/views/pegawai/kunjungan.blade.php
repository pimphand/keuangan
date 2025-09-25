@extends('pegawai.layout')

@section('title', 'Kunjungan Kerja')
@section('header-title', 'Kunjungan Kerja')
@section('header-icon', 'briefcase')

@section('content')
    <div class="space-y-4">

        @if (session('success'))
            <div class="p-3 rounded-md bg-green-50 text-green-700 border border-green-200">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="p-3 rounded-md bg-red-50 text-red-700 border border-red-200">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3">
            <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 flex-1">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Mulai</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                        class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Selesai</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                        class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                </div>
                <div class="md:col-span-1 col-span-2">
                    <label class="block text-sm text-gray-600 mb-1">Client</label>
                    <input type="text" name="client" placeholder="Cari client" value="{{ request('client') }}"
                        class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                </div>
                <div class="flex items-end gap-2 col-span-2 md:col-span-1">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">
                        <i class="fas fa-search"></i>
                        <span>Filter</span>
                    </button>
                    <a href="{{ route('pegawai.kunjungan') }}"
                        class="px-3 py-2 rounded-md border text-gray-700 hover:bg-gray-50">Reset</a>
                </div>
            </form>

            <button id="openModalBtn" type="button"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-purple-600 text-white hover:bg-purple-700 self-start md:self-auto">
                <i class="fas fa-plus"></i>
                <span>Tambah Kunjungan</span>
            </button>
        </div>

        <div class="space-y-3">
            @forelse ($kunjungans as $k)
                <div class="p-3 bg-white rounded-lg shadow-sm border">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="text-sm font-semibold text-gray-800">
                                {{ \Carbon\Carbon::parse($k->tanggal_kunjungan)->format('d M Y') }} — {{ $k->client }}
                            </div>
                            <div class="text-xs text-gray-600 mt-1 line-clamp-2">{{ $k->ringkasan }}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-location-dot mr-1"></i>{{ $k->lokasi }}
                            </div>
                        </div>
                        @if (!empty($k->foto))
                            <button type="button"
                                class="btn-view-foto inline-flex items-center gap-2 px-3 py-1.5 rounded-md bg-indigo-600 text-white hover:bg-indigo-700 text-xs"
                                data-foto="{{ asset($k->foto) }}">
                                <i class="fas fa-image"></i>
                                <span>Lihat Foto</span>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-sm text-gray-500">Belum ada data kunjungan.</div>
            @endforelse
        </div>

        <div>
            {{ $kunjungans->appends(request()->query())->links() }}
        </div>
    </div>

    <!-- Modal -->
    <div id="modalBackdrop" class="fixed inset-0 bg-black/40 hidden z-[55]"></div>
    <div id="modalCreate" class="fixed inset-0 hidden z-[60] flex items-end md:items-center md:justify-center">
        <div
            class="bg-white w-full md:max-w-xl md:rounded-xl md:shadow-2xl md:mx-auto p-5 rounded-t-2xl max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-3">
                <h5 class="font-semibold text-gray-800">Tambah Kunjungan</h5>
                <button id="closeModalBtn" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('pegawai.kunjungan.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Tanggal Kunjungan</label>
                    <input type="date" name="tanggal_kunjungan" value="{{ old('tanggal_kunjungan') }}" required
                        class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Client</label>
                    <input type="text" name="client" value="{{ old('client') }}" placeholder="Nama client" required
                        class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Lokasi</label>
                    <input type="text" name="lokasi" value="{{ old('lokasi') }}" placeholder="Lokasi kunjungan" required
                        class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Foto Kunjungan</label>
                    <input type="file" name="foto" accept="image/*"
                        class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG/PNG, maks 2MB. Akan dikonversi ke WebP.</p>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Ringkasan</label>
                    <textarea name="ringkasan" rows="4" placeholder="Ringkas kegiatan/hasil kunjungan" required
                        class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">{{ old('ringkasan') }}</textarea>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" id="cancelModalBtn"
                        class="px-4 py-2 rounded-md border text-gray-700 hover:bg-gray-50">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 rounded-md bg-purple-600 text-white hover:bg-purple-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Photo Modal -->
    <div id="modalPhotoBackdrop" class="fixed inset-0 bg-black/60 hidden z-[65]"></div>
    <div id="modalPhoto" class="fixed inset-0 hidden z-[70] flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full overflow-hidden">
            <div class="flex items-center justify-between px-4 py-3 border-b">
                <h6 class="font-semibold text-gray-800">Foto Kunjungan</h6>
                <button id="closePhotoBtn" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-4">
                <img id="photoPreview" src="" alt="Foto Kunjungan" class="w-full h-auto rounded-lg">
            </div>
        </div>
    </div>
@endsection

@push('styles')
@endpush
@push('js')
    <script>
        const openBtn = document.getElementById('openModalBtn');
        const closeBtn = document.getElementById('closeModalBtn');
        const cancelBtn = document.getElementById('cancelModalBtn');
        const modal = document.getElementById('modalCreate');
        const backdrop = document.getElementById('modalBackdrop');

        function openModal() {
            modal.classList.remove('hidden');
            backdrop.classList.remove('hidden');
        }
        function closeModal() {
            modal.classList.add('hidden');
            backdrop.classList.add('hidden');
        }

        openBtn?.addEventListener('click', openModal);
        closeBtn?.addEventListener('click', closeModal);
        cancelBtn?.addEventListener('click', closeModal);
        backdrop?.addEventListener('click', closeModal);

        // Date constraints for filters
        const fromInput = document.querySelector('input[name="date_from"]');
        const toInput = document.querySelector('input[name="date_to"]');
        const today = new Date();
        const toYYYYMMDD = (d) => {
            const yyyy = d.getFullYear();
            const mm = String(d.getMonth() + 1).padStart(2, '0');
            const dd = String(d.getDate()).padStart(2, '0');
            return `${yyyy}-${mm}-${dd}`;
        };
        const todayStr = toYYYYMMDD(today);
        if (fromInput) {
            fromInput.setAttribute('max', todayStr);
            fromInput.addEventListener('change', () => {
                if (toInput) {
                    toInput.setAttribute('min', fromInput.value || '');
                    if (toInput.value && fromInput.value && toInput.value < fromInput.value) {
                        toInput.value = fromInput.value;
                    }
                }
            });
        }
        if (toInput) {
            toInput.setAttribute('max', todayStr);
            toInput.addEventListener('change', () => {
                if (fromInput && toInput.value && fromInput.value && toInput.value < fromInput.value) {
                    toInput.value = fromInput.value;
                }
            });
        }

        // Photo modal handlers
        const photoModal = document.getElementById('modalPhoto');
        const photoBackdrop = document.getElementById('modalPhotoBackdrop');
        const closePhotoBtn = document.getElementById('closePhotoBtn');
        const photoPreview = document.getElementById('photoPreview');

        function openPhoto(src) {
            photoPreview.src = src;
            photoModal.classList.remove('hidden');
            photoBackdrop.classList.remove('hidden');
        }
        function closePhoto() {
            photoModal.classList.add('hidden');
            photoBackdrop.classList.add('hidden');
            photoPreview.src = '';
        }

        document.querySelectorAll('.btn-view-foto').forEach(btn => {
            btn.addEventListener('click', () => {
                const src = btn.getAttribute('data-foto');
                if (src) openPhoto(src);
            });
        });
        closePhotoBtn?.addEventListener('click', closePhoto);
        photoBackdrop?.addEventListener('click', closePhoto);
    </script>
@endpush