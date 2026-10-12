<?php

namespace App\Enums;

enum JenisPengaduan: string
{
    case GRATIFIKASI = 'gratifikasi';
    case BENTURAN_KEPENTINGAN = 'benturan_kepentingan';
    case KORUPSI = 'korupsi';
    case PELANGGARAN_ATURAN = 'pelanggaran_aturan';
    case LAINNYA = 'lainnya';
}