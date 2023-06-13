<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\RouteConfigValidators;

use Hotaruma\HttpRouter\Exception\RouteConfigInvalidArgument;
use Hotaruma\HttpRouter\Interfaces\Method;

class RouteGroupConfigValidator extends RouteConfigValidator
{
    /**
     * @inheritDoc
     */
    public function validatePath(string $path): void
    {
    }

    /**
     * @inheritDoc
     */
    public function validateMethods(array $methods): void
    {
        foreach ($methods as $method) {
            if (!$method instanceof Method) {
                throw new RouteConfigInvalidArgument("Invalid argument. Expected instance of Method.");
            }
        }
    }
}
