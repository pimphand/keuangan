@extends('app.master')

@section('konten')

    <div class="content-body">

        <div class="row page-titles mx-0 mt-2">

            <h3 class="col p-md-0">Role Management</h3>

            <div class="col p-md-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Role</a></li>
                </ol>
            </div>

        </div>

        <div class="container-fluid">

            <div class="card">

                <div class="card-header pt-4">
                    <a href="{{ route('role.create') }}" class="btn btn-primary float-right"><i class="fa fa-plus"></i>
                        &nbsp TAMBAH ROLE</a>
                    <h4>Data Role Sistem</h4>

                </div>
                <div class="card-body pt-0">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive">

                        <table class="table table-bordered" id="table-datatable">
                            <thead>
                                <tr>
                                    <th width="1%">NO</th>
                                    <th>NAMA ROLE</th>
                                    <th>DISPLAY NAME</th>
                                    <th>DESKRIPSI</th>
                                    <th>PERMISSIONS</th>
                                    <th>STATUS</th>
                                    <th class="text-center" width="15%">OPSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                  @endphp
                                @foreach($roles as $role)
                                    <tr>
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td><strong>{{ $role->name }}</strong></td>
                                        <td>{{ $role->display_name }}</td>
                                        <td>{{ $role->description ?? '-' }}</td>
                                        <td>
                                            @if($role->permissions->count() > 0)
                                                @foreach($role->permissions->take(3) as $permission)
                                                    <span class="badge badge-secondary mr-1">{{ $permission->display_name }}</span>
                                                @endforeach
                                                @if($role->permissions->count() > 3)
                                                    <span class="badge badge-light">+{{ $role->permissions->count() - 3 }}
                                                        lainnya</span>
                                                @endif
                                            @else
                                                <span class="text-muted">Tidak ada permission</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($role->is_active)
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-danger">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-center">
                                                <a href="{{ route('role.show', $role->id) }}" class="btn btn-info btn-sm"
                                                    title="Detail">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('role.edit', $role->id) }}" class="btn btn-warning btn-sm"
                                                    title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                @if($role->users->count() == 0)
                                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                                        data-target="#hapus_role_{{ $role->id }}" title="Hapus">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>

                                            <!-- modal hapus -->
                                            <form method="POST" action="{{ route('role.destroy', $role->id) }}">
                                                <div class="modal fade" id="hapus_role_{{$role->id}}" tabindex="-1"
                                                    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Peringatan!</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Yakin ingin menghapus role
                                                                    <strong>{{ $role->display_name }}</strong>?
                                                                </p>
                                                                @csrf
                                                                {{ method_field('DELETE') }}
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default"
                                                                    data-dismiss="modal"><i class="ti-close m-r-5 f-s-12"></i>
                                                                    Batal</button>
                                                                <button type="submit" class="btn btn-primary"><i
                                                                        class="fa fa-paper-plane m-r-5"></i> Ya, Hapus</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                </div>

            </div>

        </div>
        <!-- #/ container -->
    </div>

@endsection