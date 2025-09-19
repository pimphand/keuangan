<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KategoriTemplateExport implements FromArray, WithHeadings
{
    /**
     * @return array
     */
    public function array(): array
    {
        return [
            ['Makanan'],
            ['Transportasi'],
            ['Hiburan'],
            ['Kesehatan'],
            ['Pendidikan'],
            ['Lainnya']
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'nama_kategori'
        ];
    }
}
