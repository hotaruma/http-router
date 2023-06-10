<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter;

use Closure;
use Hotaruma\HttpRouter\Enums\{AdditionalMethod, HttpMethod};
use Hotaruma\HttpRouter\RouteConfig\RouteConfigFactory;
use Hotaruma\HttpRouter\Interfaces\{Method,
    Route\RouteCollectionInterface,
    Route\RouteFactoryInterface,
    Route\RouteInterface,
    RouteConfig\ConfigurableInterface,
    RouteConfig\RouteConfigFactoryInterface,
    RouteConfig\RouteConfigInterface,
    RouteMap\RouteMapConfigureInterface,
    RouteMap\RouteMapInterface
};
use Hotaruma\HttpRouter\Route\{RouteCollection, RouteFactory};
use Hotaruma\HttpRouter\RouteConfig\RouteConfig;

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
     * @param RouteConfigFactoryInterface $routeConfigFactory Routes config factory
     * @param RouteCollectionInterface $routesCollection Routes collection
     */
    public function __construct(
        protected RouteFactoryInterface       $routeFactory = new RouteFactory(),
        protected RouteConfigFactoryInterface $routeConfigFactory = new RouteConfigFactory(),
        protected RouteCollectionInterface    $routesCollection = new RouteCollection()
    )
    {
        $groupConfig = $this->routeConfigFactory::createRouteConfig();
        $this->groupConfig($groupConfig);
        $this->mergedGroupConfig($groupConfig);
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
    public function routeConfigFactory(RouteConfigFactoryInterface $routeConfigFactory): RouteMapConfigureInterface
    {
        $this->routeConfigFactory = $routeConfigFactory;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getConfig(): RouteConfigInterface
    {
        return $this->groupConfig;
    }

    public function config(
        array         $rules = null,
        array         $defaults = null,
        Closure|array $middlewares = null,
        string        $path = null,
        string        $name = null,
        Method|array  $methods = null,
        bool          $mergeWithPreviousConfig = false
    ): void
    {
        $groupConfig = $this->routeConfigFactory::createRouteConfig();

        isset($rules) and $groupConfig->rules($rules);
        isset($defaults) and $groupConfig->defaults($defaults);
        isset($middlewares) and $groupConfig->middlewares($middlewares);
        isset($path) and $groupConfig->path($path);
        isset($name) and $groupConfig->name($name);
        isset($methods) and $groupConfig->methods($methods);

        $groupConfig->mergeConfig($this->getMergedGroupConfig());

        isset($rules) || $mergeWithPreviousConfig and $this->getGroupConfig()->rules($groupConfig->getRules());
        isset($defaults) || $mergeWithPreviousConfig and $this->getGroupConfig()->defaults($groupConfig->getDefaults());
        isset($middlewares) || $mergeWithPreviousConfig and $this->getGroupConfig()->middlewares($groupConfig->getMiddlewares());
        isset($path) || $mergeWithPreviousConfig and $this->getGroupConfig()->path($groupConfig->getPath());
        isset($name) || $mergeWithPreviousConfig and $this->getGroupConfig()->name($groupConfig->getName());
        isset($methods) || $mergeWithPreviousConfig and $this->getGroupConfig()->methods($groupConfig->getMethods());
    }

    /**
     * @inheritDoc
     */
    public function group(
        callable      $group,
        array         $rules = null,
        array         $defaults = null,
        Closure|array $middlewares = null,
        string        $pathPrefix = null,
        string        $namePrefix = null,
        Method|array  $methods = null
    ): void
    {
        $this->mergedGroupConfig($this->getGroupConfig());
        $this->groupConfig($this->routeConfigFactory::createRouteConfig());

        $this->config(
            rules: $rules,
            defaults: $defaults,
            middlewares: $middlewares,
            path: $pathPrefix,
            name: $namePrefix,
            methods: $methods,
            mergeWithPreviousConfig: true
        );

        $group($this);
    }

    /**
     * @inheritDoc
     */
    public function create(string $path, mixed $action, Method|array $methods, string $name = ''): ConfigurableInterface
    {
        $methods = (array)$methods;
        return $this->addRoute($path, $action, $methods, $name);
    }

    /**
     * @inheritDoc
     */
    public function any(string $path, mixed $action, string $name = ''): ConfigurableInterface
    {
        return $this->addRoute($path, $action, [AdditionalMethod::ANY], $name);
    }

    /**
     * @inheritDoc
     */
    public function get(string $path, mixed $action, string $name = ''): ConfigurableInterface
    {
        return $this->addRoute($path, $action, [HttpMethod::GET], $name);
    }

    /**
     * @inheritDoc
     */
    public function post(string $path, mixed $action, string $name = ''): ConfigurableInterface
    {
        return $this->addRoute($path, $action, [HttpMethod::POST], $name);
    }

    /**
     * @inheritDoc
     */
    public function put(string $path, mixed $action, string $name = ''): ConfigurableInterface
    {
        return $this->addRoute($path, $action, [HttpMethod::PUT], $name);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $path, mixed $action, string $name = ''): ConfigurableInterface
    {
        return $this->addRoute($path, $action, [HttpMethod::DELETE], $name);
    }

    /**
     * @inheritDoc
     */
    public function head(string $path, mixed $action, string $name = ''): ConfigurableInterface
    {
        return $this->addRoute($path, $action, [HttpMethod::HEAD], $name);
    }

    /**
     * @inheritDoc
     */
    public function options(string $path, mixed $action, string $name = ''): ConfigurableInterface
    {
        return $this->addRoute($path, $action, [HttpMethod::OPTIONS], $name);
    }

    /**
     * @inheritDoc
     */
    public function trace(string $path, mixed $action, string $name = ''): ConfigurableInterface
    {
        return $this->addRoute($path, $action, [HttpMethod::TRACE], $name);
    }

    /**
     * @inheritDoc
     */
    public function connect(string $path, mixed $action, string $name = ''): ConfigurableInterface
    {
        return $this->addRoute($path, $action, [HttpMethod::CONNECT], $name);
    }

    /**
     * @inheritDoc
     */
    public function patch(string $path, mixed $action, string $name = ''): ConfigurableInterface
    {
        return $this->addRoute($path, $action, [HttpMethod::PATCH], $name);
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
        return $this->groupConfig;
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
        return $this->mergedGroupConfig;
    }

    /**
     * Create and add route to current level.
     *
     * @param string $path
     * @param mixed $action
     * @param array<Method> $methods
     * @param string $name
     * @return RouteInterface
     */
    protected function addRoute(string $path, mixed $action, array $methods, string $name = ''): RouteInterface
    {
        $route = $this->routeFactory::createRoute();

        $route->action($action);
        $route->config(path: $path, methods: $methods, name: $name);
        $route->getConfig()->mergeConfig($this->getGroupConfig());
        $route->routeMapGroupConfig($this->getGroupConfig());

        $this->routesCollection->add($route);
        return $route;
    }
}
