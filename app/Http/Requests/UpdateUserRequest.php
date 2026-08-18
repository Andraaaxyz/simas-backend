<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = $this->route('user');

        return [
            'role_id' => 'sometimes|required|exists:roles,id',

            'bidang_id' => 'nullable|exists:bidangs,id',

            'nama' => 'sometimes|required|string|max:255',

            'nip' => [
                'sometimes',
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'nip')->ignore($user),
            ],

            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user),
            ],

            'username' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($user),
            ],

            // Password tidak wajib ketika update
            'password' => 'nullable|string|min:8|confirmed',

            'status' => [
                'sometimes',
                'required',
                Rule::in([
                    'aktif',
                    'nonaktif',
                ]),
            ],
        ];
    }
}