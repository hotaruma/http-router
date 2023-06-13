<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\RouteConfigValidators;

use Hotaruma\HttpRouter\Exception\RouteConfigInvalidArgument;
use Hotaruma\HttpRouter\Interfaces\{Method, RouteConfigValidator\RouteConfigValidatorInterface};

class RouteConfigValidator implements RouteConfigValidatorInterface
{
    /**
     * @inheritDoc
     */
    public function validateRules(array $rules): void
    {
        foreach ($rules as $name => $rule) {
            if (!is_string($name) || !is_string($rule)) {
                throw new RouteConfigInvalidArgument("Invalid format for route rule. Rules must be specified as strings.");
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function validateDefaults(array $defaults): void
    {
        foreach ($defaults as $name => $value) {
            if (!is_string($name) || !is_string($value)) {
                throw new RouteConfigInvalidArgument("Invalid format for route defaults. Defaults must be specified as strings.");
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function validateMiddlewares(array $middlewares): void
    {
    }

    /**
     * @inheritDoc
     */
    public function validatePath(string $path): void
    {
        if (empty($path)) {
            throw new RouteConfigInvalidArgument("Invalid argument: path cannot be empty");
        }
    }

    /**
     * @inheritDoc
     */
    public function validateName(string $name): void
    {
    }

    /**
     * @inheritDoc
     */
    public function validateMethods(array $methods): void
    {
        if (empty($methods)) {
            throw new RouteConfigInvalidArgument("Empty array provided for route methods. At least one method must be specified.");
        }

        foreach ($methods as $method) {
            if (!$method instanceof Method) {
                throw new RouteConfigInvalidArgument("Invalid argument. Expected instance of Method.");
            }
        }
    }
}
