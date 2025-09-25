@extends('app.master')

@section('konten')
<div class="content-body">
    <div class="row page-titles mx-0 mt-2">
        <h3 class="col p-md-0">Tambah Project</h3>
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('client.index') }}">Client</a></li>
                <li class="breadcrumb-item"><a href="{{ route('client.show', $clientId) }}">Detail Client</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Tambah Project</a></li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">
        <div class="card">
            <div class="card-header pt-4">
                <h3 class="card-title">Tambah Project Baru</h3>
                <div class="card-tools">
                    <a href="{{ route('client.show', $clientId) }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('project.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="client_id" value="{{ $clientId }}">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="client_id">Client <span class="text-danger">*</span></label>
                                <select class="form-control @error('client_id') is-invalid @enderror" name="client_id" id="client_id" required>
                                    <option value="">Pilih Client</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ $clientId == $client->id ? 'selected' : '' }}>
                                            {{ $client->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('client_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="brosur_id">Brosur <span class="text-danger">*</span></label>
                                <select class="form-control @error('brosur_id') is-invalid @enderror" name="brosur_id" id="brosur_id" required>
                                    <option value="">Pilih Brosur</option>
                                    @foreach($brosurs as $brosur)
                                        <option value="{{ $brosur->id }}" data-harga="{{ $brosur->harga }}">
                                            {{ $brosur->nama }} - Rp {{ number_format($brosur->harga, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('brosur_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="status">Status <span class="text-danger">*</span></label>
                                <select class="form-control @error('status') is-invalid @enderror" name="status" id="status" required>
                                    <option value="belum bayar">Belum Bayar</option>
                                    <option value="bayar">Bayar</option>
                                    <option value="kurang">Kurang</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="harga">Harga <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('harga') is-invalid @enderror" 
                                       name="harga" id="harga" value="{{ old('harga') }}" required min="0">
                                @error('harga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="total_bayar">Total Bayar <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('total_bayar') is-invalid @enderror" 
                                       name="total_bayar" id="total_bayar" value="{{ old('total_bayar', 0) }}" required min="0">
                                @error('total_bayar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Project
                        </button>
                        <a href="{{ route('client.show', $clientId) }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const brosurSelect = document.getElementById('brosur_id');
    const hargaInput = document.getElementById('harga');
    
    brosurSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const harga = selectedOption.getAttribute('data-harga');
        
        if (harga) {
            hargaInput.value = harga;
        }
    });
    
    // Auto calculate sisa bayar
    const totalBayarInput = document.getElementById('total_bayar');
    const hargaInputCalc = document.getElementById('harga');
    
    function calculateSisaBayar() {
        const harga = parseFloat(hargaInputCalc.value) || 0;
        const totalBayar = parseFloat(totalBayarInput.value) || 0;
        const sisaBayar = harga - totalBayar;
        
        // Update status based on payment
        const statusSelect = document.getElementById('status');
        if (totalBayar === 0) {
            statusSelect.value = 'belum bayar';
        } else if (sisaBayar <= 0) {
            statusSelect.value = 'bayar';
        } else {
            statusSelect.value = 'kurang';
        }
    }
    
    hargaInputCalc.addEventListener('input', calculateSisaBayar);
    totalBayarInput.addEventListener('input', calculateSisaBayar);
});
</script>
@endsection
