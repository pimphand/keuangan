@extends('app.master')

@section('konten')

    <div class="content-body">

        <div class="row page-titles mx-0 mt-2">

            <h3 class="col p-md-0">Permission Management</h3>

            <div class="col p-md-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Permission</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Detail</a></li>
                </ol>
            </div>

        </div>

        <div class="container-fluid">

            <div class="card">

                <div class="card-header pt-4">
                    <a href="{{ route('permission') }}" class="btn btn-primary float-right"><i class="fa fa-arrow-left"></i>
                        &nbsp KEMBALI</a>
                    <h4>Detail Permission: {{ $permission->display_name }}</h4>

                </div>
                <div class="card-body pt-0">

                    <div class="row">

                        <div class="col-lg-8">

                            <div class="card">
                                <div class="card-header">
                                    <h5>Informasi Permission</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="150"><strong>Nama Permission:</strong></td>
                                            <td>{{ $permission->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Display Name:</strong></td>
                                            <td>{{ $permission->display_name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Group:</strong></td>
                                            <td>{{ $permission->group ?? 'Tidak ada group' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Deskripsi:</strong></td>
                                            <td>{{ $permission->description ?? 'Tidak ada deskripsi' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                @if($permission->is_active)
                                                    <span class="badge badge-success">Aktif</span>
                                                @else
                                                    <span class="badge badge-danger">Tidak Aktif</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Dibuat:</strong></td>
                                            <td>{{ $permission->created_at->format('d M Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Diupdate:</strong></td>
                                            <td>{{ $permission->updated_at->format('d M Y H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="card mt-4">
                                <div class="card-header">
                                    <h5>Role yang Menggunakan Permission Ini ({{ $permission->roles->count() }})</h5>
                                </div>
                                <div class="card-body">
                                    @if($permission->roles->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Nama Role</th>
                                                        <th>Display Name</th>
                                                        <th>Deskripsi</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($permission->roles as $role)
                                                        <tr>
                                                            <td><strong>{{ $role->name }}</strong></td>
                                                            <td>{{ $role->display_name }}</td>
                                                            <td>{{ $role->description ?? '-' }}</td>
                                                            <td>
                                                                @if($role->is_active)
                                                                    <span class="badge badge-success">Aktif</span>
                                                                @else
                                                                    <span class="badge badge-danger">Tidak Aktif</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted">Permission ini belum digunakan oleh role manapun</p>
                                    @endif
                                </div>
                            </div>

                        </div>

                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Aksi</h5>
                                </div>
                                <div class="card-body">
                                    <a href="{{ route('permission.edit', $permission->id) }}"
                                        class="btn btn-warning btn-block mb-2">
                                        <i class="fa fa-edit"></i> Edit Permission
                                    </a>

                                    @if($permission->roles->count() == 0)
                                        <button type="button" class="btn btn-danger btn-block" data-toggle="modal"
                                            data-target="#hapus_permission_{{ $permission->id }}">
                                            <i class="fa fa-trash"></i> Hapus Permission
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-danger btn-block" disabled
                                            title="Tidak dapat dihapus karena sedang digunakan">
                                            <i class="fa fa-trash"></i> Hapus Permission
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <!-- modal hapus -->
                            <form method="POST" action="{{ route('permission.destroy', $permission->id) }}">
                                <div class="modal fade" id="hapus_permission_{{$permission->id}}" tabindex="-1"
                                    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Peringatan!</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Yakin ingin menghapus permission
                                                    <strong>{{ $permission->display_name }}</strong>?
                                                </p>
                                                @csrf
                                                {{ method_field('DELETE') }}
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal"><i
                                                        class="ti-close m-r-5 f-s-12"></i> Batal</button>
                                                <button type="submit" class="btn btn-primary"><i
                                                        class="fa fa-paper-plane m-r-5"></i> Ya, Hapus</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>

                </div>

            </div>

        </div>
        <!-- #/ container -->
    </div>

@endsection