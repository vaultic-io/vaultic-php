<?php

namespace Vaultic\Exceptions;

class ForbiddenException extends VaulticException
{
    public function __construct(string $message = 'Forbidden', array $errors = [])
    {
        parent::__construct($message, 403, 'forbidden', $errors);
    }
}

