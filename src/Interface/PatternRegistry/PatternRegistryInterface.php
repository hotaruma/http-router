<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\PatternRegistry;

use Closure;
use Hotaruma\HttpRouter\Exception\PatternRegistryPatternNotFoundException;

interface PatternRegistryInterface
{
    /**
     * @param string $name
     * @return string|Closure
     *
     * @throws PatternRegistryPatternNotFoundException
     *
     * @phpstan-return TA_PatternRegistryTypes
     */
    public function getPattern(string $name): string|Closure;

    /**
     * @param string $name
     * @param string|Closure $pattern
     * @return void
     *
     * @phpstan-param TA_PatternRegistryTypes $pattern
     */
    public function addPattern(string $name, string|Closure $pattern): void;
}