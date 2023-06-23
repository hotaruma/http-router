<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\ConfigStore;

use Hotaruma\HttpRouter\Exception\RouteConfigInvalidArgumentException;

interface ConfigStoreConfigureInterface
{
    /**
     * Merge all config with current params.
     *
     * @param ConfigStoreInterface $routeConfig
     * @return void
     *
     * @throws RouteConfigInvalidArgumentException
     */
    public function mergeConfig(ConfigStoreInterface $routeConfig): void;
}
