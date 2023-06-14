<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Validator;

use Hotaruma\HttpRouter\Exception\RouteConfigInvalidArgumentException;
use Hotaruma\HttpRouter\Interface\Enum\RequestMethodInterface;

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
            if (!$method instanceof RequestMethodInterface) {
                throw new RouteConfigInvalidArgumentException('Invalid argument. Expected instance of Method.');
            }
        }
    }
}
