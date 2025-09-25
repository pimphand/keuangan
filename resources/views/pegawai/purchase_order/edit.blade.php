@extends('pegawai.layout')

@section('title', 'Edit Purchase Order')
@section('header-title', 'Edit Purchase Order')
@section('header-icon', 'edit')

@section('content')
<div class="mx-auto bg-white rounded-3xl shadow-xl overflow-hidden p-4">
    <div class="container max-w-lg w-full mx-auto">
        <div class="flex items-center justify-between mb-4">
            <h1 class="form-title">Edit Purchase Order</h1>
            <div class="flex items-center gap-2">
                <a href="{{ url()->previous() }}" title="Kembali"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-100 text-gray-700 font-medium shadow-sm hover:shadow hover:bg-gray-200 active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                        <path fill-rule="evenodd"
                            d="M11.03 4.47a.75.75 0 0 1 0 1.06L6.56 10h13.19a.75.75 0 0 1 0 1.5H6.56l4.47 4.47a.75.75 0 1 1-1.06 1.06l-5.75-5.75a.75.75 0 0 1 0-1.06l5.75-5.75a.75.75 0 0 1 1.06 0Z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>Kembali</span>
                </a>
                <button form="po-form" type="submit" title="Simpan Perubahan"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 text-white font-medium shadow-sm hover:shadow-md hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                        <path
                            d="M5.25 4.5A2.25 2.25 0 0 1 7.5 2.25h6a2.25 2.25 0 0 1 2.25 2.25v15a.75.75 0 0 1-1.28.53l-3.72-3.72a.75.75 0 0 0-1.06 0l-3.72 3.72a.75.75 0 0 1-1.28-.53v-15Z" />
                    </svg>
                    <span>Simpan</span>
                </button>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 rounded bg-red-100 text-red-800">
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="po-form" class="space-y-6" method="POST"
            action="{{ route('pegawai.po.update', $purchaseOrder->id) }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="ktp_photo" id="ktp_photo_input"
                value="{{ old('ktp_photo', $purchaseOrder->ktp_photo) }}">
            <div id="items-hidden-inputs"></div>

            <div class="input-group">
                <label>Nomor PO</label>
                <input type="text" class="block w-full rounded-xl focus:border-indigo-500"
                    value="{{ $purchaseOrder->po_number }}" readonly>
            </div>

            <div class="input-group">
                <label for="client-type">Jenis Klien</label>
                <select id="client-type" name="client_type"
                    class="block w-full rounded-xl focus:border-indigo-500 p-3 mt-2" required>
                    <option value="">Pilih Jenis Klien...</option>
                    <option value="Pemerintahan" @selected(old('client_type', $purchaseOrder->client_type) === 'Pemerintahan')>Pemerintahan</option>
                    <option value="Swasta" @selected(old('client_type', $purchaseOrder->client_type) === 'Swasta')>Swasta
                    </option>
                </select>
                @error('client_type')
                    <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="input-group">
                <label for="client-name">Nama Klien</label>
                <input type="text" id="client-name" name="client_name"
                    value="{{ old('client_name', $purchaseOrder->client_name) }}"
                    class="block w-full rounded-xl focus:border-indigo-500" required>
                @error('client_name')
                    <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="input-group">
                <label for="client-address">Alamat Klien</label>
                <textarea id="client-address" name="client_address" rows="3"
                    class="block w-full rounded-xl focus:border-indigo-500 pr-12"
                    required>{{ old('client_address', $purchaseOrder->client_address) }}</textarea>
                @error('client_address')
                    <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="input-group">
                <label for="client-phone-number">Nomor HP Klien</label>
                <input type="tel" id="client-phone-number" name="client_phone_number"
                    value="{{ old('client_phone_number', $purchaseOrder->client_phone_number) }}"
                    class="block w-full rounded-xl focus:border-indigo-500" pattern="[0-9]+" inputmode="numeric"
                    required>
                @error('client_phone_number')
                    <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="input-group">
                <label for="client-nik">NIK Klien</label>
                <input type="tel" id="client-nik" name="client_nik"
                    value="{{ old('client_nik', $purchaseOrder->client_nik) }}"
                    class="block w-full rounded-xl focus:border-indigo-500" pattern="[0-9]{16}" inputmode="numeric"
                    minlength="16" maxlength="16" required>
                @error('client_nik')
                    <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="input-group">
                <label for="client-ktp-name">Nama Lengkap Sesuai KTP</label>
                <input type="text" id="client-ktp-name" name="client_ktp_name"
                    value="{{ old('client_ktp_name', $purchaseOrder->client_ktp_name) }}"
                    class="block w-full rounded-xl focus:border-indigo-500" required>
                @error('client_ktp_name')
                    <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="input-group">
                <label class="block mb-2">Foto KTP Klien</label>
                <div class="mb-2">
                    @php($preview = old('ktp_photo', $purchaseOrder->ktp_photo))
                    @if ($preview)
                        <img id="ktp-preview" src="{{ $preview }}" class="w-full h-auto rounded-xl" />
                    @else
                        <img id="ktp-preview" class="w-full h-auto hidden rounded-xl" src="#" alt="Pratinjau Foto KTP">
                    @endif
                </div>
                <button type="button" id="capture-ktp-btn"
                    class="inline-flex items-center justify-center gap-2 btn-primary w-full text-sm px-4 py-2 rounded-xl bg-indigo-600 text-white font-semibold shadow-sm hover:shadow-md hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                        <path fill-rule="evenodd"
                            d="M1.5 6.75A2.25 2.25 0 0 1 3.75 4.5h2.879a2.25 2.25 0 0 1 1.59.659l.621.621H12a2.25 2.25 0 0 1 2.121 1.5h4.129A2.25 2.25 0 0 1 20.25 9v8.25A2.25 2.25 0 0 1 18 19.5H3.75A2.25 2.25 0 0 1 1.5 17.25V6.75ZM12 10.5a4.5 4.5 0 1 0 0 9 4.5 4.5 0 0 0 0-9Z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>Ganti Foto KTP</span>
                </button>
                @error('ktp_photo')
                    <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                @enderror

                <div id="camera-view" class="camera-view mt-4">
                    <div class="camera-container">
                        <video id="camera-video" autoplay playsinline></video>
                        <div class="ktp-frame-overlay">
                            <div class="ktp-frame"></div>
                        </div>
                        <canvas id="camera-canvas" class="hidden"></canvas>
                    </div>
                    <div class="camera-actions">
                        <button type="button" id="capture-btn"
                            class="bg-blue-500 text-white rounded-full h-12 w-12 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-8 h-8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.827 6.175A2.3 2.3 0 0110.154 3.75a2.3 2.3 0 013.841 2.45m-6.685 4.3a2.3 2.3 0 00-.51 2.113l-1.33 3.98a2.25 2.25 0 002.592 2.925 2.25 2.25 0 002.592-2.925L9.66 11.23a2.247 2.247 0 00-.51-2.113m6.685 4.3a2.25 2.25 0 01-2.592 2.925 2.25 2.25 0 01-2.592-2.925l1.33-3.98c.451-.795.539-1.748.24-2.671m-6.49 4.3l1.33 3.98a2.25 2.25 0 002.592 2.925 2.25 2.25 0 002.592-2.925l-1.33-3.98c-.451-.795-.539-1.748-.24-2.671M12 21.75c-5.522 0-10-4.478-10-10s4.478-10 10-10 10 4.478 10 10-4.478 10-10 10z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Detail Item</h2>
                    <button type="button" id="add-item-btn" class="add-item-btn text-sm inline-flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd"
                                d="M12 3.75a.75.75 0 0 1 .75.75v6.75H19.5a.75.75 0 0 1 0 1.5h-6.75V19.5a.75.75 0 0 1-1.5 0v-6.75H4.5a.75.75 0 0 1 0-1.5h6.75V4.5A.75.75 0 0 1 12 3.75Z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Tambah Item</span>
                    </button>
                </div>
                <div class="grid grid-cols-[1fr_80px_48px] gap-4 font-semibold text-gray-600 mb-2">
                    <span>Produk</span>
                    <span>Jumlah</span>
                    <span></span>
                </div>
                <div id="item-list"></div>
                @error('items')
                    <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="input-group">
                <label for="notes">Jelaskan Uraian Sistem</label>
                <textarea id="notes" name="notes" rows="4"
                    class="block w-full rounded-xl focus:border-indigo-500">{{ old('notes', $purchaseOrder->notes) }}</textarea>
                @error('notes')
                    <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="input-group">
                <label for="status">Status</label>
                <select id="status" name="status" class="block w-full rounded-xl focus:border-indigo-500 p-3 mt-2">
                    <option value="pending" @selected(old('status', $purchaseOrder->status) === 'pending')>Pending
                    </option>
                    <option value="approved" @selected(old('status', $purchaseOrder->status) === 'approved')>Approved
                    </option>
                    <option value="rejected" @selected(old('status', $purchaseOrder->status) === 'rejected')>Rejected
                    </option>
                </select>
                @error('status')
                    <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex items-center gap-2">
                <button type="submit"
                    class="btn-primary w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-indigo-600 text-white font-semibold shadow-sm hover:shadow-md hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                        <path
                            d="M5.25 4.5A2.25 2.25 0 0 1 7.5 2.25h6a2.25 2.25 0 0 1 2.25 2.25v15a.75.75 0 0 1-1.28.53l-3.72-3.72a.75.75 0 0 0-1.06 0l-3.72 3.72a.75.75 0 0 1-1.28-.53v-15Z" />
                    </svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
    <style>
        .item-row {
            display: grid;
            grid-template-columns: 1fr 80px 48px;
            gap: 1rem;
            margin-bottom: 1rem;
            align-items: center;
        }

        .remove-btn {
            background-color: #ef4444;
            color: #ffffff;
            border-radius: 9999px;
            width: 2rem;
            height: 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .remove-btn:hover {
            background-color: #dc2626;
        }

        .add-item-btn {
            background-color: #10b981;
            color: #ffffff;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.2s, box-shadow 0.2s;
        }

        .add-item-btn:hover {
            background-color: #059669;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
        }

        .camera-container {
            position: relative;
            width: 100%;
            max-width: 400px;
            margin: auto;
            border-radius: 1rem;
            overflow: hidden;
            background-color: #000;
        }

        .camera-view {
            display: none;
        }

        .camera-view.active {
            display: block;
        }

        #camera-canvas,
        #camera-video {
            width: 100%;
            height: auto;
            transform: scaleX(-1);
            position: relative;
            z-index: 10;
        }

        #camera-canvas {
            display: none;
        }

        .camera-actions {
            position: absolute;
            bottom: 1rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 20;
            display: flex;
            gap: 1rem;
        }

        .ktp-frame-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 15;
            display: flex;
            justify-content: center;
            align-items: center;
            pointer-events: none;
        }

        .ktp-frame {
            position: relative;
            width: 80%;
            padding-bottom: 50.93%;
            border: 2px dashed #ffffff;
            border-radius: 0.5rem;
        }
    </style>
