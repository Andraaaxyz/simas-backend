<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBidangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'nama_bidang' => [
                'required',
                'string',
                'max:100',
                Rule::unique('bidangs', 'nama_bidang')->ignore($this->route('bidang')),
            ],
        ];
    }
}
