<?php

namespace Vaultic\Resources;

use Vaultic\Http\Client;

class Projects
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get a project by ID
     *
     * @param string $projectId The project ID
     * @return array The project data
     */
    public function get(string $projectId): array
    {
        return $this->client->get("projects/{$projectId}");
    }

    /**
     * List all projects
     *
     * @param array $options Query parameters (page, per_page, etc.)
     * @return array List of projects
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
        
        return $this->client->get("projects", $queryParams);
    }
}

