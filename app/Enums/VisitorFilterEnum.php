<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class VisitorFilterEnum extends Enum
{
    public const TODAY = 'today';
    public const YESTERDAY = 'yesterday';
    public const THIS_WEEK = 'this-week';
    public const THIS_MONTH = 'this-month';
    public const THIS_YEAR = 'this-year';
    public const ALL = 'all';
}