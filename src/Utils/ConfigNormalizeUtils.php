<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Utils;

trait ConfigNormalizeUtils
{
    /**
     * Remove {/} duplicates, check start and end have {/}.
     *
     * @param string $path
     * @return string
     */
    protected function normalizePath(string $path): string
    {
        $path = "/" . $path . "/";
        return (string)preg_replace('/(\/{2,})/', '/', $path);
    }

    /**
     * Remove {.} duplicates, trim {.}.
     *
     * @param string $name
     * @return string
     */
    protected function normalizeName(string $name): string
    {
        $name = trim($name, '.');
        return (string)preg_replace('/(\.{2,})/', '.', $name);
    }

    /**
     * Prepares a given path string for regular expression matching.
     * Replacing escaped opening and closing curly braces ("{" and "}")
     * with unescaped counterparts ("{" and "}").
     *
     * @param string $path
     * @return string
     */
    protected function preparePathForRegExp(string $path): string
    {
        $routePath = preg_quote($path, '/');
        return str_replace(['\{', '\}'], ['{', '}'], $routePath);
    }
}
