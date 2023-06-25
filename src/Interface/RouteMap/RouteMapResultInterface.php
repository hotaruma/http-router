<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\RouteMap;

use Hotaruma\HttpRouter\Interface\Collection\RouteCollectionInterface;
use Hotaruma\HttpRouter\Interface\Iterator\RouteIteratorInterface;
use Hotaruma\HttpRouter\Interface\Route\RouteInterface;

interface RouteMapResultInterface
{
    /**
     * Get result routes collection.
     *
     * @return RouteCollectionInterface<RouteInterface, RouteIteratorInterface<int, RouteInterface>>
     */
    public function getRoutes(): RouteCollectionInterface;
}
