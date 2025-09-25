@extends('app.master')

@section('konten')
<div class="content-body">
    <div class="row page-titles mx-0 mt-2">
        <h3 class="col p-md-0">Detail Project</h3>
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('client.index') }}">Client</a></li>
                <li class="breadcrumb-item"><a href="{{ route('client.show', $project->client) }}">{{ $project->client->nama }}</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Detail Project</a></li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">
        <div class="card">
            <div class="card-header pt-4">
                <h3 class="card-title">Detail Project</h3>
                <div class="card-tools">
                    <a href="{{ route('client.show', $project->client) }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali ke Client
                    </a>
                    <a href="{{ route('project.edit', $project) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route("project.payment", $project) }}" class="btn btn-success btn-sm">
                        <i class="fas fa-credit-card"></i> Pembayaran
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td width="150"><strong>Client:</strong></td>
                                    <td>{{ $project->client->nama }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Brosur:</strong></td>
                                    <td>{{ $project->brosur->nama ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($project->status == 'belum bayar')
                                            <span class="badge badge-danger">Belum Bayar</span>
                                        @elseif($project->status == 'bayar')
                                            <span class="badge badge-success">Bayar</span>
                                        @elseif($project->status == 'kurang')
                                            <span class="badge badge-warning">Kurang</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Harga:</strong></td>
                                    <td>Rp {{ number_format($project->harga, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Bayar:</strong></td>
                                    <td>Rp {{ number_format($project->total_bayar, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Sisa Bayar:</strong></td>
                                    <td>Rp {{ number_format($project->sisa_bayar, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td width="150"><strong>Dibuat:</strong></td>
                                    <td>{{ $project->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Terakhir Update:</strong></td>
                                    <td>{{ $project->updated_at->format('d M Y H:i') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
