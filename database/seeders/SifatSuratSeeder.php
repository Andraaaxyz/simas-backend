<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SifatSurat;

class SifatSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SifatSurat::insert([
            ['nama_sifat' => 'Biasa'],
            ['nama_sifat' => 'Penting'],
            ['nama_sifat' => 'Rahasia'],
        ]);
    }
}
