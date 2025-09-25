@extends('pegawai.layout')

@section('title', 'Detail Purchase Order')
@section('header-title', 'Detail Purchase Order')
@section('header-icon', 'file-text')

@section('content')
    <div class="mx-auto bg-white rounded-3xl shadow-xl overflow-hidden p-4">
        <div class="container max-w-3xl w-full mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="form-title">{{ $purchaseOrder->po_number }}</h1>
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
                    <a href="{{ route('pegawai.po.edit', $purchaseOrder->id) }}" title="Edit Purchase Order"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 text-white font-medium shadow-sm hover:shadow-md hover:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                            <path
                                d="M16.862 3.487a1.5 1.5 0 0 1 2.121 0l1.53 1.53a1.5 1.5 0 0 1 0 2.121l-9.9 9.9a1.5 1.5 0 0 1-.61.37l-4.224 1.207a.75.75 0 0 1-.923-.923l1.207-4.224a1.5 1.5 0 0 1 .37-.61l9.9-9.9Zm-2.121 2.121L6.9 13.45a.75.75 0 0 0-.185.304l-.84 2.94 2.94-.84a.75.75 0 0 0 .304-.185l7.842-7.842-2.46-2.46Z" />
                        </svg>
                        <span>Edit</span>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="p-4 rounded border">
                    <div class="font-semibold text-gray-700 mb-2">Informasi Klien</div>
                    <div><span class="text-gray-500">Jenis:</span> {{ $purchaseOrder->client_type }}</div>
                    <div><span class="text-gray-500">Nama:</span> {{ $purchaseOrder->client_name }}</div>
                    <div><span class="text-gray-500">Alamat:</span> {{ $purchaseOrder->client_address }}</div>
                    <div><span class="text-gray-500">HP:</span> {{ $purchaseOrder->client_phone_number }}</div>
                    <div><span class="text-gray-500">NIK:</span> {{ $purchaseOrder->client_nik }}</div>
                    <div><span class="text-gray-500">Nama KTP:</span> {{ $purchaseOrder->client_ktp_name }}</div>
                </div>
                <div class="p-4 rounded border">
                    <div class="font-semibold text-gray-700 mb-2">Status</div>
                    <div>
                        <span
                            class="px-2 py-1 text-xs rounded {{ $purchaseOrder->status === 'approved' ? 'bg-green-100 text-green-700' : ($purchaseOrder->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                            {{ ucfirst($purchaseOrder->status) }}
                        </span>
                    </div>
                    <div class="mt-4">
                        <div class="font-semibold text-gray-700 mb-2">Foto KTP</div>
                        @if ($purchaseOrder->ktp_photo)
                            <img src="{{ $purchaseOrder->ktp_photo }}" alt="KTP" class="rounded w-full max-w-xs">
                        @else
                            <div class="text-gray-500">Tidak ada foto KTP</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <div class="font-semibold text-gray-700 mb-2">Uraian</div>
                <div class="p-4 rounded border bg-gray-50">{{ $purchaseOrder->notes ?: '-' }}</div>
            </div>

            <div>
                <div class="font-semibold text-gray-700 mb-2">Item</div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Produk</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jumlah</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Harga</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($purchaseOrder->items as $item)
                                <tr>
                                    <td class="px-4 py-2">{{ $item->product_name }}</td>
                                    <td class="px-4 py-2">{{ $item->quantity }}</td>
                                    <td class="px-4 py-2">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection