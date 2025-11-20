<?php

namespace Vaultic;

use Vaultic\Http\Client as HttpClient;
use Vaultic\Resources\Prompts;
use Vaultic\Resources\Projects;

class VaulticClient
{
    private HttpClient $httpClient;
    public Prompts $prompts;
    public Projects $projects;

    /**
     * Create a new VaulticClient instance
     *
     * @param string $apiKey Your Vaultic API key
     * @param array $config Optional configuration:
     *   - baseUrl: Custom API base URL (default: https://app.vaultic.io/api/v1)
     *   - timeout: Request timeout in seconds (default: 30)
     *   - enableLogging: Enable request/response logging (default: false)
     *   - userAgent: Custom User-Agent string
     */
    public function __construct(string $apiKey, array $config = [])
    {
        $config['apiKey'] = $apiKey;
        $this->httpClient = new HttpClient($config);
        
        $this->prompts = new Prompts($this->httpClient);
        $this->projects = new Projects($this->httpClient);
    }

    /**
     * Get the underlying HTTP client
     *
     * @return HttpClient
     */
    public function getHttpClient(): HttpClient
    {
        return $this->httpClient;
    }
}

