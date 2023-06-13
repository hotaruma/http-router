<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\Factory;

use Hotaruma\HttpRouter\Interface\Route\RouteInterface;

interface RouteFactoryInterface
{
    /**
     * @return RouteInterface
     */
    public static function createRoute(): RouteInterface;
}
