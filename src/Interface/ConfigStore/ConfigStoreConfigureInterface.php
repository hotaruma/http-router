<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\ConfigStore;

use Hotaruma\HttpRouter\Exception\RouteConfigInvalidArgumentException;
use Hotaruma\HttpRouter\Interface\Config\ConfigInterface;

interface ConfigStoreConfigureInterface
{
    /**
     * Merge all config with current params.
     *
     * @param ConfigStoreInterface|ConfigInterface $routeConfig
     * @return void
     *
     * @throws RouteConfigInvalidArgumentException
     */
    public function mergeConfig(ConfigStoreInterface|ConfigInterface $routeConfig): void;
}
