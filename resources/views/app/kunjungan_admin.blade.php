@extends('app.master')

@section('title', 'Kunjungan Pegawai')

@section('konten')
    <div class="content-body">
        <div class="row page-titles mx-0 mt-2">
            <h3 class="col p-md-0">Kunjungan Pegawai</h3>
            <div class="col p-md-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Kunjungan</a></li>
                </ol>
            </div>
        </div>

        <div class="container-fluid">
            <div class="card">
                <div class="card-header pt-4 d-flex justify-content-between align-items-center">
                    <h3 class="card-title m-0">Filter Kunjungan</h3>
                    <button type="button" class="btn btn-sm btn-success btn-open-create" data-toggle="modal"
                        data-target="#adminCreateKunjunganModal">
                        <i class="fa fa-plus"></i> Tambah
                    </button>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('kunjungan.admin') }}" id="filterForm">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Mulai</label>
                                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Selesai</label>
                                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Pegawai</label>
                                    <select name="user_id" class="form-control">
                                        <option value="">Semua</option>
                                        @foreach($users as $u)
                                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                                {{ $u->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Client</label>
                                    <input type="text" name="client" value="{{ request('client') }}"
                                        placeholder="Cari client" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-12 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary mr-2"> <i class="fa fa-search"></i> &nbsp;
                                    Filter</button>
                                <a href="{{ route('kunjungan.admin') }}" class="btn btn-outline-secondary">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card" data-resource-base="{{ url('kunjungan') }}">
                <div class="card-header pt-4">
                    <h3 class="card-title">Data Kunjungan</h3>
                </div>
                <div class="card-body">
                    @if($kunjungans->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="1%">No</th>
                                        <th class="text-center" width="12%">Tanggal</th>
                                        <th>Pegawai</th>
                                        <th>Client</th>
                                        <th>Ringkasan</th>
                                        <th>Lokasi</th>
                                        <th class="text-center" width="10%">Foto</th>
                                        {{-- <th class="text-center" width="10%">Aksi</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = ($kunjungans->currentPage() - 1) * $kunjungans->perPage() + 1; @endphp
                                    @foreach($kunjungans as $k)
                                        <tr>
                                            <td class="text-center">{{ $no++ }}</td>
                                            <td class="text-center">
                                                {{ \Carbon\Carbon::parse($k->tanggal_kunjungan)->format('d-m-Y') }}
                                            </td>
                                            <td>{{ $k->user?->name ?? '-' }}</td>
                                            <td>{{ $k->client }}</td>
                                            <td style="max-width: 320px;">{{ $k->ringkasan }}</td>
                                            <td>
                                                {{ $k->lokasi }}
                                            </td>
                                            <td class="text-center">
                                                @if(!empty($k->foto))
                                                    <button type="button" class="btn btn-sm btn-info btn-view-foto"
                                                        data-foto="{{ asset($k->foto) }}">
                                                        <i class="fa fa-image"></i> Lihat
                                                    </button>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            {{-- <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-warning btn-edit-kunjungan"
                                                        data-id="{{ $k->id }}"
                                                        data-tanggal="{{ \Carbon\Carbon::parse($k->tanggal_kunjungan)->format('Y-m-d') }}"
                                                        data-client="{{ $k->client }}" data-ringkasan="{{ $k->ringkasan }}"
                                                        data-lokasi="{{ $k->lokasi }}">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger btn-delete-kunjungan"
                                                        data-id="{{ $k->id }}" data-name="{{ $k->client }}">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-muted">Tidak ada data kunjungan.</div>
                    @endif

                    <div class="mt-3">
                        {{ $kunjungans->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
        <!-- #/ container -->
    </div>
@endsection

@push('js')
    <script>
        // Bootstrap modal for photo preview (safe element resolution on click)
        (function () {
            document.querySelectorAll('.btn-view-foto').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var modalEl = document.getElementById('adminPhotoModal');
                    var imgEl = document.getElementById('adminPhotoPreview');
                    var src = btn.getAttribute('data-foto');
                    if (!modalEl || !imgEl || !src) return;
                    imgEl.src = src;
                    if (window.jQuery && jQuery(modalEl).modal) {
                        jQuery(modalEl).modal('show');
                        // Clear image once per hide
                        jQuery(modalEl).one('hidden.bs.modal', function () { imgEl.src = ''; });
                    } else {
                        modalEl.style.display = 'block';
                        var handler = function (e) {
                            if (e.target === modalEl) {
                                modalEl.style.display = 'none';
                                imgEl.src = '';
                                modalEl.removeEventListener('click', handler);
                            }
                        };
                        modalEl.addEventListener('click', handler);
                    }
                });
            });
        })();

        // CRUD Modals handlers
        (function () {
            var resourceBase = (document.querySelector('.card[data-resource-base]') || {}).getAttribute ? document.querySelector('.card[data-resource-base]').getAttribute('data-resource-base') : '';

            // Create
            document.querySelectorAll('.btn-open-create').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var modalEl = document.getElementById('adminCreateKunjunganModal');
                    if (!modalEl) return;
                    if (window.jQuery && jQuery(modalEl).modal) { jQuery(modalEl).modal('show'); } else { modalEl.style.display = 'block'; }
                });
            });

            // Edit
            document.querySelectorAll('.btn-edit-kunjungan').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var id = btn.getAttribute('data-id');
                    var tanggal = btn.getAttribute('data-tanggal');
                    var client = btn.getAttribute('data-client');
                    var ringkasan = btn.getAttribute('data-ringkasan');
                    var lokasi = btn.getAttribute('data-lokasi');
                    var modalEl = document.getElementById('adminEditKunjunganModal');
                    if (!id || !modalEl) return;
                    var form = modalEl.querySelector('form');
                    if (form) { form.action = (resourceBase ? resourceBase : '') + '/' + id; }
                    var tglInput = modalEl.querySelector('input[name="tanggal_kunjungan"]');
                    var clientInput = modalEl.querySelector('input[name="client"]');
                    var ringkasanInput = modalEl.querySelector('textarea[name="ringkasan"]');
                    var lokasiInput = modalEl.querySelector('input[name="lokasi"]');
                    if (tglInput) tglInput.value = tanggal || '';
                    if (clientInput) clientInput.value = client || '';
                    if (ringkasanInput) ringkasanInput.value = ringkasan || '';
                    if (lokasiInput) lokasiInput.value = lokasi || '';
                    if (window.jQuery && jQuery(modalEl).modal) { jQuery(modalEl).modal('show'); } else { modalEl.style.display = 'block'; }
                });
            });

            // Delete
            document.querySelectorAll('.btn-delete-kunjungan').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var id = btn.getAttribute('data-id');
                    var name = btn.getAttribute('data-name');
                    var modalEl = document.getElementById('adminDeleteKunjunganModal');
                    if (!id || !modalEl) return;
                    var form = modalEl.querySelector('form');
                    if (form) { form.action = (resourceBase ? resourceBase : '') + '/' + id; }
                    var label = modalEl.querySelector('.delete-label');
                    if (label) { label.textContent = name ? ('Hapus kunjungan untuk client "' + name + '"?') : 'Hapus data kunjungan ini?'; }
                    if (window.jQuery && jQuery(modalEl).modal) { jQuery(modalEl).modal('show'); } else { modalEl.style.display = 'block'; }
                });
            });
        })();
    </script>

    <!-- Bootstrap Modal Structure -->
    <div class="modal fade" id="adminPhotoModal" tabindex="-1" role="dialog" aria-labelledby="adminPhotoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adminPhotoModalLabel">Foto Kunjungan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img id="adminPhotoPreview" src="" alt="Foto Kunjungan" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="adminCreateKunjunganModal" tabindex="-1" role="dialog"
        aria-labelledby="adminCreateKunjunganLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adminCreateKunjunganLabel">Tambah Kunjungan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('pegawai.kunjungan.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal_kunjungan" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Client</label>
                            <input type="text" name="client" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Ringkasan</label>
                            <textarea name="ringkasan" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Lokasi</label>
                            <input type="text" name="lokasi" class="form-control" placeholder="Alamat atau koordinat"
                                required>
                        </div>
                        <div class="form-group">
                            <label>Foto (opsional)</label>
                            <input type="file" name="foto" class="form-control-file" accept="image/*">
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

    <!-- Edit Modal -->
    <div class="modal fade" id="adminEditKunjunganModal" tabindex="-1" role="dialog"
        aria-labelledby="adminEditKunjunganLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adminEditKunjunganLabel">Edit Kunjungan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal_kunjungan" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Client</label>
                            <input type="text" name="client" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Ringkasan</label>
                            <textarea name="ringkasan" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Lokasi</label>
                            <input type="text" name="lokasi" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Foto (ganti, opsional)</label>
                            <input type="file" name="foto" class="form-control-file" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="adminDeleteKunjunganModal" tabindex="-1" role="dialog"
        aria-labelledby="adminDeleteKunjunganLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adminDeleteKunjunganLabel">Hapus Kunjungan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p class="mb-0 delete-label">Hapus data kunjungan ini?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush
