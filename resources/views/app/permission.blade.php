@extends('app.master')

@section('konten')

    <div class="content-body">

        <div class="row page-titles mx-0 mt-2">

            <h3 class="col p-md-0">Permission Management</h3>

            <div class="col p-md-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Permission</a></li>
                </ol>
            </div>

        </div>

        <div class="container-fluid">

            <div class="card">

                <div class="card-header pt-4">
                    <a href="{{ route('permission.create') }}" class="btn btn-primary float-right"><i
                            class="fa fa-plus"></i> &nbsp TAMBAH PERMISSION</a>
                    <h4>Data Permission Sistem</h4>

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

                    @foreach($permissions as $group => $groupPermissions)
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">{{ ucfirst($group) }} Permissions</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($groupPermissions as $permission)
                                        <div class="col-md-6 col-lg-4 mb-3">
                                            <div class="card border-left-primary">
                                                <div class="card-body">
                                                    <h6 class="card-title">{{ $permission->display_name }}</h6>
                                                    <p class="card-text text-muted small">
                                                        {{ $permission->description ?? 'Tidak ada deskripsi' }}</p>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <small class="text-muted">{{ $permission->name }}</small>
                                                        <div>
                                                            <a href="{{ route('permission.show', $permission->id) }}"
                                                                class="btn btn-sm btn-info" title="Detail">
                                                                <i class="fa fa-eye"></i>
                                                            </a>
                                                            <a href="{{ route('permission.edit', $permission->id) }}"
                                                                class="btn btn-sm btn-warning" title="Edit">
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                            @if($permission->roles->count() == 0)
                                                                <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                                                    data-target="#hapus_permission_{{ $permission->id }}" title="Hapus">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
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
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Yakin ingin menghapus permission
                                                                    <strong>{{ $permission->display_name }}</strong>?</p>
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
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>

            </div>

        </div>
        <!-- #/ container -->
    </div>

@endsection
