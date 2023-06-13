<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\RouteConfig;

use Hotaruma\HttpRouter\RouteConfigValidators\RouteGroupConfigValidator;
use Hotaruma\HttpRouter\Interfaces\RouteConfig\{RouteConfigFactoryInterface, RouteConfigInterface};

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
