<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\Route;

use Hotaruma\HttpRouter\Exception\RouteInvalidArgumentException;
use Hotaruma\HttpRouter\Interface\Factory\ConfigStoreFactoryInterface;
use Hotaruma\HttpRouter\Interface\ConfigStore\ConfigStoreInterface;

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
     * Set route config type.
     *
     * @param ConfigStoreFactoryInterface $configStoreFactory
     * @return void
     */
    public function configStoreFactory(ConfigStoreFactoryInterface $configStoreFactory): void;

    /**
     * @return ConfigStoreInterface
     */
    public function getConfigStore(): ConfigStoreInterface;

    /**
     * Set route map config.
     *
     * @param ConfigStoreInterface $routeMapConfigStore
     * @return void
     */
    public function routeMapConfigStore(ConfigStoreInterface $routeMapConfigStore): void;
}
