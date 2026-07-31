<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSuratMasukRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $suratMasuk = $this->route('surat_masuk');
    
        return [
    
            'jenis_surat_id' => 'sometimes|exists:jenis_surats,id',
    
            'sifat_surat_id' => 'sometimes|exists:sifat_surats,id',
    
            'no_agenda' => [
                'sometimes',
                Rule::unique('surat_masuks')->ignore($suratMasuk),
            ],
    
            'no_surat' => [
                'sometimes',
                Rule::unique('surat_masuks')->ignore($suratMasuk),
            ],
    
            'asal_surat' => 'sometimes|string',
    
            'tujuan_surat' => 'sometimes|nullable|string',
    
            'perihal' => 'sometimes|string',
    
            'tanggal_surat' => 'sometimes|date',
    
            'tanggal_terima' => 'sometimes|date',
    
            'lampiran' => 'sometimes|nullable|string',
    
            'file_surat' => 'sometimes|nullable|file|mimes:pdf|max:5120',
        ];
    }
}