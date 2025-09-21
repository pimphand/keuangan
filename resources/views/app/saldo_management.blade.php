@extends('app.master')

@section('konten')

    <div class="content-body">

        <div class="row page-titles mx-0 mt-2">

            <h3 class="col p-md-0">Manajemen Saldo</h3>

            <div class="col p-md-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Manajemen Saldo</a></li>
                </ol>
            </div>

        </div>

        <div class="container-fluid">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{ session('error') }}
                </div>
            @endif

            <div class="row">
                <!-- Add Saldo Form -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Tambah Saldo User</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('saldo.add') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label>Pilih User</label>
                                    <select name="user_id" class="form-control" required>
                                        <option value="">-- Pilih User --</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" @if(!$user->canReceiveSaldoThisMonth()) disabled
                                            @endif>
                                                {{ $user->name }}
                                                @if(!$user->canReceiveSaldoThisMonth())
                                                    (Sudah menerima saldo bulan ini)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Jumlah Saldo</label>
                                    <input type="number" name="amount" class="form-control" step="0.01" min="0.01" required
                                        placeholder="Masukkan jumlah saldo">
                                </div>
                                <div class="form-group">
                                    <label>Catatan (Opsional)</label>
                                    <textarea name="notes" class="form-control" rows="3"
                                        placeholder="Catatan untuk penambahan saldo"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block"
                                    @if(!$isDateAllowed) disabled @endif>
                                    <i class="fa fa-plus"></i> Tambah Saldo
                                </button>
                                @if(!$isDateAllowed)
                                    <small class="text-danger d-block mt-2">
                                        <i class="fa fa-exclamation-triangle"></i>
                                        Penambahan saldo hanya dapat dilakukan pada tanggal {{ env('SALDO_ALLOWED_START_DATE', '1') }}-{{ env('SALDO_ALLOWED_END_DATE', '10') }} setiap bulannya.
                                    </small>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>

                <!-- User List with Saldo -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Daftar User dan Saldo</h4>
                            <small class="text-muted">Bulan: {{ date('F Y') }}</small>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>Saldo Saat Ini</th>
                                            <th>Status Bulan Ini</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no = 1; @endphp
                                        @foreach($users as $user)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    <span class="badge badge-success">
                                                        Rp {{ number_format($user->saldo, 2, ',', '.') }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($user->canReceiveSaldoThisMonth())
                                                        <span class="badge badge-warning">
                                                            <i class="fa fa-clock"></i> Belum menerima
                                                        </span>
                                                    @else
                                                        <span class="badge badge-info">
                                                            <i class="fa fa-check"></i> Sudah menerima
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('saldo.history', $user->id) }}"
                                                        class="btn btn-sm btn-info">
                                                        <i class="fa fa-history"></i> Riwayat
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Panel -->
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Informasi Penting</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <h6><i class="fa fa-info-circle"></i> Aturan Penambahan Saldo:</h6>
                                <ul class="mb-0">
                                    <li>Penambahan saldo hanya dapat dilakukan pada tanggal
                                        <strong>{{ env('SALDO_ALLOWED_START_DATE', '1') }}-{{ env('SALDO_ALLOWED_END_DATE', '10') }}</strong>
                                        setiap bulannya
                                    </li>
                                    <li>Setiap user hanya dapat menerima penambahan saldo <strong>1 kali per bulan</strong>
                                    </li>
                                    <li>Hari ini: <strong>{{ date('d F Y') }}</strong>
                                        @if(date('d') >= env('SALDO_ALLOWED_START_DATE', 1) && date('d') <= env('SALDO_ALLOWED_END_DATE', 10))
                                            <span class="badge badge-success">Dalam periode yang diizinkan</span>
                                        @else
                                            <span class="badge badge-danger">Di luar periode yang diizinkan</span>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection
