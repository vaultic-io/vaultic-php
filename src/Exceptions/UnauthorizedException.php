<?php

namespace Vaultic\Exceptions;

class UnauthorizedException extends VaulticException
{
    public function __construct(string $message = 'Unauthorized', array $errors = [])
    {
        parent::__construct($message, 401, 'unauthorized', $errors);
    }
}

