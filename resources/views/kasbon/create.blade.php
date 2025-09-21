@extends('app.master')

@section('konten')

    <div class="content-body">

        <div class="row page-titles mx-0 mt-2">

            <h3 class="col p-md-0">Ajukan Kasbon</h3>

            <div class="col p-md-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('kasbon.index') }}">Kasbon</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Ajukan</a></li>
                </ol>
            </div>

        </div>

        <div class="container-fluid">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card">

                <div class="card-header pt-4">
                    <div class="float-right">
                        <a href="{{ route('kasbon.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> &nbsp Kembali
                        </a>
                    </div>
                    <h4>Ajukan Kasbon</h4>
                </div>

                <div class="card-body pt-0">

                    <!-- Info Saldo Kasbon -->
                    <div class="alert alert-info">
                        <h6 class="alert-heading">
                            <i class="fa fa-info-circle"></i> Informasi Saldo Kasbon
                        </h6>
                        <p class="mb-0">
                            <strong>Saldo Kasbon Tersedia:</strong>
                            <span class="text-primary fw-bold">Rp {{ number_format($user->saldo, 0, ',', '.') }}</span>
                        </p>
                        <small class="text-muted">
                            Anda hanya dapat mengajukan kasbon maksimal sesuai dengan saldo yang tersedia.
                        </small>
                    </div>

                    <form action="{{ route('kasbon.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="nominal">
                                Nominal Kasbon <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control @error('nominal') is-invalid @enderror" id="nominal"
                                    name="nominal" value="{{ old('nominal') }}" placeholder="Masukkan nominal kasbon"
                                    required>
                            </div>
                            @error('nominal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Maksimal: Rp {{ number_format($user->saldo, 0, ',', '.') }}
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="keterangan">
                                Keterangan <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan"
                                name="keterangan" rows="4" placeholder="Jelaskan alasan pengajuan kasbon"
                                required>{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Maksimal 255 karakter
                            </small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('kasbon.index') }}" class="btn btn-default">
                                <i class="ti-close m-r-5 f-s-12"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-paper-plane m-r-5"></i> Ajukan Kasbon
                            </button>
                        </div>
                    </form>

                </div>

            </div>

        </div>
        <!-- #/ container -->
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const nominalInput = document.getElementById('nominal');
            const maxNominal = {{ $user->saldo }};

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