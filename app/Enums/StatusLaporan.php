<?php

namespace App\Enums;

enum StatusLaporan: string
{
    case DITERIMA = 'diterima';
    case DIVERIFIKASI = 'diverifikasi';
    case DIPROSES = 'diproses';
    case SELESAI = 'selesai';
}