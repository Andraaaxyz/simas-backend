<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDisposisiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'surat_masuk_id' => 'required|exists:surat_masuks,id',
            'kepada_user' => 'required|exists:users,id',
            'tanggal_disposisi' => 'required|date',
            'instruksi' => 'required|string',
            'catatan' => 'nullable|string',
        ];
    }
}