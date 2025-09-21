@extends('app.master')

@section('konten')

    <div class="content-body">

        <div class="row page-titles mx-0 mt-2">

            <h3 class="col p-md-0">Role Management</h3>

            <div class="col p-md-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Role</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Detail</a></li>
                </ol>
            </div>

        </div>

        <div class="container-fluid">

            <div class="card">

                <div class="card-header pt-4">
                    <a href="{{ route('role') }}" class="btn btn-primary float-right"><i class="fa fa-arrow-left"></i> &nbsp
                        KEMBALI</a>
                    <h4>Detail Role: {{ $role->display_name }}</h4>

                </div>
                <div class="card-body pt-0">

                    <div class="row">

                        <div class="col-lg-8">

                            <div class="card">
                                <div class="card-header">
                                    <h5>Informasi Role</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="150"><strong>Nama Role:</strong></td>
                                            <td>{{ $role->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Display Name:</strong></td>
                                            <td>{{ $role->display_name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Deskripsi:</strong></td>
                                            <td>{{ $role->description ?? 'Tidak ada deskripsi' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                @if($role->is_active)
                                                    <span class="badge badge-success">Aktif</span>
                                                @else
                                                    <span class="badge badge-danger">Tidak Aktif</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Dibuat:</strong></td>
                                            <td>{{ $role->created_at->format('d M Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Diupdate:</strong></td>
                                            <td>{{ $role->updated_at->format('d M Y H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="card mt-4">
                                <div class="card-header">
                                    <h5>Permissions ({{ $role->permissions->count() }})</h5>
                                </div>
                                <div class="card-body">
                                    @if($role->permissions->count() > 0)
                                        <div class="row">
                                            @foreach($role->permissions->groupBy('group') as $group => $groupPermissions)
                                                <div class="col-md-6 mb-3">
                                                    <div class="card border-left-primary">
                                                        <div class="card-header">
                                                            <h6 class="mb-0">{{ ucfirst($group) }}</h6>
                                                        </div>
                                                        <div class="card-body">
                                                            @foreach($groupPermissions as $permission)
                                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                                    <div>
                                                                        <strong>{{ $permission->display_name }}</strong>
                                                                        @if($permission->description)
                                                                            <br><small
                                                                                class="text-muted">{{ $permission->description }}</small>
                                                                        @endif
                                                                    </div>
                                                                    <small class="text-muted">{{ $permission->name }}</small>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted">Role ini belum memiliki permission</p>
                                    @endif
                                </div>
                            </div>

                            <div class="card mt-4">
                                <div class="card-header">
                                    <h5>Users dengan Role Ini ({{ $role->users->count() }})</h5>
                                </div>
                                <div class="card-body">
                                    @if($role->users->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Nama</th>
                                                        <th>Email</th>
                                                        <th>Level</th>
                                                        <th>Bergabung</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($role->users as $user)
                                                        <tr>
                                                            <td>
                                                                @if($user->foto)
                                                                    <img src="{{ asset('gambar/user/' . $user->foto) }}"
                                                                        style="width: 30px" class="mr-2">
                                                                @else
                                                                    <img src="{{ asset('gambar/sistem/user.png') }}" style="width: 30px"
                                                                        class="mr-2">
                                                                @endif
                                                                {{ $user->name }}
                                                            </td>
                                                            <td>{{ $user->email }}</td>
                                                            <td>{{ $user->level }}</td>
                                                            <td>{{ $user->created_at->format('d M Y') }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted">Tidak ada user yang menggunakan role ini</p>
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
                                    <a href="{{ route('role.edit', $role->id) }}" class="btn btn-warning btn-block mb-2">
                                        <i class="fa fa-edit"></i> Edit Role
                                    </a>

                                    @if($role->users->count() == 0)
                                        <button type="button" class="btn btn-danger btn-block" data-toggle="modal"
                                            data-target="#hapus_role_{{ $role->id }}">
                                            <i class="fa fa-trash"></i> Hapus Role
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-danger btn-block" disabled
                                            title="Tidak dapat dihapus karena sedang digunakan">
                                            <i class="fa fa-trash"></i> Hapus Role
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <!-- modal hapus -->
                            <form method="POST" action="{{ route('role.destroy', $role->id) }}">
                                <div class="modal fade" id="hapus_role_{{$role->id}}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Peringatan!</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Yakin ingin menghapus role <strong>{{ $role->display_name }}</strong>?
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
