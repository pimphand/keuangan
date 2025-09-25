@extends('app.master')

@section('konten')
    <div class="content-body">
        <div class="row page-titles mx-0 mt-2">
            <h3 class="col p-md-0">Buat Purchase Order</h3>
            <div class="col p-md-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.po.index') }}">Purchase Orders</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Buat</a></li>
                </ol>
            </div>
        </div>

        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Form Purchase Order</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="po-form" method="POST" action="{{ route('admin.po.store') }}">
                        @csrf
                        <input type="hidden" name="ktp_photo" id="ktp_photo_input" value="{{ old('ktp_photo') }}">
                        <div id="items-hidden-inputs"></div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Jenis Klien</label>
                                <select name="client_type" class="form-control" required>
                                    <option value="">Pilih Jenis Klien...</option>
                                    <option value="Pemerintahan" {{ old('client_type') === 'Pemerintahan' ? 'selected' : '' }}>Pemerintahan</option>
                                    <option value="Swasta" {{ old('client_type') === 'Swasta' ? 'selected' : '' }}>Swasta
                                    </option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Nama Klien</label>
                                <input type="text" name="client_name" value="{{ old('client_name') }}" class="form-control"
                                    required />
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Alamat Klien</label>
                            <textarea name="client_address" rows="3" class="form-control"
                                required>{{ old('client_address') }}</textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Nomor HP Klien</label>
                                <input type="tel" name="client_phone_number" pattern="[0-9]+" inputmode="numeric"
                                    value="{{ old('client_phone_number') }}" class="form-control" required />
                            </div>
                            <div class="form-group col-md-6">
                                <label>NIK Klien</label>
                                <input type="tel" name="client_nik" pattern="[0-9]{16}" minlength="16" maxlength="16"
                                    inputmode="numeric" value="{{ old('client_nik') }}" class="form-control" required />
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Nama Lengkap Sesuai KTP</label>
                            <input type="text" name="client_ktp_name" value="{{ old('client_ktp_name') }}"
                                class="form-control" required />
                        </div>

                        <div class="form-group">
                            <label>Foto KTP Klien</label>
                            <div>
                                <button type="button" id="capture-ktp-btn" class="btn btn-outline-primary btn-sm">Ambil Foto
                                    KTP</button>
                                <img id="ktp-preview" class="img-fluid mt-3 {{ old('ktp_photo') ? '' : 'd-none' }}"
                                    src="{{ old('ktp_photo') ?: '#' }}" alt="Pratinjau Foto KTP">
                            </div>
                            <div id="camera-view" class="mt-3" style="display: none;">
                                <video id="camera-video" autoplay playsinline style="width:100%; max-width: 480px;"></video>
                                <canvas id="camera-canvas" class="d-none"></canvas>
                                <div class="mt-2">
                                    <button type="button" id="capture-btn" class="btn btn-primary btn-sm">Ambil</button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="mb-0">Detail Item</label>
                                <button type="button" id="add-item-btn" class="btn btn-success btn-sm">Tambah Item</button>
                            </div>
                            <div class="row font-weight-bold text-muted mb-2">
                                <div class="col">Produk</div>
                                <div class="col-md-2">Jumlah</div>
                                <div class="col-md-1"></div>
                            </div>
                            <div id="item-list"></div>
                            @error('items')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Jelaskan Uraian Sistem</label>
                            <textarea name="notes" rows="4" class="form-control">{{ old('notes') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('admin.po.index') }}" class="btn btn-light">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const itemList = document.getElementById('item-list');
                const addItemBtn = document.getElementById('add-item-btn');
                const itemsHiddenInputs = document.getElementById('items-hidden-inputs');
                const captureKtpBtn = document.getElementById('capture-ktp-btn');
                const cameraView = document.getElementById('camera-view');
                const cameraVideo = document.getElementById('camera-video');
                const cameraCanvas = document.getElementById('camera-canvas');
                const captureBtn = document.getElementById('capture-btn');
                const ktpPreview = document.getElementById('ktp-preview');
                const ktpPhotoInput = document.getElementById('ktp_photo_input');
                let cameraStream = null;

                const createItemRow = (name = '', qty = 1, price = 0) => {
                    const row = document.createElement('div');
                    row.className = 'row align-items-center mb-2';

                    const nameCol = document.createElement('div');
                    nameCol.className = 'col';
                    const nameInput = document.createElement('input');
                    nameInput.className = 'form-control';
                    nameInput.placeholder = 'Nama Produk';
                    nameInput.value = name;
                    nameCol.appendChild(nameInput);

                    const qtyCol = document.createElement('div');
                    qtyCol.className = 'col-md-2';
                    const qtyInput = document.createElement('input');
                    qtyInput.type = 'number';
                    qtyInput.className = 'form-control text-center';
                    qtyInput.placeholder = 'Qty';
                    qtyInput.min = '1';
                    qtyInput.value = qty;
                    qtyCol.appendChild(qtyInput);

                    const removeCol = document.createElement('div');
                    removeCol.className = 'col-md-1';
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'btn btn-danger btn-sm';
                    removeBtn.textContent = 'Hapus';
                    removeBtn.addEventListener('click', () => row.remove());
                    removeCol.appendChild(removeBtn);

                    row.appendChild(nameCol);
                    row.appendChild(qtyCol);
                    row.appendChild(removeCol);

                    return row;
                };

                addItemBtn.addEventListener('click', () => itemList.appendChild(createItemRow()));
                itemList.appendChild(createItemRow());

                document.getElementById('po-form').addEventListener('submit', (e) => {
                    itemsHiddenInputs.innerHTML = '';
                    document.querySelectorAll('#item-list .row').forEach((row, idx) => {
                        const name = row.querySelector('input[placeholder="Nama Produk"]').value.trim();
                        const qty = parseInt(row.querySelector('input[type="number"]').value) || 0;
                        if (name && qty) {
                            const nameInput = document.createElement('input');
                            nameInput.type = 'hidden';
                            nameInput.name = `items[${idx}][name]`;
                            nameInput.value = name;
                            itemsHiddenInputs.appendChild(nameInput);

                            const qtyInput = document.createElement('input');
                            qtyInput.type = 'hidden';
                            qtyInput.name = `items[${idx}][quantity]`;
                            qtyInput.value = qty;
                            itemsHiddenInputs.appendChild(qtyInput);

                            const priceInput = document.createElement('input');
                            priceInput.type = 'hidden';
                            priceInput.name = `items[${idx}][price]`;
                            priceInput.value = 0;
                            itemsHiddenInputs.appendChild(priceInput);
                        }
                    });
                });

                captureKtpBtn.addEventListener('click', async () => {
                    try {
                        cameraView.style.display = 'block';
                        if (cameraStream) cameraStream.getTracks().forEach(t => t.stop());
                        cameraStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
                        cameraVideo.srcObject = cameraStream;
                        cameraVideo.play();
                    } catch (error) {
                        cameraView.style.display = 'none';
                    }
                });

                captureBtn.addEventListener('click', () => {
                    cameraCanvas.width = 400;
                    cameraCanvas.height = 255;
                    const ctx = cameraCanvas.getContext('2d');
                    ctx.drawImage(cameraVideo, 0, 0, cameraCanvas.width, cameraCanvas.height);
                    const data = cameraCanvas.toDataURL('image/jpeg');
                    ktpPreview.src = data;
                    ktpPreview.classList.remove('d-none');
                    ktpPhotoInput.value = data;
                    if (cameraStream) cameraStream.getTracks().forEach(t => t.stop());
                    cameraVideo.srcObject = null;
                    cameraView.style.display = 'none';
                });
            });
        </script>
    @endpush
@endsection