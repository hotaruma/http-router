<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interfaces\RouteMap;

use Hotaruma\HttpRouter\Interfaces\Route\RouteCollectionInterface;

interface RouteMapResultInterface
{
    /**
     * Get result routes collection.
     *
     * @return RouteCollectionInterface
     */
    public function getRoutes(): RouteCollectionInterface;
}
