<?php

namespace Vaultic\Exceptions;

class UnprocessableEntityException extends VaulticException
{
    public function __construct(string $message = 'Unprocessable Entity', array $errors = [])
    {
        parent::__construct($message, 422, 'unprocessable_entity', $errors);
    }
}

