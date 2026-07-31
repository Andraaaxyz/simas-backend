<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSuratMasukRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'jenis_surat_id' => 'required|exists:jenis_surats,id',
            'sifat_surat_id' => 'required|exists:sifat_surats,id',

            'no_agenda' => 'required|string|max:100|unique:surat_masuks,no_agenda',
            'no_surat' => 'required|string|max:100|unique:surat_masuks,no_surat',

            'asal_surat' => 'required|string|max:255',
            'tujuan_surat' => 'nullable|string|max:255',

            'perihal' => 'required|string',

            'tanggal_surat' => 'required|date',
            'tanggal_terima' => 'required|date',

            'lampiran' => 'nullable|string|max:255',

            'file_surat' => 'required|file|mimes:pdf|max:5120',
        ];
    }
}