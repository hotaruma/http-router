<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter;

use Hotaruma\HttpRouter\Collection\RouteCollection;
use Hotaruma\HttpRouter\Enum\{AdditionalMethod, HttpMethod};
use Hotaruma\HttpRouter\Exception\{RouteConfigInvalidArgumentException, RouteInvalidArgumentException};
use Hotaruma\HttpRouter\Factory\{RouteFactory, GroupConfigStoreFactory};
use Hotaruma\HttpRouter\Interface\{Collection\RouteCollectionInterface,
    Enum\RequestMethodInterface,
    Factory\ConfigStoreFactoryInterface,
    Factory\RouteFactoryInterface,
    Route\RouteConfigureInterface,
    Route\RouteInterface,
    ConfigStore\ConfigStoreInterface,
    RouteMap\RouteMapConfigureInterface,
    RouteMap\RouteMapInterface};

class RouteMap implements RouteMapInterface
{
    /**
     * @var ConfigStoreInterface
     */
    protected ConfigStoreInterface $configStore;

    /**
     * All previous groups config.
     *
     * @var ConfigStoreInterface
     */
    protected ConfigStoreInterface $mergedConfigStore;

    /**
     * @var RouteFactoryInterface Route factory
     */
    protected RouteFactoryInterface $routeFactory;

    /**
     * @var ConfigStoreFactoryInterface Group config factory
     */
    protected ConfigStoreFactoryInterface $groupConfigStoreFactory;

    /**
     * @var RouteCollectionInterface Routes collection
     *
     * @phpstan-var TA_RouteCollection
     */
    protected RouteCollectionInterface $routesCollection;

