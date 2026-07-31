<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSifatSuratRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_sifat' => [
                'required',
                'string',
                'max:100',
                Rule::unique('sifat_surats', 'nama_sifat')->ignore($this->route('sifat_surat')),
            ],
        ];
    }
}