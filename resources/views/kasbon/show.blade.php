@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Detail Pengajuan Kasbon</h4>
                        <a href="{{ route('kasbon.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

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
                                                <span class="badge bg-success fs-6">Disetujui</span>
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
                                    <i class="fas fa-check"></i> Setujui
                                </button>
                                <button type="button" class="btn btn-danger" onclick="rejectKasbon({{ $kasbon->id }})">
                                    <i class="fas fa-times"></i> Tolak
                                </button>
                            </div>
                        @endif

                        @if(auth()->user()->id === $kasbon->user_id && $kasbon->isPending())
                            <div class="mt-4">
                                <form action="{{ route('kasbon.destroy', $kasbon) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus pengajuan kasbon ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="fas fa-trash"></i> Hapus Pengajuan
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Pengajuan Kasbon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="rejectForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="alasan" class="form-label">Alasan Penolakan</label>
                            <textarea class="form-control" id="alasan" name="alasan" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Tolak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
            new bootstrap.Modal(document.getElementById('rejectModal')).show();
        }
    </script>
@endsection