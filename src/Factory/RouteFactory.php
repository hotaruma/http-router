<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Factory;

use Hotaruma\HttpRouter\Interface\Factory\RouteFactoryInterface;
use Hotaruma\HttpRouter\Interface\Route\{RouteInterface};
use Hotaruma\HttpRouter\Route\Route;

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
