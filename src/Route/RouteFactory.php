<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Route;

use Hotaruma\HttpRouter\Interfaces\Route\{RouteFactoryInterface, RouteInterface};

class RouteFactory implements RouteFactoryInterface
{
    /**
     * @inheritDoc
     */
    public static function createRoute(): RouteInterface
    {
        return new Route();
    }
}
