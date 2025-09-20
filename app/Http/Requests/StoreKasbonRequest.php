<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreKasbonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Clean nominal value by removing thousand separators
        if ($this->has('nominal')) {
            $nominal = $this->input('nominal');
            // Remove thousand separators (dots) but keep decimal point
            $cleanedNominal = preg_replace('/\.(?=\d{3})/', '', $nominal);
            $this->merge([
                'nominal' => $cleanedNominal
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nominal' => [
                'required',
                'numeric',
                'min:1',
                'max:999999999.99',
                function ($attribute, $value, $fail) {
                    $user = auth()->user();
                    if ($user && $user->kasbon < $value) {
                        $fail('Saldo kasbon tidak mencukupi. Saldo tersedia: Rp ' . number_format($user->kasbon, 0, ',', '.'));
                    }
                }
            ],
            'keterangan' => 'required|string|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nominal.required' => 'Nominal kasbon harus diisi.',
            'nominal.numeric' => 'Nominal kasbon harus berupa angka.',
            'nominal.min' => 'Nominal kasbon minimal Rp 1.',
            'nominal.max' => 'Nominal kasbon maksimal Rp 999.999.999,99.',
            'keterangan.required' => 'Keterangan harus diisi.',
            'keterangan.max' => 'Keterangan maksimal 255 karakter.',
        ];
    }
}