@endpush

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const itemList = document.getElementById('item-list');
            const addItemBtn = document.getElementById('add-item-btn');
            const itemsHiddenInputs = document.getElementById('items-hidden-inputs');
            const ktpPhotoInput = document.getElementById('ktp_photo_input');

            const captureKtpBtn = document.getElementById('capture-ktp-btn');
            const cameraView = document.getElementById('camera-view');
            const cameraVideo = document.getElementById('camera-video');
            const cameraCanvas = document.getElementById('camera-canvas');
            const captureBtn = document.getElementById('capture-btn');
            const ktpPreview = document.getElementById('ktp-preview');
            let cameraStream = null;

            const createItemRow = (name = '', qty = 1, price = 0) => {
                const itemDiv = document.createElement('div');
                itemDiv.className = 'item-row';

                const nameInput = document.createElement('input');
                nameInput.type = 'text';
                nameInput.placeholder = 'Nama Produk';
                nameInput.className = 'rounded-xl p-3';
                nameInput.value = name;

                const quantityInput = document.createElement('input');
                quantityInput.type = 'number';
                quantityInput.placeholder = 'Qty';
                quantityInput.className = 'rounded-xl p-3 text-center';
                quantityInput.min = '1';
                quantityInput.value = qty;

                const removeBtn = document.createElement('div');
                removeBtn.className = 'remove-btn';
                removeBtn.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        `;

                removeBtn.addEventListener('click', () => {
                    itemDiv.remove();
                });

                itemDiv.appendChild(nameInput);
                itemDiv.appendChild(quantityInput);
                itemDiv.appendChild(removeBtn);

                return itemDiv;
            };

            const oldItems = @json(old('items'));
            const initialItems = oldItems && Array.isArray(oldItems) ? oldItems : @json($purchaseOrder->items->map(fn($i) => ['name' => $i->product_name, 'quantity' => $i->quantity, 'price' => $i->price]));
            if (initialItems.length) {
                initialItems.forEach(it => itemList.appendChild(createItemRow(it.name ?? it['name'], it.quantity ?? it['quantity'], it.price ?? it['price'])));
            } else {
                itemList.appendChild(createItemRow());
            }

            addItemBtn.addEventListener('click', () => {
                itemList.appendChild(createItemRow());
            });

            document.getElementById('po-form').addEventListener('submit', (e) => {
                itemsHiddenInputs.innerHTML = '';
                document.querySelectorAll('.item-row').forEach((row, idx) => {
                    const name = row.querySelector('input[type="text"]').value.trim();
                    const qty = parseInt(row.querySelector('input[type="number"]').value) || 0;
                    const price = 0;
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
                        priceInput.value = price;
                        itemsHiddenInputs.appendChild(priceInput);
                    }
                });
            });

            captureKtpBtn.addEventListener('click', async () => {
                try {
                    cameraView.classList.add('active');
                    captureKtpBtn.style.display = 'none';

                    if (cameraStream) {
                        cameraStream.getTracks().forEach(track => track.stop());
                    }

                    cameraStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
                    cameraVideo.srcObject = cameraStream;
                    cameraVideo.play();
                } catch (error) {
                    cameraView.classList.remove('active');
                    captureKtpBtn.style.display = 'block';
                }
            });

            captureBtn.addEventListener('click', () => {
                const ktpRatio = 1.57;
                cameraCanvas.width = 400;
                cameraCanvas.height = cameraCanvas.width / ktpRatio;
                const ctx = cameraCanvas.getContext('2d');
                ctx.drawImage(cameraVideo, 0, 0, cameraCanvas.width, cameraCanvas.height);
                const ktpPhotoData = cameraCanvas.toDataURL('image/jpeg');
                ktpPreview.src = ktpPhotoData;
                ktpPreview.classList.remove('hidden');
                ktpPhotoInput.value = ktpPhotoData;
                cameraStream.getTracks().forEach(track => track.stop());
                cameraVideo.srcObject = null;
                cameraView.classList.remove('active');
                captureKtpBtn.style.display = 'block';
            });
        });
    </script>
@endpush