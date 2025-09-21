@extends('app.master')

@section('konten')

    <div class="content-body">

        <div class="row page-titles mx-0 mt-2">

            <h3 class="col p-md-0">Daftar Kasbon</h3>

            <div class="col p-md-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Kasbon</a></li>
                </ol>
            </div>
        </div>

        <div class="container-fluid">

            <div class="card">

                <div class="card-header pt-4">
                    <h3 class="card-title">Filter Kasbon</h3>
                    @if(auth()->user()->level !== 'admin')
                        <a href="{{ route('kasbon.create') }}" class="btn btn-primary float-end">
                            <i class="icon-credit-card menu-icon"></i> Ajukan Kasbon
                        </a>
                    @endif
                </div>

                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('kasbon.index') }}" id="filterForm">
                        @csrf
                        <div class="row">

                            <div class="col-lg-offset-1 col-lg-3">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control" name="status">
                                        <option value="">Semua Status</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>
                                            Disetujui</option>
                                        <option value="di proses" {{ request('status') == 'di proses' ? 'selected' : '' }}>
                                            Di Proses</option>
                                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>
                                            Selesai</option>
                                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>
                                            Ditolak</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary" value="Tampilkan" style="margin-top: 25px">
                                </div>
                            </div>

                        </div>

                    </form>
                    <br>
                </div>

            </div>

            <div class="card">

                <div class="card-header pt-4">
                    <h3 class="card-title">Data Kasbon</h3>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center" width="1%">NO</th>
                                    @if(auth()->user()->level === 'admin')
                                        <th class="text-center">PEGAWAI</th>
                                    @endif
                                    <th class="text-center">NOMINAL</th>
                                    <th class="text-center">KETERANGAN</th>
                                    <th class="text-center">STATUS</th>
                                    <th class="text-center">TANGGAL PENGAJUAN</th>
                                    @if(auth()->user()->level === 'admin')
                                        <th class="text-center">DISETUJUI OLEH</th>
                                        <th class="text-center">AKSI</th>
                                    @else
                                        <th class="text-center">AKSI</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kasbons as $index => $kasbon)
                                    <tr>
                                        <td class="text-center">{{ $kasbons->firstItem() + $index }}</td>
                                        @if(auth()->user()->level === 'admin')
                                            <td>{{ $kasbon->user->name }}</td>
                                        @endif
                                        <td class="text-center">Rp {{ number_format($kasbon->nominal, 0, ',', '.') }}</td>
                                        <td>{{ $kasbon->keterangan }}</td>
                                        <td class="text-center text-white">
                                            @if($kasbon->status === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($kasbon->status === 'disetujui')
                                                <span class="badge bg-info">Disetujui</span>
                                            @elseif($kasbon->status === 'di proses')
                                                <span class="badge bg-primary">Di Proses</span>
                                            @elseif($kasbon->status === 'selesai')
                                                <span class="badge bg-success">Selesai</span>
                                            @else
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $kasbon->created_at->format('d/m/Y H:i') }}</td>
                                        @if(auth()->user()->level === 'admin')
                                            <td class="text-center">
                                                @if($kasbon->disetujui)
                                                    {{ $kasbon->disetujui->name }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('kasbon.show', $kasbon) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($kasbon->isPending())
                                                        <button type="button" class="btn btn-sm btn-success"
                                                            onclick="approveKasbon({{ $kasbon->id }})">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            onclick="rejectKasbon({{ $kasbon->id }})">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        @else
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('kasbon.show', $kasbon) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($kasbon->isPending() && $kasbon->user_id === auth()->id())
                                                        <form action="{{ route('kasbon.destroy', $kasbon) }}" method="POST"
                                                            style="display: inline;"
                                                            onsubmit="return confirm('Yakin ingin menghapus pengajuan kasbon ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ auth()->user()->level === 'admin' ? '8' : '6' }}" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Belum ada pengajuan kasbon</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>

        </div>
        <!-- #/ container -->
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