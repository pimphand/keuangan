<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Laporan Kunjungan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        @page {
            margin: 1.2cm 1cm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            color: #222;
            margin: 0;
            padding: 5px;
            background: #fff;
        }

        .header {
            margin-bottom: 10px;
        }

        .logo-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 5px;
        }

        .logo-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logo {
            width: 90px;
            height: auto;
            object-fit: contain;
        }

        .title-block {
            text-align: right;
        }

        .title-block h1 {
            margin: 0;
            font-size: 10pt;
            font-weight: 700;
            color: #2c3e50;
        }

        .subtitle {
            margin: 2px 0 0;
            font-size: 9pt;
            color: #2c3e50;
        }

        .line {
            border-bottom: 1px solid #2c3e50;
            margin-top: 3px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            margin-bottom: 10px;
        }

        .info-table td {
            padding: 1px 3px;
            vertical-align: top;
        }

        .info-table td:first-child {
            width: 28%;
            background: #f8f9fa;
            font-weight: 600;
        }

        .info-table td:nth-child(2) {
            width: 4%;
            text-align: center;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .table th,
        .table td {
            border: 1px solid #333;
            padding: 3px;
        }

        .table th {
            background: #f4f6f8;
            font-weight: 700;
            text-align: center;
            font-size: 9pt;
        }

        .text-center {
            text-align: center;
        }

        .nowrap {
            white-space: nowrap;
        }

        .ringkasan {
            white-space: pre-line;
        }

        .foto {
            width: 70px;
            height: 48px;
            object-fit: cover;
            border-radius: 3px;
        }

        .foto-empty {
            width: 70px;
            height: 48px;
            background: #f1f3f5;
            border: 1px dashed #ced4da;
            border-radius: 3px;
        }

        .note {
            margin-top: 6px;
            font-size: 8pt;
            color: #666;
        }

        /* Repeat table header on print page breaks */
        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
        }

        /* Utilities */
        .mt-8 {
            margin-top: 6px;
        }
    </style>
</head>

<body>
    @php
        use Carbon\Carbon;
        use Illuminate\Support\Str;
        Carbon::setLocale('id');
        $dateFrom = isset($date_from) ? Carbon::parse($date_from) : (request('date_from') ? Carbon::parse(request('date_from')) : null);
        $dateTo = isset($date_to) ? Carbon::parse($date_to) : (request('date_to') ? Carbon::parse(request('date_to')) : null);
        $karyawanLabel = $karyawanLabel ?? ($karyawan ?? 'SEMUA PEGAWAI');
        $logoPath = isset($logoPath) ? $logoPath : (file_exists(public_path('logo.png')) ? public_path('logo.png') : public_path('favicon.png'));
        $items = $kunjungans ?? $kunjungan ?? collect();
    @endphp

    <div class="header">
        <div class="logo-title">
            <div class="logo-wrap">
                <img src="{{ $logoPath }}" alt="Logo" class="logo">
            </div>
            <div class="title-block">
                <h1>LAPORAN KUNJUNGAN</h1>
                <div class="line"></div>
                <div class="subtitle">PT Mataram Digital Teknologi</div>
            </div>
        </div>
    </div>

    <table class="info-table">
        <tr>
            <td>DARI TANGGAL</td>
            <td>:</td>
            <td>{{ $dateFrom ? $dateFrom->translatedFormat('d F Y') : '-' }}</td>
        </tr>
        <tr>
            <td>SAMPAI TANGGAL</td>
            <td>:</td>
            <td>{{ $dateTo ? $dateTo->translatedFormat('d F Y') : '-' }}</td>
        </tr>
        <tr>
            <td>KARYAWAN</td>
            <td>:</td>
            <td>{{ $karyawanLabel ?: 'SEMUA PEGAWAI' }}</td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th width="5%">NO</th>
                <th width="10%">TANGGAL</th>
                <th width="12%">KARYAWAN</th>
                <th width="16%">NAMA CLIENT</th>
                <th width="16%">LOKASI KUNJUNGAN</th>
                <th width="23%">RINGKASAN KUNJUNGAN</th>
                <th width="18%">FOTO KUNJUNGAN</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse ($items as $row)
                @php
                    $tanggal = isset($row->tanggal_kunjungan) ? Carbon::parse($row->tanggal_kunjungan) : (isset($row['tanggal_kunjungan']) ? Carbon::parse($row['tanggal_kunjungan']) : null);
                    $pegawaiNama = data_get($row, 'user.name') ?? ($row->user?->name ?? $row->user_name ?? $row->name ?? data_get($row, 'user_name') ?? data_get($row, 'name') ?? '-');
                    $client = $row->client ?? $row['client'] ?? '-';
                    $lokasi = $row->lokasi ?? $row['lokasi'] ?? '-';
                    $ringkasan = $row->ringkasan ?? $row['ringkasan'] ?? '-';
                    $foto = $row->foto ?? $row['foto'] ?? null;
                    $fotoUrl = $foto ? (Str::startsWith($foto, ['http://', 'https://']) ? $foto : asset($foto)) : null;
                @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td class="text-center nowrap">{{ $tanggal ? $tanggal->format('d-m-Y') : '-' }}</td>
                    <td>{{ $pegawaiNama }}</td>
                    <td>{{ $client }}</td>
                    <td>{{ $lokasi }}</td>
                    <td class="ringkasan">{{ $ringkasan }}</td>
                    <td class="text-center">
                        @if($fotoUrl)
                            <img src="{{ $fotoUrl }}" alt="Foto" class="foto">
                        @else
                            <div class="foto-empty"></div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data kunjungan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="note">Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}</div>

    <script>
        // Auto open print dialog if requested with ?print=1
        (function () {
            var params = new URLSearchParams(window.location.search);
            if (params.get('print') === '1') {
                window.addEventListener('load', function () { window.print(); });
            }
        })();
    </script>
</body>

</html>