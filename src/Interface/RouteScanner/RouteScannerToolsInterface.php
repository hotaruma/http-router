<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\RouteScanner;

use Hotaruma\HttpRouter\Exception\RouteScannerReflectionException;
use Hotaruma\HttpRouter\Interface\RouteMap\RouteMapInterface;

interface RouteScannerToolsInterface
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
     * Recursively scan all PHP files within that directory and its subdirectories,
     * extract the class names, and scan attributes on each class.
     *
     * @param string ...$directories
     * @return RouteMapInterface
     */
    public function scanRoutesFromDirectory(string ...$directories): RouteMapInterface;
}
