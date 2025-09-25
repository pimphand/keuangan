@extends('app.master')

@section('konten')
    <div class="content-body">
        <div class="row page-titles mx-0 mt-2">
            <h3 class="col p-md-0">
                <i class="fas fa-credit-card text-primary"></i> Pembayaran Project
            </h3>
            <div class="col p-md-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('client.index') }}">Client</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('client.show', $project->client) }}">{{ $project->client->nama }}</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Pembayaran</a></li>
                </ol>
            </div>
        </div>

        <div class="container-fluid">
            <!-- Project Info Card -->
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-info-circle mr-2"></i>Informasi Project
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-box">
                                <div class="info-box-icon bg-info">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="info-box-content">
                                    <span class="info-box-text">Client</span>
                                    <span class="info-box-number">{{ $project->client->nama }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <div class="info-box-icon bg-warning">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div class="info-box-content">
                                    <span class="info-box-text">Brosur</span>
                                    <span class="info-box-number">{{ $project->brosur->nama ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="info-box">
                                <div class="info-box-icon bg-primary">
                                    <i class="fas fa-tag"></i>
                                </div>
                                <div class="info-box-content">
                                    <span class="info-box-text">Harga Total</span>
                                    <span class="info-box-number text-primary">Rp {{ number_format($project->harga, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="info-box">
                                <div class="info-box-icon bg-success">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="info-box-content">
                                    <span class="info-box-text">Sudah Bayar</span>
                                    <span class="info-box-number text-success">Rp {{ number_format($project->total_bayar, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="info-box">
                                <div class="info-box-icon bg-danger">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="info-box-content">
                                    <span class="info-box-text">Sisa Bayar</span>
                                    <span class="info-box-number text-danger">Rp {{ number_format($project->sisa_bayar, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="progress-wrapper">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="font-weight-bold">Progress Pembayaran</span>
                                    <span class="font-weight-bold">{{ number_format(($project->total_bayar / $project->harga) * 100, 1) }}%</span>
                                </div>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-gradient-success" role="progressbar"
                                         style="width: {{ ($project->total_bayar / $project->harga) * 100 }}%"
                                         aria-valuenow="{{ ($project->total_bayar / $project->harga) * 100 }}"
                                         aria-valuemin="0" aria-valuemax="100">
                                        <span class="progress-text">{{ number_format(($project->total_bayar / $project->harga) * 100, 1) }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Badge -->
                    <div class="row mt-3">
                        <div class="col-md-12 text-center">
                            <strong class="mr-2">Status:</strong>
                            @if($project->status == 'belum bayar')
                                <span class="badge badge-danger badge-lg">
                                    <i class="fas fa-times-circle mr-1"></i>Belum Bayar
                                </span>
                            @elseif($project->status == 'bayar')
                                <span class="badge badge-success badge-lg">
                                    <i class="fas fa-check-circle mr-1"></i>Lunas
                                </span>
                            @elseif($project->status == 'kurang')
                                <span class="badge badge-warning badge-lg">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Kurang
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Payment Form -->
                <div class="col-md-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-gradient-success text-white">
                            <h4 class="card-title mb-0">
                                <i class="fas fa-plus-circle mr-2"></i>Tambah Pembayaran
                            </h4>
                        </div>
                        <div class="card-body">
                            @if($project->sisa_bayar > 0)
                                <form action="{{ route('project.payment.process', $project) }}" method="POST" id="paymentForm">
                                    @csrf

                                    <div class="form-group">
                                        <label for="amount" class="font-weight-bold">
                                            <i class="fas fa-money-bill-wave text-success mr-1"></i>Jumlah Pembayaran
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group input-group-lg">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-success text-white font-weight-bold">Rp</span>
                                            </div>
                                            <input type="number" class="form-control form-control-lg @error('amount') is-invalid @enderror"
                                                   name="amount" id="amount" value="{{ old('amount') }}"
                                                   required min="1" max="{{ $project->sisa_bayar }}"
                                                   placeholder="Masukkan jumlah pembayaran">
                                        </div>
                                        <small class="form-text text-muted">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Maksimal: Rp {{ number_format($project->sisa_bayar, 0, ',', '.') }}
                                        </small>
                                        @error('amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="payment_date" class="font-weight-bold">
                                            <i class="fas fa-calendar-alt text-primary mr-1"></i>Tanggal Pembayaran
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" class="form-control form-control-lg @error('payment_date') is-invalid @enderror"
                                               name="payment_date" id="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                                        @error('payment_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="payment_method" class="font-weight-bold">
                                            <i class="fas fa-credit-card text-info mr-1"></i>Metode Pembayaran
                                        </label>
                                        <select class="form-control form-control-lg @error('payment_method') is-invalid @enderror"
                                                name="payment_method" id="payment_method">
                                            <option value="">Pilih Metode Pembayaran</option>
                                            <option value="Tunai" {{ old('payment_method') == 'Tunai' ? 'selected' : '' }}>
                                                Tunai
                                            </option>
                                            <option value="Transfer Bank" {{ old('payment_method') == 'Transfer Bank' ? 'selected' : '' }}>
                                                Transfer Bank
                                            </option>
                                            <option value="E-Wallet" {{ old('payment_method') == 'E-Wallet' ? 'selected' : '' }}>
                                                E-Wallet
                                            </option>
                                            <option value="Kartu Kredit" {{ old('payment_method') == 'Kartu Kredit' ? 'selected' : '' }}>
                                                Kartu Kredit
                                            </option>
                                            <option value="Lainnya" {{ old('payment_method') == 'Lainnya' ? 'selected' : '' }}>
                                                Lainnya
                                            </option>
                                        </select>
                                        @error('payment_method')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="notes" class="font-weight-bold">
                                            <i class="fas fa-sticky-note text-warning mr-1"></i>Catatan
                                        </label>
                                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                                  name="notes" id="notes" rows="3"
                                                  placeholder="Catatan pembayaran (opsional)">{{ old('notes') }}</textarea>
                                        @error('notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group text-center">
                                        <button type="submit" class="btn btn-success btn-lg px-5 mr-3" id="submitBtn">
                                            <i class="fas fa-credit-card mr-2"></i> Proses Pembayaran
                                        </button>
                                        <a href="{{ route('project.show', $project) }}" class="btn btn-secondary btn-lg px-4">
                                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                                        </a>
                                    </div>
                                </form>
                            @else
                                <div class="alert alert-success text-center border-0 shadow-sm">
                                    <i class="fas fa-check-circle fa-3x mb-3 text-success"></i>
                                    <h4 class="alert-heading">Project Sudah Lunas!</h4>
                                    <p class="mb-0">Semua pembayaran untuk project ini sudah selesai.</p>
                                    <hr>
                                    <a href="{{ route('project.show', $project) }}" class="btn btn-outline-success">
                                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Detail Project
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Payment History -->
                <div class="col-md-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-gradient-info text-white">
                            <h4 class="card-title mb-0">
                                <i class="fas fa-history mr-2"></i>Riwayat Pembayaran
                            </h4>
                        </div>
                        <div class="card-body">
                            @if($project->paymentHistories->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th><i class="fas fa-calendar mr-1"></i>Tanggal</th>
                                                <th><i class="fas fa-money-bill-wave mr-1"></i>Jumlah</th>
                                                <th><i class="fas fa-credit-card mr-1"></i>Metode</th>
                                                <th><i class="fas fa-sticky-note mr-1"></i>Catatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($project->paymentHistories->sortByDesc('payment_date') as $payment)
                                                <tr class="payment-row">
                                                    <td>
                                                        <span class="badge badge-light">
                                                            {{ $payment->payment_date->format('d/m/Y') }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="text-success font-weight-bold">
                                                            <i class="fas fa-arrow-up mr-1"></i>
                                                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($payment->payment_method)
                                                            <span class="badge badge-outline-primary">
                                                                {{ $payment->payment_method }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($payment->notes)
                                                            <small class="text-muted">{{ Str::limit($payment->notes, 30) }}</small>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-4 p-3 bg-light rounded">
                                    <div class="row text-center">
                                        <div class="col-md-6">
                                            <h5 class="text-success mb-1">
                                                <i class="fas fa-chart-line mr-1"></i>
                                                Total Pembayaran
                                            </h5>
                                            <h4 class="text-success font-weight-bold">
                                                Rp {{ number_format($project->paymentHistories->sum('amount'), 0, ',', '.') }}
                                            </h4>
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="text-info mb-1">
                                                <i class="fas fa-receipt mr-1"></i>
                                                Jumlah Transaksi
                                            </h5>
                                            <h4 class="text-info font-weight-bold">
                                                {{ $project->paymentHistories->count() }}
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center text-muted py-5">
                                    <i class="fas fa-receipt fa-4x mb-4 text-muted"></i>
                                    <h5>Belum ada riwayat pembayaran</h5>
                                    <p class="mb-0">Riwayat pembayaran akan muncul setelah ada transaksi.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .bg-gradient-success {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }

    .bg-gradient-info {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .info-box {
        display: flex;
        align-items: center;
        padding: 15px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .info-box:hover {
        transform: translateY(-2px);
    }

    .info-box-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 24px;
        color: white;
    }

    .info-box-content {
        flex: 1;
    }

    .info-box-text {
        display: block;
        font-size: 14px;
        color: #6c757d;
        margin-bottom: 5px;
    }

    .info-box-number {
        display: block;
        font-size: 18px;
        font-weight: bold;
        color: #333;
    }

    .progress-wrapper {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        border: 1px solid #e9ecef;
    }

    .progress {
        border-radius: 10px;
        overflow: hidden;
    }

    .progress-bar {
        position: relative;
        border-radius: 10px;
    }

    .progress-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-weight: bold;
        font-size: 12px;
    }

    .badge-lg {
        font-size: 14px;
        padding: 8px 16px;
    }

    .payment-row:hover {
        background-color: #f8f9fa;
        transform: scale(1.01);
        transition: all 0.3s ease;
    }

    .badge-outline-primary {
        background-color: transparent;
        border: 1px solid #007bff;
        color: #007bff;
        padding: 4px 8px;
        border-radius: 15px;
        font-size: 12px;
    }

    .form-control-lg {
        border-radius: 8px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .form-control-lg:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .btn-lg {
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-lg:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    .card {
        border-radius: 15px;
        overflow: hidden;
    }

    .card-header {
        border-bottom: none;
        padding: 20px;
    }

    .card-body {
        padding: 25px;
    }

    .alert {
        border-radius: 15px;
        border: none;
    }

    .shadow-sm {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }

    @media (max-width: 768px) {
        .info-box {
            margin-bottom: 15px;
        }

        .btn-lg {
            width: 100%;
            margin-bottom: 10px;
        }
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const amountInput = document.getElementById('amount');
        const maxAmount = {{ $project->sisa_bayar }};
        const form = document.getElementById('paymentForm');
        const submitBtn = document.getElementById('submitBtn');

        if (amountInput) {
            // Set max amount
            amountInput.setAttribute('max', maxAmount);

            // Quick payment buttons with enhanced styling
            const quickButtons = document.createElement('div');
            quickButtons.className = 'mt-3';
            quickButtons.innerHTML = `
                <div class="card border-0 bg-light">
                    <div class="card-body p-3">
                        <h6 class="card-title text-muted mb-3">
                            <i class="fas fa-bolt text-warning mr-1"></i>Pembayaran Cepat
                        </h6>
                        <div class="btn-group-vertical w-100" role="group">
                            <button type="button" class="btn btn-outline-primary btn-sm mb-2" onclick="setAmount(${maxAmount})">
                                <i class="fas fa-check-double mr-2"></i>
                                Bayar Semua (Rp ${new Intl.NumberFormat('id-ID').format(maxAmount)})
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm mb-2" onclick="setAmount(${Math.floor(maxAmount/2)})">
                                <i class="fas fa-divide mr-2"></i>
                                Setengah (Rp ${new Intl.NumberFormat('id-ID').format(Math.floor(maxAmount/2))})
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setAmount(${Math.floor(maxAmount/4)})">
                                <i class="fas fa-percentage mr-2"></i>
                                Seperempat (Rp ${new Intl.NumberFormat('id-ID').format(Math.floor(maxAmount/4))})
                            </button>
                        </div>
                    </div>
                </div>
            `;

            amountInput.parentNode.appendChild(quickButtons);

            window.setAmount = function(amount) {
                amountInput.value = amount;
                amountInput.focus();

                // Add visual feedback
                amountInput.style.borderColor = '#28a745';
                amountInput.style.backgroundColor = '#f8fff9';

                setTimeout(() => {
                    amountInput.style.borderColor = '';
                    amountInput.style.backgroundColor = '';
                }, 1000);
            };
        }

        // Form validation and submission
        if (form && submitBtn) {
            form.addEventListener('submit', function(e) {
                const amount = parseFloat(amountInput.value);

                // Validate amount
                if (!amount || amount <= 0) {
                    e.preventDefault();
                    alert('Jumlah pembayaran harus diisi dan lebih dari 0!');
                    amountInput.focus();
                    return false;
                }

                if (amount > maxAmount) {
                    e.preventDefault();
                    alert('Jumlah pembayaran tidak boleh melebihi sisa bayar!');
                    amountInput.focus();
                    return false;
                }

                // Show loading state
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
                submitBtn.disabled = true;

                // Allow form to submit normally
                return true;
            });
        }
    });
    </script>
@endsection
