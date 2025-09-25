@extends('pegawai.layout')

@section('title', 'Daftar Purchase Order')
@section('header-title', 'Daftar Purchase Order')
@section('header-icon', 'list')

@section('content')
    <div class="mx-auto bg-white rounded-3xl shadow-xl overflow-hidden p-4">
        <div class="container max-w-4xl w-full mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="form-title">Purchase Orders</h1>
                <a href="{{ route('pegawai.po.create') }}" class="add-item-btn">Buat PO</a>
            </div>

            {{-- Toolbar: Search & Filter --}}
            <form method="GET" action="" class="mb-5">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Cari</label>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Nomor PO atau nama klien" class="w-full border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-200" />
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Status</label>
                        <select name="status" class="w-full border border-gray-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-200">
                            <option value="">Semua</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 transition">Terapkan</button>
                        <a href="{{ url()->current() }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">Reset</a>
                    </div>
                </div>
            </form>

            @if (session('success'))
                <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('success') }}</div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 rounded-xl overflow-hidden">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nomor PO</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Klien</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Items</th>
                            <th scope="col" class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($purchaseOrders as $po)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-700">{{ ($purchaseOrders->currentPage() - 1) * $purchaseOrders->perPage() + $loop->iteration }}</td>
                                <td class="px-4 py-3 font-semibold text-gray-900">{{ $po->po_number }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $po->client_name }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs rounded-full font-medium {{ $po->status === 'approved' ? 'bg-green-100 text-green-700' : ($po->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                        {{ ucfirst($po->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium bg-indigo-50 text-indigo-700 rounded-full">{{ $po->items_count }} item</span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('pegawai.po.show', $po->id) }}" class="px-3 py-1.5 text-xs md:text-sm bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">Lihat</a>
                                        <a href="{{ route('pegawai.po.edit', $po->id) }}" class="px-3 py-1.5 text-xs md:text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Edit</a>
                                        <form action="{{ route('pegawai.po.destroy', $po->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus PO ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 text-xs md:text-sm bg-red-600 text-white rounded-md hover:bg-red-700 transition">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center">
                                    <div class="flex flex-col items-center gap-2 text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-10 h-10 opacity-60">
                                            <path d="M3.75 4.5A2.25 2.25 0 016 2.25h8.25A2.25 2.25 0 0116.5 4.5v.75H18A2.25 2.25 0 0120.25 7.5v10.125a2.625 2.625 0 01-2.625 2.625H6.75A3.75 3.75 0 013 16.5V6.75A2.25 2.25 0 015.25 4.5H6V3.75A.75.75 0 016.75 3h7.5a.75.75 0 01.75.75V4.5H6a.75.75 0 00-.75.75V6H5.25A.75.75 0 004.5 6.75V16.5a2.25 2.25 0 002.25 2.25h10.875A1.125 1.125 0 0018.75 17.625V7.5A.75.75 0 0018 6.75h-1.5v.75a.75.75 0 01-1.5 0V6.75H6.75a.75.75 0 01-.75-.75V4.5z" />
                                        </svg>
                                        <div class="text-sm">Belum ada purchase order.</div>
                                        <a href="{{ route('pegawai.po.create') }}" class="mt-2 inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 transition">Buat PO Pertama</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex justify-center">
                {{ $purchaseOrders->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
