<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class SurveiEnum extends Enum
{
    public const OPTION1 = 'option1';
    public const OPTION2 = 'option2';
    public const OPTION3 = 'option3';
    public const OPTION4 = 'option4';

    /**
     * Get the human-readable label for the response.
     *
     * @param string $value
     * @return string
     */
    public static function getDescription($value): string
    {
        return match ($value) {
            self::OPTION1 => 'Sangat Baik',
            self::OPTION2 => 'Baik',
            self::OPTION3 => 'Cukup',
            self::OPTION4 => 'Kurang',
            default => parent::getDescription($value),
        };
    }
}