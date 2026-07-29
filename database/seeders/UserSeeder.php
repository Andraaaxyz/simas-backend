<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'role_id' => 1,
            'bidang_id' => 1,
            'nama' => 'Administrator',
            'nip' => '000000000000000001',
            'email' => 'admin@simas.com',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'status' => 'aktif',
        ]);
    }
}