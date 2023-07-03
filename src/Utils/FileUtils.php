<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Utils;

trait FileUtils
{
    /**
     * @param string $filename
     * @return class-string|null
     */
    protected function getClassNameFromFile(string $filename): ?string
    {
        if (empty($filename)) {
            return null;
        }
        $sourceCode = file_get_contents($filename);

        $tokens = token_get_all($sourceCode);
        $tokens = array_filter($tokens, fn($token) => is_array($token));

        $namespaceFound = $classFound = false;

        foreach ($tokens as $token) {
            [$tokenType, $tokenValue] = $token;
            match ($tokenType) {
                T_NAMESPACE => $namespaceFound = true,
                T_NAME_QUALIFIED => $namespaceFound and $namespace ??= $tokenValue . '\\',
                T_CLASS => $classFound = true,
                T_STRING => $classFound and $class ??= $tokenValue,
                default => false
            };
            if (!empty($class)) {
                return ($namespace ?? '') . $class;
            }
        }
        return null;
    }
}
