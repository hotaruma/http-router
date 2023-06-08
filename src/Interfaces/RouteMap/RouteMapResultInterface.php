<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interfaces\RouteMap;

use Hotaruma\HttpRouter\Interfaces\Route\RouteInterface;

interface RouteMapResultInterface
{
    /**
     * Get result routes list.
     *
     * @return array<RouteInterface>
     */
    public function getRoutes(): array;
}
