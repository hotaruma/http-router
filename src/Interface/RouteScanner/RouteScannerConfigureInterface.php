<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\RouteScanner;

use Closure;
use Hotaruma\HttpRouter\Interface\RouteMap\RouteMapInterface;

interface RouteScannerConfigureInterface
{
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