    /**
     * @inheritDoc
     */
    public function routeFactory(RouteFactoryInterface $routeFactory): RouteMapConfigureInterface
    {
        $this->routeFactory = $routeFactory;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function groupConfigStoreFactory(
        ConfigStoreFactoryInterface $groupConfigStoreFactory
    ): RouteMapConfigureInterface {
        $this->groupConfigStoreFactory = $groupConfigStoreFactory;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function changeGroupConfig(
        array                        $rules = null,
        array                        $defaults = null,
        mixed                        $middlewares = null,
        string                       $pathPrefix = null,
        string                       $namePrefix = null,
        RequestMethodInterface|array $methods = null,
        bool                         $mergeWithPreviousConfig = false
    ): void {
        $groupConfig = $this->getGroupConfigStoreFactory()::create();

        isset($rules) and $groupConfig->rules($rules);
        isset($defaults) and $groupConfig->defaults($defaults);
        isset($middlewares) and $groupConfig->middlewares($middlewares);
        isset($pathPrefix) and $groupConfig->path($pathPrefix);
        isset($namePrefix) and $groupConfig->name($namePrefix);
        isset($methods) and $groupConfig->methods($methods);

        $groupConfig->mergeConfig($this->getMergedConfigStore());

        isset($rules) and $this->getConfigStore()->rules($groupConfig->getRules());
        isset($defaults) and $this->getConfigStore()->defaults($groupConfig->getDefaults());
        isset($middlewares) and $this->getConfigStore()->middlewares($groupConfig->getMiddlewares());
        isset($pathPrefix) and $this->getConfigStore()->path($groupConfig->getPath());
        isset($namePrefix) and $this->getConfigStore()->name($groupConfig->getName());
        isset($methods) and $this->getConfigStore()->methods($groupConfig->getMethods());
    }

    /**
     * @inheritDoc
     */
    public function group(
        callable                     $group,
        array                        $rules = null,
        array                        $defaults = null,
        mixed                        $middlewares = null,
        string                       $pathPrefix = null,
        string                       $namePrefix = null,
        RequestMethodInterface|array $methods = null
    ): void {
        $this->mergedConfigStore($this->getConfigStore());
        $this->configStore($this->getGroupConfigStoreFactory()::create());

        $this->changeGroupConfig(
            rules: $rules,
            defaults: $defaults,
            middlewares: $middlewares,
            pathPrefix: $pathPrefix,
            namePrefix: $namePrefix,
            methods: $methods,
            mergeWithPreviousConfig: true
        );

        $group($this);
    }

    /**
     * @inheritDoc
     */
    public function add(string $path, mixed $action): RouteConfigureInterface
    {
        return $this->addRoute($path, $action, [AdditionalMethod::NULL]);
    }

    /**
     * @inheritDoc
     */
    public function any(string $path, mixed $action): RouteConfigureInterface
    {
        return $this->addRoute($path, $action, [AdditionalMethod::ANY]);
    }

    /**
     * @inheritDoc
     */
    public function get(string $path, mixed $action): RouteConfigureInterface
    {
        return $this->addRoute($path, $action, [HttpMethod::GET]);
    }

    /**
     * @inheritDoc
     */
    public function post(string $path, mixed $action): RouteConfigureInterface
    {
        return $this->addRoute($path, $action, [HttpMethod::POST]);
    }

    /**
     * @inheritDoc
     */
    public function put(string $path, mixed $action): RouteConfigureInterface
    {
        return $this->addRoute($path, $action, [HttpMethod::PUT]);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $path, mixed $action): RouteConfigureInterface
    {
        return $this->addRoute($path, $action, [HttpMethod::DELETE]);
    }

    /**
     * @inheritDoc
     */
    public function head(string $path, mixed $action): RouteConfigureInterface
    {
        return $this->addRoute($path, $action, [HttpMethod::HEAD]);
    }

    /**
     * @inheritDoc
     */
    public function options(string $path, mixed $action): RouteConfigureInterface
    {
        return $this->addRoute($path, $action, [HttpMethod::OPTIONS]);
    }

    /**
     * @inheritDoc
     */
    public function trace(string $path, mixed $action): RouteConfigureInterface
    {
        return $this->addRoute($path, $action, [HttpMethod::TRACE]);
    }

    /**
     * @inheritDoc
     */
    public function connect(string $path, mixed $action): RouteConfigureInterface
    {
        return $this->addRoute($path, $action, [HttpMethod::CONNECT]);
    }

    /**
     * @inheritDoc
     */
    public function patch(string $path, mixed $action): RouteConfigureInterface
    {
        return $this->addRoute($path, $action, [HttpMethod::PATCH]);
    }

    /**
     * @inheritDoc
     */
    public function getRoutes(): RouteCollectionInterface
    {
        return $this->routesCollection ??= new RouteCollection();
    }

    /**
     * @inheritDoc
     */
    public function getConfigStore(): ConfigStoreInterface
    {
        return $this->configStore ??= $this->getGroupConfigStoreFactory()::create();
    }

    /**
     * Set current group config.
     *
     * @param ConfigStoreInterface $configStore
     * @return void
     */
    protected function configStore(ConfigStoreInterface $configStore): void
    {
        $this->configStore = $configStore;
    }

    /**
     * Set merged config.
     *
     * @param ConfigStoreInterface $mergedConfigStore
     * @return void
     */
    protected function mergedConfigStore(ConfigStoreInterface $mergedConfigStore): void
    {
        $this->mergedConfigStore = $mergedConfigStore;
    }

    /**
     * Get merged config.
     *
     * @return ConfigStoreInterface
     */
    protected function getMergedConfigStore(): ConfigStoreInterface
    {
        return $this->mergedConfigStore ??= $this->getGroupConfigStoreFactory()::create();
    }

    /**
     * Create and add route to current level.
     *
     * @param string $path
     * @param mixed $action
     * @param array<RequestMethodInterface> $methods
     * @param string $name
     * @return RouteInterface
     *
     * @throws RouteConfigInvalidArgumentException|RouteInvalidArgumentException
     */
    protected function addRoute(string $path, mixed $action, array $methods, string $name = ''): RouteInterface
    {
        $route = $this->getRouteFactory()::createRoute();

        $route->action($action);
        $route->routeMapConfigStore($this->getConfigStore());

        $route->getConfigStore()->mergeConfig($this->getConfigStore());
        $route->config(path: $path, methods: $methods, name: $name);

        $this->getRoutes()->add($route);
        return $route;
    }

    /**
     * @return RouteFactoryInterface
     */
    protected function getRouteFactory(): RouteFactoryInterface
    {
        return $this->routeFactory ??= new RouteFactory();
    }

    /**
     * @return ConfigStoreFactoryInterface
     */
    protected function getGroupConfigStoreFactory(): ConfigStoreFactoryInterface
    {
        return $this->groupConfigStoreFactory ??= new GroupConfigStoreFactory();
    }
}
