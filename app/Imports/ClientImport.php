<?php

namespace App\Imports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ClientImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading
{
    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Normalize phone formats similar to controller logic
        $phone = $row['telepon'] ?? null;
        if (!empty($phone)) {
            $normalized = preg_replace('/[^0-9+]/', '', $phone);
            if (strpos($normalized, '+62') === 0) {
                $phone = '62' . substr($normalized, 3);
            } elseif (strpos($normalized, '08') === 0) {
                $phone = '62' . substr($normalized, 2);
            } elseif (strpos($normalized, '62') === 0) {
                $phone = $normalized;
            } else {
                $phone = $normalized;
            }
        }

        return Client::firstOrCreate([
            'nama' => $row['nama'] ?? '',
        ], [
            'alamat' => $row['alamat'] ?? null,
            'telepon' => $phone,
            'type' => $row['type'] ?? null,
            'industri' => $row['industri'] ?? null,
            'maps' => $row['maps'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.nama' => 'required|string|max:255',
            '*.alamat' => 'nullable|string|max:255',
            '*.telepon' => 'nullable|string|max:30',
            '*.type' => 'nullable|string|max:100',
            '*.industri' => 'nullable|string|max:100',
            '*.maps' => 'nullable|string',
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
