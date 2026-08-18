<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDisposisiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'surat_masuk_id' => 'sometimes|exists:surat_masuks,id',
            'dari_user' => 'sometimes|exists:users,id',
            'kepada_user' => 'sometimes|exists:users,id',
            'tanggal_disposisi' => 'sometimes|date',
            'instruksi' => 'sometimes|string',
            'catatan' => 'nullable|string',
            'status' => 'sometimes|in:menunggu,dibaca,diproses,selesai',
        ];
    }
}