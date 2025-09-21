@extends('app.master')

@section('konten')

    <div class="content-body">

        <div class="row page-titles mx-0 mt-2">

            <h3 class="col p-md-0">Proses Pengajuan Kasbon</h3>

            <div class="col p-md-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('kasbon.index') }}">Kasbon</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Proses</a></li>
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
                    <h4>Proses Pengajuan Kasbon</h4>
                </div>

                <div class="card-body pt-0">

                    <!-- Info Pengajuan -->
                    <div class="alert alert-info">
                        <h6 class="alert-heading">
                            <i class="fa fa-info-circle"></i> Informasi Pengajuan
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Pegawai:</strong> {{ $kasbon->user->name }}</p>
                                <p class="mb-1"><strong>Nominal:</strong> Rp
                                    {{ number_format($kasbon->nominal, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Tanggal:</strong>
                                    {{ $kasbon->created_at->format('d F Y, H:i') }}</p>
                                <p class="mb-1"><strong>Saldo Kasbon Pegawai:</strong> Rp
                                    {{ number_format($kasbon->user->kasbon, 0, ',', '.') }}
                                </p>
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

                        <div class="form-group">
                            <label>Status <span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="approve"
                                            value="disetujui" required>
                                        <label class="form-check-label text-success fw-bold" for="approve">
                                            <i class="fa fa-check"></i> Setujui
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="reject"
                                            value="ditolak" required>
                                        <label class="form-check-label text-danger fw-bold" for="reject">
                                            <i class="fa fa-times"></i> Tolak
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="alasanField" style="display: none;">
                            <label for="alasan">
                                Alasan Penolakan <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('alasan') is-invalid @enderror" id="alasan" name="alasan"
                                rows="3"
                                placeholder="Jelaskan alasan penolakan pengajuan kasbon">{{ old('alasan') }}</textarea>
                            @error('alasan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('kasbon.index') }}" class="btn btn-default">
                                <i class="ti-close m-r-5 f-s-12"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save m-r-5"></i> Proses Pengajuan
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