<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\RouteMap;

use Hotaruma\HttpRouter\Interface\Collection\RouteCollectionInterface;

interface RouteMapResultInterface
{
    /**
     * Get result routes collection.
     *
     * @return RouteCollectionInterface
     *
     * @phpstan-return TA_RouteCollection
     */
    public function getRoutes(): RouteCollectionInterface;
}
