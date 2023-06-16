<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\Route;

use Hotaruma\HttpRouter\Exception\RouteInvalidArgumentException;
use Hotaruma\HttpRouter\Interface\Factory\RouteConfigFactoryInterface;
use Hotaruma\HttpRouter\Interface\RouteConfig\RouteConfigInterface;

interface RouteToolsInterface
{
    /**
     * @param mixed $action Route action
     * @return RouteInterface
     *
     * @throws RouteInvalidArgumentException
     */
    public function action(mixed $action): RouteInterface;

    /**
     * @return mixed Callable action
     */
    public function getAction(): mixed;

    /**
     * @param array<string, string> $attributes
     * @return RouteInterface
     */
    public function attributes(array $attributes): RouteInterface;

    /**
     * @return array<string, string>
     */
    public function getAttributes(): array;

    /**
     * @param string $url
     * @return RouteInterface
     */
    public function url(string $url): RouteInterface;

    /**
     * @return string
     */
    public function getUrl(): string;

    /**
     * Set route config implementation.
     *
     * @param RouteConfigFactoryInterface $routeConfigFactory
     * @return void
     */
    public function routeConfigFactory(RouteConfigFactoryInterface $routeConfigFactory): void;

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
