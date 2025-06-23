<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class SurveiEnum extends Enum
{
    public const SANGAT_BAIK = 'sangat_baik';
    public const BAIK = 'baik';
    public const CUKUP = 'cukup';
    public const KURANG = 'kurang';

    /**
     * Get the human-readable label for the response.
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        return match ($value) {
            self::SANGAT_BAIK => 'Sangat Baik',
            self::BAIK => 'Baik',
            self::CUKUP => 'Cukup',
            self::KURANG => 'Kurang',
            default => parent::getDescription($value),
        };
    }
}