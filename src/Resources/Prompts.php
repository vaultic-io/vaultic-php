<?php

namespace Vaultic\Resources;

use Vaultic\Http\Client;
use Vaultic\Helpers\VariableReplacer;

class Prompts
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get a prompt by ID
     *
     * @param string $promptId The prompt ID
     * @param array $options Options including 'variables' array
     * @return array The prompt data
     */
    public function get(string $promptId, array $options = []): array
    {
        $queryParams = [];
        
        if (isset($options['variables']) && is_array($options['variables'])) {
            foreach ($options['variables'] as $key => $value) {
                $queryParams["variables[{$key}]"] = $value;
            }
        }
        
        $prompt = $this->client->get("prompts/{$promptId}", $queryParams);
        
        // If variables were provided, replace them in the response
        if (isset($options['variables']) && is_array($options['variables']) && !empty($options['variables'])) {
            $prompt = VariableReplacer::replace($prompt, $options['variables']);
        }
        
        return $prompt;
    }

    /**
     * Get a specific version of a prompt
     *
     * @param string $promptId The prompt ID
     * @param string $version The version identifier
     * @param array $options Options including 'variables' array
     * @return array The prompt data
     */
    public function getVersion(string $promptId, string $version, array $options = []): array
    {
        $queryParams = [];
        
        if (isset($options['variables']) && is_array($options['variables'])) {
            foreach ($options['variables'] as $key => $value) {
                $queryParams["variables[{$key}]"] = $value;
            }
        }
        
        $prompt = $this->client->get("/prompts/{$promptId}/versions/{$version}", $queryParams);
        
        // If variables were provided, replace them in the response
        if (isset($options['variables']) && is_array($options['variables']) && !empty($options['variables'])) {
            $prompt = VariableReplacer::replace($prompt, $options['variables']);
        }
        
        return $prompt;
    }

    /**
     * List all prompts
     *
     * @param array $options Query parameters (page, per_page, etc.)
     * @return array List of prompts
     */
    public function list(array $options = []): array
    {
        $queryParams = [];
        
        if (isset($options['page'])) {
            $queryParams['page'] = $options['page'];
        }
        
        if (isset($options['per_page'])) {
            $queryParams['per_page'] = $options['per_page'];
        }
        
        return $this->client->get("/prompts", $queryParams);
    }

    /**
     * Create a new prompt
     *
     * @param array $data Prompt data
     * @return array The created prompt
     */
    public function create(array $data): array
    {
        return $this->client->post("/prompts", $data);
    }

    /**
     * Update a prompt
     *
     * @param string $promptId The prompt ID
     * @param array $data Updated prompt data
     * @return array The updated prompt
     */
    public function update(string $promptId, array $data): array
    {
        return $this->client->put("/prompts/{$promptId}", $data);
    }

    /**
     * Delete a prompt
     *
     * @param string $promptId The prompt ID
     * @return array Empty array on success
     */
    public function delete(string $promptId): array
    {
        return $this->client->delete("/prompts/{$promptId}");
    }
}

