<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interfaces\RouteConfigValidator;

use Hotaruma\HttpRouter\Exception\RouteConfigInvalidArgument;

interface RouteConfigValidatorInterface
{
    /**
     * @param array $rules
     * @return void
     *
     * @throws RouteConfigInvalidArgument
     */
    public function validateRules(array $rules): void;

    /**
     * @param array $defaults
     * @return void
     *
     * @throws RouteConfigInvalidArgument
     */
    public function validateDefaults(array $defaults): void;

    /**
     * @param array $middlewares
     * @return void
     *
     * @throws RouteConfigInvalidArgument
     */
    public function validateMiddlewares(array $middlewares): void;

    /**
     * @param string $path
     * @return void
     *
     * @throws RouteConfigInvalidArgument
     */
    public function validatePath(string $path): void;

    /**
     * @param string $name
     * @return void
     *
     * @throws RouteConfigInvalidArgument
     */
    public function validateName(string $name): void;

    /**
     * @param array $methods
     * @return void
     *
     * @throws RouteConfigInvalidArgument
     */
    public function validateMethods(array $methods): void;
}
