<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\RouteConfig;

use Hotaruma\HttpRouter\Interfaces\RouteConfig\{RouteConfigFactoryInterface, RouteConfigInterface};

class RouteConfigFactory implements RouteConfigFactoryInterface
{
    /**
     * @inheritDoc
     */
    public static function createRouteConfig(): RouteConfigInterface
    {
        return new RouteConfig();
    }
}
