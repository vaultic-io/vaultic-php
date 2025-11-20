<?php

namespace Vaultic\Http;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException as GuzzleServerException;
use Vaultic\Exceptions\VaulticException;
use Vaultic\Exceptions\UnauthorizedException;
use Vaultic\Exceptions\ForbiddenException;
use Vaultic\Exceptions\NotFoundException;
use Vaultic\Exceptions\UnprocessableEntityException;
use Vaultic\Exceptions\RateLimitException;
use Vaultic\Exceptions\ServerException;

class Client
{
    private GuzzleClient $client;
    private string $apiKey;
    private bool $enableLogging;

    public function __construct(array $config)
    {
        $this->apiKey = $config['apiKey'] ?? '';
        $baseUrl = $config['baseUrl'] ?? 'https://app.vaultic.io/api/v1/';
        $timeout = $config['timeout'] ?? 30;
        $this->enableLogging = $config['enableLogging'] ?? false;

        $this->client = new GuzzleClient([
            'base_uri' => $baseUrl,
            'timeout' => $timeout,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'User-Agent' => $config['userAgent'] ?? 'VaulticSDK/1.0.0 PHP',
            ],
        ]);
    }

    public function get(string $path, array $queryParams = []): array
    {
        try {
            $options = [];
            if (!empty($queryParams)) {
                $options['query'] = $queryParams;
            }

            $response = $this->client->get($path, $options);
            return json_decode($response->getBody()->getContents(), true) ?? [];
        } catch (GuzzleException $e) {
            dd($e->getMessage());
            $this->handleException($e);
        }
    }

    public function post(string $path, array $data = []): array
    {
        try {
            $response = $this->client->post($path, [
                'json' => $data,
            ]);
            return json_decode($response->getBody()->getContents(), true) ?? [];
        } catch (GuzzleException $e) {
            $this->handleException($e);
        }
    }

    public function put(string $path, array $data = []): array
    {
        try {
            $response = $this->client->put($path, [
                'json' => $data,
            ]);
            return json_decode($response->getBody()->getContents(), true) ?? [];
        } catch (GuzzleException $e) {
            $this->handleException($e);
        }
    }

    public function patch(string $path, array $data = []): array
    {
        try {
            $response = $this->client->patch($path, [
                'json' => $data,
            ]);
            return json_decode($response->getBody()->getContents(), true) ?? [];
        } catch (GuzzleException $e) {
            $this->handleException($e);
        }
    }

    public function delete(string $path): array
    {
        try {
            $response = $this->client->delete($path);
            $body = $response->getBody()->getContents();
            return empty($body) ? [] : json_decode($body, true) ?? [];
        } catch (GuzzleException $e) {
            $this->handleException($e);
        }
    }

    private function handleException(GuzzleException $e): void
    {
        $statusCode = 0;
        $message = 'An error occurred';
        $errorType = '';
        $errors = [];
        $retryAfter = 60;

        if ($e instanceof RequestException && $e->hasResponse()) {
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);

            if ($data) {
                $message = $data['message'] ?? $data['error'] ?? $message;
                $errorType = $data['error_type'] ?? $data['type'] ?? '';
                $errors = $data['errors'] ?? [];
            } else {
                $message = $response->getReasonPhrase() ?? $message;
            }

            // Extract Retry-After header for rate limits
            if ($statusCode === 429 && $response->hasHeader('Retry-After')) {
                $retryAfter = (int) $response->getHeaderLine('Retry-After');
            }
        } else {
            $message = $e->getMessage();
        }

        $exception = match ($statusCode) {
            401 => new UnauthorizedException($message, $errors),
            403 => new ForbiddenException($message, $errors),
            404 => new NotFoundException($message, $errors),
            422 => new UnprocessableEntityException($message, $errors),
            429 => new RateLimitException($message, $errors, $retryAfter),
            500, 502, 503, 504 => new ServerException($message, $errors),
            default => new VaulticException($message, $statusCode ?: 500, $errorType, $errors),
        };

        throw $exception;
    }
}

