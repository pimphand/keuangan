@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-bullhorn mr-2"></i>
                            Manajemen Pengumuman
                        </h3>
                        <a href="{{ route('pengumuman.admin.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus mr-1"></i>
                            Tambah Pengumuman
                        </a>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul</th>
                                        <th>Prioritas</th>
                                        <th>Status</th>
                                        <th>Target Role</th>
                                        <th>Views</th>
                                        <th>Tanggal Dibuat</th>
                                        <th>Dibuat Oleh</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pengumuman as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $item->judul }}</strong>
                                                @if($item->gambar)
                                                    <i class="fas fa-image text-info ml-1" title="Memiliki gambar"></i>
                                                @endif
                                                @if($item->link)
                                                    <i class="fas fa-link text-success ml-1" title="Memiliki link"></i>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $item->priority_color }}">
                                                    {{ $item->priority_text }}
                                                </span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge {{ $item->status === 'aktif' ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ ucfirst($item->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($item->target_role)
                                                    @foreach($item->target_role as $role)
                                                        <span class="badge bg-info me-1">{{ ucfirst($role) }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">Semua</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    <i class="fas fa-eye"></i> {{ $item->views_count }}
                                                </span>
                                            </td>
                                            <td>{{ $item->tanggal_formatted }}</td>
                                            <td>{{ $item->creator ? $item->creator->name : 'Sistem' }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('pengumuman.admin.edit', $item->id) }}"
                                                        class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('pengumuman.admin.destroy', $item->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted">
                                                <i class="fas fa-bullhorn fa-2x mb-2"></i><br>
                                                Belum ada pengumuman
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($pengumuman->hasPages())
                            <div class="d-flex justify-content-center mt-3">
                                {{ $pengumuman->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection