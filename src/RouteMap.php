<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter;

use Closure;
use Hotaruma\HttpRouter\Enums\{AdditionalMethod, HttpMethod};
use Hotaruma\HttpRouter\Route\{Route, RouteCollection};
use Hotaruma\HttpRouter\Utils\GroupConfig;
use Hotaruma\HttpRouter\Interfaces\{Route\RouteCollectionInterface,
    Route\RouteConfigureInterface,
    Route\RouteInterface,
    RouteMap\RouteMapConfigureInterface,
    RouteMap\RouteMapInterface,
    Method
};

class RouteMap implements RouteMapInterface
{
    final protected const KEY_REDUCE = 1;
    final protected const MERGE_REDUCE = 2;
    final protected const CONCATENATE_REDUCE = 3;

    protected const PATH_SEPARATOR = '/';
    protected const NAME_SEPARATOR = '.';

    /**
     * Current group config.
     *
     * @var GroupConfig
     */
    protected GroupConfig $groupConfig;

    /**
     * All previous groups config.
     *
     * @var GroupConfig
     */
    protected GroupConfig $mergedConfig;

    /**
     * @param RouteInterface $route Route for clone
     * @param RouteCollectionInterface $routesCollection Routes collection
     */
    public function __construct(
        protected RouteInterface           $route = new Route(),
        protected RouteCollectionInterface $routesCollection = new RouteCollection(),
    )
    {
        $this->groupConfig = $this->mergedConfig = new GroupConfig();
    }

