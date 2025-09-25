<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KategoriBrosur;

class KategoriBrosurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            'Brosur Produk',
            'Brosur Layanan',
            'Brosur Promosi',
            'Brosur Informasi',
            'Brosur Event',
            'Brosur Korporat'
        ];

        foreach ($kategoris as $kategori) {
            KategoriBrosur::create([
                'nama' => $kategori
            ]);
        }
    }
}
