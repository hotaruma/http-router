<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\ConfigStore;

use Hotaruma\HttpRouter\Interface\Config\ConfigInterface;

interface ConfigStoreToolsInterface
{
    /**
     * Set config.
     *
     * @param ConfigInterface $config
     * @return ConfigStoreToolsInterface
     */
    public function config(ConfigInterface $config): ConfigStoreToolsInterface;

    /**
     * @return ConfigInterface
     */
    public function getConfig(): ConfigInterface;
}
