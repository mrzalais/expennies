<?php

namespace App\Exception;

use RuntimeException;
use Throwable;

class ValidationException extends RuntimeException
{
    public function __construct(
        public readonly array $errors,
        $message = 'Validation Error(s)',
        $code = 422,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
