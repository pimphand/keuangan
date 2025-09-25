@extends('app.master')

@section('konten')

    <div class="content-body">

        <div class="row page-titles mx-0 mt-2">

            <h3 class="col p-md-0">Edit Client: {{ $client->nama }}</h3>

            <div class="col p-md-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('client.index') }}">Client</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit Client</a></li>
                </ol>
            </div>
        </div>

        <div class="container-fluid">

            <div class="card">

                <div class="card-header pt-4">
                    <h3 class="card-title">Edit Client: {{ $client->nama }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('client.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">

                    <form action="{{ route('client.update', $client) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama">Nama Client <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                                        name="nama" value="{{ old('nama', $client->nama) }}" required>
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="telepon">Nomor Telepon</label>
                                    <input type="text" class="form-control @error('telepon') is-invalid @enderror"
                                        id="telepon" name="telepon" value="{{ old('telepon', $client->telepon) }}">
                                    @error('telepon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">Type</label>
                                    <select class="form-control @error('type') is-invalid @enderror" id="type" name="type">
                                        <option value="">Pilih Type</option>
                                        <option value="Pemerintahan" {{ old('type', $client->type) == 'Pemerintahan' ? 'selected' : '' }}>Pemerintahan</option>
                                        <option value="Swasta" {{ old('type', $client->type) == 'Swasta' ? 'selected' : '' }}>
                                            Swasta</option>
                                        <option value="BUMN" {{ old('type', $client->type) == 'BUMN' ? 'selected' : '' }}>BUMN
                                        </option>
                                        <option value="BUMD" {{ old('type', $client->type) == 'BUMD' ? 'selected' : '' }}>BUMD
                                        </option>
                                        <option value="Lainnya" {{ old('type', $client->type) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="industri">Industri</label>
                                    <select class="form-control @error('industri') is-invalid @enderror" id="industri"
                                        name="industri">
                                        <option value="">Pilih Industri</option>
                                        <option value="Perdagangan" {{ old('industri', $client->industri) == 'Perdagangan' ? 'selected' : '' }}>Perdagangan</option>
                                        <option value="Teknologi" {{ old('industri', $client->industri) == 'Teknologi' ? 'selected' : '' }}>Teknologi</option>
                                        <option value="Manufaktur" {{ old('industri', $client->industri) == 'Manufaktur' ? 'selected' : '' }}>Manufaktur</option>
                                        <option value="Konstruksi" {{ old('industri', $client->industri) == 'Konstruksi' ? 'selected' : '' }}>Konstruksi</option>
                                        <option value="Pertanian" {{ old('industri', $client->industri) == 'Pertanian' ? 'selected' : '' }}>Pertanian</option>
                                        <option value="Perkebunan" {{ old('industri', $client->industri) == 'Perkebunan' ? 'selected' : '' }}>Perkebunan</option>
                                        <option value="Pariwisata" {{ old('industri', $client->industri) == 'Pariwisata' ? 'selected' : '' }}>Pariwisata</option>
                                        <option value="Lainnya" {{ old('industri', $client->industri) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('industri')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat"
                                rows="3">{{ old('alamat', $client->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="logo">Logo</label>
                            @if($client->logo)
                                <div class="mb-2">
                                    <img src="{{ $client->logo_url }}" alt="{{ $client->nama }}" class="img-thumbnail"
                                        style="width: 100px; height: 100px; object-fit: cover;">
                                    <small class="form-text text-muted">Logo saat ini</small>
                                </div>
                            @endif
                            <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo"
                                name="logo" accept="image/*">
                            <small class="form-text text-muted">Format yang didukung: JPEG, PNG, JPG, GIF, WebP. Maksimal
                                2MB. Kosongkan jika tidak ingin mengubah logo.</small>
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="maps">Maps (Link atau Embed)</label>
                            <textarea class="form-control @error('maps') is-invalid @enderror" id="maps" name="maps"
                                rows="3"
                                placeholder="Masukkan link Google Maps atau embed code">{{ old('maps', $client->maps) }}</textarea>
                            @error('maps')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update
                            </button>
                            <a href="{{ route('client.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>

                </div>

            </div>

        </div>
        <!-- #/ container -->
    </div>

@endsection