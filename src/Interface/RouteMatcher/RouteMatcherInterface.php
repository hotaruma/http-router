<?php

namespace Hotaruma\HttpRouter\Interface\RouteMatcher;

use Hotaruma\HttpRouter\Interface\Enum\RequestMethodInterface;
use Hotaruma\HttpRouter\Interface\Route\RouteInterface;

interface RouteMatcherInterface
{
    /**
     * Checks if the given route matches the specified HTTP method.
     *
     * @param RouteInterface $route
     * @param RequestMethodInterface $method
     * @return bool
     */
    public function matchRouteByHttpMethod(RouteInterface $route, RequestMethodInterface $method): bool;

    /**
     * Matches the given route using regular expressions and retrieves the route attributes.
     *
     * @param RouteInterface $route
     * @param string $path
     * @return array<string, string>|null Route attributes
     */
    public function matchRouteByRegex(RouteInterface $route, string $path): ?array;
}
