<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter;

use Hotaruma\HttpRouter\Enums\AdditionalMethod;
use Hotaruma\HttpRouter\Enums\HttpMethod;
use Hotaruma\HttpRouter\Exception\RouteInvalidArgument;
use Hotaruma\HttpRouter\Route\Route;
use Hotaruma\HttpRouter\Interfaces\{Route\RouteConfigureInterface,
    Route\RouteInterface,
    RouteMap\RouteMapConfigureInterface,
    RouteMap\RouteMapInterface
};
use Hotaruma\HttpRouter\Interfaces\Method;

class RouteMap implements RouteMapInterface
{
    final protected const KEY_REDUCE = 1;
    final protected const MERGE_REDUCE = 2;
    final protected const CONCATENATE_REDUCE = 3;

    /**
     * @var array<int, array<string,string>>
     */
    protected array $rules = [];

    /**
     * @var array<int, array<string,string>>
     */
    protected array $defaults = [];

    /**
     * @var array<int, array>
     */
    protected array $middlewares = [];

    /**
     * @var array<int, string>
     */
    protected array $path = [];

    /**
     * @var array<int, string>
     */
    protected array $name = [];

    /**
     * @var array<int, array<Method>>
     */
    protected array $methods = [];

    /**
     * @var array<RouteInterface>
     */
    protected array $routes = [];

    /**
     * @var array<int, array<RouteInterface>>
     */
    protected array $routesStore = [];

    /**
     * Current group level.
     *
     * @var int
     */
    protected int $level = 0;

    /**
     * @param RouteInterface $route Route for clone.
     */
    public function __construct(
        protected RouteInterface $route = new Route()
    )
    {
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
    public function rules(array $rules): RouteMapConfigureInterface
    {
        $this->rules[$this->level] = $rules;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function defaults(array $defaults): RouteMapConfigureInterface
    {
        $this->defaults[$this->level] = $defaults;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function middlewares(callable|array $defaults): RouteMapConfigureInterface
    {
        $this->middlewares[$this->level] = (array)$defaults;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function path(string $path): RouteMapConfigureInterface
    {
        $this->path[$this->level] = $path;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function name(string $name): RouteMapConfigureInterface
    {
        $this->name[$this->level] = $name;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function methods(Method|array $methods): RouteMapConfigureInterface
    {
        $methods = (array)$methods;

        foreach ($methods as $method) {
            if (!$method instanceof Method) {
                throw new RouteInvalidArgument("Invalid argument. Expected instance of Method.");
            }
        }
        $this->methods[$this->level] = $methods;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function group(
        array $rules = [],
        array $defaults = [],
        callable|array $middlewares = [],
        string $path = '',
        string $name = '',
        Method|array $methods = [],
        callable $group = null
    ): void
    {
        $this->calculateGroupConfig();
        $this->level++;
        $group($this);
        $this->mergeRoutesWithGroupConfig();
        $this->cleanRoutesStore();
        $this->level--;
        $this->cleanGroupConfig();
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
    public function getRoutes(): array
    {
        $this->cleanRoutesStore();
        return $this->routes;
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
        $this->routesStore[$this->level][] = $route->path($path)->action($action)->methods($methods)->name($name);

        return $route;
    }

    /**
     * Merge config with prev group.
     *
     * @return void
     */
    protected function calculateGroupConfig(): void
    {
        if (0 >= $this->level) {
            return;
        }
        $prevLevel = $this->level - 1;

        $this->rules[$this->level] = $this->reduceConfig([
            $this->rules[$prevLevel] ?? [],
            $this->rules[$this->level] ?? []
        ], self::KEY_REDUCE);
        $this->defaults[$this->level] = $this->reduceConfig([
            $this->defaults[$prevLevel] ?? [],
            $this->defaults[$this->level] ?? []
        ], self::KEY_REDUCE);
        $this->middlewares[$this->level] = $this->reduceConfig([
            $this->middlewares[$prevLevel] ?? [],
            $this->middlewares[$this->level] ?? []
        ], self::MERGE_REDUCE);
        $this->path[$this->level] = $this->reduceConfig([
            $this->path[$prevLevel] ?? '',
            $this->path[$this->level] ?? ''
        ], self::CONCATENATE_REDUCE, '/');
        $this->name[$this->level] = $this->reduceConfig([
            $this->name[$prevLevel] ?? '',
            $this->name[$this->level] ?? ''
        ], self::CONCATENATE_REDUCE, '.');
        $this->methods[$this->level] = $this->reduceConfig([
            $this->methods[$prevLevel] ?? [],
            $this->methods[$this->level] ?? []
        ], self::MERGE_REDUCE);
    }

    /**
     * Merge routes config with current group.
     *
     * @return void
     */
    protected function mergeRoutesWithGroupConfig(): void
    {
        foreach ($this->routesStore[$this->level] as $route) {
            $this->mergeRouteWithGroupConfig($route);
        }
    }

    /**
     * Merge route config with current group.
     *
     * @param RouteInterface $route
     * @return void
     */
    protected function mergeRouteWithGroupConfig(RouteInterface $route): void
    {
        $configLevel = $this->level - 1;

        $route->rules($this->reduceConfig([$this->rules[$configLevel], $route->getRules()], self::KEY_REDUCE));
        $route->defaults($this->reduceConfig([$this->defaults[$configLevel], $route->getDefaults()], self::KEY_REDUCE));
        $route->middlewares($this->reduceConfig([$this->middlewares[$configLevel], $route->getMiddlewares()], self::MERGE_REDUCE));
        $route->path($this->reduceConfig([$this->path[$configLevel], $route->getPath()], self::CONCATENATE_REDUCE, '/'));
        $route->name($this->reduceConfig([$this->name[$configLevel], $route->getName()], self::CONCATENATE_REDUCE, '.'));
        $route->methods($this->reduceConfig([$this->methods[$configLevel], $route->getMethods()], self::MERGE_REDUCE));
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
        $reduceConfig = function (array $config, callable $fn, $separator = ''): mixed {
            return array_reduce($config, function ($carry, $item) use ($fn, $separator) {
                if ($carry === null) {
                    return $item;
                }
                return $fn($carry, $item, $separator);
            });
        };

        return match ($reduceType) {
            self::KEY_REDUCE => $reduceConfig($items, function ($carry, $item): array {
                return $item + $carry;
            }, $separator),
            self::MERGE_REDUCE => $reduceConfig($items, function ($carry, $item): array {
                return array_merge($carry, $item);
            }, $separator),
            self::CONCATENATE_REDUCE => $reduceConfig($items, function ($carry, $item, $separator): string {
                return $carry . $separator . $item;
            }, $separator),
            default => $items,
        };
    }

    /**
     * Clean routes store.
     *
     * @return void
     */
    protected function cleanRoutesStore(): void
    {
        if (!isset($this->routesStore[$this->level])) {
            return;
        }

        array_push($this->routes, ...$this->routesStore[$this->level]);
        unset($this->routesStore[$this->level]);
    }

    /**
     * Unset current level config.
     *
     * @return void
     */
    protected function cleanGroupConfig(): void
    {
        unset(
            $this->rules[$this->level],
            $this->defaults[$this->level],
            $this->middlewares[$this->level],
            $this->path[$this->level],
            $this->name[$this->level],
            $this->methods[$this->level]
        );
    }
}
