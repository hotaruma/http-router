<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interfaces\RouteMap;

use Hotaruma\HttpRouter\Interfaces\RouteConfig\ConfigurableInterface;

interface RouteMapInterface extends RouteMapMethodsInterface, RouteMapConfigureInterface, RouteMapResultInterface, ConfigurableInterface
{
}
