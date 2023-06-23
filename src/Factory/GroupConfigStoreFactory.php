<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Factory;

use Hotaruma\HttpRouter\Config\RouteGroupConfig;
use Hotaruma\HttpRouter\Interface\Factory\ConfigStoreFactoryInterface;
use Hotaruma\HttpRouter\Interface\ConfigStore\ConfigStoreInterface;
use Hotaruma\HttpRouter\ConfigStore\ConfigStore;

class GroupConfigStoreFactory implements ConfigStoreFactoryInterface
{
    /**
     * @inheritDoc
     */
    public static function create(): ConfigStoreInterface
    {
        $config = new ConfigStore();

        $config->config(new RouteGroupConfig());

        return $config;
    }
}
