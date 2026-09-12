<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'Admin';
    case PIMPINAN = 'Pimpinan';
    case PEGAWAI = 'Pegawai';
}
