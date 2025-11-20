<?php

namespace Vaultic\Exceptions;

class NotFoundException extends VaulticException
{
    public function __construct(string $message = 'Not Found', array $errors = [])
    {
        parent::__construct($message, 404, 'not_found', $errors);
    }
}

