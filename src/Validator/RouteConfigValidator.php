<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Validator;

use Hotaruma\HttpRouter\Exception\RouteConfigInvalidArgumentException;
use Hotaruma\HttpRouter\Interface\{Enum\RequestMethodInterface, Validator\RouteConfigValidatorInterface};

class RouteConfigValidator implements RouteConfigValidatorInterface
{
    /**
     * @inheritDoc
     */
    public function validateRules(array $rules): void
    {
        foreach ($rules as $name => $rule) {
            if (!is_string($name) || !is_string($rule)) {
                throw new RouteConfigInvalidArgumentException(
                    'Invalid format for route rule. Rules must be specified as strings.'
                );
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
                throw new RouteConfigInvalidArgumentException(
                    'Invalid format for route defaults. Defaults must be specified as strings.'
                );
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
            throw new RouteConfigInvalidArgumentException('Invalid argument: path cannot be empty');
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
            throw new RouteConfigInvalidArgumentException(
                'Empty array provided for route methods. At least one method must be specified.'
            );
        }

        foreach ($methods as $method) {
            if (!$method instanceof RequestMethodInterface) {
                throw new RouteConfigInvalidArgumentException('Invalid argument. Expected instance of Method.');
            }
        }
    }
}
