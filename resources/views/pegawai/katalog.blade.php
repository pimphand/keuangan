@extends('pegawai.layout')

@section('title', 'Ekatalog Brosur')
@section('header-title', 'Ekatalog Brosur')
@section('header-icon', 'book-open')

@section('content')
    <div class="mx-auto bg-white rounded-3xl shadow-xl overflow-hidden">

        <!-- Halaman Utama (Daftar Brosur) -->
        <div id="main-page" class="page active">
            <!-- Header & Search Bar -->
            <div class="purple-gradient p-6 sm:p-8 flex flex-col justify-center items-center relative">
                <h1 class="text-2xl sm:text-3xl text-white font-bold mb-2">Ekatalog Brosur</h1>
                <p class="text-white/80 text-center mb-6">Temukan brosur terbaru dari berbagai produk.</p>
                <div class="w-full relative">
                    <input id="search-input" type="text" placeholder="Cari brosur..."
                        class="w-full py-3 pl-12 pr-4 rounded-full text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-purple-400">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <!-- Navigasi Tab -->
            <div class="bg-white px-4 py-4 border-b border-gray-200">
                <div class="flex space-x-2 sm:space-x-4 overflow-x-auto whitespace-nowrap scrollbar-hide">
                    <button
                        class="tab-button px-4 py-2 text-sm font-medium border-b-2 border-transparent transition-colors duration-200 hover:text-purple-500 active"
                        data-tab="all">Semua</button>
                    <button
                        class="tab-button px-4 py-2 text-sm font-medium border-b-2 border-transparent transition-colors duration-200 hover:text-purple-500"
                        data-tab="terbaru">Terbaru</button>
                    <button
                        class="tab-button px-4 py-2 text-sm font-medium border-b-2 border-transparent transition-colors duration-200 hover:text-purple-500"
                        data-tab="terpopuler">Terpopuler</button>
                    <button
                        class="tab-button px-4 py-2 text-sm font-medium border-b-2 border-transparent transition-colors duration-200 hover:text-purple-500"
                        data-tab="promo">Promo</button>
                </div>
            </div>

            <!-- Daftar Brosur (List Layout) -->
            <div id="brochure-list" class="p-4 sm:p-6 space-y-4">
                <!-- Item brosur akan dimasukkan di sini oleh JavaScript -->
            </div>
        </div>

        <!-- Halaman Detail Produk -->
        <div id="detail-page" class="page hidden p-6 sm:p-8 relative">
            <button id="back-button" class="absolute top-4 left-4 text-gray-500 hover:text-gray-700 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <div id="detail-content" class="mt-8">
                <!-- Konten detail akan dimasukkan di sini oleh JavaScript -->
            </div>
        </div>
    </div>

    <!-- Modal untuk Konfirmasi Unduh -->
    <div id="brochure-detail-modal" class="modal-overlay hidden">
        <div class="modal-content relative">
            <button id="close-modal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <div id="modal-content-body">
                <!-- Konten akan dimasukkan di sini -->
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* CSS Kustom untuk nuansa ungu */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f2f5;
        }

        .purple-gradient {
            background: linear-gradient(to right, #8A2BE2, #9400D3);
        }

        .brochure-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .brochure-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        .tab-button.active {
            color: #8A2BE2;
            border-bottom-width: 2px;
            border-color: #8A2BE2;
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .modal-overlay.open {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background-color: white;
            padding: 1.5rem;
            border-radius: 1rem;
            width: 90%;
            max-width: 600px;
            transform: translateY(-20px);
            transition: transform 0.3s ease;
        }

        .modal-overlay.open .modal-content {
            transform: translateY(0);
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }

        .page {
            display: none;
        }

        .page.active {
            display: block;
        }

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endpush
@push('js')
    <script>
        // Global variables
        let brochures = [];
        let currentPage = 1;
        let isLoading = false;

        const brochureListContainer = document.getElementById('brochure-list');
        const searchInput = document.getElementById('search-input');
        const tabButtons = document.querySelectorAll('.tab-button');
        const modal = document.getElementById('brochure-detail-modal');
        const closeModalButton = document.getElementById('close-modal');
        const modalContentBody = document.getElementById('modal-content-body');
        const mainPage = document.getElementById('main-page');
        const detailPage = document.getElementById('detail-page');
        const detailContent = document.getElementById('detail-content');
        const backButton = document.getElementById('back-button');

        // Fungsi untuk mengambil data brosur dari backend
        async function fetchBrochures(searchTerm = '', category = 'all', page = 1) {
            if (isLoading) return;

            isLoading = true;
            showLoading();

            try {
                const params = new URLSearchParams({
                    search: searchTerm,
                    kategori: category,
                    page: page
                });

                const response = await fetch(`{{ route('pegawai.katalog.index') }}?${params}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();
                brochures = data.data || [];

                renderBrochures(brochures);

                currentPage = data.current_page || 1;

            } catch (error) {
                console.error('Error fetching brochures:', error);
                showError('Gagal memuat data brosur. Silakan coba lagi.');
            } finally {
                isLoading = false;
                hideLoading();
            }
        }

        // Fungsi untuk menampilkan loading
        function showLoading() {
            brochureListContainer.innerHTML = `
                                                    <div class="flex justify-center items-center py-12">
                                                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-600"></div>
                                                        <span class="ml-3 text-gray-600">Memuat brosur...</span>
                                                    </div>
                                                `;
        }

        // Fungsi untuk menyembunyikan loading
        function hideLoading() {
            // Loading akan diganti oleh renderBrochures
        }

        // Fungsi untuk menampilkan error
        function showError(message) {
            brochureListContainer.innerHTML = `
                                                    <div class="text-center py-12">
                                                        <div class="text-red-500 mb-4">
                                                            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                            </svg>
                                                        </div>
                                                        <p class="text-gray-600">${message}</p>
                                                    </div>
                                                `;
        }

        // Fungsi untuk membuat HTML untuk satu item brosur
        function createBrochureCard(brochure) {
            const imageUrl = brochure.gambar ? `{{ asset('gambar/brosur') }}/${brochure.gambar}` : 'https://placehold.co/300x200/8A2BE2/ffffff?text=No+Image';
            const downloadLink = brochure.file ? `{{ asset('gambar/brosur') }}/${brochure.file}` : '#';

            // Generate tag badges
            let tagBadges = '';
            if (brochure.tag && Array.isArray(brochure.tag)) {
                tagBadges = brochure.tag.map(tag =>
                    `<span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-700 mr-1 mb-1">${tag}</span>`
                ).join('');
            }

            return `
                                                    <div class="brochure-card bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300" data-brochure-id="${brochure.id}">
                                                        <div class="flex flex-col sm:flex-row">
                                                            <!-- Image Section -->
                                                            <div class="sm:w-1/4 w-full">
                                                                <img src="${imageUrl}" alt="Gambar ${brochure.nama}" class="w-full h-48 sm:h-full object-cover">
                                                            </div>

                                                            <!-- Content Section -->
                                                            <div class="sm:w-3/4 w-full p-6 flex flex-col justify-between">
                                                                <div class="flex-1">
                                                                    <h3 class="font-bold text-xl text-gray-800 mb-2">${brochure.nama}</h3>
                                                                    <p class="text-gray-600 mb-4 line-clamp-3">${brochure.deskripsi || 'Tidak ada deskripsi'}</p>
                                                                    ${tagBadges ? `<div class="mb-4">${tagBadges}</div>` : ''}
                                                                </div>

                                                                <!-- Bottom Section with Price and Button -->
                                                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pt-4 border-t border-gray-100">
                                                                    <div class="mb-4 sm:mb-0">
                                                                        ${brochure.harga ? `
                                                                            <span class="text-sm font-semibold text-gray-600">Harga:</span>
                                                                            <p class="text-xl font-bold text-purple-600">Rp ${formatNumber(brochure.harga)}</p>
                                                                        ` : ''}
                                                                    </div>
                                                                    <div class="flex space-x-3">
                                                                        <button onclick="showBrochureDetails(${brochure.id})" class="px-4 py-2 text-purple-700 font-semibold border border-purple-700 rounded-lg transition-colors hover:bg-purple-50 text-sm">
                                                                            Lihat Detail
                                                                        </button>
                                                                        <a href="${downloadLink}" class="px-4 py-2 text-center text-white font-semibold purple-gradient rounded-lg transition-opacity hover:opacity-90 text-sm">
                                                                            Unduh Brosur
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                                </div>
                                                            </div>
                                                        `;
        }

        // Fungsi untuk format angka
        function formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }

        // Fungsi untuk merender daftar brosur berdasarkan filter
        function renderBrochures(filteredBrochures) {
            if (filteredBrochures.length === 0) {
                brochureListContainer.innerHTML = '<p class="text-center text-gray-500 mt-8">Tidak ada brosur yang cocok dengan kriteria Anda.</p>';
                return;
            }
            brochureListContainer.innerHTML = filteredBrochures.map(createBrochureCard).join('');
        }

        // Fungsi untuk menampilkan modal konfirmasi unduh
        function showDownloadModal(brochure) {
            const imageUrl = brochure.gambar ? `{{ asset('gambar/brosur') }}/${brochure.gambar}` : 'https://placehold.co/600x400/8A2BE2/ffffff?text=No+Image';
            const downloadLink = brochure.file ? `{{ asset('gambar/brosur') }}/${brochure.file}` : '#';

            modalContentBody.innerHTML = `
                                                                                                                                <img src="${imageUrl}" alt="Gambar ${brochure.nama}" class=" rounded-lg mb-4">
                                                                                                                                <h2 class="text-2xl font-bold text-gray-900 mb-2">${brochure.nama}</h2>
                                                                                                                                <p class="text-gray-600 mt-4">${brochure.deskripsi || 'Tidak ada deskripsi'}</p>
                                                                                                                                ${brochure.harga ? `<p class="text-lg font-bold text-purple-600 mt-4">Rp ${formatNumber(brochure.harga)}</p>` : ''}
                                                            <div class="flex justify-center mt-6">
                                                                                                                                    <a href="${downloadLink}" class="text-center py-2 px-4 rounded-full text-white font-semibold purple-gradient transition-opacity hover:opacity-90 text-sm">
                                                                    Unduh Brosur
                                                                </a>
                                                            </div>
                                                        `;
            modal.classList.add('open');
            modal.classList.remove('hidden');
        }

        // Fungsi untuk menyembunyikan modal
        function hideModal() {
            modal.classList.remove('open');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Fungsi untuk menampilkan halaman detail produk
        function showDetailPage(brochureId) {
            const brochure = brochures.find(b => b.id === parseInt(brochureId));
            if (!brochure) return;

            const imageUrl = brochure.gambar ? `{{ asset('gambar/brosur') }}/${brochure.gambar}` : 'https://placehold.co/600x400/8A2BE2/ffffff?text=No+Image';
            const downloadLink = brochure.file ? `{{ asset('gambar/brosur') }}/${brochure.file}` : '#';

            // Generate tag badges for detail page
            let tagBadges = '';
            if (brochure.tag && Array.isArray(brochure.tag)) {
                tagBadges = brochure.tag.map(tag =>
                    `<span class="inline-block px-3 py-1 text-sm font-semibold rounded-full bg-purple-100 text-purple-700 mr-2 mb-2">${tag}</span>`
                ).join('');
            }

            let specsHtml = '';
            if (brochure.spesifikasi && Array.isArray(brochure.spesifikasi)) {
                specsHtml = brochure.spesifikasi.map(spec => `<li class="text-gray-600">${spec}</li>`).join('');
            }

            detailContent.innerHTML = `
                                                                                                <h1 class="text-3xl font-bold text-gray-900 mb-4">${brochure.nama}</h1>
                                                                                                <img src="${imageUrl}" alt="Gambar ${brochure.nama}" class=" h-64 object-cover rounded-lg mb-6">
                                                                                                ${tagBadges ? `<div class="mb-6">${tagBadges}</div>` : ''}
                                                                                                <p class="text-gray-700 leading-relaxed mb-6">${brochure.deskripsi || 'Tidak ada deskripsi'}</p>

                                                                                                ${specsHtml ? `
                                                            <div class="bg-gray-50 p-6 rounded-xl">
                                                                <h2 class="text-xl font-bold text-gray-800 mb-4">Spesifikasi</h2>
                                                                <ul class="list-disc list-inside space-y-2">
                                                                    ${specsHtml}
                                                                </ul>
                                                            </div>
                                                                                                ` : ''}

                                                            <div class="mt-6 flex justify-between items-center bg-gray-50 p-6 rounded-xl">
                                                                <div class="text-gray-800">
                                                                                                        ${brochure.harga ? `
                                                                    <span class="text-sm font-semibold">Harga:</span>
                                                                                                        <p class="text-sm font-bold text-purple-600"> Mulai dari Rp ${formatNumber(brochure.harga)},- (tergantung paket dan fitur tambahan)</p>
                                                                                                        ` : ''}
                                                                </div>
                                                                <div class="flex space-x-4">
                                                                                             <a href="${downloadLink}" class="text-center py-2 px-4 rounded-full text-white font-semibold purple-gradient transition-opacity hover:opacity-90 text-sm">
                                                                        Unduh Brosur
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        `;

            mainPage.classList.remove('active');
            detailPage.classList.add('active');
        }

        // Mengelola klik pada item brosur
        brochureListContainer.addEventListener('click', (e) => {
            const brochureCard = e.target.closest('.brochure-card');
            if (brochureCard) {
                const brochureId = brochureCard.dataset.brochureId;
                showBrochureDetails(brochureId);
            }
        });

        // Mengelola klik tombol dan area di luar modal untuk menutup
        closeModalButton.addEventListener('click', hideModal);
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                hideModal();
            }
        });

        // Mengelola klik tombol "Lihat Detail" di modal
        function showBrochureDetails(brochureId) {
            const brochure = brochures.find(b => b.id === parseInt(brochureId));
            if (!brochure) return;

            const imageUrl = brochure.gambar ? `{{ asset('gambar/brosur') }}/${brochure.gambar}` : 'https://placehold.co/600x400/8A2BE2/ffffff?text=No+Image';
            const downloadLink = brochure.file ? `{{ asset('gambar/brosur') }}/${brochure.file}` : '#';

            // Generate tag badges for modal
            let tagBadges = '';
            if (brochure.tag && Array.isArray(brochure.tag)) {
                tagBadges = brochure.tag.map(tag =>
                    `<span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-700 mr-1 mb-1">${tag}</span>`
                ).join('');
            }

            modalContentBody.innerHTML = `
                                                                                                    <img src="${imageUrl}" alt="Gambar ${brochure.nama}" class=" rounded-lg mb-4">
                                                                                                    <h2 class="text-2xl font-bold text-gray-900 mb-2">${brochure.nama}</h2>
                                                                                                    ${tagBadges ? `<div class="mb-4">${tagBadges}</div>` : ''}
                                                                                                    <p class="text-gray-600 mt-4">${brochure.deskripsi || 'Tidak ada deskripsi'}</p>
                                                                                                    ${brochure.harga ? `<p class="text-lg font-bold text-purple-600 mt-4">Rp ${formatNumber(brochure.harga)}</p>` : ''}
                                                            <div class="flex space-x-4 mt-6">
                                                                                                     <button onclick="showDetailPage(${brochure.id}); hideModal();" class="flex-1 text-center py-2 px-4 rounded-full text-purple-700 font-semibold border border-purple-700 transition-colors hover:bg-purple-50 text-sm">
                                                                    Lihat Detail
                                                                </button>
                                                                                                     <a href="${downloadLink}" class="flex-1 text-center py-2 px-4 rounded-full text-white font-semibold purple-gradient transition-opacity hover:opacity-90 text-sm">
                                                                    Unduh Brosur
                                                                </a>
                                                            </div>
                                                        `;
            modal.classList.add('open');
            modal.classList.remove('hidden');
        }

        // Mengelola klik tombol "Kembali"
        backButton.addEventListener('click', () => {
            detailPage.classList.remove('active');
            mainPage.classList.add('active');
        });

        // Mengelola perubahan tab
        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                tabButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                const searchTerm = searchInput.value;
                const categoryFilter = button.dataset.tab;
                fetchBrochures(searchTerm, categoryFilter, 1);
            });
        });

        // Mengelola input pencarian dengan debounce
        let searchTimeout;
        searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const searchTerm = e.target.value;
                const activeTab = document.querySelector('.tab-button.active').dataset.tab;
                fetchBrochures(searchTerm, activeTab, 1);
            }, 500); // Debounce 500ms
        });

        // Render brosur awal saat halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            fetchBrochures('', 'all', 1);
        });
    </script>
@endpush