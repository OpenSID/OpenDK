<?php

namespace App\Exceptions;

use Exception;

class TenantIdRangeExceededException extends Exception
{
    /**
     * TenantIdRangeExceededException constructor.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        string $message = 'Tenant ID range has been exceeded.',
        int $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}