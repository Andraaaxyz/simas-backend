<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bidang;

class BidangSeeder extends Seeder
{
    public function run(): void
    {
        Bidang::insert([
            ['nama_bidang' => 'Umum'],
            ['nama_bidang' => 'Keuangan'],
            ['nama_bidang' => 'Pengadaan'],
            ['nama_bidang' => 'Kinerja'],
            ['nama_bidang' => 'Pengembangan Aparatur'],
            ['nama_bidang' => 'Mutasi'],
        ]);
    }
}