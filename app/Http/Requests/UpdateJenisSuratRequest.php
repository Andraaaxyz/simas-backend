<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJenisSuratRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_jenis' => [
                'required',
                'string',
                'max:100',
                Rule::unique('jenis_surats', 'nama_jenis')
                    ->ignore($this->route('jenis_surat')),
            ],
        ];
    }
}