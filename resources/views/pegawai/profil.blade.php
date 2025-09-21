@extends('pegawai.layout')

@section('title', 'Profil')
@section('header-title', 'Profil')
@section('header-icon', 'user-circle')
@section('header-subtitle', 'Kelola informasi pribadi Anda')

@section('content')
    <style>
        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            width: 100%;
        }

        .signature-table {
            width: 100%;
            text-align: center;
            margin-top: 40px;
            table-layout: fixed;
        }

        .signature-table td {
            vertical-align: bottom;
            padding: 0 20px;
        }

        .signature-title {
            font-size: 11pt;
            margin-bottom: 50px;
            color: #2c3e50;
        }

        .signature-space {
            height: 60px;
        }

        .signature-name {
            font-size: 11pt;
            font-weight: bold;
            color: #2c3e50;
            border-top: 1px solid #333;
            padding-top: 5px;
        }

        @media print {
            .signature-section {
                page-break-inside: avoid;
                margin-top: 50px;
                display: flex;
                justify-content: space-between;
                align-items: flex-end;
                width: 100%;
            }
        }
    </style>

    <div class="space-y-6">
        <!-- Profile Photo Section -->
        <div class="text-center">
            <div class="relative inline-block">
                <div class="w-24 h-24 mx-auto rounded-full overflow-hidden border-4 border-blue-200">
                    @if($user->photo)
                        <img src="{{ Storage::url($user->photo) }}" alt="Profile Photo" class="w-full h-full object-cover"
                            id="profile-photo-preview">
                    @else
                        <div class="w-full h-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-user text-3xl text-blue-400"></i>
                        </div>
                    @endif
                </div>
                <label for="photo"
                    class="absolute bottom-0 right-0 bg-blue-500 text-white rounded-full p-2 cursor-pointer hover:bg-blue-600 transition-colors">
                    <i class="fas fa-camera text-sm"></i>
                </label>
            </div>
            <p class="text-sm text-gray-500 mt-2">Klik untuk mengubah foto profil</p>
        </div>

        <!-- Profile Form -->
        <form id="profile-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Hidden file input for photo -->
            <input type="file" id="photo" name="photo" accept="image/*" class="hidden" onchange="previewPhoto(this)">

            <div class="space-y-4">
                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-2 text-blue-500"></i>Nama Lengkap
                    </label>
                    <input type="text" id="name" name="name" value="{{ $user->name }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                        placeholder="Masukkan nama lengkap">
                    <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2 text-blue-500"></i>Email
                    </label>
                    <input type="email" id="email" name="email" value="{{ $user->email }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                        placeholder="Masukkan email">
                    <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                </div>

                <!-- Phone Field -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-phone mr-2 text-blue-500"></i>Nomor Telepon
                    </label>
                    <input type="text" id="phone" name="phone" value="{{ $user->phone ?? '' }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                        placeholder="Masukkan nomor telepon">
                    <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                </div>

                <!-- Rekening Field -->
                <div>
                    <label for="rekening" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-credit-card mr-2 text-blue-500"></i>Nomor Rekening
                    </label>
                    <input type="text" id="rekening" name="rekening" value="{{ $user->rekening ?? '' }}" {{ $user->rekening ? 'readonly' : '' }}
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                        placeholder="Masukkan nomor rekening">
                    <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                </div>

                <!-- Bank Field -->
                <div>
                    <label for="bank" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-university mr-2 text-blue-500"></i>Nama Bank
                    </label>
                    <input type="text" id="bank" name="bank" value="{{ $user->bank ?? '' }}" {{ $user->bank ? 'readonly' : '' }}
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                        placeholder="Masukkan nama bank">
                    <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                </div>

                <!-- Address Field -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>Alamat
                    </label>
                    <textarea id="address" name="address" rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors resize-none"
                        placeholder="Masukkan alamat lengkap">{{ $user->address ?? '' }}</textarea>
                    <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                </div>

                <!-- Password Section -->
                <div class="border-t pt-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-lock mr-2 text-blue-500"></i>Ubah Password
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">Kosongkan jika tidak ingin mengubah password</p>

                    <div class="space-y-4">
                        <!-- Current Password -->
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password Lama
                            </label>
                            <input type="password" id="current_password" name="current_password"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                placeholder="Masukkan password lama">
                            <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <!-- New Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password Baru
                            </label>
                            <input type="password" id="password" name="password"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                placeholder="Masukkan password baru">
                            <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Konfirmasi Password Baru
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                                placeholder="Konfirmasi password baru">
                            <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-4 pt-6">
                    <button type="button" onclick="resetForm()"
                        class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-undo mr-2"></i>Reset
                    </button>
                    <button type="submit" id="save-btn"
                        class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>

    </div>

    <!-- Success/Error Messages -->
    <div id="message-container" class="fixed top-4 right-4 z-50 hidden">
        <div id="message" class="px-6 py-4 rounded-lg shadow-lg text-white font-medium"></div>
    </div>
