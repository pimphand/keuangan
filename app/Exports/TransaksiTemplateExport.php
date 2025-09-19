<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransaksiTemplateExport implements FromArray, WithHeadings
{
    /**
     * @return array
     */
    public function array(): array
    {
        return [
            ['2024-01-15', 'Pemasukan', 'Gaji', '500000', 'Gaji bulanan'],
            ['2024-01-16', 'Pengeluaran', 'Makanan', '50000', 'Makan siang'],
            ['2024-01-17', 'Pengeluaran', 'Transportasi', '100000', 'Bensin motor'],
            ['2024-01-18', 'Pemasukan', 'Freelance', '200000', 'Freelance project'],
            ['2024-01-19', 'Pengeluaran', 'Belanja', '75000', 'Belanja kebutuhan'],
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'tanggal',
            'jenis',
            'kategori',
            'nominal',
            'keterangan'
        ];
    }
}
