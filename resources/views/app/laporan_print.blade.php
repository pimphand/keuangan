<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Laporan Keuangan</title>
    <style type="text/css">
        body {
            font-size: 11pt;
            margin: 0;
            padding: 20px;
            font-family: Arial, sans-serif;
        }

        .header {
            margin-bottom: 25px;
            position: relative;
        }

        .logo-section {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            position: relative;
        }

        .logo {
            width: 100px;
            height: auto;
            max-height: 100px;
            margin-right: 20px;
            object-fit: contain;
        }

        .company-info {
            flex: 1;
        }

        .company-info h1 {
            margin: 0;
            font-size: 12pt;
            font-weight: bold;
            color: #2c3e50;
            line-height: 1.2;
        }

        .company-info p {
            margin: 3px 0;
            font-size: 11pt;
            color: #666;
        }

        .report-title {
            position: absolute;
            top: 0;
            right: 0;
            text-align: right;
        }

        .report-title h2 {
            margin: 0;
            font-size: 14pt;
            font-weight: bold;
            color: #2c3e50;
            line-height: 1.3;
        }

        .header-line {
            border-bottom: 1px solid #2c3e50;
            margin-top: 5px;
            margin-bottom: 10px;
        }

        .info-table {
            margin-bottom: 20px;
            font-size: 11pt;
        }

        .info-table td {
            padding: 5px 10px;
            vertical-align: top;
        }

        .info-table td:first-child {
            font-weight: bold;
            background-color: #f8f9fa;
            width: 25%;
        }

        .info-table td:nth-child(2) {
            width: 5%;
            text-align: center;
        }

        .table,
        .table th,
        .table td {
            border: 1px solid #333;
        }

        .table {
            border-collapse: collapse;
            width: 100%;
            font-size: 10pt;
        }

        .table th {
            background-color: #f8f9fa;
            padding: 8px;
            text-align: center;
            font-weight: bold;
        }

        .table td {
            padding: 6px;
            vertical-align: top;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-bold {
            font-weight: bold;
        }

        .tfoot tr:last-child {
            background-color: #e9ecef;
            font-weight: bold;
        }

        .tfoot tr:first-child {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .tfoot td {
            padding: 8px;
        }

        @media print {
            body {
                margin: 0;
                padding: 15px;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="header">
        <div class="logo-section">
            <img src="{{ asset('logo.png') }}" alt="Logo" class="logo">
            <div class="company-info">
                <h1>mataram</h1>
                <h1>digital</h1>
                <h1>teknologi</h1>
            </div>
            <div class="report-title">
                <h2>LAPORAN KEUANGAN</h2>
                <div class="header-line"></div>
                <h2>PT Mataram Digital Teknologi</h2>
            </div>
        </div>
    </div>

    <table class="info-table">
        <tr>
            <td>DARI TANGGAL</td>
            <td>:</td>
            <td>{{ date('d-m-Y', strtotime($_GET['dari'])) }}</td>
        </tr>
        <tr>
            <td>SAMPAI TANGGAL</td>
            <td>:</td>
            <td>{{ date('d-m-Y', strtotime($_GET['sampai'])) }}</td>
        </tr>
        <tr>
            <td>KATEGORI</td>
            <td>:</td>
            <td>
                @php
                    $id_kategori = $_GET['kategori'];
                @endphp

                @if($id_kategori == "")
                    @php
                        $kat = "SEMUA KATEGORI";
                    @endphp
                @else
                    @php
                        $katt = DB::table('kategori')->where('id', $id_kategori)->first();
                        $kat = $katt->kategori;
                    @endphp
                @endif

                {{$kat}}
            </td>
        </tr>
    </table>
    <table class="table">
        <thead>
            <tr>
                <th rowspan="2" class="text-center" width="5%">NO</th>
                <th rowspan="2" class="text-center" width="12%">TANGGAL</th>
                <th rowspan="2" class="text-center" width="18%">KATEGORI</th>
                <th rowspan="2" class="text-center" width="25%">KETERANGAN</th>
                <th colspan="2" class="text-center" width="40%">JENIS</th>
            </tr>
            <tr>
                <th class="text-center" width="20%">PEMASUKAN</th>
                <th class="text-center" width="20%">PENGELUARAN</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $saldo = 0;
                $total_pemasukan = 0;
                $total_pengeluaran = 0;
              @endphp
            @foreach($transaksi as $t)
                @php
                    if ($t->jenis == "Pemasukan") {
                        $saldo += $t->nominal;
                    } else {
                        $saldo -= $t->nominal;
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td class="text-center">{{ date('d-m-Y', strtotime($t->tanggal)) }}</td>
                    <td>{{ $t->kategori->kategori }}</td>
                    <td>{{ $t->keterangan }}</td>
                    <td class="text-center">
                        @if($t->jenis == "Pemasukan")
                            {{ "Rp." . number_format($t->nominal) . ",-" }}
                            @php $total_pemasukan += $t->nominal; @endphp
                        @else
                            {{ "-" }}
                        @endif
                    </td>
                    <td class="text-center">
                        @if($t->jenis == "Pengeluaran")
                            {{ "Rp." . number_format($t->nominal) . ",-" }}
                            @php $total_pengeluaran += $t->nominal; @endphp
                        @else
                            {{ "-" }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot class="tfoot">
            <tr>
                <td colspan="4" class="text-bold text-right">Sub Total</td>
                <td class="text-center text-bold">{{ "Rp." . number_format($total_pemasukan) . ",-" }}</td>
                <td class="text-center text-bold">{{ "Rp." . number_format($total_pengeluaran) . ",-" }}</td>
            </tr>
            <tr>
                <td colspan="4" class="text-bold text-right">Total (Pemasukan - Pengeluaran)</td>
                <td colspan="2" class="text-center text-bold">
                    {{ "Rp." . number_format($total_pemasukan - $total_pengeluaran) . ",-" }}
                </td>
            </tr>
        </tfoot>
    </table>

    <script type="text/javascript">
        window.print();
    </script>

</body>

</html>