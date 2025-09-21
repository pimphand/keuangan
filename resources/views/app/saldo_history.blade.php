@extends('app.master')

@section('konten')

    <div class="content-body">

        <div class="row page-titles mx-0 mt-2">

            <h3 class="col p-md-0">Riwayat Saldo - {{ $user->name }}</h3>

            <div class="col p-md-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('saldo.management') }}">Manajemen Saldo</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Riwayat Saldo</a></li>
                </ol>
            </div>

        </div>

        <div class="container-fluid">

            <div class="row">
                <!-- User Info -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Informasi User</h4>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <div class="profile-photo">
                                    @if($user->foto)
                                        <img src="{{ asset('gambar/user/' . $user->foto) }}" alt="{{ $user->name }}"
                                            class="img-fluid rounded-circle" width="100" height="100">
                                    @else
                                        <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center"
                                            style="width: 100px; height: 100px;">
                                            <i class="fa fa-user text-white" style="font-size: 40px;"></i>
                                        </div>
                                    @endif
                                </div>
                                <h5 class="mt-3">{{ $user->name }}</h5>
                                <p class="text-muted">{{ $user->email }}</p>
                                <div class="alert alert-success">
                                    <strong>Saldo Saat Ini:</strong><br>
                                    <span style="font-size: 24px; font-weight: bold;">
                                        Rp {{ number_format($user->saldo, 2, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Saldo History -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Riwayat Penambahan Saldo</h4>
                        </div>
                        <div class="card-body">
                            @if($history->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Bulan/Tahun</th>
                                                <th>Jumlah</th>
                                                <th>Admin</th>
                                                <th>Catatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no = 1; @endphp
                                            @foreach($history as $item)
                                                <tr>
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ date('d F Y', strtotime($item->created_at)) }}</td>
                                                    <td>
                                                        <span class="badge badge-primary">
                                                            {{ date('F Y', strtotime($item->month_year . '-01')) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-success">
                                                            +Rp {{ number_format($item->amount, 2, ',', '.') }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $item->admin->name }}</td>
                                                    <td>{{ $item->notes ?: '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Summary -->
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                <h5>Total Penambahan</h5>
                                                <h3 class="text-success">
                                                    Rp {{ number_format($history->sum('amount'), 2, ',', '.') }}
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card bg-light">
                                            <div class="card-body text-center">
                                                <h5>Jumlah Transaksi</h5>
                                                <h3 class="text-info">{{ $history->count() }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fa fa-history fa-3x text-muted mb-3"></i>
                                    <h5>Belum Ada Riwayat Saldo</h5>
                                    <p class="text-muted">User ini belum pernah menerima penambahan saldo.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection
