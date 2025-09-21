@extends('app.master')

@section('konten')

    <div class="content-body">

        <div class="row page-titles mx-0 mt-2">

            <h3 class="col p-md-0">Pengguna</h3>

            <div class="col p-md-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Pengguna</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Tambah</a></li>
                </ol>
            </div>

        </div>

        <div class="container-fluid">

            <div class="card">

                <div class="card-header pt-4">
                    <a href="{{ route('user') }}" class="btn btn-primary float-right"><i class="fa fa-arrow-left"></i> &nbsp
                        KEMBALI</a>
                    <h4>Tambah Pengguna Sistem</h4>

                </div>
                <div class="card-body pt-0">

                    <div class="row">

                        <div class="col-lg-5">

                            <form method="POST" action="{{ route('user.aksi') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <div class="form-group has-feedback">
                                        <label class="text-dark">Nama</label>
                                        <input id="nama" type="text" placeholder="nama"
                                            class="form-control @error('nama') is-invalid @enderror" name="nama"
                                            value="{{ old('nama') }}" autocomplete="off">
                                        @error('nama')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-group has-feedback">
                                        <label class="text-dark">Email</label>
                                        <input id="email" type="email" placeholder="Email"
                                            class="form-control @error('email') is-invalid @enderror" name="email"
                                            value="{{ old('email') }}" autocomplete="off">

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="form-group has-feedback">
                                        <label class="text-dark">Role</label>
                                        <select class="form-control @error('roles') is-invalid @enderror" name="roles[]"
                                            multiple>
                                            @foreach($roles as $role)
                                                @if($role->display_name != "Super Admin")
                                                    <option value="{{ $role->id }}" {{ in_array($role->id, old('roles', [])) ? 'selected' : '' }}>
                                                        {{ $role->display_name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Pilih satu atau lebih role (tekan Ctrl untuk memilih
                                            multiple)</small>

                                        @error('roles')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="form-group has-feedback">
                                        <label class="text-dark">Password</label>
                                        <input id="password" type="password" placeholder="Password"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            autocomplete="current-password">

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-group has-feedback">
                                        <label class="text-dark">Gaji</label>
                                        <input id="saldo" type="number" step="0.01" placeholder="Gaji"
                                            class="form-control @error('saldo') is-invalid @enderror" name="saldo"
                                            value="{{ old('saldo') }}" autocomplete="off">
                                        @error('saldo')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-group has-feedback">
                                        <label class="text-dark">Limit Kasbon</label>
                                        <input id="kasbon" type="number" step="0.01" placeholder="Limit Kasbon"
                                            class="form-control @error('kasbon') is-invalid @enderror" name="kasbon"
                                            value="{{ old('kasbon') }}" autocomplete="off">
                                        @error('kasbon')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-group has-feedback">
                                        <label class="text-dark">Foto Profil</label>
                                        <br>
                                        <input id="foto" type="file" placeholder="foto"
                                            class="@error('foto') is-invalid @enderror" name="foto"
                                            value="{{ old('foto') }}" autocomplete="off">
                                        <br>
                                        <small class="text-muted"><i>Boleh dikosongkan</i></small>
                                        @error('foto')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
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