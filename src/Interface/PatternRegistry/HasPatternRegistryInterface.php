<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\PatternRegistry;

interface HasPatternRegistryInterface
{
    /**
     * Set pattern registry.
     *
     * @param PatternRegistryInterface $patternRegistry
     * @return void
     */
    public function patternRegistry(PatternRegistryInterface $patternRegistry): void;
}
