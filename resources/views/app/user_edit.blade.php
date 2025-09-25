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
                    <h4>Edit Pengguna Sistem</h4>

                </div>
                <div class="card-body pt-0">

                    <form method="POST" action="{{ route('user.update', ['id' => $user->id]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        {{ method_field('PUT') }}

                        <div class="row">
                            <!-- Kolom Kiri -->
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="form-group has-feedback">
                                        <label class="text-dark">Nama</label>
                                        <input id="nama" type="text" placeholder="nama"
                                            class="form-control @error('nama') is-invalid @enderror" name="nama"
                                            value="{{ old('nama', $user->name) }}" autocomplete="off">
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
                                            value="{{ old('email', $user->email) }}" autocomplete="off">

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
                                                    <option value="{{ $role->id }}" {{ in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())) ? 'selected' : '' }}>
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
                                        <small class="text-muted"><i>Kosongkan jika tidak ingin mengubah
                                                password</i></small>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Kolom Kanan -->

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="form-group has-feedback">
                                        <label class="text-dark">Tanggal Gajian</label>
                                        <input id="tanggal_gajian" type="text" maxlength="2" placeholder="Tanggal (01-31)"
                                            class="form-control @error('tanggal_gajian') is-invalid @enderror"
                                            name="tanggal_gajian"
                                            value="{{ old('tanggal_gajian', $user->tanggal_gajian ?? '') }}"
                                            autocomplete="off">
                                        <small class="text-muted">Masukkan tanggal (1-31)</small>
                                        @error('tanggal_gajian')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-group has-feedback">
                                        <label class="text-dark">Gaji</label>
                                        <input id="saldo" type="number" min="0" placeholder=" Gaji"
                                            class="form-control @error('saldo') is-invalid @enderror" name="saldo"
                                            value="{{ old('saldo', (int) $user->saldo) }}" autocomplete="off">
                                        <span id="text_saldo">Rp. 0</span>
                                        @error('saldo')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-group has-feedback">
                                        <label class="text-dark">Tunjangan</label>
                                        <input id="tunjangan" type="number" step="0.01" placeholder="Tunjangan"
                                            class="form-control @error('tunjangan') is-invalid @enderror" name="tunjangan"
                                            value="{{ old('saldo', (int) $user->tunjangan) }}" autocomplete="off">
                                        @error('tunjangan')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-group has-feedback">
                                        <label class="text-dark">Limit Kasbon</label>
                                        <input id="kasbon" type="number" min="0" placeholder=" Limit Kasbon"
                                            class="form-control @error('kasbon') is-invalid @enderror" name="kasbon"
                                            value="{{ old('kasbon', (int) $user->kasbon) }}" autocomplete="off">
                                        <span id="text_kasbon">Rp. 0</span>
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
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>

            </div>

        </div>
        <!-- #/ container -->
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const saldoInput = document.getElementById('saldo');
            const kasbonInput = document.getElementById('kasbon');
            const textSaldo = document.getElementById('text_saldo');
            const textKasbon = document.getElementById('text_kasbon');
            const tanggalGajianInput = document.getElementById('tanggal_gajian');

            // Format number with thousand separators (dots)
            function formatNumber(num) {
                if (!num || isNaN(num) || num === 0) return '0';
                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            // Update display text for saldo
            function updateSaldoDisplay() {
                if (saldoInput.value && !isNaN(saldoInput.value)) {
                    textSaldo.textContent = 'Rp. ' + formatNumber(saldoInput.value);
                } else {
                    textSaldo.textContent = 'Rp. 0';
                }
            }

            // Update display text for kasbon
            function updateKasbonDisplay() {
                if (kasbonInput.value && !isNaN(kasbonInput.value)) {
                    textKasbon.textContent = 'Rp. ' + formatNumber(kasbonInput.value);
                } else {
                    textKasbon.textContent = 'Rp. 0';
                }
            }

            // Update display on input change for saldo
            saldoInput.addEventListener('input', updateSaldoDisplay);
            saldoInput.addEventListener('blur', updateSaldoDisplay);

            // Update display on input change for kasbon
            kasbonInput.addEventListener('input', updateKasbonDisplay);
            kasbonInput.addEventListener('blur', updateKasbonDisplay);

            // Initialize display
            updateSaldoDisplay();
            updateKasbonDisplay();

            // Validation for tanggal gajian input
            tanggalGajianInput.addEventListener('input', function (e) {
                let value = e.target.value;

                // Only allow numbers
                value = value.replace(/[^0-9]/g, '');

                // Limit to 2 characters
                if (value.length > 2) {
                    value = value.substring(0, 2);
                }

                // Check if value exceeds 31
                if (value && parseInt(value) > 31) {
                    value = '31';
                }

                // Check if value is 0
                if (value === '0') {
                    value = '';
                }

                e.target.value = value;
            });

            // Additional validation on blur
            tanggalGajianInput.addEventListener('blur', function (e) {
                let value = e.target.value;
                if (value && parseInt(value) > 31) {
                    e.target.value = '31';
                }
            });

        });
    </script>

@endsection