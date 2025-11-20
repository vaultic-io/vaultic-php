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
        
        $prompt = $this->client->get("prompts/{$promptId}/version/{$version}", $queryParams);
        
        // If variables were provided, replace them in the response
        if (isset($options['variables']) && is_array($options['variables']) && !empty($options['variables'])) {
            $prompt = VariableReplacer::replace($prompt, $options['variables']);
        }
        
        return $prompt;
    }

    /**
     * Get all prompts for a specific project
     *
     * @param string $projectId The project ID
     * @param array $options Query parameters (page, per_page, variables, etc.)
     * @return array List of prompts for the project
     */
    public function getProjectPrompts(string $projectId, array $options = []): array
    {
        $queryParams = [];
        
        if (isset($options['page'])) {
            $queryParams['page'] = $options['page'];
        }
        
        if (isset($options['per_page'])) {
            $queryParams['per_page'] = $options['per_page'];
        }
        
        if (isset($options['variables']) && is_array($options['variables'])) {
            foreach ($options['variables'] as $key => $value) {
                $queryParams["variables[{$key}]"] = $value;
            }
        }
        
        $result = $this->client->get("projects/{$projectId}/prompts", $queryParams);
        
        // If variables were provided, replace them in the response
        if (isset($options['variables']) && is_array($options['variables']) && !empty($options['variables']) && isset($result['data'])) {
            foreach ($result['data'] as &$prompt) {
                $prompt = VariableReplacer::replace($prompt, $options['variables']);
            }
        }
        
        return $result;
    }
}

