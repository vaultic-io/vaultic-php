<?php

namespace Vaultic\Helpers;

class VariableExtractor
{
    /**
     * Extract all variable names from prompt content
     *
     * @param array $prompt The prompt array
     * @return array Array of variable names found in the prompt
     */
    public static function extract(array $prompt): array
    {
        $variables = [];
        $content = ($prompt['system_content'] ?? '') . ' ' . ($prompt['user_content'] ?? '');
        
        // Find all variable placeholders in the content
        preg_match_all('/\{\{(\w+)\}\}/', $content, $matches);
        
        if (!empty($matches[1])) {
            $variables = array_unique($matches[1]);
        }
        
        return $variables;
    }

    /**
     * Extract variables from a specific content string
     *
     * @param string $content The content string
     * @return array Array of variable names found
     */
    public static function extractFromContent(string $content): array
    {
        $variables = [];
        preg_match_all('/\{\{(\w+)\}\}/', $content, $matches);
        
        if (!empty($matches[1])) {
            $variables = array_unique($matches[1]);
        }
        
        return $variables;
    }
}

