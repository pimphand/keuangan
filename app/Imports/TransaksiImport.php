<?php

namespace App\Imports;

use App\Transaksi;
use App\Kategori;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;

class TransaksiImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Validate jenis
        if (!in_array($row['jenis'], ['Pemasukan', 'Pengeluaran'])) {
            throw new \Exception("Jenis harus 'Pemasukan' atau 'Pengeluaran'");
        }

        // Parse tanggal
        try {
            $tanggal = Carbon::parse($row['tanggal'])->format('Y-m-d');
        } catch (\Exception $e) {
            throw new \Exception("Format tanggal tidak valid: {$row['tanggal']}");
        }

        // Find or create kategori by name
        $kategori = Kategori::firstOrCreate([
            'kategori' => $row['kategori']
        ]);

        // Use firstOrCreate to avoid duplicate entries based on tanggal and keterangan
        return Transaksi::firstOrCreate([
            'tanggal' => $this->transformDate($row['tanggal']),
            'keterangan' => $row['keterangan'] ?? '',
        ], [
            'jenis' => $row['jenis'],
            'kategori_id' => $kategori->id,
            'nominal' => $row['nominal'],
        ]);
    }

    public function rules(): array
    {
        return [
            '*.tanggal' => 'required',
            '*.jenis' => 'required|in:Pemasukan,Pengeluaran',
            '*.kategori' => 'required|string|max:255',
            '*.nominal' => 'required|numeric|min:0',
            '*.keterangan' => 'nullable|string|max:255',
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    private function transformDate($value)
    {
        try {
            if (is_numeric($value)) {
                return Date::excelToDateTimeObject($value)->format('Y-m-d');
            } else {
                return \Carbon\Carbon::parse($value)->format('Y-m-d');
            }
        } catch (\Exception $e) {
            return null;
        }
    }
}
