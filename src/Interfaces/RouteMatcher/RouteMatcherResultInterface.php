<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interfaces\RouteMatcher;

interface RouteMatcherResultInterface
{
    /**
     * Set routes matching result.
     *
     * @param array<string,string> $attributes
     * @param mixed $action
     * @param array $middlewares
     * @return void
     */
    public function result(array $attributes, mixed $action, array $middlewares): void;

    /**
     * Return attributes.
     *
     * @return array<string,string>
     */
    public function getAttributes(): array;

    /**
     * Return route action.
     *
     * @return mixed
     */
    public function getAction(): mixed;

    /**
     * Return route middlewares.
     *
     * @return array
     */
    public function getMiddlewares(): array;
}
