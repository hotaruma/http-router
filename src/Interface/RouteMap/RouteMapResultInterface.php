<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\RouteMap;

use Hotaruma\HttpRouter\Interface\Route\RouteInterface;

interface RouteMapResultInterface
{
    /**
     * Get result routes collection.
     *
     * @return array<RouteInterface>
     */
    public function getRoutes(): array;
}
