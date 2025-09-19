<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Laporan Keuangan</title>
</head>

<body>

    <style type="text/css">
        body {
            font-size: 11pt;
            margin: 0;
            padding: 0;
        }

        .header {
            margin-bottom: 20px;
        }

        .logo-section {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .logo {
            width: 60px;
            height: 60px;
            margin-right: 15px;
        }

        .company-info h1 {
            margin: 0;
            font-size: 18pt;
            font-weight: bold;
        }

        .company-info p {
            margin: 2px 0;
            font-size: 10pt;
            color: #666;
        }

        .report-title {
            text-align: center;
            margin: 10px 0;
        }

        .report-title h2 {
            margin: 0;
            font-size: 14pt;
            font-weight: bold;
        }

        .table,
        .table th,
        .table td {
            border: 1px solid;
        }

        .table {
            border-collapse: collapse;
        }
    </style>

    <div class="header">
        <div class="logo-section">
            <img src="{{ public_path('logo.png') }}" alt="Logo" class="logo">
            <div class="company-info">
                <h1>PT Mataram Digital Teknologi</h1>
                <p>Perusahaan Teknologi Digital</p>
                <p>www.mataramdigital.com</p>
            </div>
        </div>

        <div class="report-title">
            <h2>LAPORAN KEUANGAN</h2>
        </div>
    </div>

    <table style="width: 50%">
        <tr>
            <td width="40%">DARI TANGGAL</td>
            <td width="5%" class="text-center">:</td>
            <td>{{ date('d-m-Y', strtotime($_GET['dari'])) }}</td>
        </tr>
        <tr>
            <td width="40%">SAMPAI TANGGAL</td>
            <td width="5%" class="text-center">:</td>
            <td>{{ date('d-m-Y', strtotime($_GET['sampai'])) }}</td>
        </tr>
        <tr>
            <td width="40%">KATEGORI</td>
            <td width="5%" class="text-center">:</td>
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
                        $kat = $katt->kategori
                    @endphp
                @endif

                {{$kat}}
            </td>
        </tr>
    </table>
    <br>
    <table class="table" width="100%">
        <thead>
            <tr>
                <th rowspan="2" class="text-center" width="1%">NO</th>
                <th rowspan="2" class="text-center" width="9%">TANGGAL</th>
                <th rowspan="2" class="text-center">KATEGORI</th>
                <th rowspan="2" class="text-center">KETERANGAN</th>
                <th colspan="2" class="text-center">JENIS</th>
            </tr>
            <tr>
                <th class="text-center">PEMASUKAN</th>
                <th class="text-center">PENGELUARAN</th>
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
                        <?php
                if ($t->jenis == "Pemasukan") {
                    $saldo += $t->nominal;
                } else {
                    $saldo -= $t->nominal;
                }
                                                                                                                                          ?>
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
        <tfoot>
            <tr>
                <td colspan="4" class="text-bold text-right">Sub Total</td>
                <td class="text-center">{{ "Rp." . number_format($total_pemasukan) . ",-" }}</td>
                <td class="text-center">{{ "Rp." . number_format($total_pengeluaran) . ",-" }}</td>
            </tr>
            <tr>
                <td colspan="4" class="text-bold text-right">Total (Pemasukan - Pengeluaran)</td>
                <td colspan="2" class="text-center">
                    {{ "Rp." . number_format($total_pemasukan - $total_pengeluaran) . ",-" }}
                </td>
            </tr>
        </tfoot>
    </table>

</body>

</html>