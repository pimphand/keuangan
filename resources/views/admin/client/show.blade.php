@extends('app.master')

@section('konten')

    <div class="content-body">

        <div class="row page-titles mx-0 mt-2">

            <h3 class="col p-md-0">Detail Client: {{ $client->nama }}</h3>

            <div class="col p-md-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('client.index') }}">Client</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Detail Client</a></li>
                </ol>
            </div>
        </div>

        <div class="container-fluid">

            <div class="card">

                <div class="card-header pt-4">
                    <h3 class="card-title">Detail Client: {{ $client->nama }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('client.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <a href="{{ route('client.edit', $client) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center">
                                @if($client->logo)
                                    <img src="{{ $client->logo_url }}" alt="{{ $client->nama }}" class="img-thumbnail"
                                        style="max-width: 200px; max-height: 200px;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center"
                                        style="width: 200px; height: 200px; margin: 0 auto;">
                                        <i class="fas fa-building text-muted" style="font-size: 4rem;"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td width="150"><strong>Nama Client:</strong></td>
                                        <td>{{ $client->nama }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Type:</strong></td>
                                        <td>
                                            @if($client->type)
                                                <span class="badge badge-info text-white">{{ $client->type }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Industri:</strong></td>
                                        <td>
                                            @if($client->industri)
                                                <span class="badge badge-success text-white">{{ $client->industri }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Telepon:</strong></td>
                                        <td>{{ $client->telepon ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Alamat:</strong></td>
                                        <td>{{ $client->alamat ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Maps:</strong></td>
                                        <td>
                                            @if($client->maps)
                                                <a href="{{ $client->maps }}" target="_blank" class="btn btn-info btn-sm">
                                                    <i class="fas fa-map-marker-alt"></i> Lihat di Maps
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Dibuat:</strong></td>
                                        <td>{{ $client->created_at->format('d M Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Terakhir Update:</strong></td>
                                        <td>{{ $client->updated_at->format('d M Y H:i') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>

        </div>
        <!-- #/ container -->
    </div>

@endsection