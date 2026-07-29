<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::insert([
            ['nama_role' => 'Admin'],
            ['nama_role' => 'Pimpinan'],
            ['nama_role' => 'Pegawai'],
        ]);
    }
}