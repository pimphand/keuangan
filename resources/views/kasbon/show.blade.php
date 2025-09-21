@extends('app.master')

@section('konten')

    <div class="content-body">

        <div class="row page-titles mx-0 mt-2">

            <h3 class="col p-md-0">Detail Pengajuan Kasbon</h3>

            <div class="col p-md-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('kasbon.index') }}">Kasbon</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Detail</a></li>
                </ol>
            </div>

        </div>

        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card">

                <div class="card-header pt-4">
                    <div class="float-right">
                        <a href="{{ route('kasbon.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> &nbsp Kembali
                        </a>
                    </div>
                    <h4>Detail Pengajuan Kasbon</h4>
                </div>

                <div class="card-body pt-0">

                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Pegawai:</th>
                                    <td>{{ $kasbon->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Nominal:</th>
                                    <td class="fw-bold text-primary">
                                        Rp {{ number_format($kasbon->nominal, 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($kasbon->status === 'pending')
                                            <span class="badge bg-warning fs-6">Pending</span>
                                        @elseif($kasbon->status === 'disetujui')
                                            <span class="badge bg-info fs-6">Disetujui</span>
                                        @elseif($kasbon->status === 'di proses')
                                            <span class="badge bg-primary fs-6">Di Proses</span>
                                        @elseif($kasbon->status === 'selesai')
                                            <span class="badge bg-success fs-6">Selesai</span>
                                        @else
                                            <span class="badge bg-danger fs-6">Ditolak</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tanggal Pengajuan:</th>
                                    <td>{{ $kasbon->created_at->format('d F Y, H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                @if($kasbon->disetujui)
                                    <tr>
                                        <th width="40%">Disetujui Oleh:</th>
                                        <td>{{ $kasbon->disetujui->name }}</td>
                                    </tr>
                                @endif
                                @if($kasbon->updated_at != $kasbon->created_at)
                                    <tr>
                                        <th>Tanggal Diproses:</th>
                                        <td>{{ $kasbon->updated_at->format('d F Y, H:i') }}</td>
                                    </tr>
                                @endif
                                @if($kasbon->alasan)
                                    <tr>
                                        <th>Alasan:</th>
                                        <td class="text-danger">{{ $kasbon->alasan }}</td>
                                    </tr>
                                @endif
                                @if($kasbon->bukti)
                                    <tr>
                                        <th>Bukti Pengiriman:</th>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="{{ asset('gambar/kasbon/' . $kasbon->bukti) }}" alt="Bukti Pengiriman"
                                                    class="img-thumbnail"
                                                    style="max-width: 200px; max-height: 150px; object-fit: cover; cursor: pointer;"
                                                    onclick="window.open('{{ asset('gambar/kasbon/' . $kasbon->bukti) }}', '_blank')">
                                                <div>
                                                    <a href="{{ asset('gambar/kasbon/' . $kasbon->bukti) }}" target="_blank"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="fa fa-download"></i> Download
                                                    </a>
                                                    <div class="mt-1">
                                                        <small class="text-muted">{{ $kasbon->bukti }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                @if($kasbon->tanggal_pengiriman)
                                    <tr>
                                        <th>Tanggal Pengiriman:</th>
                                        <td>{{ \Carbon\Carbon::parse($kasbon->tanggal_pengiriman)->format('d F Y') }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h6>Keterangan:</h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <p class="mb-0">{{ $kasbon->keterangan }}</p>
                            </div>
                        </div>
                    </div>

                    @if(auth()->user()->level === 'admin' && $kasbon->isPending())
                        <div class="mt-4 d-flex gap-2">
                            <button type="button" class="btn btn-success" onclick="approveKasbon({{ $kasbon->id }})">
                                <i class="fa fa-check"></i> &nbsp Setujui
                            </button>
                            <button type="button" class="btn btn-danger" onclick="rejectKasbon({{ $kasbon->id }})">
                                <i class="fa fa-times"></i> &nbsp Tolak
                            </button>
                        </div>
                    @endif

                    @if(auth()->user()->level === 'admin' && $kasbon->isApproved())
                        <div class="mt-4 d-flex gap-2">
                            <button type="button" class="btn btn-primary" onclick="processKasbon({{ $kasbon->id }})">
                                <i class="fa fa-cogs"></i> &nbsp Proses
                            </button>
                        </div>
                    @endif

                    @if(auth()->user()->level === 'admin' && $kasbon->isProcessing())
                        <div class="mt-4 d-flex gap-2">
                            <button type="button" class="btn btn-success" onclick="completeKasbon({{ $kasbon->id }})">
                                <i class="fa fa-check-circle"></i> &nbsp Selesaikan
                            </button>
                        </div>
                    @endif

                    @if(auth()->user()->id === $kasbon->user_id && $kasbon->isPending())
                        <div class="mt-4">
                            <button type="button" class="btn btn-danger" data-toggle="modal"
                                data-target="#hapus_kasbon_{{ $kasbon->id }}">
                                <i class="fa fa-trash"></i> &nbsp Hapus Pengajuan
                            </button>
                        </div>
                    @endif

                </div>

            </div>

        </div>
        <!-- #/ container -->
    </div>

    <!-- Reject Modal -->
    <form id="rejectForm" method="POST">
        <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectModalLabel">Tolak Pengajuan Kasbon</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="alasan">Alasan Penolakan</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            <i class="ti-close m-r-5 f-s-12"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fa fa-times m-r-5"></i> Tolak
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Complete Modal -->
    <form id="completeForm" method="POST" enctype="multipart/form-data">
        <div class="modal fade" id="completeModal" tabindex="-1" role="dialog" aria-labelledby="completeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="completeModalLabel">Selesaikan Kasbon</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="bukti">Upload Bukti <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="bukti" name="bukti" accept=".jpeg,.png,.jpg,.webp"
                                required>
                            <small class="form-text text-muted">Format: JPEG, PNG, JPG, WebP. Maksimal 2MB. File akan
                                dikonversi ke WebP untuk optimasi.</small>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_pengiriman">Tanggal Pengiriman <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_pengiriman" name="tanggal_pengiriman"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            <i class="ti-close m-r-5 f-s-12"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-check-circle m-r-5"></i> Selesaikan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Delete Modal -->
    @if(auth()->user()->id === $kasbon->user_id && $kasbon->isPending())
        <form method="POST" action="{{ route('kasbon.destroy', $kasbon) }}">
            <div class="modal fade" id="hapus_kasbon_{{ $kasbon->id }}" tabindex="-1" role="dialog"
                aria-labelledby="hapusModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="hapusModalLabel">Peringatan!</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Yakin ingin menghapus pengajuan kasbon ini?</p>
                            @csrf
                            {{ method_field('DELETE') }}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                <i class="ti-close m-r-5 f-s-12"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fa fa-trash m-r-5"></i> Ya, Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endif

    <script>
        function approveKasbon(kasbonId) {
            if (confirm('Yakin ingin menyetujui pengajuan kasbon ini?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/kasbon/${kasbonId}/approve`;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';

                form.appendChild(csrfToken);
                document.body.appendChild(form);
                form.submit();
            }
        }

        function rejectKasbon(kasbonId) {
            document.getElementById('rejectForm').action = `/kasbon/${kasbonId}/reject`;
            $('#rejectModal').modal('show');
        }

        function processKasbon(kasbonId) {
            if (confirm('Yakin ingin memproses kasbon ini?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/kasbon/${kasbonId}/process`;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';

                form.appendChild(csrfToken);
                document.body.appendChild(form);
                form.submit();
            }
        }

        function completeKasbon(kasbonId) {
            document.getElementById('completeForm').action = `/kasbon/${kasbonId}/complete`;
            $('#completeModal').modal('show');
        }
    </script>
@endsection