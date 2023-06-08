<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interfaces\RouteMap;

use Hotaruma\HttpRouter\Interfaces\Method;
use Hotaruma\HttpRouter\Interfaces\Route\RouteInterface;

interface RouteMapConfigureInterface
{
    /**
     * @param array<string,string> $rules Regex rules for attributes in path
     * @return RouteMapConfigureInterface
     */
    public function rules(array $rules): RouteMapConfigureInterface;

    /**
     * @param array<string,string> $defaults Default values for attributes in path
     * @return RouteMapConfigureInterface
     */
    public function defaults(array $defaults): RouteMapConfigureInterface;

    /**
     * @param callable|array $defaults Middlewares list
     * @return RouteMapConfigureInterface
     */
    public function middlewares(callable|array $defaults): RouteMapConfigureInterface;

    /**
     * @param string $path Path prefix
     * @return RouteMapConfigureInterface
     */
    public function path(string $path): RouteMapConfigureInterface;

    /**
     * @param string $name Name prefix
     * @return RouteMapConfigureInterface
     */
    public function name(string $name): RouteMapConfigureInterface;

    /**
     * @param Method|array<Method> $methods Http methods
     * @return RouteMapConfigureInterface
     */
    public function methods(Method|array $methods): RouteMapConfigureInterface;

    /**
     * Group routes by config.
     *
     * @param array $rules
     * @param array $defaults
     * @param callable|array $middlewares
     * @param string $path
     * @param string $name
     * @param Method|array $methods
     * @param null|callable $group
     * @return void
     */
    public function group(
        array $rules = [],
        array $defaults = [],
        callable|array $middlewares = [],
        string $path = '',
        string $name = '',
        Method|array $methods = [],
        callable $group = null
    ): void;

    /**
     * Set base route for clone.
     *
     * @param RouteInterface $route
     * @return RouteMapConfigureInterface
     */
    public function baseRoute(RouteInterface $route): RouteMapConfigureInterface;
}
