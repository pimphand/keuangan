@extends('app.master')

@section('konten')
<div class="content-body">
    <div class="row page-titles mx-0 mt-2">
        <h3 class="col p-md-0">Daftar Project</h3>
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Project</a></li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">
        <div class="card">
            <div class="card-header pt-4">
                <h3 class="card-title">Daftar Project</h3>
                <div class="card-tools">
                    <a href="{{ route('client.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali ke Client
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($projects->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Client</th>
                                    <th>Brosur</th>
                                    <th>Status</th>
                                    <th>Harga</th>
                                    <th>Total Bayar</th>
                                    <th>Sisa Bayar</th>
                                    <th>Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($projects as $project)
                                    <tr>
                                        <td>
                                            <a href="{{ route('client.show', $project->client) }}">
                                                {{ $project->client->nama }}
                                            </a>
                                        </td>
                                        <td>{{ $project->brosur->nama ?? '-' }}</td>
                                        <td>
                                            @if($project->status == 'belum bayar')
                                                <span class="badge badge-danger">Belum Bayar</span>
                                            @elseif($project->status == 'bayar')
                                                <span class="badge badge-success">Bayar</span>
                                            @elseif($project->status == 'kurang')
                                                <span class="badge badge-warning">Kurang</span>
                                            @endif
                                        </td>
                                        <td>Rp {{ number_format($project->harga, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($project->total_bayar, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($project->sisa_bayar, 0, ',', '.') }}</td>
                                        <td>{{ $project->created_at->format('d M Y') }}</td>
                                        <td>
                                            <a href="{{ route('project.show', $project) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('project.edit', $project) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            <a href="{{ route("project.payment", $project) }}" class="btn btn-success btn-sm">
                                                <i class="fas fa-credit-card"></i>
                                            </a>
                                            </a>
                                            <form action="{{ route('project.destroy', $project) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus project ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        {{ $projects->links() }}
                    </div>
                @else
                    <div class="text-center">
                        <p class="text-muted">Belum ada project.</p>
                        <a href="{{ route('client.index') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Lihat Client
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
