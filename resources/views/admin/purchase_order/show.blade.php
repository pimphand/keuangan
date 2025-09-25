@extends('app.master')

@section('konten')
    <div class="content-body">
        <div class="row page-titles mx-0 mt-2">
            <h3 class="col p-md-0">Detail Purchase Order</h3>
            <div class="col p-md-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.po.index') }}">Purchase Orders</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Detail</a></li>
                </ol>
            </div>
        </div>

        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">{{ $purchaseOrder->po_number }}</h4>
                    <div>
                        <a href="{{ route('admin.po.edit', $purchaseOrder->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <a href="{{ route('admin.po.index') }}" class="btn btn-light btn-sm">Kembali</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-muted">Informasi Klien</h5>
                            <table class="table table-sm">
                                <tr>
                                    <th class="w-25">Jenis</th>
                                    <td>{{ $purchaseOrder->client_type }}</td>
                                </tr>
                                <tr>
                                    <th>Nama</th>
                                    <td>{{ $purchaseOrder->client_name }}</td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td>{{ $purchaseOrder->client_address }}</td>
                                </tr>
                                <tr>
                                    <th>HP</th>
                                    <td>{{ $purchaseOrder->client_phone_number }}</td>
                                </tr>
                                <tr>
                                    <th>NIK</th>
                                    <td>{{ $purchaseOrder->client_nik }}</td>
                                </tr>
                                <tr>
                                    <th>Nama KTP</th>
                                    <td>{{ $purchaseOrder->client_ktp_name }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-muted">Status</h5>
                            <p>
                                <span
                                    class="badge badge-{{ $purchaseOrder->status === 'approved' ? 'success' : ($purchaseOrder->status === 'rejected' ? 'danger' : 'warning') }}">{{ ucfirst($purchaseOrder->status) }}</span>
                            </p>
                            <div class="mt-3">
                                <h6>Foto KTP</h6>
                                @if ($purchaseOrder->ktp_photo)
                                    <img src="{{ $purchaseOrder->ktp_photo }}" alt="KTP" class="img-fluid rounded"
                                        style="max-width: 280px;">
                                @else
                                    <div class="text-muted">Tidak ada foto KTP</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h5 class="text-muted">Uraian</h5>
                        <div class="border rounded p-3 bg-light">{{ $purchaseOrder->notes ?: '-' }}</div>
                    </div>

                    <div class="mt-4">
                        <h5 class="text-muted">Item</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th width="15%">Jumlah</th>
                                        <th width="20%">Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchaseOrder->items as $item)
                                        <tr>
                                            <td>{{ $item->product_name }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection