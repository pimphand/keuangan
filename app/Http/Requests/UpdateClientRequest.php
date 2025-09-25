<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:500',
            'telepon' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'type' => 'nullable|string|max:100',
            'industri' => 'nullable|string|max:100',
            'maps' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'nama.required' => 'Nama client wajib diisi.',
            'nama.max' => 'Nama client maksimal 255 karakter.',
            'alamat.max' => 'Alamat maksimal 500 karakter.',
            'telepon.max' => 'Nomor telepon maksimal 20 karakter.',
            'logo.image' => 'Logo harus berupa file gambar.',
            'logo.mimes' => 'Logo harus berformat JPEG, PNG, JPG, GIF, atau WebP.',
            'logo.max' => 'Ukuran logo maksimal 2MB.',
            'type.max' => 'Type maksimal 100 karakter.',
            'industri.max' => 'Industri maksimal 100 karakter.',
        ];
    }
}
