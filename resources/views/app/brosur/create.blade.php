@extends('app.master')

@section('konten')

    <div class="content-body">

        <div class="row page-titles mx-0 mt-2">

            <h3 class="col p-md-0">Tambah Brosur</h3>

            <div class="col p-md-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('brosur.index') }}">Brosur</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Tambah</a></li>
                </ol>
            </div>
        </div>

        <div class="container-fluid">

            <div class="card">

                <div class="card-header pt-4">
                    <h3 class="card-title">Tambah Brosur</h3>
                    <div class="card-tools">
                        <a href="{{ route('brosur.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form action="{{ route('brosur.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama">Nama Brosur <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                                        name="nama" value="{{ old('nama') }}" required>
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="harga">Harga <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('harga') is-invalid @enderror"
                                        id="harga" name="harga" value="{{ old('harga') }}" min="0" required>
                                    @error('harga')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status"
                                        name="status" required>
                                        <option value="">Pilih Status</option>
                                        <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="gambar">Gambar <span class="text-danger">*</span></label>
                            <input type="file" class="form-control-file @error('gambar') is-invalid @enderror" id="gambar"
                                name="gambar" accept="image/*" required>
                            <small class="form-text text-muted">Format: JPEG, PNG, JPG, GIF, WebP. Maksimal 2MB</small>

                            <!-- Image Preview Container -->
                            <div id="image-preview" class="mt-3" style="display: none;">
                                <div class="text-center">
                                    <img id="preview-img" src="" alt="Preview" class="img-thumbnail"
                                        style="max-width: 300px; max-height: 300px; object-fit: cover;">
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-danger" id="remove-preview">
                                            <i class="fas fa-times"></i> Hapus Preview
                                        </button>
                                    </div>
                                </div>
                            </div>

                            @error('gambar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="file">File Brosur</label>
                            <input type="file" class="form-control-file @error('file') is-invalid @enderror" id="file"
                                name="file" accept=".pdf,.doc,.docx">
                            <small class="form-text text-muted">Format: PDF, DOC, DOCX. Maksimal 10MB</small>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Tag Brosur</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terbaru" name="tags[]" value="terbaru"
                                    {{ in_array('terbaru', old('tags', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="terbaru">
                                    Terbaru
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terpopuler" name="tags[]"
                                    value="terpopuler" {{ in_array('terpopuler', old('tags', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="terpopuler">
                                    Terpopuler
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="promo" name="tags[]" value="promo" {{ in_array('promo', old('tags', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="promo">
                                    Promo
                                </label>
                            </div>
                            @error('tags')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi"
                                name="deskripsi" rows="4">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Spesifikasi Brosur</label>
                            <div id="spesifikasi-container">
                                <div class="row mb-2 spesifikasi-row">
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="spesifikasi_key[]"
                                            placeholder="Spesifikasi" value="{{ old('spesifikasi_key.0') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger btn-sm remove-spesifikasi"
                                            style="display: none;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-success btn-sm" id="add-spesifikasi">
                                <i class="fas fa-plus"></i> Tambah Spesifikasi
                            </button>
                            <small class="form-text text-muted">Tambahkan spesifikasi brosur seperti ukuran, material,
                                warna, dll.</small>
                            @error('spesifikasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                            <a href="{{ route('brosur.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>

                </div>

            </div>

        </div>
        <!-- #/ container -->
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('spesifikasi-container');
            const addButton = document.getElementById('add-spesifikasi');
            const gambarInput = document.getElementById('gambar');
            const imagePreview = document.getElementById('image-preview');
            const previewImg = document.getElementById('preview-img');
            const removePreviewBtn = document.getElementById('remove-preview');
            let rowCount = 1;

            // Image preview functionality
            gambarInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        previewImg.src = e.target.result;
                        imagePreview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Remove preview functionality
            removePreviewBtn.addEventListener('click', function () {
                gambarInput.value = '';
                imagePreview.style.display = 'none';
                previewImg.src = '';
            });

            // Add new specification row
            addButton.addEventListener('click', function () {
                const newRow = document.createElement('div');
                newRow.className = 'row mb-2 spesifikasi-row';
                newRow.innerHTML = `
                                                                        <div class="col-md-10">
                                                                            <input type="text" class="form-control" name="spesifikasi_key[]" placeholder="Spesifikasi" value="{{ old('spesifikasi_key.${rowCount}') }}">
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <button type="button" class="btn btn-danger btn-sm remove-spesifikasi">
                                                                                <i class="fas fa-trash"></i>
                                                                            </button>
                                                                        </div>
                                                                    `;

                container.appendChild(newRow);
                rowCount++;
                updateRemoveButtons();
            });

            // Remove specification row
            container.addEventListener('click', function (e) {
                if (e.target.closest('.remove-spesifikasi')) {
                    e.target.closest('.spesifikasi-row').remove();
                    updateRemoveButtons();
                }
            });

            // Update remove buttons visibility
            function updateRemoveButtons() {
                const rows = container.querySelectorAll('.spesifikasi-row');
                const removeButtons = container.querySelectorAll('.remove-spesifikasi');

                removeButtons.forEach((button, index) => {
                    if (rows.length > 1) {
                        button.style.display = 'inline-block';
                    } else {
                        button.style.display = 'none';
                    }
                });
            }

            // Initialize remove buttons
            updateRemoveButtons();
        });
    </script>

@endsection
