<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\RouteScanner;

use Closure;
use Hotaruma\HttpRouter\Exception\RouteScannerReflectionException;
use Hotaruma\HttpRouter\Interface\RouteMap\RouteMapInterface;

interface RouteScannerInterface
{
    /**
     * Scan route/group attributes and add to current route-map level.
     *
     * @param class-string ...$classes
     * @return RouteMapInterface
     *
     * @throws RouteScannerReflectionException
     */
    public function scanRoutes(...$classes): RouteMapInterface;

    /**
     * Set route-map.
     *
     * @param RouteMapInterface $routeMap
     * @return void
     */
    public function routeMap(RouteMapInterface $routeMap): void;

    /**
     * Set builder for route action from classname and method. function(string $className, string $methodName): mixed
     *
     * @param Closure(string $className, string $methodName): mixed $routeActionBuilder
     * @return void
     */
    public function routeActionBuilder(Closure $routeActionBuilder): void;
}
