<?php

namespace Vaultic\Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;
use Vaultic\Helpers\VariableExtractor;

class VariableExtractorTest extends TestCase
{
    public function testExtractVariables(): void
    {
        $prompt = [
            'system_content' => 'Hello {{name}}, welcome to {{platform}}',
            'user_content' => 'Your email is {{email}}',
        ];

        $variables = VariableExtractor::extract($prompt);

        $this->assertCount(3, $variables);
        $this->assertContains('name', $variables);
        $this->assertContains('platform', $variables);
        $this->assertContains('email', $variables);
    }

    public function testExtractFromContent(): void
    {
        $content = 'Hello {{name}}, your code is {{code}}';
        $variables = VariableExtractor::extractFromContent($content);

        $this->assertCount(2, $variables);
        $this->assertContains('name', $variables);
        $this->assertContains('code', $variables);
    }

    public function testExtractNoVariables(): void
    {
        $prompt = [
            'system_content' => 'Hello world',
        ];

        $variables = VariableExtractor::extract($prompt);

        $this->assertEmpty($variables);
    }

    public function testExtractUniqueVariables(): void
    {
        $prompt = [
            'system_content' => 'Hello {{name}}, {{name}} is your name',
        ];

        $variables = VariableExtractor::extract($prompt);

        $this->assertCount(1, $variables);
        $this->assertContains('name', $variables);
    }
}

