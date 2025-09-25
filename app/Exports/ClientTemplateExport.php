<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClientTemplateExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            ['PT Sukses Abadi', 'Jl. Merdeka No. 123, Jakarta', '081234567890', 'Swasta', 'Teknologi', 'https://maps.app.goo.gl/xxxxxxxx'],
            ['Dinas Kominfo Kota X', 'Jl. Protokol No. 45, Kota X', '0211234567', 'Pemerintahan', 'Pemerintahan', 'https://maps.app.goo.gl/yyyyyyyy'],
        ];
    }

    public function headings(): array
    {
        return [
            'nama',
            'alamat',
            'telepon',
            'type',
            'industri',
            'maps'
        ];
    }
}
