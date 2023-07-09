<?php

namespace Hotaruma\HttpRouter\Interface\RouteMatcher;

use Hotaruma\HttpRouter\Exception\RouteMatcherInvalidArgumentException;
use Hotaruma\HttpRouter\Exception\RouteMatcherRuntimeException;
use Hotaruma\HttpRouter\Interface\Enum\RequestMethodInterface;
use Hotaruma\HttpRouter\Interface\PatternRegistry\HasPatternRegistryInterface;
use Hotaruma\HttpRouter\Interface\Route\RouteInterface;

interface RouteMatcherInterface extends HasPatternRegistryInterface
{
    /**
     * Checks if the given route matches the specified HTTP method.
     *
     * @param RouteInterface $route
     * @param RequestMethodInterface $requestMethod
     * @return bool
     */
    public function matchRouteByHttpMethod(RouteInterface $route, RequestMethodInterface $requestMethod): bool;

    /**
     * Matches the given route using regular expressions and retrieves the route attributes.
     *
     * @param array<RouteInterface> $routes
     * @param string $requestPath
     * @return RouteInterface|null
     *
     * @throws RouteMatcherRuntimeException|RouteMatcherInvalidArgumentException
     */
    public function matchRouteByRegex(array $routes, string $requestPath): ?RouteInterface;
}
