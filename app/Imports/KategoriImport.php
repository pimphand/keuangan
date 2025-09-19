<?php

namespace App\Imports;

use App\Kategori;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class KategoriImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return Kategori::firstOrCreate([
            'kategori' => $row['nama_kategori'],
        ]);
    }

    public function rules(): array
    {
        return [
            '*.nama_kategori' => 'required|string|max:255',
        ];
    }
}
