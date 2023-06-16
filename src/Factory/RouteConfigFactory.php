<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Factory;

use Hotaruma\HttpRouter\Enum\AdditionalMethod;
use Hotaruma\HttpRouter\Interface\Factory\RouteConfigFactoryInterface;
use Hotaruma\HttpRouter\Interface\RouteConfig\RouteConfigInterface;
use Hotaruma\HttpRouter\RouteConfig\RouteConfig;
use Hotaruma\HttpRouter\Validator\RouteConfigValidator;

class RouteConfigFactory implements RouteConfigFactoryInterface
{
    /**
     * @inheritDoc
     */
    public static function createRouteConfig(): RouteConfigInterface
    {
        $config = new RouteConfig();
        $config->methods([AdditionalMethod::ANY]);

        return $config;
    }
}
