<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisSurat;

class JenisSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JenisSurat::insert([
            ['nama_jenis' => 'Undangan'],
            ['nama_jenis' => 'Edaran'],
            ['nama_jenis' => 'Permohonan'],
            ['nama_jenis' => 'Pemberitahuan'],
        ]);
    }
}
