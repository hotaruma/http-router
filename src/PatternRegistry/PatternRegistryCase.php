<?php

namespace Hotaruma\HttpRouter\PatternRegistry;

use Hotaruma\HttpRouter\Interface\PatternRegistry\PatternRegistryInterface;

trait PatternRegistryCase
{
    /**
     * @var PatternRegistryInterface
     */
    protected PatternRegistryInterface $patternRegistry;

    /**
     * Set pattern registry.
     *
     * @param PatternRegistryInterface $patternRegistry
     * @return void
     */
    public function patternRegistry(PatternRegistryInterface $patternRegistry): void
    {
        $this->patternRegistry = $patternRegistry;
    }

    /**
     * @return PatternRegistryInterface
     */
    protected function getPatternRegistry(): PatternRegistryInterface
    {
        return $this->patternRegistry ??= new PatternRegistry();
    }
}
