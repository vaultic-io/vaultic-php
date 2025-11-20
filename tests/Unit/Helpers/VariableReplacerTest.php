<?php

namespace Vaultic\Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;
use Vaultic\Helpers\VariableReplacer;

class VariableReplacerTest extends TestCase
{
    public function testReplaceVariables(): void
    {
        $prompt = [
            'system_content' => 'Hello {{name}}',
            'user_content' => 'Email: {{email}}',
            'variables' => ['name', 'email'],
        ];

        $result = VariableReplacer::replace($prompt, ['name' => 'John']);

        $this->assertEquals('Hello John', $result['system_content']);
        $this->assertStringContainsString('{{email}}', $result['user_content']);
        $this->assertContains('email', $result['missing_variables']);
    }

    public function testReplaceAllVariables(): void
    {
        $prompt = [
            'system_content' => 'Hello {{name}}, welcome to {{platform}}',
            'user_content' => 'Your email is {{email}}',
        ];

        $result = VariableReplacer::replace($prompt, [
            'name' => 'John',
            'platform' => 'Vaultic',
            'email' => 'john@example.com',
        ]);

        $this->assertEquals('Hello John, welcome to Vaultic', $result['system_content']);
        $this->assertEquals('Your email is john@example.com', $result['user_content']);
        $this->assertEmpty($result['missing_variables']);
    }

    public function testReplaceWithEmptyVariables(): void
    {
        $prompt = [
            'system_content' => 'Hello {{name}}',
        ];

        $result = VariableReplacer::replace($prompt, []);

        $this->assertEquals('Hello {{name}}', $result['system_content']);
        $this->assertContains('name', $result['missing_variables']);
    }
}

