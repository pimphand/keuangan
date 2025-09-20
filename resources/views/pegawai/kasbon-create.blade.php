@extends('pegawai.layout')

@section('title', 'Ajukan Kasbon')
@section('header-title', 'Ajukan Kasbon')
@section('header-icon', 'plus')

@section('content')
    <div class="space-y-6">
        <!-- Back Button -->
        <div class="flex items-center space-x-3">
            <a href="{{ route('pegawai.kasbon') }}"
                class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left text-gray-600"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-gray-800">Ajukan Kasbon</h1>
                <p class="text-sm text-gray-600">Isi form di bawah ini untuk mengajukan kasbon</p>
            </div>
        </div>

        <!-- Saldo Info Card -->
        <div class="bg-gradient-to-br from-purple-600 to-purple-700 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-sm font-medium opacity-90 mb-1">Saldo Kasbon Tersedia</h2>
                    <p class="text-2xl font-bold">Rp {{ number_format($user->kasbon, 0, ',', '.') }}</p>
                </div>
                <div class="text-right">
                    <i class="fas fa-wallet text-3xl opacity-50"></i>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                        <span class="text-red-700 text-sm">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <form action="{{ route('kasbon.store') }}" method="POST" id="kasbonForm">
                @csrf

                <!-- Nominal Input -->
                <div class="mb-6">
                    <label for="nominal" class="block text-sm font-medium text-gray-700 mb-2">
                        Nominal Kasbon <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 text-lg">Rp</span>
                        </div>
                        <input type="text" id="nominal" name="nominal" value="{{ old('nominal') }}"
                            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('nominal') border-red-500 @enderror"
                            placeholder="Masukkan nominal kasbon" required>
                    </div>
                    @error('nominal')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">
                        Maksimal: Rp {{ number_format($user->kasbon, 0, ',', '.') }}
                    </p>
                </div>

                <!-- Keterangan Input -->
                <div class="mb-6">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                        Keterangan <span class="text-red-500">*</span>
                    </label>
                    <textarea id="keterangan" name="keterangan" rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('keterangan') border-red-500 @enderror"
                        placeholder="Jelaskan alasan pengajuan kasbon" required>{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">
                        Maksimal 255 karakter
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-3">
                    <a href="{{ route('pegawai.kasbon') }}"
                        class="flex-1 bg-gray-100 text-gray-700 py-3 px-4 rounded-lg text-center font-medium hover:bg-gray-200 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="flex-1 bg-purple-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-purple-700 transition-colors flex items-center justify-center space-x-2">
                        <i class="fas fa-paper-plane"></i>
                        <span>Ajukan Kasbon</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Card -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start space-x-3">
                <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                <div>
                    <h3 class="text-sm font-medium text-blue-800 mb-1">Informasi Penting</h3>
                    <ul class="text-xs text-blue-700 space-y-1">
                        <li>• Pengajuan kasbon akan diproses oleh admin</li>
                        <li>• Pastikan nominal tidak melebihi saldo kasbon tersedia</li>
                        <li>• Berikan keterangan yang jelas dan lengkap</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const nominalInput = document.getElementById('nominal');
            const maxNominal = {{ $user->kasbon }};
            const form = document.getElementById('kasbonForm');

            // Handle input formatting - single event listener
            nominalInput.addEventListener('input', function () {
                // Only allow numbers and one decimal point
                let value = this.value.replace(/[^\d.]/g, '');

                // Ensure only one decimal point
                const parts = value.split('.');
                if (parts.length > 2) {
                    value = parts[0] + '.' + parts.slice(1).join('');
                }

                // Update the input value
                this.value = value;

                // Validate range
                const numericValue = parseFloat(value) || 0;
                if (numericValue > maxNominal) {
                    this.setCustomValidity('Nominal tidak boleh melebihi saldo kasbon yang tersedia');
                    this.style.borderColor = '#ef4444';
                } else if (numericValue <= 0) {
                    this.setCustomValidity('Nominal harus lebih dari 0');
                    this.style.borderColor = '#ef4444';
                } else {
                    this.setCustomValidity('');
                    this.style.borderColor = '#d1d5db';
                }
            });

            // Validasi saat submit
            form.addEventListener('submit', function (e) {
                // Clean the input value before validation
                let cleanValue = nominalInput.value.replace(/[^\d.]/g, '');

                // Remove thousand separators but keep decimal point
                cleanValue = cleanValue.replace(/\.(?=\d{3})/g, '');

                const nominal = parseFloat(cleanValue);

                if (isNaN(nominal) || nominal <= 0) {
                    e.preventDefault();
                    alert('Nominal harus lebih dari 0');
                    return false;
                }

                if (nominal > maxNominal) {
                    e.preventDefault();
                    alert('Nominal tidak boleh melebihi saldo kasbon yang tersedia');
                    return false;
                }

                // Set the clean value back to the input for form submission
                nominalInput.value = cleanValue;
            });

            // Format display with thousand separators on blur
            nominalInput.addEventListener('blur', function () {
                const value = parseFloat(this.value);
                if (!isNaN(value) && value > 0) {
                    // Format with thousand separators
                    this.value = value.toLocaleString('id-ID');
                }
            });

            // Remove formatting on focus
            nominalInput.addEventListener('focus', function () {
                // Remove thousand separators, keep only numbers and decimal point
                let value = this.value.replace(/[^\d.]/g, '');
                this.value = value;
            });
        });
    </script>
@endsection