<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\RouteMap;

use Hotaruma\HttpRouter\Exception\RouteConfigInvalidArgumentException;
use Hotaruma\HttpRouter\Interface\Enum\RequestMethodInterface;
use Hotaruma\HttpRouter\Interface\Factory\ConfigStoreFactoryInterface;
use Hotaruma\HttpRouter\Interface\Factory\RouteFactoryInterface;
use Hotaruma\HttpRouter\Interface\ConfigStore\ConfigStoreInterface;

interface RouteMapConfigureInterface
{
    /**
     * Set route group config.
     *
     * @param array<string,string>|null $rules Regex rules for attributes in path
     * @param array<string,string>|null $defaults Default values for attributes in path
     * @param mixed $middlewares Middlewares list
     * @param string|null $pathPrefix Url path prefix
     * @param string|null $namePrefix Route name prefix
     * @param RequestMethodInterface|array<RequestMethodInterface>|null $methods Http methods
     * @return void
     *
     * @throws RouteConfigInvalidArgumentException
     */
    public function changeGroupConfig(
        array                        $rules = null,
        array                        $defaults = null,
        mixed                        $middlewares = null,
        string                       $pathPrefix = null,
        string                       $namePrefix = null,
        RequestMethodInterface|array $methods = null
    ): void;

    /**
     * Create group routes by config.
     *
     * @param callable(RouteMapInterface $routeMap): void $group
     * @param array<string,string>|null $rules Regex rules for attributes in path
     * @param array<string,string>|null $defaults Default values for attributes in path
     * @param mixed $middlewares Middlewares list
     * @param string|null $pathPrefix Url path prefix
     * @param string|null $namePrefix Routes name prefix
     * @param RequestMethodInterface|array<RequestMethodInterface>|null $methods Http methods
     * @return void
     *
     * @throws RouteConfigInvalidArgumentException
     */
    public function group(
        callable                     $group,
        array                        $rules = null,
        array                        $defaults = null,
        mixed                        $middlewares = null,
        string                       $pathPrefix = null,
        string                       $namePrefix = null,
        RequestMethodInterface|array $methods = null
    ): void;

    /**
     * Set base route factory.
     *
     * @param RouteFactoryInterface $routeFactory
     * @return RouteMapConfigureInterface
     */
    public function routeFactory(RouteFactoryInterface $routeFactory): RouteMapConfigureInterface;

    /**
     * Set group config factory.
     *
     * @param ConfigStoreFactoryInterface $groupConfigStoreFactory
     * @return RouteMapConfigureInterface
     */
    public function groupConfigStoreFactory(
        ConfigStoreFactoryInterface $groupConfigStoreFactory
    ): RouteMapConfigureInterface;

    /**
     * Get current group config.
     *
     * @return ConfigStoreInterface
     */
    public function getConfigStore(): ConfigStoreInterface;
}
