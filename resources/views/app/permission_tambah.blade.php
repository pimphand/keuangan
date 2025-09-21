@extends('app.master')

@section('konten')

    <div class="content-body">

        <div class="row page-titles mx-0 mt-2">

            <h3 class="col p-md-0">Permission Management</h3>

            <div class="col p-md-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Permission</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Tambah</a></li>
                </ol>
            </div>

        </div>

        <div class="container-fluid">

            <div class="card">

                <div class="card-header pt-4">
                    <a href="{{ route('permission') }}" class="btn btn-primary float-right"><i class="fa fa-arrow-left"></i>
                        &nbsp KEMBALI</a>
                    <h4>Tambah Permission Baru</h4>

                </div>
                <div class="card-body pt-0">

                    <div class="row">

                        <div class="col-lg-6">

                            <form method="POST" action="{{ route('permission.store') }}">
                                @csrf

                                <div class="form-group">
                                    <div class="form-group has-feedback">
                                        <label class="text-dark">Nama Permission</label>
                                        <input id="name" type="text" placeholder="Nama permission (contoh: user.view)"
                                            class="form-control @error('name') is-invalid @enderror" name="name"
                                            value="{{ old('name') }}" autocomplete="off">
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
                                            name="display_name" value="{{ old('display_name') }}" autocomplete="off">
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
                                            value="{{ old('group') }}" autocomplete="off">
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
                                            name="description" rows="3">{{ old('description') }}</textarea>
                                        @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Simpan Permission</button>
                                    <a href="{{ route('permission') }}" class="btn btn-secondary">Batal</a>
                                </div>
                            </form>
                        </div>

                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Contoh Group Permission</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><strong>user</strong> - Manajemen pengguna</li>
                                        <li class="list-group-item"><strong>role</strong> - Manajemen role</li>
                                        <li class="list-group-item"><strong>permission</strong> - Manajemen permission</li>
                                        <li class="list-group-item"><strong>transaksi</strong> - Manajemen transaksi</li>
                                        <li class="list-group-item"><strong>kategori</strong> - Manajemen kategori</li>
                                        <li class="list-group-item"><strong>laporan</strong> - Manajemen laporan</li>
                                        <li class="list-group-item"><strong>pegawai</strong> - Manajemen pegawai</li>
                                        <li class="list-group-item"><strong>absensi</strong> - Manajemen absensi</li>
                                        <li class="list-group-item"><strong>kasbon</strong> - Manajemen kasbon</li>
                                        <li class="list-group-item"><strong>pengumuman</strong> - Manajemen pengumuman</li>
                                    </ul>
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
