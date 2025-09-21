@extends('app.master')

@section('konten')

    <div class="content-body">

        <div class="row page-titles mx-0 mt-2">

            <h3 class="col p-md-0">Permission Management</h3>

            <div class="col p-md-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Permission</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit</a></li>
                </ol>
            </div>

        </div>

        <div class="container-fluid">

            <div class="card">

                <div class="card-header pt-4">
                    <a href="{{ route('permission') }}" class="btn btn-primary float-right"><i class="fa fa-arrow-left"></i>
                        &nbsp KEMBALI</a>
                    <h4>Edit Permission: {{ $permission->display_name }}</h4>

                </div>
                <div class="card-body pt-0">

                    <div class="row">

                        <div class="col-lg-6">

                            <form method="POST" action="{{ route('permission.update', $permission->id) }}">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <div class="form-group has-feedback">
                                        <label class="text-dark">Nama Permission</label>
                                        <input id="name" type="text" placeholder="Nama permission (contoh: user.view)"
                                            class="form-control @error('name') is-invalid @enderror" name="name"
                                            value="{{ old('name', $permission->name) }}" autocomplete="off">
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-group has-feedback">
                                        <label class="text-dark">Display Name</label>
                                        <input id="display_name" type="text" placeholder="Nama yang ditampilkan"
                                            class="form-control @error('display_name') is-invalid @enderror"
                                            name="display_name" value="{{ old('display_name', $permission->display_name) }}"
                                            autocomplete="off">
                                        @error('display_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-group has-feedback">
                                        <label class="text-dark">Group</label>
                                        <input id="group" type="text"
                                            placeholder="Group permission (contoh: user, transaksi)"
                                            class="form-control @error('group') is-invalid @enderror" name="group"
                                            value="{{ old('group', $permission->group) }}" autocomplete="off">
                                        <small class="text-muted">Gunakan untuk mengelompokkan permission</small>
                                        @error('group')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-group has-feedback">
                                        <label class="text-dark">Deskripsi</label>
                                        <textarea id="description" placeholder="Deskripsi permission"
                                            class="form-control @error('description') is-invalid @enderror"
                                            name="description"
                                            rows="3">{{ old('description', $permission->description) }}</textarea>
                                        @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Update Permission</button>
                                    <a href="{{ route('permission') }}" class="btn btn-secondary">Batal</a>
                                </div>
                            </form>
                        </div>

                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Role yang Menggunakan Permission Ini</h5>
                                </div>
                                <div class="card-body">
                                    @if($permission->roles->count() > 0)
                                        @foreach($permission->roles as $role)
                                            <span class="badge badge-info mr-2 mb-2">{{ $role->display_name }}</span>
                                        @endforeach
                                    @else
                                        <p class="text-muted">Permission ini belum digunakan oleh role manapun</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>
        <!-- #/ container -->
    </div>

@endsection
