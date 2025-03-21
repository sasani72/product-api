<?php

namespace App\Exceptions;

class ValidationException extends \Exception
{
    public function __construct(string $message = "Validation failed", int $code = 422)
    {
        parent::__construct($message, $code);
    }
} 