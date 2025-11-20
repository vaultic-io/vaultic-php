<?php

namespace Vaultic\Helpers;

class VariableReplacer
{
    /**
     * Replace variables in prompt content
     *
     * @param array $prompt The prompt array with system_content, user_content, etc.
     * @param array $variables Associative array of variable names to values
     * @return array The prompt with variables replaced
     */
    public static function replace(array $prompt, array $variables): array
    {
        $result = $prompt;
        
        if (isset($prompt['system_content'])) {
            $result['system_content'] = self::replaceInContent(
                $prompt['system_content'],
                $variables
            );
        }
        
        if (isset($prompt['user_content'])) {
            $result['user_content'] = self::replaceInContent(
                $prompt['user_content'],
                $variables
            );
        }
        
        // Update missing_variables array
        $result['missing_variables'] = self::findMissingVariables(
            $result,
            $variables
        );
        
        return $result;
    }

    /**
     * Replace variables in a content string
     *
     * @param string $content The content string with variable placeholders
     * @param array $variables Associative array of variable names to values
     * @return string The content with variables replaced
     */
    private static function replaceInContent(string $content, array $variables): string
    {
        foreach ($variables as $key => $value) {
            $content = str_replace("{{" . $key . "}}", (string) $value, $content);
        }
        return $content;
    }

    /**
     * Find variables that are still missing after replacement
     *
     * @param array $prompt The prompt array
     * @param array $variables The variables that were provided
     * @return array Array of missing variable names
     */
    private static function findMissingVariables(array $prompt, array $variables): array
    {
        $missing = [];
        $content = ($prompt['system_content'] ?? '') . ' ' . ($prompt['user_content'] ?? '');
        
        // Find all variable placeholders in the content
        preg_match_all('/\{\{(\w+)\}\}/', $content, $matches);
        
        if (!empty($matches[1])) {
            foreach ($matches[1] as $varName) {
                if (!isset($variables[$varName])) {
                    $missing[] = $varName;
                }
            }
        }
        
        return array_unique($missing);
    }
}

