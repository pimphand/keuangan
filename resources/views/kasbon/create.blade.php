@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Ajukan Kasbon</h4>
                    </div>

                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Info Saldo Kasbon -->
                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="fas fa-info-circle"></i> Informasi Saldo Kasbon
                            </h6>
                            <p class="mb-0">
                                <strong>Saldo Kasbon Tersedia:</strong>
                                <span class="text-primary fw-bold">Rp {{ number_format($user->kasbon, 0, ',', '.') }}</span>
                            </p>
                            <small class="text-muted">
                                Anda hanya dapat mengajukan kasbon maksimal sesuai dengan saldo yang tersedia.
                            </small>
                        </div>

                        <form action="{{ route('kasbon.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="nominal" class="form-label">
                                    Nominal Kasbon <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control @error('nominal') is-invalid @enderror"
                                        id="nominal" name="nominal" value="{{ old('nominal') }}"
                                        placeholder="Masukkan nominal kasbon" required>
                                </div>
                                @error('nominal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Maksimal: Rp {{ number_format($user->kasbon, 0, ',', '.') }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="keterangan" class="form-label">
                                    Keterangan <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan"
                                    name="keterangan" rows="4" placeholder="Jelaskan alasan pengajuan kasbon"
                                    required>{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Maksimal 255 karakter
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('kasbon.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i> Ajukan Kasbon
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const nominalInput = document.getElementById('nominal');
            const maxNominal = {{ $user->kasbon }};

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
                } else if (numericValue <= 0) {
                    this.setCustomValidity('Nominal harus lebih dari 0');
                } else {
                    this.setCustomValidity('');
                }
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

            // Validasi saat submit
            document.querySelector('form').addEventListener('submit', function (e) {
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
        });
    </script>
@endsection