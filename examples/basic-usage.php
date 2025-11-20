<?php

require __DIR__ . '/../vendor/autoload.php';

use Vaultic\VaulticClient;
use Vaultic\Exceptions\VaulticException;

// Initialize the client
$client = new VaulticClient('vt_your_api_key_here');

try {
    // Get a prompt with variables
    $prompt = $client->prompts->get('0sZNC8EC9D', [
        'variables' => [
            'user_name' => 'John Doe',
            'issue' => 'Password reset request'
        ]
    ]);

    echo "System Content:\n";
    echo $prompt['system_content'] . "\n\n";
    
    echo "User Content:\n";
    echo $prompt['user_content'] . "\n\n";
    
    if (!empty($prompt['missing_variables'])) {
        echo "Missing Variables: " . implode(', ', $prompt['missing_variables']) . "\n";
    }
} catch (VaulticException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Status Code: " . $e->getStatusCode() . "\n";
}

