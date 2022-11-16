<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Status untuk melihat aktif dan tidak aktif
 */
final class Status extends Enum
{
    const TidakAktif = 0;
    const Aktif      = 1;
}
