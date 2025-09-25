@extends('app.master')

@section('konten')

    <div class="content-body">

        <div class="row page-titles mx-0 mt-2">

            <h3 class="col p-md-0">Detail Brosur</h3>

            <div class="col p-md-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('brosur.index') }}">Brosur</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Detail</a></li>
                </ol>
            </div>
        </div>

        <div class="container-fluid">

            <div class="card">

                <div class="card-header pt-4">
                    <h3 class="card-title">Detail Brosur</h3>
                    <div class="card-tools">
                        <a href="{{ route('brosur.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <a href="{{ route('brosur.edit', $brosur) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-4">
                            @if($brosur->gambar)
                                <div class="text-center mb-3">
                                    <img src="{{ asset('gambar/brosur/' . $brosur->gambar) }}"
                                        alt="{{ $brosur->nama }}" class="img-fluid rounded shadow"
                                        style="max-height: 300px;">
                                </div>
                            @else
                                <div class="text-center mb-3">
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                        style="height: 200px;">
                                        <span class="text-muted">Tidak ada gambar</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="30%"><strong>Nama Brosur:</strong></td>
                                    <td>{{ $brosur->nama }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Harga:</strong></td>
                                    <td>Rp {{ number_format($brosur->harga, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span
                                            class="badge badge-{{ $brosur->status == 'aktif' ? 'success' : 'danger' }}">
                                            {{ ucfirst($brosur->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>File Brosur:</strong></td>
                                    <td>
                                        @if($brosur->file)
                                            <a href="{{ asset('files/brosur/' . $brosur->file) }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-download"></i> Download File
                                            </a>
                                        @else
                                            <span class="text-muted">Tidak ada file</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Dibuat:</strong></td>
                                    <td>{{ $brosur->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Diperbarui:</strong></td>
                                    <td>{{ $brosur->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($brosur->deskripsi)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5>Deskripsi</h5>
                                <div class="border rounded p-3 bg-light">
                                    {!! nl2br(e($brosur->deskripsi)) !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($brosur->spesifikasi)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5>Spesifikasi</h5>
                                <div class="border rounded p-3 bg-light">
                                    <ul>
                                        @foreach($brosur->spesifikasi as $item)
                                            <li> {{ $item }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>

            </div>

        </div>
        <!-- #/ container -->
    </div>

@endsection
