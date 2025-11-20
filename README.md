# Vaultic PHP SDK

Official PHP SDK for the Vaultic API. This SDK provides a simple and intuitive interface for interacting with the Vaultic API to manage prompts, projects, and more.

## Installation

Install the package via Composer:

```bash
composer require vaultic/sdk
```

## Requirements

- PHP 8.0 or higher
- Composer
- Guzzle HTTP Client (automatically installed via Composer)

## Quick Start

```php
<?php

require 'vendor/autoload.php';

use Vaultic\VaulticClient;

// Initialize the client with your API key
$client = new VaulticClient('vt_your_api_key_here');

// Get a prompt with variables
$prompt = $client->prompts->get('0sZNC8EC9D', [
    'variables' => [
        'user_name' => 'John',
        'issue' => 'Password reset'
    ]
]);

echo $prompt['system_content'];
echo $prompt['user_content'];
```

## Configuration

You can customize the client with additional configuration options:

```php
$client = new VaulticClient('vt_your_api_key_here', [
    'baseUrl' => 'https://app.vaultic.io/api/v1',   // Custom API URL
    'timeout' => 30,                                // Request timeout in seconds
    'enableLogging' => false,                       // Enable request/response logging
    'userAgent' => 'MyApp/1.0.0',                   // Custom User-Agent
]);
```

## Usage Examples

### Prompts

#### Get a Prompt

```php
// Get a prompt by ID
$prompt = $client->prompts->get('prompt_id_here');

// Get a prompt with variables replaced
$prompt = $client->prompts->get('prompt_id_here', [
    'variables' => [
        'name' => 'John Doe',
        'email' => 'john@example.com'
    ]
]);
```

#### Get a Specific Version

```php
$prompt = $client->prompts->getVersion('prompt_id_here', 'v1.0.0', [
    'variables' => ['name' => 'John']
]);
```

#### List All Prompts

```php
$prompts = $client->prompts->list([
    'page' => 1,
    'per_page' => 20
]);
```

#### Create a Prompt

```php
$prompt = $client->prompts->create([
    'name' => 'Welcome Email',
    'system_content' => 'You are a helpful assistant.',
    'user_content' => 'Write an email to {{name}}',
    'variables' => ['name']
]);
```

#### Update a Prompt

```php
$prompt = $client->prompts->update('prompt_id_here', [
    'name' => 'Updated Welcome Email',
    'system_content' => 'You are a friendly assistant.'
]);
```

#### Delete a Prompt

```php
$client->prompts->delete('prompt_id_here');
```

### Projects

#### Get a Project

```php
$project = $client->projects->get('project_id_here');
```

#### List All Projects

```php
$projects = $client->projects->list([
    'page' => 1,
    'per_page' => 20
]);
```

#### Create a Project

```php
$project = $client->projects->create([
    'name' => 'My Project',
    'description' => 'Project description'
]);
```

#### Update a Project

```php
$project = $client->projects->update('project_id_here', [
    'name' => 'Updated Project Name'
]);
```

#### Delete a Project

```php
$client->projects->delete('project_id_here');
```

## Helper Classes

The SDK includes helper classes for working with variables in prompts:

### VariableReplacer

Replace variables in prompt content:

```php
use Vaultic\Helpers\VariableReplacer;

$prompt = [
    'system_content' => 'Hello {{name}}',
    'user_content' => 'Email: {{email}}'
];

$result = VariableReplacer::replace($prompt, [
    'name' => 'John',
    'email' => 'john@example.com'
]);
```

### VariableExtractor

Extract variable names from prompts:

```php
use Vaultic\Helpers\VariableExtractor;

$variables = VariableExtractor::extract($prompt);
// Returns: ['name', 'email']
```

### VariableValidator

Validate that all required variables are provided:

```php
use Vaultic\Helpers\VariableValidator;

$result = VariableValidator::validate($prompt, [
    'name' => 'John',
    'email' => 'john@example.com'
]);

if ($result['valid']) {
    echo "All variables provided";
} else {
    echo "Missing: " . implode(', ', $result['missing']);
}
```

## Error Handling

The SDK throws specific exceptions for different HTTP status codes:

```php
use Vaultic\Exceptions\UnauthorizedException;
use Vaultic\Exceptions\NotFoundException;
use Vaultic\Exceptions\RateLimitException;
use Vaultic\Exceptions\VaulticException;

try {
    $prompt = $client->prompts->get('invalid_id');
} catch (UnauthorizedException $e) {
    echo "Invalid API key";
} catch (NotFoundException $e) {
    echo "Prompt not found";
} catch (RateLimitException $e) {
    echo "Rate limit exceeded. Retry after: " . $e->getRetryAfter() . " seconds";
} catch (VaulticException $e) {
    echo "Error: " . $e->getMessage();
    echo "Status Code: " . $e->getStatusCode();
}
```

### Available Exceptions

- `VaulticException` - Base exception for all Vaultic errors
- `UnauthorizedException` - 401 Unauthorized
- `ForbiddenException` - 403 Forbidden
- `NotFoundException` - 404 Not Found
- `UnprocessableEntityException` - 422 Validation Error
- `RateLimitException` - 429 Rate Limit Exceeded
- `ServerException` - 500+ Server Errors

## Testing

Run the test suite:

```bash
composer test
```

Or with PHPUnit directly:

```bash
./vendor/bin/phpunit
```

## Development

### Running Static Analysis

```bash
composer analyse
```

### Code Style

This package follows PSR-12 coding standards.

## License

This SDK is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Support

For API documentation and support, visit [https://vaultic.io/documentation](https://vaultic.io/documentation)

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

