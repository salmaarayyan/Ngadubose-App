<?php

namespace App\Enums;

enum StatusLaporan: string
{
    case DITERIMA = 'Diterima';
    case DIVERIFIKASI = 'Diverifikasi';
    case DIPROSES = 'Diproses';
    case SELESAI = 'Selesai';
}