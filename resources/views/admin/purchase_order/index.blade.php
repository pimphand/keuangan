@extends('app.master')

@section('konten')

    <div class="content-body">
        <div class="row page-titles mx-0 mt-2">
            <h3 class="col p-md-0">Purchase Orders</h3>
            <div class="col p-md-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('admin.po.index') }}">Purchase Orders</a></li>
                </ol>
            </div>
        </div>

        <div class="container-fluid">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Daftar Purchase Order</h4>
                    <a href="{{ route('admin.po.create') }}" class="btn btn-primary btn-sm">Buat PO</a>
                </div>
                <div class="card-body">
                    <form method="GET" action="" class="mb-3">
                        <div class="form-row">
                            <div class="col-md-4 mb-2">
                                <label class="small text-muted">Cari</label>
                                <input type="text" name="q" value="{{ request('q') }}"
                                    placeholder="Nomor PO atau nama klien" class="form-control" />
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="small text-muted">Status</label>
                                <select name="status" class="form-control">
                                    <option value="">Semua</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved
                                    </option>
                                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary mr-2">Terapkan</button>
                                <a href="{{ url()->current() }}" class="btn btn-light">Reset</a>
                            </div>
                        </div>
                    </form>

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nomor PO</th>
                                    <th>Klien</th>
                                    <th>Status</th>
                                    <th width="10%">Items</th>
                                    <th width="20%" class="text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($purchaseOrders as $po)
                                    <tr>
                                        <td>{{ ($purchaseOrders->currentPage() - 1) * $purchaseOrders->perPage() + $loop->iteration }}
                                        </td>
                                        <td class="font-weight-bold">{{ $po->po_number }}</td>
                                        <td>{{ $po->client_name }}</td>
                                        <td>
                                            <span
                                                class="badge badge-{{ $po->status === 'approved' ? 'success' : ($po->status === 'rejected' ? 'danger' : 'warning') }}">{{ ucfirst($po->status) }}</span>
                                        </td>
                                        <td><span class="badge badge-info">{{ $po->items_count }} item</span></td>
                                        <td class="text-right">
                                            <a href="{{ route('admin.po.show', $po->id) }}"
                                                class="btn btn-info btn-sm">Lihat</a>
                                            <a href="{{ route('admin.po.edit', $po->id) }}"
                                                class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('admin.po.destroy', $po->id) }}" method="POST"
                                                class="d-inline" onsubmit="return confirm('Hapus PO ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Belum ada purchase order.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $purchaseOrders->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection