@extends('pegawai.layout')

@section('title', 'Daftar Klien')
@section('header-title', 'Daftar Klien')
@section('header-icon', 'users')

@section('content')
    <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-xl overflow-hidden">

        <!-- Header & Search Bar -->
        <div class="traveloka-gradient p-6 sm:p-8 flex flex-col justify-center items-center relative">
            <h1 class="text-2xl sm:text-3xl text-white font-bold mb-2">Daftar Klien</h1>
            <p class="text-white/80 text-center mb-6">Kelola dan lihat informasi klien Anda.</p>
            <div class="w-full relative">
                <input id="search-input" type="text" placeholder="Cari klien..."
                    class="w-full py-3 pl-12 pr-4 rounded-full text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-400">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="bg-white px-4 py-4 border-b border-gray-200">
            <div class="flex space-x-2 sm:space-x-4 overflow-x-auto whitespace-nowrap scrollbar-hide">
                <button
                    class="tab-button px-4 py-2 text-sm font-medium border-b-2 border-transparent transition-colors duration-200 hover:text-blue-500 active"
                    data-tab="all">Semua</button>
                <button
                    class="tab-button px-4 py-2 text-sm font-medium border-b-2 border-transparent transition-colors duration-200 hover:text-blue-500"
                    data-tab="lunas">Lunas</button>
                <button
                    class="tab-button px-4 py-2 text-sm font-medium border-b-2 border-transparent transition-colors duration-200 hover:text-blue-500"
                    data-tab="belumlunas">Belum Lunas</button>
                <button
                    class="tab-button px-4 py-2 text-sm font-medium border-b-2 border-transparent transition-colors duration-200 hover:text-blue-500"
                    data-tab="belumpks">Tertunda</button>
            </div>
        </div>

        <!-- Loading State -->
        <div id="loading-state" class="p-8 text-center">
            <div class="inline-flex items-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <span class="text-gray-600">Memuat data klien...</span>
            </div>
        </div>

        <!-- Client List -->
        <div id="client-list" class="p-4 sm:p-6 space-y-4 hidden">
            <!-- Client items will be injected here by JavaScript -->
        </div>

        <!-- Error State -->
        <div id="error-state" class="p-8 text-center hidden">
            <div class="text-red-500 mb-4">
                <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
            </div>
            <p class="text-gray-600 mb-4">Gagal memuat data klien</p>
            <button id="retry-button"
                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                Coba Lagi
            </button>
        </div>
    </div>

    <!-- Modal untuk Detail Klien -->
    <div id="client-detail-modal" class="modal-overlay hidden">
        <div class="modal-content relative">
            <button id="close-modal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <h2 id="modal-client-name" class="text-2xl font-bold mb-4"></h2>
            <div class="space-y-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Kategori</p>
                    <p id="modal-client-category" class="text-lg font-semibold text-gray-800"></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Status</p>
                    <span id="modal-client-status" class="px-3 py-1 text-xs font-semibold rounded-full"></span>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Nomor HP</p>
                    <p id="modal-client-phone" class="text-lg font-semibold text-gray-800"></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Alamat</p>
                    <p id="modal-client-address" class="text-lg font-semibold text-gray-800"></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Jumlah Proyek</p>
                    <p id="modal-client-projects" class="text-lg font-semibold text-gray-800"></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Nilai Proyek</p>
                    <p id="modal-client-total-value" class="text-lg font-semibold text-gray-800"></p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Status Proyek</p>
                    <div id="modal-client-status-counts" class="flex flex-wrap gap-2 mt-2">
                        <!-- Status counts will be populated here -->
                    </div>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3 mt-6">
                <a id="modal-call-link" href="#" target="_blank"
                    class="flex-1 w-full text-center py-3 rounded-full text-white font-semibold bg-green-600 transition-opacity hover:opacity-90">
                    Hubungi Klien
                </a>
                <a id="modal-direction-link" href="#" target="_blank"
                    class="flex-1 w-full text-center py-3 rounded-full text-white font-semibold traveloka-gradient transition-opacity hover:opacity-90">
                    Lihat di Peta
                </a>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .traveloka-gradient {
            background: linear-gradient(to right, #007bff, #0099ff);
        }

        .tab-button.active {
            color: #007bff;
            border-bottom-width: 2px;
            border-color: #007bff;
        }

        .client-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
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
            max-width: 400px;
            transform: translateY(-20px);
            transition: transform 0.3s ease;
        }

        .modal-overlay.open .modal-content {
            transform: translateY(0);
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
    </style>
@endpush

@push('js')
    <script>
        // Global variables
        let clients = [];
        const clientListContainer = document.getElementById('client-list');
        const searchInput = document.getElementById('search-input');
        const tabButtons = document.querySelectorAll('.tab-button');
        const modal = document.getElementById('client-detail-modal');
        const closeModalButton = document.getElementById('close-modal');
        const loadingState = document.getElementById('loading-state');
        const errorState = document.getElementById('error-state');
        const retryButton = document.getElementById('retry-button');

        // Format currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);
        }

        // Load clients data via AJAX
        async function loadClients() {
            try {
                showLoading();

                const response = await fetch('{{ route("pegawai.client.index") }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                clients = await response.json();
                hideLoading();
                renderClients(clients);

            } catch (error) {
                console.error('Error loading clients:', error);
                hideLoading();
                showError();
            }
        }

        // Show loading state
        function showLoading() {
            loadingState.classList.remove('hidden');
            clientListContainer.classList.add('hidden');
            errorState.classList.add('hidden');
        }

        // Hide loading state
        function hideLoading() {
            loadingState.classList.add('hidden');
            clientListContainer.classList.remove('hidden');
        }

        // Show error state
        function showError() {
            errorState.classList.remove('hidden');
            clientListContainer.classList.add('hidden');
        }

        // Hide error state
        function hideError() {
            errorState.classList.add('hidden');
        }

        // Function to determine client overall status from status_counts
        function getClientOverallStatus(statusCounts) {
            if (!statusCounts || Object.keys(statusCounts).length === 0) {
                return 'Tertunda';
            }

            // If all projects are paid (bayar), status is Lunas
            if (statusCounts.bayar && !statusCounts.kurang && !statusCounts.pending) {
                return 'Lunas';
            }

            // If there are unpaid projects (kurang), status is Belum Lunas
            if (statusCounts.kurang) {
                return 'Belum Lunas';
            }

            // Default to Tertunda
            return 'Tertunda';
        }

        // Function to create status count badges
        function createStatusCountBadges(statusCounts) {
            if (!statusCounts) return '';

            const statusLabels = {
                'bayar': { label: 'Lunas', class: 'bg-green-100 text-green-700' },
                'kurang': { label: 'Kurang Bayar', class: 'bg-red-100 text-red-700' },
                'pending': { label: 'Pending', class: 'bg-yellow-100 text-yellow-700' }
            };

            return Object.entries(statusCounts)
                .map(([status, count]) => {
                    const config = statusLabels[status] || { label: status, class: 'bg-gray-100 text-gray-700' };
                    return `<span class="px-2 py-1 text-xs font-medium rounded-full ${config.class}">
                                    ${config.label}: ${count}
                                </span>`;
                })
                .join('');
        }

        // Fungsi untuk membuat HTML untuk satu item klien
        function createClientItem(client) {
            const overallStatus = getClientOverallStatus(client.status_counts);
            const statusClass = {
                'Lunas': 'bg-green-100 text-green-700',
                'Belum Lunas': 'bg-red-100 text-red-700',
                'Tertunda': 'bg-yellow-100 text-yellow-700'
            }[overallStatus] || 'bg-gray-100 text-gray-700';

            return `
                            <div class="client-item flex items-center bg-gray-50 p-4 rounded-xl shadow-sm transition-all duration-300 cursor-pointer" data-client-id="${client.id}">
                                <img src="{{ asset('') }}${client.logo}" alt="Logo ${client.nama}" class="h-12 w-12 rounded-full mr-4 object-cover">
                                <div class="flex-1 min-w-0">
                                    <div class="font-semibold text-lg text-gray-800 truncate">${client.nama}</div>
                                    <div class="text-sm text-gray-500 truncate">${client.type}</div>
                                </div>
                                <div class="flex-shrink-0 ml-4">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full ${statusClass}">
                                        ${overallStatus}
                                    </span>
                                </div>
                            </div>
                        `;
        }

        // Fungsi untuk merender daftar klien berdasarkan filter
        function renderClients(filteredClients) {
            if (filteredClients.length === 0) {
                clientListContainer.innerHTML = '<p class="text-center text-gray-500 mt-8">Tidak ada klien yang cocok dengan kriteria Anda.</p>';
                return;
            }
            clientListContainer.innerHTML = filteredClients.map(createClientItem).join('');
        }

        // Fungsi untuk memfilter klien
        function filterClients(searchTerm, status) {
            return clients.filter(client => {
                const matchesSearch = client.nama.toLowerCase().includes(searchTerm.toLowerCase()) ||
                    client.type.toLowerCase().includes(searchTerm.toLowerCase()) ||
                    client.industri.toLowerCase().includes(searchTerm.toLowerCase());

                const clientStatus = getClientOverallStatus(client.status_counts);
                const matchesStatus = status === 'all' || clientStatus.toLowerCase().replace(/\s/g, '') === status;
                return matchesSearch && matchesStatus;
            });
        }

        // Fungsi untuk menampilkan modal dengan detail klien
        function showClientDetails(clientId) {
            const client = clients.find(c => c.id === parseInt(clientId));
            if (!client) return;

            document.getElementById('modal-client-name').textContent = client.nama;
            document.getElementById('modal-client-category').textContent = client.industri || client.type;

            const overallStatus = getClientOverallStatus(client.status_counts);
            const statusEl = document.getElementById('modal-client-status');
            statusEl.textContent = overallStatus;
            statusEl.className = 'px-3 py-1 text-xs font-semibold rounded-full ' + ({
                'Lunas': 'bg-green-100 text-green-700',
                'Belum Lunas': 'bg-red-100 text-red-700',
                'Tertunda': 'bg-yellow-100 text-yellow-700'
            }[overallStatus] || 'bg-gray-100 text-gray-700');

            document.getElementById('modal-client-phone').textContent = client.telepon;
            document.getElementById('modal-client-address').textContent = client.alamat;
            document.getElementById('modal-client-projects').textContent = client.projects_count;

            // Calculate total value from projects
            const totalValue = client.projects ? client.projects.reduce((sum, project) => sum + project.harga, 0) : 0;
            document.getElementById('modal-client-total-value').textContent = formatCurrency(totalValue);

            // Display status counts
            const statusCountsEl = document.getElementById('modal-client-status-counts');
            statusCountsEl.innerHTML = createStatusCountBadges(client.status_counts);

            const mapsUrl = client.maps || `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(client.alamat)}`;
            document.getElementById('modal-direction-link').href = mapsUrl;

            // Update the "Hubungi Klien" link
            const callUrl = `https://wa.me/${client.telepon}`;
            document.getElementById('modal-call-link').href = callUrl;

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

        // Event Listeners
        document.addEventListener('DOMContentLoaded', function () {
            // Load clients on page load
            loadClients();

            // Mengelola klik pada item klien
            clientListContainer.addEventListener('click', (e) => {
                const clientItem = e.target.closest('.client-item');
                if (clientItem) {
                    const clientId = clientItem.dataset.clientId;
                    showClientDetails(clientId);
                }
            });

            // Mengelola klik tombol dan area di luar modal untuk menutup
            closeModalButton.addEventListener('click', hideModal);
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    hideModal();
                }
            });

            // Mengelola perubahan tab
            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');
                    const searchTerm = searchInput.value;
                    const statusFilter = button.dataset.tab;
                    const filtered = filterClients(searchTerm, statusFilter);
                    renderClients(filtered);
                });
            });

            // Mengelola input pencarian
            searchInput.addEventListener('input', (e) => {
                const searchTerm = e.target.value;
                const activeTab = document.querySelector('.tab-button.active').dataset.tab;
                const filtered = filterClients(searchTerm, activeTab);
                renderClients(filtered);
            });

            // Retry button
            retryButton.addEventListener('click', () => {
                hideError();
                loadClients();
            });
        });
    </script>
@endpush