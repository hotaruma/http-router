<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Factory;

use Hotaruma\HttpRouter\Config\RouteConfig;
use Hotaruma\HttpRouter\Enum\AdditionalMethod;
use Hotaruma\HttpRouter\Interface\Factory\ConfigStoreFactoryInterface;
use Hotaruma\HttpRouter\ConfigStore\ConfigStore;

class ConfigStoreFactory implements ConfigStoreFactoryInterface
{
    /**
     * @inheritDoc
     */
    public static function create(): ConfigStore
    {
        $config = new ConfigStore();

        $config->config(new RouteConfig());
        $config->methods([AdditionalMethod::ANY]);

        return $config;
    }
}
