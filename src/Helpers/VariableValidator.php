<?php

namespace Vaultic\Helpers;

class VariableValidator
{
    /**
     * Validate that all required variables are provided
     *
     * @param array $prompt The prompt array
     * @param array $variables The variables to validate
     * @return array Array with 'valid' boolean and 'missing' array of missing variables
     */
    public static function validate(array $prompt, array $variables): array
    {
        $required = VariableExtractor::extract($prompt);
        $missing = array_diff($required, array_keys($variables));
        
        return [
            'valid' => empty($missing),
            'missing' => array_values($missing),
        ];
    }

    /**
     * Check if a specific variable exists in the prompt
     *
     * @param array $prompt The prompt array
     * @param string $variableName The variable name to check
     * @return bool True if the variable is found in the prompt
     */
    public static function hasVariable(array $prompt, string $variableName): bool
    {
        $variables = VariableExtractor::extract($prompt);
        return in_array($variableName, $variables, true);
    }

    /**
     * Get all variables that are in the prompt but not provided
     *
     * @param array $prompt The prompt array
     * @param array $variables The provided variables
     * @return array Array of missing variable names
     */
    public static function getMissing(array $prompt, array $variables): array
    {
        $required = VariableExtractor::extract($prompt);
        return array_values(array_diff($required, array_keys($variables)));
    }
}

