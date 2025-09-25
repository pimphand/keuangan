@extends('app.master')

@section('konten')

    <div class="content-body">

        <div class="row page-titles mx-0 mt-2">

            <h3 class="col p-md-0">Data Client</h3>

            <div class="col p-md-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Client</a></li>
                </ol>
            </div>
        </div>

        <div class="container-fluid">

            <div class="card">

                <div class="card-header pt-4">
                    <h3 class="card-title">Data Client</h3>
                    <div class="card-tools">
                        <a href="{{ route('client.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Client
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

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th class="text-center" width="10%">Logo</th>
                                    <th class="text-center" width="20%">Nama</th>
                                    <th class="text-center" width="15%">Type</th>
                                    <th class="text-center" width="15%">Industri</th>
                                    <th class="text-center" width="15%">Telepon</th>
                                    <th class="text-center" width="25%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($clients as $index => $client)
                                    <tr>
                                        <td class="text-center">{{ $clients->firstItem() + $index }}</td>
                                        <td class="text-center">
                                            @if($client->logo)
                                                <img src="{{ $client->logo_url }}" alt="{{ $client->nama }}" class="img-thumbnail"
                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center"
                                                    style="width: 50px; height: 50px; margin: 0 auto;">
                                                    <i class="fa fa-building text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $client->nama }}</td>
                                        <td>
                                            @if($client->type)
                                                <span class="badge badge-info text-white">{{ $client->type }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($client->industri)
                                                <span class="badge badge-success text-white">{{ $client->industri }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $client->telepon ?? '-' }}</td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('client.show', $client) }}" class="btn btn-info btn-sm"
                                                    title="Lihat">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('client.edit', $client) }}" class="btn btn-warning btn-sm"
                                                    title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('client.destroy', $client) }}" method="POST"
                                                    style="display: inline-block;"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus client ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data client</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $clients->links() }}
                    </div>

                </div>

            </div>

        </div>
        <!-- #/ container -->
    </div>

@endsection