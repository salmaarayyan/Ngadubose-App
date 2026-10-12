<?php

namespace App\Enums;

enum SystemConfig: string
{
    case DRIVE_FOLDER = 'bukti_pelaporan';
    case DEFAULT_PELAPOR = 'Anonim';
    case TICKET_PREFIX = 'WBS';
}