@endsection

@push('scripts')
    <script>
        let originalData = {};

        // Store original form data
        document.addEventListener('DOMContentLoaded', function () {
            originalData = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                phone: document.getElementById('phone').value,
                rekening: document.getElementById('rekening').value,
                bank: document.getElementById('bank').value,
                address: document.getElementById('address').value
            };
        });

        // Photo preview function
        function previewPhoto(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('profile-photo-preview').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Reset form function
        function resetForm() {
            document.getElementById('name').value = originalData.name;
            document.getElementById('email').value = originalData.email;
            document.getElementById('phone').value = originalData.phone;
            document.getElementById('rekening').value = originalData.rekening;
            document.getElementById('bank').value = originalData.bank;
            document.getElementById('address').value = originalData.address;
            document.getElementById('current_password').value = '';
            document.getElementById('password').value = '';
            document.getElementById('password_confirmation').value = '';
            document.getElementById('photo').value = '';

            // Reset photo preview
            @if($user->photo)
                document.getElementById('profile-photo-preview').src = '{{ Storage::url($user->photo) }}';
            @else
                document.getElementById('profile-photo-preview').src = '';
            @endif

            // Clear error messages
            clearErrors();
        }

        // Clear error messages
        function clearErrors() {
            document.querySelectorAll('.error-message').forEach(error => {
                error.classList.add('hidden');
                error.textContent = '';
            });
        }

        // Show error message
        function showError(field, message) {
            const errorElement = document.querySelector(`#${field}`).parentNode.querySelector('.error-message');
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
        }

        // Show success/error message
        function showMessage(message, type = 'success') {
            const container = document.getElementById('message-container');
            const messageElement = document.getElementById('message');

            messageElement.textContent = message;
            messageElement.className = `px-6 py-4 rounded-lg shadow-lg text-white font-medium ${type === 'success' ? 'bg-green-500' : 'bg-red-500'
                }`;

            container.classList.remove('hidden');

            setTimeout(() => {
                container.classList.add('hidden');
            }, 5000);
        }

        // Form submission
        document.getElementById('profile-form').addEventListener('submit', function (e) {
            e.preventDefault();

            clearErrors();

            const saveBtn = document.getElementById('save-btn');
            const originalText = saveBtn.innerHTML;

            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';

            const formData = new FormData(this);

            fetch('{{ route("pegawai.profil.update") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showMessage(data.message, 'success');

                        // Update original data
                        originalData = {
                            name: data.data.name,
                            email: data.data.email,
                            phone: data.data.phone || '',
                            rekening: data.data.rekening || '',
                            bank: data.data.bank || '',
                            address: data.data.address || ''
                        };

                        // Reset password fields
                        document.getElementById('current_password').value = '';
                        document.getElementById('password').value = '';
                        document.getElementById('password_confirmation').value = '';

                        // Reset photo input
                        document.getElementById('photo').value = '';
                    } else {
                        showMessage(data.message, 'error');

                        // Show field errors
                        if (data.errors) {
                            Object.keys(data.errors).forEach(field => {
                                showError(field, data.errors[field][0]);
                            });
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('Terjadi kesalahan saat menyimpan data', 'error');
                })
                .finally(() => {
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = originalText;
                });
        });
    </script>
@endpush