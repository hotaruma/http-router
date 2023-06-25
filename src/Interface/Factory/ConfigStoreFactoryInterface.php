<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\Factory;

use Hotaruma\HttpRouter\Interface\ConfigStore\ConfigStoreInterface;

interface ConfigStoreFactoryInterface
{
    /**
     * @return ConfigStoreInterface
     */
    public static function create(): ConfigStoreInterface;
}
