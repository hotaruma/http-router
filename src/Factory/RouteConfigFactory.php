<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Factory;

use Hotaruma\HttpRouter\Interface\Factory\RouteConfigFactoryInterface;
use Hotaruma\HttpRouter\Interface\RouteConfig\RouteConfigInterface;
use Hotaruma\HttpRouter\RouteConfig\RouteConfig;

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
