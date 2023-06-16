<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Factory;

use Hotaruma\HttpRouter\Interface\Factory\RouteConfigFactoryInterface;
use Hotaruma\HttpRouter\Interface\RouteConfig\RouteConfigInterface;
use Hotaruma\HttpRouter\RouteConfig\RouteConfig;
use Hotaruma\HttpRouter\Validator\RouteGroupConfigValidator;

class RouteGroupConfigFactory implements RouteConfigFactoryInterface
{
    /**
     * @inheritDoc
     */
    public static function createRouteConfig(): RouteConfigInterface
    {
        $config = new RouteConfig();
        $config->validator(new RouteGroupConfigValidator());

        return $config;
    }
}
