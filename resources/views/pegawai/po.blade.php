@extends('pegawai.layout')

@section('title', 'Purchase Order')
@section('header-title', 'Purchase Order')
@section('header-icon', 'file-text')

@section('content')
    <div class="mx-auto bg-white rounded-3xl shadow-xl overflow-hidden p-4">
        <div class="container max-w-lg w-full mx-auto">
            <h1 class="form-title">Buat Purchase Order Baru</h1>

            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="font-bold text-gray-800">Purchase Order</p>
                    <p class="text-sm text-gray-500">Buat permintaan pembelian</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Tanggal</p>
                    <p id="current-date" class="font-semibold text-gray-700"></p>
                </div>
            </div>

            <form id="po-form" class="space-y-6">
                <div class="input-group">
                    <label for="po-number">Nomor PO</label>
                    <input type="text" id="po-number" class="block w-full rounded-xl focus:border-indigo-500" readonly>
                </div>

                <div class="input-group">
                    <label for="client-type">Jenis Klien</label>
                    <select id="client-type" class="block w-full rounded-xl focus:border-indigo-500 p-3 mt-2">
                        <option value="">Pilih Jenis Klien...</option>
                        <option value="Pemerintahan">Pemerintahan</option>
                        <option value="Swasta">Swasta</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="client-name">Nama Klien</label>
                    <input type="text" id="client-name" placeholder="Masukkan nama klien"
                        class="block w-full rounded-xl focus:border-indigo-500">
                </div>

                <div class="input-group">
                    <label for="client-address">Alamat Klien</label>
                    <div class="relative mt-2">
                        <textarea id="client-address" rows="3" placeholder="Masukkan alamat klien"
                            class="block w-full rounded-xl focus:border-indigo-500 pr-12"></textarea>
                        <button type="button" id="map-btn"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-indigo-600 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="input-group">
                    <label for="client-phone-number">Nomor HP Klien</label>
                    <input type="tel" id="client-phone-number" placeholder="Masukkan nomor HP klien"
                        class="block w-full rounded-xl focus:border-indigo-500" pattern="[0-9]+" inputmode="numeric">
                </div>

                <div class="input-group">
                    <label for="client-nik">NIK Klien</label>
                    <input type="tel" id="client-nik" placeholder="Masukkan 16 digit NIK"
                        class="block w-full rounded-xl focus:border-indigo-500" pattern="[0-9]{16}" inputmode="numeric"
                        minlength="16" maxlength="16">
                </div>

                <div class="input-group">
                    <label for="client-ktp-name">Nama Lengkap Sesuai KTP</label>
                    <input type="text" id="client-ktp-name" placeholder="Masukkan nama sesuai KTP"
                        class="block w-full rounded-xl focus:border-indigo-500">
                </div>

                <div class="input-group">
                    <label class="block mb-2">Foto KTP Klien</label>
                    <div id="ktp-upload-container">
                        <button type="button" id="capture-ktp-btn" class="btn-primary w-full text-sm">Ambil Foto
                            KTP</button>
                        <img id="ktp-preview" class="w-full h-auto mt-4 hidden rounded-xl" src="#" alt="Pratinjau Foto KTP">
                    </div>

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
                        <button type="button" id="add-item-btn" class="add-item-btn text-sm">Tambah Item</button>
                    </div>
                    <div class="grid grid-cols-[1fr_80px_48px] gap-4 font-semibold text-gray-600 mb-2">
                        <span>Produk</span>
                        <span>Jumlah</span>
                        <span></span>
                    </div>
                    <div id="item-list"></div>
                </div>

                <div class="input-group">
                    <label for="notes">Jelaskan Uraian Sistem</label>
                    <textarea id="notes" rows="4" placeholder="Jelaskan uraian sistem yang diminta di sini..."
                        class="block w-full rounded-xl focus:border-indigo-500"></textarea>
                </div>

                <button type="submit" class="btn-primary w-full">Kirim Permintaan PO</button>
            </form>
        </div>
    </div>

    <div id="toast" class="toast">Permintaan PO berhasil dikirim!</div>
