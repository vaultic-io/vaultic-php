<?php

namespace Vaultic\Exceptions;

class VaulticException extends \Exception
{
    protected int $statusCode;
    protected string $errorType;
    protected array $errors = [];

    public function __construct(string $message, int $statusCode, string $errorType = '', array $errors = [])
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
        $this->errorType = $errorType;
        $this->errors = $errors;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getErrorType(): string
    {
        return $this->errorType;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}

