<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\Validator;

use Hotaruma\HttpRouter\Exception\RouteConfigInvalidArgumentException;

interface RouteConfigValidatorInterface
{
    /**
     * @param array $rules
     * @return void
     *
     * @throws RouteConfigInvalidArgumentException
     */
    public function validateRules(array $rules): void;

    /**
     * @param array $defaults
     * @return void
     *
     * @throws RouteConfigInvalidArgumentException
     */
    public function validateDefaults(array $defaults): void;

    /**
     * @param array $middlewares
     * @return void
     *
     * @throws RouteConfigInvalidArgumentException
     */
    public function validateMiddlewares(array $middlewares): void;

    /**
     * @param string $path
     * @return void
     *
     * @throws RouteConfigInvalidArgumentException
     */
    public function validatePath(string $path): void;

    /**
     * @param string $name
     * @return void
     *
     * @throws RouteConfigInvalidArgumentException
     */
    public function validateName(string $name): void;

    /**
     * @param array $methods
     * @return void
     *
     * @throws RouteConfigInvalidArgumentException
     */
    public function validateMethods(array $methods): void;
}
