<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\ConfigStore;

use Hotaruma\HttpRouter\Interface\Config\ConfigConfigureInterface;

/**
 * @mixin ConfigConfigureInterface
 */
interface ConfigStoreInterface extends ConfigStoreConfigureInterface, ConfigStoreToolsInterface
{
}
