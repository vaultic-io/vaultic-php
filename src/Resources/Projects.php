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
        
        return $this->client->get("/projects", $queryParams);
    }

    /**
     * Create a new project
     *
     * @param array $data Project data
     * @return array The created project
     */
    public function create(array $data): array
    {
        return $this->client->post("/projects", $data);
    }

    /**
     * Update a project
     *
     * @param string $projectId The project ID
     * @param array $data Updated project data
     * @return array The updated project
     */
    public function update(string $projectId, array $data): array
    {
        return $this->client->put("/projects/{$projectId}", $data);
    }

    /**
     * Delete a project
     *
     * @param string $projectId The project ID
     * @return array Empty array on success
     */
    public function delete(string $projectId): array
    {
        return $this->client->delete("/projects/{$projectId}");
    }
}

