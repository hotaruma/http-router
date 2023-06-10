<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interfaces\Route;

interface RouteFactoryInterface
{
    /**
     * @return RouteInterface
     */
    public static function createRoute(): RouteInterface;
}
