<?php

namespace Hotaruma\HttpRouter\Exception;

use Hotaruma\HttpRouter\Interface\Exception\RouterExceptionInterface;
use RuntimeException;

class PatternRegistryPatternNotFoundException extends RuntimeException implements RouterExceptionInterface
{
    public function __construct(string $name)
    {
        parent::__construct("Pattern with name '{$name}' not found in the registry.");
    }
}
