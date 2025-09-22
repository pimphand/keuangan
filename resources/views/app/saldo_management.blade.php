@extends('app.master')

@section('konten')
    <style>
    .user-row:hover {
        background-color: #f8f9fa !important;
    }

    .user-row.table-secondary:hover {
        background-color: #e9ecef !important;
    }

    .user-row.table-primary {
        background-color: #cce5ff !important;
    }

    .user-row.selected {
        background-color: #007bff !important;
        color: white;
    }

    .user-row.selected:hover {
        background-color: #0056b3 !important;
    }

    .user-row[data-disabled="true"] {
        cursor: not-allowed !important;
    }

    .user-row[data-disabled="true"]:hover {
        background-color: #f5f5f5 !important;
    }

    .badge-lg {
        font-size: 0.9rem;
        padding: 0.5rem 0.75rem;
    }

    .text-muted {
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
    }
    </style>

        <div class="content-body">

            <div class="row page-titles mx-0 mt-2">

                <h3 class="col p-md-0">Manajemen Gaji</h3>

                <div class="col p-md-0">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Manajemen Gaji</a></li>
                    </ol>
                </div>

            </div>

            <div class="container-fluid">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ session('error') }}
                    </div>
                @endif

                <div class="row">
                    <!-- Add Saldo Form -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h4>Gaji Pegawai</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('saldo.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="tunjangan" id="tunjangan_hidden" value="">
                                    <div class="form-group">
                                        <label>Pilih User</label>
                                        <select name="user_id" id="user_select" class="form-control" required>
                                            <option value="">-- Pilih User --</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" data-bank="{{ $user->bank }}" data-rekening="{{ $user->rekening }}" @if(!$user->canReceiveSaldoThisMonth()) disabled
                                                @endif>
                                                    {{ $user->name }} - {{ $user->bank }} ({{ $user->rekening }})
                                                    @if(!$user->canReceiveSaldoThisMonth())
                                                        - Sudah menerima saldo bulan ini
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Atau klik pada baris user di tabel untuk memilih</small>
                                    </div>
                                    <!-- Selected User Details -->
                                    <div id="selected_user_info" class="mt-3" style="display: none;">
                                        <div class="alert alert-info">
                                            <h6><i class="fa fa-user"></i> Detail User Terpilih:</h6>
                                            <div id="selected_user_details"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Tunjangan (Otomatis)</label>
                                        <input type="number" name="amount" id="tunjangan" class="form-control" step="0.01" min="0.01" required
                                            placeholder="Tunjangan di input otomatis" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Gaji Akhir (Otomatis)</label>
                                        <input type="number" name="amount" id="gaji_akhir" class="form-control" step="0.01" min="0.01" required
                                            placeholder="Gaji Akhir akan dihitung otomatis" readonly>
                                        <small class="text-muted">Gaji Akhir = (Gaji + Tunjangan) - Kasbon Terpakai</small>
                                    </div>
                                    <div class="form-group">
                                        <label>Catatan (Opsional)</label>
                                        <textarea name="notes" class="form-control" rows="3"
                                            placeholder="Catatan untuk Gaji Akhir"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block"
                                        @if(!$isDateAllowed) disabled @endif>
                                        <i class="fa fa-plus"></i> Tambah Gaji Akhir
                                    </button>


                                    @if(!$isDateAllowed)
                                        <small class="text-danger d-block mt-2">
                                            <i class="fa fa-exclamation-triangle"></i>
                                            Gaji  hanya dapat dilakukan pada tanggal {{ env('SALDO_ALLOWED_START_DATE', '1') }}-{{ env('SALDO_ALLOWED_END_DATE', '10') }} setiap bulannya.
                                        </small>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- User List with Saldo -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h4>Daftar User dan Saldo</h4>
                                <small class="text-muted">Bulan: {{ date('F Y') }}</small>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>Email</th>
                                                <th>Bank</th>
                                                <th>Gaji</th>
                                                <th>Kasbon</th>
                                                <th>Status Bulan Ini</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $no = 1; @endphp
                                            @foreach($users as $user)
                                                <tr class="user-row @if(!$user->canReceiveSaldoThisMonth()) table-secondary @endif"
                                                    data-user-id="{{ $user->id }}"
                                                    data-user-name="{{ $user->name }}"
                                                    data-user-bank="{{ $user->bank }}"
                                                    data-user-rekening="{{ $user->rekening }}"
                                                    data-user-email="{{ $user->email }}"
                                                    data-user-saldo="{{ $user->saldo }}"
                                                    data-user-tunjangan="{{ $user->tunjangan }}"
                                                    data-user-kasbon="{{ $user->kasbon }}"
                                                    data-user-kasbon-terpakai="{{ $user->kasbon_terpakai }}"
                                                    
                                                    
                                                    @if(!$user->canReceiveSaldoThisMonth()) data-disabled="true" @endif
                                                    style="cursor: pointer; transition: background-color 0.2s ease;">
                                                    <td>{{ $no++ }}</td>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>{{ $user->bank }} <br> {{ $user->rekening }}</td>
                                                    <td>
                                                        <span class="badge badge-success">
                                                            Rp {{ number_format($user->saldo, 2, ',', '.') }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="mb-1">
                                                            <small class="text-muted d-block">Tersedia:</small>
                                                            <span class="badge badge-info">
                                                                Rp {{ number_format($user->kasbon, 2, ',', '.') }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <small class="text-muted d-block">Terpakai:</small>
                                                            <span class="badge badge-warning">
                                                                Rp {{ number_format($user->kasbon_terpakai, 2, ',', '.') }}
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if($user->canReceiveSaldoThisMonth())
                                                            <span class="badge badge-warning">
                                                                <i class="fa fa-clock"></i> Belum menerima
                                                            </span>
                                                        @else
                                                            <span class="badge badge-info">
                                                                <i class="fa fa-check"></i> Sudah menerima
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('saldo.history', $user->id) }}"
                                                            class="btn btn-sm btn-info" onclick="event.stopPropagation();">
                                                            <i class="fa fa-history"></i> Riwayat
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Panel -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Informasi Penting</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <h6><i class="fa fa-info-circle"></i> Aturan Penambahan Saldo:</h6>
                                    <ul class="mb-0">
                                        <li>Penambahan saldo hanya dapat dilakukan pada tanggal
                                            <strong>{{ env('SALDO_ALLOWED_START_DATE', '1') }}-{{ env('SALDO_ALLOWED_END_DATE', '10') }}</strong>
                                            setiap bulannya
                                        </li>
                                        <li>Setiap user hanya dapat menerima penambahan saldo <strong>1 kali per bulan</strong>
                                        </li>
                                        <li>Hari ini: <strong>{{ date('d F Y') }}</strong>
                                            @if(date('d') >= env('SALDO_ALLOWED_START_DATE', 1) && date('d') <= env('SALDO_ALLOWED_END_DATE', 10))
                                                <span class="badge badge-success">Dalam periode yang diizinkan</span>
                                            @else
                                                <span class="badge badge-danger">Di luar periode yang diizinkan</span>
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

@endsection

@push('js')
    <script>
    $(document).ready(function() {

        // Handle table row clicks with event delegation
        $(document).on('click', '.user-row', function(e) {
            e.preventDefault();
            e.stopPropagation();

            console.log('Row clicked!');

            var $row = $(this);
            var userId = $row.data('user-id');
            var userName = $row.data('user-name');
            var userBank = $row.data('user-bank');
            var userRekening = $row.data('user-rekening');
            var userEmail = $row.data('user-email');
            var userSaldo = $row.data('user-saldo');
            var isDisabled = $row.data('disabled');

            console.log('User ID:', userId, 'Disabled:', isDisabled);

            // Check if user can receive saldo this month
            if (isDisabled) {
                return; // Simply return without any alert
            }

            // Set the select dropdown value
            $('#user_select').val(userId);

            // Calculate and set Gaji Akhir automatically
            var userSaldo = parseFloat($row.data('user-saldo')) || 0;
            var userTunjangan = parseFloat($row.data('user-tunjangan')) || 0;
            
            // Set tunjangan field
            $('#tunjangan').val(userTunjangan.toFixed(2));
                $('#tunjangan_hidden').val(userTunjangan.toFixed(2));
            $('#tunjangan_hidden').val(userTunjangan.toFixed(2));
            var userKasbonTerpakai = parseFloat($row.data('user-kasbon-terpakai')) || 0;
            var gajiAkhir = (userSaldo + userTunjangan) - userKasbonTerpakai;

            // Ensure gaji akhir is not negative
            gajiAkhir = Math.max(0, gajiAkhir);

            $('#gaji_akhir').val(gajiAkhir.toFixed(2));

            // Show selected user info
            var userKasbon = $row.data('user-kasbon') || 0;
            var userKasbonTerpakai = $row.data('user-kasbon-terpakai') || 0;

            var userDetails = `
                <div class="row">
                    <div class="col-md-6">
                        <strong>Nama:</strong> ${userName}<br>
                        <strong>Email:</strong> ${userEmail}
                    </div>
                    <div class="col-md-6">
                        <strong>Bank:</strong> ${userBank}<br>
                        <strong>No. Rekening:</strong> ${userRekening}
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3">
                        <div class="text-center">
                            <strong class="d-block text-muted">Gaji</strong> <br>
                            <span class="badge badge-success badge-xl">Rp ${new Intl.NumberFormat('id-ID', {minimumFractionDigits: 2}).format(userSaldo)}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <strong class="d-block text-muted">Tunjangan</strong> <br>
                            <span class="badge badge-primary badge-xl">Rp ${new Intl.NumberFormat('id-ID', {minimumFractionDigits: 2}).format(userTunjangan)}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <strong class="d-block text-muted">Kasbon Tersedia</strong>
                            <span class="badge badge-info badge-xl">Rp ${new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2 }).format(userKasbon)}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <strong class="d-block text-muted">Kasbon Terpakai</strong>
                            <span class="badge badge-warning badge-xl">Rp ${new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2 }).format(userKasbonTerpakai)}</span>
                        </div>
                    </div>
                </div>
            `;

            $('#selected_user_details').html(userDetails);
            $('#selected_user_info').show();

            // Add visual feedback
            $('.user-row').removeClass('table-primary selected');
            $row.addClass('table-primary selected');

            // Scroll to form if needed
            $('html, body').animate({
                scrollTop: $('#user_select').offset().top - 100
            }, 500);

        });

        // Handle select dropdown change
        $('#user_select').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var userId = selectedOption.val();

            console.log('Dropdown changed to user ID:', userId);

            if (userId) {
                var userName = selectedOption.text().split(' - ')[0];
                var bankInfo = selectedOption.text().split(' - ')[1];
                var bank = bankInfo.split(' (')[0];
                var rekening = bankInfo.split(' (')[1].replace(')', '');

                // Find corresponding row and highlight it
                $('.user-row').removeClass('table-primary selected');
                var $targetRow = $('.user-row[data-user-id="' + userId + '"]');
                $targetRow.addClass('table-primary selected');

                // Calculate and set Gaji Akhir automatically
                var userSaldo = parseFloat($targetRow.data('user-saldo')) || 0;
                var userTunjangan = parseFloat($targetRow.data('user-tunjangan')) || 0;
                
                // Set tunjangan field
                $('#tunjangan').val(userTunjangan.toFixed(2));
                $('#tunjangan_hidden').val(userTunjangan.toFixed(2));
            $('#tunjangan_hidden').val(userTunjangan.toFixed(2));
                var userKasbonTerpakai = parseFloat($targetRow.data('user-kasbon-terpakai')) || 0;
                var gajiAkhir = (userSaldo + userTunjangan) - userKasbonTerpakai;

                // Ensure gaji akhir is not negative
                gajiAkhir = Math.max(0, gajiAkhir);

                $('#gaji_akhir').val(gajiAkhir);

                // Get kasbon data
                var userKasbon = $targetRow.data('user-kasbon') || 0;
                var userKasbonTerpakai = $targetRow.data('user-kasbon-terpakai') || 0;

                // Show selected user info
                var userDetails = `
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Nama:</strong> ${userName}<br>
                            <strong>Email:</strong> ${$targetRow.data('user-email')}
                        </div>
                        <div class="col-md-6">
                            <strong>Bank:</strong> ${bank}<br>
                            <strong>No. Rekening:</strong> ${rekening}
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <div class="text-center">
                                <strong class="d-block text-muted">Saldo Saat Ini</strong>
                                <span class="badge badge-success badge-lg">Rp ${new Intl.NumberFormat('id-ID', {minimumFractionDigits: 2}).format($targetRow.data('user-saldo'))}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <strong class="d-block text-muted">Tunjangan</strong>
                                <span class="badge badge-primary badge-lg">Rp ${new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2 }).format(userTunjangan)}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <strong class="d-block text-muted">Kasbon Tersedia</strong>
                                <span class="badge badge-info badge-lg">Rp ${new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2 }).format(userKasbon)}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <strong class="d-block text-muted">Kasbon Terpakai</strong>
                                <span class="badge badge-warning badge-lg">Rp ${new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2 }).format(userKasbonTerpakai)}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <strong class="d-block text-muted">Gaji Akhir</strong>
                                <span class="badge badge-primary badge-lg">Rp ${new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2 }).format(gajiAkhir)}</span>
                            </div>
                        </div>
                    </div>
                `;

                $('#selected_user_details').html(userDetails);
                $('#selected_user_info').show();
            } else {
                $('.user-row').removeClass('table-primary selected');
                $('#selected_user_info').hide();
                $('#gaji_akhir').val(''); // Clear gaji akhir field
            }
        });

        // Add hover effects
        $(document).on('mouseenter', '.user-row', function() {
            var $row = $(this);
            if (!$row.hasClass('table-primary') && !$row.data('disabled')) {
                $row.addClass('hover-effect');
            }
        });

        $(document).on('mouseleave', '.user-row', function() {
            $(this).removeClass('hover-effect');
        });

        console.log('All events bound successfully');
    });
    </script>
@endpush
