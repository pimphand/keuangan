@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Daftar Kasbon</h4>
                        @if(auth()->user()->level !== 'admin')
                            <a href="{{ route('kasbon.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Ajukan Kasbon
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
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <form method="GET" action="{{ route('kasbon.index') }}">
                                    <div class="input-group">
                                        <select name="status" class="form-select">
                                            <option value="">Semua Status</option>
                                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                                Pending</option>
                                            <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>
                                                Ditolak</option>
                                        </select>
                                        <button class="btn btn-outline-secondary" type="submit">Filter</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        @if(auth()->user()->level === 'admin')
                                            <th>Pegawai</th>
                                        @endif
                                        <th>Nominal</th>
                                        <th>Keterangan</th>
                                        <th>Status</th>
                                        <th>Tanggal Pengajuan</th>
                                        @if(auth()->user()->level === 'admin')
                                            <th>Disetujui Oleh</th>
                                            <th>Aksi</th>
                                        @else
                                            <th>Aksi</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($kasbons as $index => $kasbon)
                                        <tr>
                                            <td>{{ $kasbons->firstItem() + $index }}</td>
                                            @if(auth()->user()->level === 'admin')
                                                <td>{{ $kasbon->user->name }}</td>
                                            @endif
                                            <td>Rp {{ number_format($kasbon->nominal, 0, ',', '.') }}</td>
                                            <td>{{ $kasbon->keterangan }}</td>
                                            <td>
                                                @if($kasbon->status === 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($kasbon->status === 'disetujui')
                                                    <span class="badge bg-success">Disetujui</span>
                                                @else
                                                    <span class="badge bg-danger">Ditolak</span>
                                                @endif
                                            </td>
                                            <td>{{ $kasbon->created_at->format('d/m/Y H:i') }}</td>
                                            @if(auth()->user()->level === 'admin')
                                                <td>
                                                    @if($kasbon->disetujui)
                                                        {{ $kasbon->disetujui->name }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
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
                                                <td>
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
                                            <td colspan="{{ auth()->user()->level === 'admin' ? '8' : '6' }}"
                                                class="text-center">
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

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $kasbons->links() }}
                        </div>
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