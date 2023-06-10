<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interfaces\RouteMap;

use Closure;
use Hotaruma\HttpRouter\Interfaces\Method;
use Hotaruma\HttpRouter\Interfaces\Route\RouteFactoryInterface;
use Hotaruma\HttpRouter\Interfaces\RouteConfig\RouteConfigFactoryInterface;

interface RouteMapConfigureInterface
{
    /**
     * Create group routes by config.
     *
     * @param array $rules Regex rules for attributes in path
     * @param array $defaults Default values for attributes in path
     * @param Closure|array $middlewares Middlewares list
     * @param string $pathPrefix Path prefix
     * @param string $namePrefix Name prefix
     * @param Method|array $methods Http methods
     * @param callable $group function (RouteMapInterface $routeMap) {}
     * @return void
     */
    public function group(
        callable      $group,
        array         $rules = [],
        array         $defaults = [],
        Closure|array $middlewares = [],
        string        $pathPrefix = '',
        string        $namePrefix = '',
        Method|array  $methods = []
    ): void;

    /**
     * Set base route factory.
     *
     * @param RouteFactoryInterface $routeFactory
     * @return RouteMapConfigureInterface
     */
    public function routeFactory(RouteFactoryInterface $routeFactory): RouteMapConfigureInterface;

    /**
     * Set route config factory.
     *
     * @param RouteConfigFactoryInterface $routeConfigFactory
     * @return RouteMapConfigureInterface
     */
    public function routeConfigFactory(RouteConfigFactoryInterface $routeConfigFactory): RouteMapConfigureInterface;
}
