<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJenisSuratRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'nama_jenis' => 'required|string|max:100|unique:jenis_surats,nama_jenis'
        ];
    }
}
