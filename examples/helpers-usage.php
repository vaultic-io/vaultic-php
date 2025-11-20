<?php

require __DIR__ . '/../vendor/autoload.php';

use Vaultic\Helpers\VariableReplacer;
use Vaultic\Helpers\VariableExtractor;
use Vaultic\Helpers\VariableValidator;

// Example prompt
$prompt = [
    'system_content' => 'You are a helpful assistant. User name: {{name}}',
    'user_content' => 'Write an email to {{email}} about {{topic}}',
];

// Extract variables
echo "Extracting variables...\n";
$variables = VariableExtractor::extract($prompt);
echo "Found variables: " . implode(', ', $variables) . "\n\n";

// Validate variables
echo "Validating variables...\n";
$validation = VariableValidator::validate($prompt, [
    'name' => 'John',
    'email' => 'john@example.com',
    // 'topic' is missing
]);

if ($validation['valid']) {
    echo "All variables provided!\n\n";
} else {
    echo "Missing variables: " . implode(', ', $validation['missing']) . "\n\n";
}

// Replace variables
echo "Replacing variables...\n";
$result = VariableReplacer::replace($prompt, [
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'topic' => 'Project update',
]);

echo "System Content: " . $result['system_content'] . "\n";
echo "User Content: " . $result['user_content'] . "\n";
echo "Missing Variables: " . (empty($result['missing_variables']) ? 'None' : implode(', ', $result['missing_variables'])) . "\n";

