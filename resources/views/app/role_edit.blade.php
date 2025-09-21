@extends('app.master')

@section('konten')

    <div class="content-body">

        <div class="row page-titles mx-0 mt-2">

            <h3 class="col p-md-0">Role Management</h3>

            <div class="col p-md-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Role</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit</a></li>
                </ol>
            </div>

        </div>

        <div class="container-fluid">

            <div class="card">

                <div class="card-header pt-4">
                    <a href="{{ route('role') }}" class="btn btn-primary float-right"><i class="fa fa-arrow-left"></i> &nbsp
                        KEMBALI</a>
                    <h4>Edit Role: {{ $role->display_name }}</h4>

                </div>
                <div class="card-body pt-0">

                    <div class="row">

                        <div class="col-lg-8">

                            <form method="POST" action="{{ route('role.update', $role->id) }}">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <div class="form-group has-feedback">
                                        <label class="text-dark">Nama Role</label>
                                        <input id="name" type="text" placeholder="Nama role (contoh: admin, manager)"
                                            class="form-control @error('name') is-invalid @enderror" name="name"
                                            value="{{ old('name', $role->name) }}" autocomplete="off">
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
                                            name="display_name" value="{{ old('display_name', $role->display_name) }}"
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
                                        <label class="text-dark">Deskripsi</label>
                                        <textarea id="description" placeholder="Deskripsi role"
                                            class="form-control @error('description') is-invalid @enderror"
                                            name="description"
                                            rows="3">{{ old('description', $role->description) }}</textarea>
                                        @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-group has-feedback">
                                        <label class="text-dark">Permissions</label>
                                        <div class="row">
                                            @foreach($permissions as $group => $groupPermissions)
                                                <div class="col-md-6 mb-3">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h6 class="mb-0">{{ ucfirst($group) }}</h6>
                                                        </div>
                                                        <div class="card-body">
                                                            @foreach($groupPermissions as $permission)
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="permissions[]" value="{{ $permission->id }}"
                                                                        id="permission_{{ $permission->id }}" {{ in_array($permission->id, old('permissions', $role->permissions->pluck('id')->toArray())) ? 'checked' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="permission_{{ $permission->id }}">
                                                                        {{ $permission->display_name }}
                                                                    </label>
                                                                    @if($permission->description)
                                                                        <small
                                                                            class="text-muted d-block">{{ $permission->description }}</small>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        @error('permissions')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Update Role</button>
                                    <a href="{{ route('role') }}" class="btn btn-secondary">Batal</a>
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
