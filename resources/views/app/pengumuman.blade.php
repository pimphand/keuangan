@extends('app.master')

@section('konten')

    <div class="content-body">

        <div class="row page-titles mx-0 mt-2">

            <h3 class="col p-md-0">Pengumuman</h3>

            <div class="col p-md-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Pengumuman</a></li>
                </ol>
            </div>
        </div>

        <div class="container-fluid">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card-header pt-4 d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Daftar Pengumuman</h3>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCreate">
                        <i class="fa fa-plus mr-1"></i> Tambah Pengumuman
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table-datatable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center" width="1%">NO</th>
                                    <th class="text-center">JUDUL</th>
                                    <th class="text-center">Prioritas</th>
                                    <th class="text-center">TANGGAL</th>
                                    <th class="text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pengumuman as $idx => $p)
                                    <tr>
                                        <td class="text-center">{{ $pengumuman->firstItem() + $idx }}</td>
                                        <td>{{ $p->judul }}</td>
                                        <td class="text-center">
                                            <span
                                                class="badge {{ $p->prioritas == 'sedang' ? 'badge-success' : 'badge-danger' }}">{{ ucfirst($p->prioritas) }}</span>
                                        </td>
                                        <td class="text-center">{{ optional($p->created_at)->format('d/m/Y H:i') }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-warning" data-toggle="modal"
                                                data-target="#modalEdit{{ $p->id }}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $p->id }}">
                                                <i class="fa fa-trash"></i>
                                            </button>

                                            <form id="form-delete-{{ $p->id }}"
                                                action="{{ route('pengumuman.destroy', $p->id) }}" method="POST"
                                                style="display:none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="modalEdit{{ $p->id }}" tabindex="-1" role="dialog"
                                        aria-labelledby="modalEditLabel{{ $p->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalEditLabel{{ $p->id }}">Edit Pengumuman</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('pengumuman.update', $p->id) }}" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Judul</label>
                                                            <input type="text" name="judul" class="form-control"
                                                                value="{{ old('judul', $p->judul) }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Isi</label>
                                                            <textarea name="isi" class="form-control" rows="5"
                                                                required>{{ old('isi', $p->isi) }}</textarea>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label>Prioritas</label>
                                                                <select name="prioritas" class="form-control" required>
                                                                    <option value="sedang" {{ $p->prioritas == 'sedang' ? 'selected' : '' }}>Sedang</option>
                                                                    <option value="tinggi" {{ $p->prioritas == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label>Link (opsional)</label>
                                                                <input type="url" name="link" class="form-control"
                                                                    value="{{ old('link', $p->link) }}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Gambar (opsional)</label>
                                                            <input type="file" name="gambar" class="form-control">
                                                            @if($p->gambar)
                                                                <small class="d-block mt-1">Gambar saat ini:
                                                                    {{ $p->gambar }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Belum ada pengumuman.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if(method_exists($pengumuman, 'links'))
                        {{ $pengumuman->links() }}
                    @endif
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="modalCreateLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCreateLabel">Tambah Pengumuman</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('pengumuman.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Judul</label>
                                <input type="text" name="judul" class="form-control" value="{{ old('judul') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Isi</label>
                                <textarea name="isi" class="form-control" rows="5" required>{{ old('isi') }}</textarea>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Status</label>
                                    <select name="status" class="form-control" required>
                                        <option value="aktif">Aktif</option>
                                        <option value="nonaktif">Nonaktif</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Link (opsional)</label>
                                    <input type="url" name="link" class="form-control" value="{{ old('link') }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Gambar (opsional)</label>
                                <input type="file" name="gambar" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    Swal.fire({
                        title: 'Hapus pengumuman? ',
                        text: 'Tindakan ini tidak bisa dibatalkan.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, hapus',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = document.getElementById('form-delete-' + id);
                            if (form) form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush