@extends('app.master')

@section('konten')

    <div class="content-body">

        <div class="container-fluid mt-3">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Manajemen Absensi Pegawai</h4>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered zero-configuration">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Pegawai</th>
                                            <th>Jenis Absensi</th>
                                            <th>Waktu Absen</th>
                                            <th>Lokasi</th>
                                            <th>Status</th>
                                            <th>Foto</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($absensi as $key => $absen)
                                            <tr>
                                                <td>{{ $key + 1 + ($absensi->currentPage() - 1) * $absensi->perPage() }}</td>
                                                <td>{{ $absen->user->name }}</td>
                                                <td>
                                                    <span
                                                        class="badge badge-{{ $absen->jenis == 'masuk' ? 'success' : ($absen->jenis == 'keluar' ? 'danger' : 'warning') }}">
                                                        {{ ucwords(str_replace('_', ' ', $absen->jenis)) }}
                                                    </span>
                                                </td>
                                                <td>{{ $absen->waktu_absen->format('d M Y H:i:s') }}</td>
                                                <td>
                                                    @if($absen->alamat)
                                                        {{ Str::limit($absen->alamat, 30) }}
                                                    @else
                                                        {{ $absen->latitude }}, {{ $absen->longitude }}
                                                    @endif
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge badge-{{ $absen->status == 'valid' ? 'success' : ($absen->status == 'invalid' ? 'danger' : 'warning') }}">
                                                        {{ ucfirst($absen->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($absen->foto)
                                                        <button class="btn btn-sm btn-info"
                                                            onclick="viewPhoto('{{ $absen->foto }}')">
                                                            <i class="fa fa-eye"></i> Lihat
                                                        </button>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <form method="POST"
                                                        action="{{ route('absensi.update_status', $absen->id) }}"
                                                        style="display: inline;">
                                                        @csrf
                                                        @method('PUT')
                                                        <select name="status" class="form-control form-control-sm"
                                                            onchange="this.form.submit()">
                                                            <option value="pending" {{ $absen->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                            <option value="valid" {{ $absen->status == 'valid' ? 'selected' : '' }}>Valid</option>
                                                            <option value="invalid" {{ $absen->status == 'invalid' ? 'selected' : '' }}>Invalid</option>
                                                        </select>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center">
                                {{ $absensi->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Photo Modal -->
    <div class="modal fade" id="photoModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Foto Absensi</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img id="modal-photo" src="" alt="Foto Absensi" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>

    <script>
        function viewPhoto(photoPath) {
            document.getElementById('modal-photo').src = '/storage/' + photoPath;
            $('#photoModal').modal('show');
        }
    </script>

@endsection