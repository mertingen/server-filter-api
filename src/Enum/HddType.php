<?php

namespace App\Enum;

enum HddType: string
{
    case SAS = 'SAS';
    case SATA = 'SATA';
    case SSD = 'SSD';
}