@endsection

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }

        .container {
            max-width: 600px;
            margin: auto;
            padding: 1rem;
        }

        .input-group label {
            font-weight: 500;
            color: #4b5563;
        }

        .input-group input,
        .input-group select,
        .input-group textarea {
            width: 100%;
            padding: 0.75rem;
            border-radius: 0.75rem;
            border: 1px solid #d1d5db;
            transition: border-color 0.2s;
            margin-top: 0.5rem;
        }

        .input-group input:focus,
        .input-group select:focus,
        .input-group textarea:focus {
            outline: none;
            border-color: #2563eb;
        }

        .form-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 1rem;
            text-align: center;
        }

        .btn-primary {
            background-color: #2563eb;
            color: #ffffff;
            padding: 1rem;
            border-radius: 1rem;
            font-weight: 600;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-primary:hover {
            background-color: #1d4ed8;
        }

        .item-list {
            margin-top: 1rem;
        }

        .item-row {
            display: grid;
            grid-template-columns: 1fr 80px 48px;
            gap: 1rem;
            margin-bottom: 1rem;
            align-items: center;
        }

        @media (max-width: 640px) {
            .item-row {
                grid-template-columns: 1fr 60px 48px;
            }
        }

        .item-row input,
        .item-row select {
            flex-grow: 1;
        }

        .item-row .remove-btn {
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

        .item-row .remove-btn:hover {
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
            transition: background-color 0.2s;
        }

        .add-item-btn:hover {
            background-color: #059669;
        }

        .toast {
            position: fixed;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            background-color: #333;
            color: #fff;
            padding: 1rem 2rem;
            border-radius: 1rem;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
            z-index: 1000;
        }

        .toast.show {
            opacity: 1;
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
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dateElement = document.getElementById('current-date');
            const today = new Date();
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            dateElement.textContent = today.toLocaleDateString('id-ID', options);

            const poNumberInput = document.getElementById('po-number');
            const generatePONumber = () => {
                const now = new Date();
                const timestamp = now.getTime();
                return `PO-MKT-${timestamp}`;
            };
            poNumberInput.value = generatePONumber();

            const form = document.getElementById('po-form');
            const itemList = document.getElementById('item-list');
            const addItemBtn = document.getElementById('add-item-btn');
            const toast = document.getElementById('toast');
            const mapBtn = document.getElementById('map-btn');
            const clientAddressInput = document.getElementById('client-address');

            const captureKtpBtn = document.getElementById('capture-ktp-btn');
            const cameraView = document.getElementById('camera-view');
            const cameraVideo = document.getElementById('camera-video');
            const cameraCanvas = document.getElementById('camera-canvas');
            const captureBtn = document.getElementById('capture-btn');
            const ktpPreview = document.getElementById('ktp-preview');
            let cameraStream = null;
            let ktpPhotoData = null;

            const products = [
                { name: 'Peralatan Kantor', price: 150000 },
                { name: 'Lisensi Software', price: 750000 },
                { name: 'Materi Promosi', price: 200000 },
                { name: 'Jasa Desain Grafis', price: 500000 },
                { name: 'Perlengkapan Event', price: 300000 }
            ];

            const createItemRow = () => {
                const itemDiv = document.createElement('div');
                itemDiv.className = 'item-row';

                const selectElement = document.createElement('select');
                selectElement.className = 'rounded-xl p-3';
                selectElement.innerHTML = `<option value="">Pilih Produk...</option>`;
                products.forEach(product => {
                    const option = document.createElement('option');
                    option.value = product.price;
                    option.textContent = product.name;
                    selectElement.appendChild(option);
                });

                const quantityInput = document.createElement('input');
                quantityInput.type = 'number';
                quantityInput.placeholder = 'Qty';
                quantityInput.className = 'rounded-xl p-3 text-center';
                quantityInput.min = '1';

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

                itemDiv.appendChild(selectElement);
                itemDiv.appendChild(quantityInput);
                itemDiv.appendChild(removeBtn);

                return itemDiv;
            };

            itemList.appendChild(createItemRow());
            addItemBtn.addEventListener('click', () => {
                itemList.appendChild(createItemRow());
            });

            mapBtn.addEventListener('click', () => {
                const address = clientAddressInput.value;
                if (address.trim() !== '') {
                    const encodedAddress = encodeURIComponent(address);
                    window.open(`https://www.google.com/maps/search/?api=1&query=${encodedAddress}`, '_blank');
                } else {
                    showToast('Harap isi alamat klien terlebih dahulu.', 'error');
                }
            });

            captureKtpBtn.addEventListener('click', async () => {
                try {
                    cameraView.classList.add('active');
                    captureKtpBtn.style.display = 'none';
                    ktpPreview.style.display = 'none';

                    if (cameraStream) {
                        cameraStream.getTracks().forEach(track => track.stop());
                    }

                    cameraStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
                    cameraVideo.srcObject = cameraStream;
                    cameraVideo.play();
                } catch (error) {
                    showToast('Gagal mengakses kamera.', 'error');
                    console.error('Error accessing camera:', error);
                    cameraView.classList.remove('active');
                    captureKtpBtn.style.display = 'block';
                }
            });

            captureBtn.addEventListener('click', () => {
                const videoRatio = cameraVideo.videoWidth / cameraVideo.videoHeight;
                const ktpRatio = 1.57;

                let sx, sy, sWidth, sHeight;
                if (videoRatio > ktpRatio) {
                    sHeight = cameraVideo.videoHeight;
                    sWidth = sHeight * ktpRatio;
                    sx = (cameraVideo.videoWidth - sWidth) / 2;
                    sy = 0;
                } else {
                    sWidth = cameraVideo.videoWidth;
                    sHeight = sWidth / ktpRatio;
                    sx = 0;
                    sy = (cameraVideo.videoHeight - sHeight) / 2;
                }

                cameraCanvas.width = 400;
                cameraCanvas.height = cameraCanvas.width / ktpRatio;
                const ctx = cameraCanvas.getContext('2d');
                ctx.drawImage(cameraVideo, sx, sy, sWidth, sHeight, 0, 0, cameraCanvas.width, cameraCanvas.height);
                ktpPhotoData = cameraCanvas.toDataURL('image/jpeg');

                cameraStream.getTracks().forEach(track => track.stop());
                cameraVideo.srcObject = null;
                cameraView.classList.remove('active');
                captureKtpBtn.style.display = 'block';
                ktpPreview.src = ktpPhotoData;
                ktpPreview.style.display = 'block';
            });

            form.addEventListener('submit', (e) => {
                e.preventDefault();

                const poNumber = poNumberInput.value;
                const clientType = document.getElementById('client-type').value;
                const clientName = document.getElementById('client-name').value;
                const clientAddress = document.getElementById('client-address').value;
                const clientPhoneNumber = document.getElementById('client-phone-number').value;
                const clientNIK = document.getElementById('client-nik').value;
                const clientKTPName = document.getElementById('client-ktp-name').value;
                const notes = document.getElementById('notes').value;
                const items = [];

                document.querySelectorAll('.item-row').forEach(row => {
                    const selectElement = row.querySelector('select');
                    const itemName = selectElement.options[selectElement.selectedIndex].text;
                    const itemQty = parseInt(row.querySelector('input[type="number"]').value) || 0;
                    const itemPrice = parseInt(selectElement.value) || 0;
                    if (itemName && itemQty) {
                        items.push({ name: itemName, quantity: itemQty, price: itemPrice });
                    }
                });

                if (!poNumber || !clientType || !clientName || !clientAddress || !clientPhoneNumber || !clientNIK || !clientKTPName || items.length === 0 || items.some(item => !item.name || !item.quantity)) {
                    showToast('Harap lengkapi semua field yang diperlukan!', 'error');
                    return;
                }

                if (!ktpPhotoData) {
                    showToast('Harap ambil foto KTP klien!', 'error');
                    return;
                }

                const formData = {
                    poNumber,
                    clientType,
                    clientName,
                    clientAddress,
                    clientPhoneNumber,
                    clientNIK,
                    clientKTPName,
                    notes,
                    items,
                    ktpPhoto: ktpPhotoData
                };

                console.log('Data Permintaan PO:', formData);
                showToast('Permintaan PO berhasil dikirim!');

                form.reset();
                itemList.innerHTML = '';
                itemList.appendChild(createItemRow());
                poNumberInput.value = generatePONumber();
                ktpPreview.style.display = 'none';
                ktpPhotoData = null;
            });

            function showToast(message, type = 'success') {
                toast.textContent = message;
                toast.className = 'toast show';
                if (type === 'error') {
                    toast.style.backgroundColor = '#dc2626';
                } else {
                    toast.style.backgroundColor = '#333';
                }
                setTimeout(() => {
                    toast.className = 'toast';
                }, 3000);
            }
        });
    </script>
@endpush