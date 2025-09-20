@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Proses Pengajuan Kasbon</h4>
                    </div>

                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Info Pengajuan -->
                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="fas fa-info-circle"></i> Informasi Pengajuan
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Pegawai:</strong> {{ $kasbon->user->name }}</p>
                                    <p class="mb-1"><strong>Nominal:</strong> Rp
                                        {{ number_format($kasbon->nominal, 0, ',', '.') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Tanggal:</strong>
                                        {{ $kasbon->created_at->format('d F Y, H:i') }}</p>
                                    <p class="mb-1"><strong>Saldo Kasbon Pegawai:</strong> Rp
                                        {{ number_format($kasbon->user->kasbon, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6>Keterangan Pengajuan:</h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p class="mb-0">{{ $kasbon->keterangan }}</p>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('kasbon.update', $kasbon) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status" id="approve"
                                                value="disetujui" required>
                                            <label class="form-check-label text-success fw-bold" for="approve">
                                                <i class="fas fa-check"></i> Setujui
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status" id="reject"
                                                value="ditolak" required>
                                            <label class="form-check-label text-danger fw-bold" for="reject">
                                                <i class="fas fa-times"></i> Tolak
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3" id="alasanField" style="display: none;">
                                <label for="alasan" class="form-label">
                                    Alasan Penolakan <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('alasan') is-invalid @enderror" id="alasan"
                                    name="alasan" rows="3"
                                    placeholder="Jelaskan alasan penolakan pengajuan kasbon">{{ old('alasan') }}</textarea>
                                @error('alasan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('kasbon.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Proses Pengajuan
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
            const rejectRadio = document.getElementById('reject');
            const alasanField = document.getElementById('alasanField');
            const alasanTextarea = document.getElementById('alasan');

            rejectRadio.addEventListener('change', function () {
                if (this.checked) {
                    alasanField.style.display = 'block';
                    alasanTextarea.required = true;
                } else {
                    alasanField.style.display = 'none';
                    alasanTextarea.required = false;
                    alasanTextarea.value = '';
                }
            });

            // Check if reject is already selected (from old input)
            if (rejectRadio.checked) {
                alasanField.style.display = 'block';
                alasanTextarea.required = true;
            }
        });
    </script>
@endsection