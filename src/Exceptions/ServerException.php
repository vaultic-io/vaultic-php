<?php

namespace Vaultic\Exceptions;

class ServerException extends VaulticException
{
    public function __construct(string $message = 'Internal Server Error', array $errors = [])
    {
        parent::__construct($message, 500, 'server_error', $errors);
    }
}