    /**
     * @inheritDoc
     */
    public function baseRoute(RouteInterface $route): RouteMapConfigureInterface
    {
        $this->route = $route;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function config(
        array         $rules = null,
        array         $defaults = null,
        Closure|array $middlewares = null,
        string        $pathPrefix = null,
        string        $namePrefix = null,
        Method|array  $methods = null,
        ?GroupConfig  $baseConfig = null
    ): void
    {
        isset($rules) and $rules = $this->reduceConfig([$this->getMergedConfig()->getRules(), $rules], self::KEY_REDUCE);
        isset($defaults) and $defaults = $this->reduceConfig([$this->getMergedConfig()->getDefaults(), $defaults], self::KEY_REDUCE);
        isset($middlewares) and $middlewares = $this->reduceConfig([$this->getMergedConfig()->getMiddlewares(), $middlewares], self::MERGE_REDUCE);
        isset($pathPrefix) and $pathPrefix = $this->reduceConfig([$this->getMergedConfig()->getPathPrefix(), $pathPrefix], self::CONCATENATE_REDUCE, self::PATH_SEPARATOR);
        isset($namePrefix) and $namePrefix = $this->reduceConfig([$this->getMergedConfig()->getNamePrefix(), $namePrefix], self::CONCATENATE_REDUCE, self::NAME_SEPARATOR);
        isset($methods) and $methods = $this->reduceConfig([$this->getMergedConfig()->getMethods(), $methods], self::MERGE_REDUCE);

        $this->groupConfig(
            new GroupConfig(
                rules: $rules ?? $baseConfig?->getRules() ?? $this->getGroupConfig()->getRules(),
                defaults: $defaults ?? $baseConfig?->getDefaults() ?? $this->getGroupConfig()->getDefaults(),
                middlewares: $middlewares ?? $baseConfig?->getMiddlewares() ?? $this->getGroupConfig()->getMiddlewares(),
                pathPrefix: $pathPrefix ?? $baseConfig?->getPathPrefix() ?? $this->getGroupConfig()->getPathPrefix(),
                namePrefix: $namePrefix ?? $baseConfig?->getNamePrefix() ?? $this->getGroupConfig()->getNamePrefix(),
                methods: $methods ?? $baseConfig?->getMethods() ?? $this->getGroupConfig()->getMethods()
            )
        );
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
        $this->mergedConfig($this->getGroupConfig());
        $this->groupConfig(new GroupConfig());

        $this->config(
            rules: $rules,
            defaults: $defaults,
            middlewares: $middlewares,
            pathPrefix: $pathPrefix,
            namePrefix: $namePrefix,
            methods: $methods,
            baseConfig: $this->getMergedConfig()
        );

        $group($this);
    }

    /**
     * @inheritDoc
     */
    public function create(string $path, mixed $action, Method|array $methods, string $name = ''): RouteConfigureInterface
    {
        $methods = (array)$methods;
        return $this->addRoute($path, $action, $methods, $name);
    }

    /**
     * @inheritDoc
     */
    public function any(string $path, mixed $action, string $name = ''): RouteConfigureInterface
    {
        return $this->addRoute($path, $action, [AdditionalMethod::ANY], $name);
    }

    /**
     * @inheritDoc
     */
    public function get(string $path, mixed $action, string $name = ''): RouteConfigureInterface
    {
        return $this->addRoute($path, $action, [HttpMethod::GET], $name);
    }

    /**
     * @inheritDoc
     */
    public function post(string $path, mixed $action, string $name = ''): RouteConfigureInterface
    {
        return $this->addRoute($path, $action, [HttpMethod::POST], $name);
    }

    /**
     * @inheritDoc
     */
    public function put(string $path, mixed $action, string $name = ''): RouteConfigureInterface
    {
        return $this->addRoute($path, $action, [HttpMethod::PUT], $name);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $path, mixed $action, string $name = ''): RouteConfigureInterface
    {
        return $this->addRoute($path, $action, [HttpMethod::DELETE], $name);
    }

    /**
     * @inheritDoc
     */
    public function head(string $path, mixed $action, string $name = ''): RouteConfigureInterface
    {
        return $this->addRoute($path, $action, [HttpMethod::HEAD], $name);
    }

    /**
     * @inheritDoc
     */
    public function options(string $path, mixed $action, string $name = ''): RouteConfigureInterface
    {
        return $this->addRoute($path, $action, [HttpMethod::OPTIONS], $name);
    }

    /**
     * @inheritDoc
     */
    public function trace(string $path, mixed $action, string $name = ''): RouteConfigureInterface
    {
        return $this->addRoute($path, $action, [HttpMethod::TRACE], $name);
    }

    /**
     * @inheritDoc
     */
    public function connect(string $path, mixed $action, string $name = ''): RouteConfigureInterface
    {
        return $this->addRoute($path, $action, [HttpMethod::CONNECT], $name);
    }

    /**
     * @inheritDoc
     */
    public function patch(string $path, mixed $action, string $name = ''): RouteConfigureInterface
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
     * @param GroupConfig $groupConfig
     * @return void
     */
    protected function groupConfig(GroupConfig $groupConfig): void
    {
        $this->groupConfig = $groupConfig;
    }

    /**
     * Get current group config.
     *
     * @return GroupConfig
     */
    protected function getGroupConfig(): GroupConfig
    {
        return $this->groupConfig;
    }

    /**
     * Set merged config.
     *
     * @param GroupConfig $mergedConfig
     * @return void
     */
    protected function mergedConfig(GroupConfig $mergedConfig): void
    {
        $this->mergedConfig = $mergedConfig;
    }

    /**
     * Get merged config.
     *
     * @return GroupConfig
     */
    protected function getMergedConfig(): GroupConfig
    {
        return $this->mergedConfig;
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
        $route = clone $this->route;
        $route->action($action);
        $route->fnMergeConfigWithGroup($this->mergeRouteWithGroupConfig(...));

        $this->mergeRouteWithGroupConfig($route, path: $path, methods: $methods, name: $name);
        $this->routesCollection->add($route);

        return $route;
    }

    /**
     * Merge route config with current group.
     *
     * @param RouteInterface $route
     * @param string|null $path
     * @param array|null $methods
     * @param string|null $name
     * @param array|null $rules
     * @param array|null $defaults
     * @param Closure|array|null $middlewares
     * @return void
     */
    protected function mergeRouteWithGroupConfig(
        RouteInterface $route,
        string         $path = null,
        array          $methods = null,
        string         $name = null,
        array          $rules = null,
        array          $defaults = null,
        Closure|array  $middlewares = null
    ): void
    {
        isset($path) && $route->path($this->reduceConfig([$this->getGroupConfig()->getPathPrefix(), $path], self::CONCATENATE_REDUCE, self::PATH_SEPARATOR));
        isset($methods) && $route->methods($this->reduceConfig([$this->getGroupConfig()->getMethods(), $methods], self::MERGE_REDUCE));
        isset($name) && $route->name($this->reduceConfig([$this->getGroupConfig()->getNamePrefix(), $name], self::CONCATENATE_REDUCE, self::NAME_SEPARATOR));
        isset($rules) && $route->rules($this->reduceConfig([$this->getGroupConfig()->getRules(), $rules], self::KEY_REDUCE));
        isset($defaults) && $route->defaults($this->reduceConfig([$this->getGroupConfig()->getDefaults(), $defaults], self::KEY_REDUCE));
        isset($middlewares) && $route->middlewares($this->reduceConfig([$this->getGroupConfig()->getMiddlewares(), $middlewares], self::MERGE_REDUCE));
    }

    /**
     * Reduce array by type.
     *
     * @param array $items
     * @param int $reduceType
     * @param string $separator
     * @return mixed
     */
    protected function reduceConfig(array $items, int $reduceType, string $separator = ''): mixed
    {
        $reduceConfig = static function (array $config, callable $fn, $separator = ''): mixed {
            return array_reduce($config, function ($carry, $item) use ($fn, $separator) {
                if ($carry === null) {
                    return $item;
                }
                return $fn($carry, $item, $separator);
            });
        };

        return match ($reduceType) {
            self::KEY_REDUCE => $reduceConfig($items, function ($carry, $item): array {
                return (array)$item + (array)$carry;
            }, $separator),
            self::MERGE_REDUCE => $reduceConfig($items, function ($carry, $item): array {
                return array_merge((array)$carry, (array)$item);
            }, $separator),
            self::CONCATENATE_REDUCE => $reduceConfig($items, function ($carry, $item, $separator): string {
                return $carry . $separator . $item;
            }, $separator),
            default => $items,
        };
    }
}
