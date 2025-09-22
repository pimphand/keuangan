<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - {{ $gajian->formatted_periode_gaji }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .slip-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .header p {
            font-size: 16px;
            opacity: 0.9;
        }

        .content {
            padding: 30px;
        }

        .employee-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .info-section h3 {
            font-size: 18px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 15px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 8px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px 0;
        }

        .info-row:not(:last-child) {
            border-bottom: 1px solid #f3f4f6;
        }

        .info-label {
            color: #6b7280;
            font-weight: 500;
        }

        .info-value {
            color: #111827;
            font-weight: 600;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-approved {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-paid {
            background: #d1fae5;
            color: #065f46;
        }

        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .salary-section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 15px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 8px;
        }

        .salary-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .salary-row.total {
            border-top: 2px solid #e5e7eb;
            border-bottom: none;
            font-weight: 600;
            background: #f9fafb;
            margin-top: 10px;
            padding: 15px 0;
        }

        .salary-label {
            color: #374151;
        }

        .salary-value {
            color: #111827;
            font-weight: 600;
        }

        .net-salary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            margin: 30px 0;
        }

        .net-salary-label {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .net-salary-value {
            font-size: 32px;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #6b7280;
            font-style: italic;
            border-top: 1px solid #e5e7eb;
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #10b981;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .print-button:hover {
            background: #059669;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .print-button {
                display: none;
            }

            .slip-container {
                box-shadow: none;
                border-radius: 0;
            }
        }
    </style>
</head>

<body>
    <button class="print-button" onclick="window.print()">
        🖨️ Cetak Slip Gaji
    </button>

    <div class="slip-container">
        <!-- Header -->
        <div class="header">
            <h1>SLIP GAJI KARYAWAN</h1>
            <p>Periode Gaji: {{ $gajian->formatted_periode_gaji }}</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Employee Information -->
            <div class="employee-info">
                <div class="info-section">
                    <h3>Informasi Karyawan</h3>
                    <div class="info-row">
                        <span class="info-label">Nama:</span>
                        <span class="info-value">{{ $gajian->nama }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Jabatan:</span>
                        <span class="info-value">{{ $gajian->jabatan }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status:</span>
                        <span class="info-value">
                            <span class="status-badge status-{{ $gajian->status }}">
                                @php
                                    $statusLabels = [
                                        'pending' => 'Pending',
                                        'approved' => 'Disetujui',
                                        'paid' => 'Dibayar',
                                        'rejected' => 'Ditolak'
                                    ];
                                @endphp
                                {{ $statusLabels[$gajian->status] ?? ucfirst($gajian->status) }}
                            </span>
                        </span>
                    </div>
                </div>

                <div class="info-section">
                    <h3>Informasi Pembayaran</h3>
                    <div class="info-row">
                        <span class="info-label">Tanggal Pembayaran:</span>
                        <span class="info-value">
                            {{ $gajian->tanggal_pembayaran ? $gajian->tanggal_pembayaran->format('d M Y') : '-' }}
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Periode:</span>
                        <span class="info-value">{{ $gajian->formatted_periode_gaji }}</span>
                    </div>
                </div>
            </div>

            <!-- Salary Breakdown -->
            <div class="salary-section">
                <h3 class="section-title">Penghasilan</h3>
                <div class="salary-row">
                    <span class="salary-label">Gaji Pokok:</span>
                    <span class="salary-value">{{ $gajian->formatted_gaji_pokok }}</span>
                </div>
                <div class="salary-row">
                    <span class="salary-label">Tunjangan:</span>
                    <span class="salary-value">{{ $gajian->formatted_tunjangan }}</span>
                </div>
                <div class="salary-row total">
                    <span class="salary-label">Total Penghasilan:</span>
                    <span class="salary-value">Rp
                        {{ number_format((int) $gajian->gaji_pokok + (int) $gajian->tunjangan, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="salary-section">
                <h3 class="section-title">Potongan</h3>
                <div class="salary-row">
                    <span class="salary-label">Potongan:</span>
                    <span class="salary-value" style="color: #dc2626;">- {{ $gajian->formatted_potongan }}</span>
                </div>
                <div class="salary-row total">
                    <span class="salary-label">Total Potongan:</span>
                    <span class="salary-value" style="color: #dc2626;">- {{ $gajian->formatted_potongan }}</span>
                </div>
            </div>

            <!-- Net Salary -->
            <div class="net-salary">
                <div class="net-salary-label">GAJI BERSIH</div>
                <div class="net-salary-value">{{ $gajian->formatted_gaji_bersih }}</div>
            </div>

            <!-- Additional Information -->
            @if($gajian->keterangan)
                <div class="salary-section">
                    <h3 class="section-title">Keterangan</h3>
                    <div style="background: #f9fafb; padding: 15px; border-radius: 8px; color: #6b7280;">
                        {{ $gajian->keterangan }}
                    </div>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Terima kasih atas kerja keras Anda.</p>
        </div>
    </div>
</body>

</html>