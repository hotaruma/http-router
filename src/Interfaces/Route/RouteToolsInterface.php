<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interfaces\Route;

use Hotaruma\HttpRouter\Exception\RouteInvalidArgument;
use Hotaruma\HttpRouter\Interfaces\RouteConfig\RouteConfigInterface;

interface RouteToolsInterface
{
    /**
     * @param mixed $action Route action
     * @return RouteInterface
     *
     * @throws RouteInvalidArgument
     */
    public function action(mixed $action): RouteInterface;

    /**
     * @return mixed Callable action
     */
    public function getAction(): mixed;

    /**
     * Set route config implementation.
     *
     * @param RouteConfigInterface $routeConfig
     * @return void
     */
    public function routeConfig(RouteConfigInterface $routeConfig): void;

    /**
     * @return RouteConfigInterface
     */
    public function getRouteConfig(): RouteConfigInterface;

    /**
     * Set route map group config.
     *
     * @param RouteConfigInterface $routeMapGroupConfig
     * @return void
     */
    public function routeMapGroupConfig(RouteConfigInterface $routeMapGroupConfig): void;
}
