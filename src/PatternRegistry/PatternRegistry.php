<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\PatternRegistry;

use Closure;
use Hotaruma\HttpRouter\Exception\PatternRegistryPatternNotFoundException;
use Hotaruma\HttpRouter\Interface\PatternRegistry\PatternRegistryInterface;

class PatternRegistry implements PatternRegistryInterface
{
    /**
     * @var array<string, string|TA_PatternRegistryTypes>
     */
    protected array $patterns = [
        'int' => '\d+',
        'alpha' => '[A-Za-z]+',
        'alnum' => '[A-Za-z0-9]+',
        'slug' => '[A-Za-z0-9-_]+',
        'uuid' => '[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{12}',
    ];

    /**
     * @inheritDoc
     */
    public function getPattern(string $name): string|Closure
    {
        return $this->patterns[$name] ?? throw new PatternRegistryPatternNotFoundException($name);
    }

    /**
     * @inheritDoc
     */
    public function addPattern(string $name, string|Closure $pattern): void
    {
        $this->patterns[$name] = $pattern;
    }

    /**
     * @inheritDoc
     */
    public function hasPattern(string $name): bool
    {
        return isset($this->patterns[$name]);
    }
}
