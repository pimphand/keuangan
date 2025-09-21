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
</style>

    <div class="content-body">

        <div class="row page-titles mx-0 mt-2">

            <h3 class="col p-md-0">Manajemen Saldo</h3>

            <div class="col p-md-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">Manajemen Saldo</a></li>
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
                            <h4>Tambah Saldo User</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('saldo.add') }}" method="POST">
                                @csrf
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
                                <div class="form-group" id="selected_user_info" style="display: none;">
                                    <div class="alert alert-info">
                                        <strong>User Terpilih:</strong>
                                        <div id="selected_user_details"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Jumlah Saldo</label>
                                    <input type="number" name="amount" class="form-control" step="0.01" min="0.01" required
                                        placeholder="Masukkan jumlah saldo">
                                </div>
                                <div class="form-group">
                                    <label>Catatan (Opsional)</label>
                                    <textarea name="notes" class="form-control" rows="3"
                                        placeholder="Catatan untuk penambahan saldo"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block"
                                    @if(!$isDateAllowed) disabled @endif>
                                    <i class="fa fa-plus"></i> Tambah Saldo
                                </button>
                                @if(!$isDateAllowed)
                                    <small class="text-danger d-block mt-2">
                                        <i class="fa fa-exclamation-triangle"></i>
                                        Penambahan saldo hanya dapat dilakukan pada tanggal {{ env('SALDO_ALLOWED_START_DATE', '1') }}-{{ env('SALDO_ALLOWED_END_DATE', '10') }} setiap bulannya.
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
                                            <th>Saldo Saat Ini</th>
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

        // Show selected user info
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
            <div class="mt-2">
                <strong>Saldo Saat Ini:</strong>
                <span class="badge badge-success">Rp ${new Intl.NumberFormat('id-ID', {minimumFractionDigits: 2}).format(userSaldo)}</span>
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

        console.log('Selection completed for user:', userName);
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
                <div class="mt-2">
                    <strong>Saldo Saat Ini:</strong>
                    <span class="badge badge-success">Rp ${new Intl.NumberFormat('id-ID', {minimumFractionDigits: 2}).format($targetRow.data('user-saldo'))}</span>
                </div>
            `;

            $('#selected_user_details').html(userDetails);
            $('#selected_user_info').show();
        } else {
            $('.user-row').removeClass('table-primary selected');
            $('#selected_user_info').hide();
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
