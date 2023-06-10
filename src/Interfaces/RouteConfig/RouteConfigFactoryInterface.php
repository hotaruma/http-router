<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interfaces\RouteConfig;

interface RouteConfigFactoryInterface
{
    /**
     * @return RouteConfigInterface
     */
    public static function createRouteConfig(): RouteConfigInterface;
}
