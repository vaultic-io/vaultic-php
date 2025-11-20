<?php

namespace Vaultic\Exceptions;

class RateLimitException extends VaulticException
{
    protected int $retryAfter;

    public function __construct(string $message = 'Rate Limit Exceeded', array $errors = [], int $retryAfter = 60)
    {
        parent::__construct($message, 429, 'rate_limit_exceeded', $errors);
        $this->retryAfter = $retryAfter;
    }

    public function getRetryAfter(): int
    {
        return $this->retryAfter;
    }
}

