<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Utils;

trait RouteTrait
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
        return preg_replace('/(\/{2,})/', '/', $path);
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
        return preg_replace('/(\.{2,})/', '.', $name);
    }
}
