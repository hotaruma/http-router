<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter;

use Closure;
use Hotaruma\HttpRouter\Collection\RouteCollection;
use Hotaruma\HttpRouter\Enum\{AdditionalMethod, HttpMethod};
use Hotaruma\HttpRouter\Exception\{RouteConfigInvalidArgumentException, RouteInvalidArgumentException};
use Hotaruma\HttpRouter\Factory\{RouteFactory, RouteGroupConfigFactory};
use Hotaruma\HttpRouter\Interface\{Collection\RouteCollectionInterface,
    Enum\RequestMethodInterface,
    Factory\RouteConfigFactoryInterface,
    Factory\RouteFactoryInterface,
    Route\RouteConfigureInterface,
    Route\RouteInterface,
    RouteConfig\RouteConfigInterface,
    RouteMap\RouteMapConfigureInterface,
    RouteMap\RouteMapInterface
};
use Hotaruma\HttpRouter\RouteConfig\{RouteConfig};

class RouteMap implements RouteMapInterface
{
    /**
     * @var RouteConfig
     */
    protected RouteConfig $groupConfig;

    /**
     * All previous groups config.
     *
     * @var RouteConfig
     */
    protected RouteConfig $mergedGroupConfig;

    /**
     * @param RouteFactoryInterface $routeFactory Route factory
     * @param RouteConfigFactoryInterface $routeGroupConfigFactory Routes config factory
     * @param RouteCollectionInterface $routesCollection Routes collection
     */
    public function __construct(
        protected RouteFactoryInterface       $routeFactory = new RouteFactory(),
        protected RouteConfigFactoryInterface $routeGroupConfigFactory = new RouteGroupConfigFactory(),
        protected RouteCollectionInterface    $routesCollection = new RouteCollection()
    ) {
    }

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
    public function routeGroupConfigFactory(
        RouteConfigFactoryInterface $routeGroupConfigFactory
    ): RouteMapConfigureInterface {
        $this->routeGroupConfigFactory = $routeGroupConfigFactory;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRouteGroupConfig(): RouteConfigInterface
    {
        return $this->getGroupConfig();
    }

    /**
     * @inheritDoc
     */
    public function changeGroupConfig(
        array                        $rules = null,
        array                        $defaults = null,
        Closure|array                $middlewares = null,
        string                       $pathPrefix = null,
        string                       $namePrefix = null,
        RequestMethodInterface|array $methods = null,
        bool                         $mergeWithPreviousConfig = false
    ): void {
        $groupConfig = $this->getRouteGroupConfigFactory()::createRouteConfig();
        $groupConfig->config(
            rules: $rules,
            defaults: $defaults,
            middlewares: $middlewares,
            path: $pathPrefix,
            name: $namePrefix,
            methods: $methods,
        );
        $groupConfig->mergeConfig($this->getMergedGroupConfig());

        $this->getGroupConfig()->config(
            rules: isset($rules) ? $groupConfig->getRules() : null,
            defaults: isset($defaults) ? $groupConfig->getDefaults() : null,
            middlewares: isset($middlewares) ? $groupConfig->getMiddlewares() : null,
            path: isset($pathPrefix) ? $groupConfig->getPath() : null,
            name: isset($namePrefix) ? $groupConfig->getName() : null,
            methods: isset($methods) ? $groupConfig->getMethods() : null,
        );
    }

    /**
     * @inheritDoc
     */
    public function group(
        callable                     $group,
        array                        $rules = null,
        array                        $defaults = null,
        Closure|array                $middlewares = null,
        string                       $pathPrefix = null,
        string                       $namePrefix = null,
        RequestMethodInterface|array $methods = null
    ): void {
        $this->mergedGroupConfig($this->getGroupConfig());
        $this->groupConfig($this->getRouteGroupConfigFactory()::createRouteConfig());

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
        return $this->routesCollection;
    }

    /**
     * Set current group config.
     *
     * @param RouteConfig $groupConfig
     * @return void
     */
    protected function groupConfig(RouteConfig $groupConfig): void
    {
        $this->groupConfig = $groupConfig;
    }

    /**
     * Get current group config.
     *
     * @return RouteConfig
     */
    protected function getGroupConfig(): RouteConfig
    {
        return $this->groupConfig ??= $this->getRouteGroupConfigFactory()::createRouteConfig();
    }

    /**
     * Set merged config.
     *
     * @param RouteConfig $mergedGroupConfig
     * @return void
     */
    protected function mergedGroupConfig(RouteConfig $mergedGroupConfig): void
    {
        $this->mergedGroupConfig = $mergedGroupConfig;
    }

    /**
     * Get merged config.
     *
     * @return RouteConfig
     */
    protected function getMergedGroupConfig(): RouteConfig
    {
        return $this->mergedGroupConfig ??= $this->getRouteGroupConfigFactory()::createRouteConfig();
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
        $route->routeMapGroupConfig($this->getGroupConfig());

        $route->getRouteConfig()->mergeConfig($this->getGroupConfig());
        $route->config(path: $path, methods: $methods, name: $name);

        $this->getRoutes()->add($route);
        return $route;
    }

    /**
     * @return RouteFactoryInterface
     */
    protected function getRouteFactory(): RouteFactoryInterface
    {
        return $this->routeFactory;
    }

    /**
     * @return RouteConfigFactoryInterface
     */
    protected function getRouteGroupConfigFactory(): RouteConfigFactoryInterface
    {
        return $this->routeGroupConfigFactory;
    }
}
