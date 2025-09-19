<?php

namespace App\Exports;

use App\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransaksiExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $transaksi;

    public function __construct($transaksi)
    {
        $this->transaksi = $transaksi;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->transaksi;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Kategori',
            'Jenis',
            'Nominal',
            'Keterangan'
        ];
    }

    /**
     * @param mixed $transaksi
     * @return array
     */
    public function map($transaksi): array
    {
        static $no = 1;
        return [
            $no++,
            date('d-m-Y', strtotime($transaksi->tanggal)),
            $transaksi->kategori->kategori,
            $transaksi->jenis,
            'Rp. ' . number_format($transaksi->nominal, 0, ',', '.'),
            $transaksi->keterangan
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }
}
