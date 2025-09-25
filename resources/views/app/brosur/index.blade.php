@extends('app.master')

@section('konten')

    <div class="content-body">

        <div class="row page-titles mx-0 mt-2">

            <h3 class="col p-md-0">Data Brosur</h3>

            <div class="col p-md-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Brosur</a></li>
                </ol>
            </div>
        </div>

        <div class="container-fluid">

            <div class="card">

                <div class="card-header pt-4">
                    <h3 class="card-title">Data Brosur</h3>
                    <div class="card-tools">
                        <a href="{{ route('brosur.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Brosur
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
                                    <th class="text-center" width="10%">Gambar</th>
                                    <th class="text-center" width="20%">Nama</th>
                                    <th class="text-center" width="15%">Tag</th>
                                    <th class="text-center" width="15%">Harga</th>
                                    <th class="text-center" width="10%">Status</th>
                                    <th class="text-center" width="25%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($brosurs as $index => $brosur)
                                    <tr>
                                        <td class="text-center">{{ $brosurs->firstItem() + $index }}</td>
                                        <td class="text-center">
                                            @if($brosur->gambar)
                                                <img src="{{ asset('gambar/brosur/' . $brosur->gambar) }}" alt="{{ $brosur->nama }}"
                                                    class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <span class="text-muted">No Image</span>
                                            @endif
                                        </td>
                                        <td>{{ $brosur->nama }}</td>
                                        <td>
                                            @if ($brosur->tag)
                                                @foreach ($brosur->tag as $tag)
                                                    <span class="badge badge-secondary text-white">{{ $tag }}</span>
                                                @endforeach
                                            @else
                                                belum di tambahkan
                                            @endif
                                        </td>
                                        <td class="text-right">Rp {{ number_format($brosur->harga, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <span class="badge badge-{{ $brosur->status == 'aktif' ? 'success' : 'danger' }}">
                                                {{ ucfirst($brosur->status) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('brosur.show', $brosur) }}" class="btn btn-info btn-sm"
                                                    title="Lihat">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('brosur.edit', $brosur) }}" class="btn btn-warning btn-sm"
                                                    title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('brosur.destroy', $brosur) }}" method="POST"
                                                    style="display: inline-block;"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus brosur ini?')">
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
                                        <td colspan="7" class="text-center">Tidak ada data brosur</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $brosurs->links() }}
                    </div>

                </div>

            </div>

        </div>
        <!-- #/ container -->
    </div>

@endsection