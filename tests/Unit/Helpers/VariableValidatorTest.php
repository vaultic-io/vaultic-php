<?php

namespace Vaultic\Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;
use Vaultic\Helpers\VariableValidator;

class VariableValidatorTest extends TestCase
{
    public function testValidateWithAllVariables(): void
    {
        $prompt = [
            'system_content' => 'Hello {{name}}, your email is {{email}}',
        ];

        $result = VariableValidator::validate($prompt, [
            'name' => 'John',
            'email' => 'john@example.com',
        ]);

        $this->assertTrue($result['valid']);
        $this->assertEmpty($result['missing']);
    }

    public function testValidateWithMissingVariables(): void
    {
        $prompt = [
            'system_content' => 'Hello {{name}}, your email is {{email}}',
        ];

        $result = VariableValidator::validate($prompt, [
            'name' => 'John',
        ]);

        $this->assertFalse($result['valid']);
        $this->assertContains('email', $result['missing']);
    }

    public function testHasVariable(): void
    {
        $prompt = [
            'system_content' => 'Hello {{name}}',
        ];

        $this->assertTrue(VariableValidator::hasVariable($prompt, 'name'));
        $this->assertFalse(VariableValidator::hasVariable($prompt, 'email'));
    }

    public function testGetMissing(): void
    {
        $prompt = [
            'system_content' => 'Hello {{name}}, your email is {{email}}',
        ];

        $missing = VariableValidator::getMissing($prompt, ['name' => 'John']);

        $this->assertContains('email', $missing);
        $this->assertNotContains('name', $missing);
    }
}

