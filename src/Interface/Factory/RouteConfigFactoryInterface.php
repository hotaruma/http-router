<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\Factory;

use Hotaruma\HttpRouter\Interface\RouteConfig\RouteConfigInterface;

interface RouteConfigFactoryInterface
{
    /**
     * @return RouteConfigInterface
     */
    public static function createRouteConfig(): RouteConfigInterface;
}
