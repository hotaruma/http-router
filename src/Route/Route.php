<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Route;

use Closure;
use Hotaruma\HttpRouter\Exception\RouteInvalidArgument;
use Hotaruma\HttpRouter\Interfaces\{Method,
    Route\RouteInterface,
    RouteConfig\RouteConfigInterface
};
use Hotaruma\HttpRouter\RouteConfig\RouteConfig;

class Route implements RouteInterface
{
    /**
     * @var mixed
     */
    protected mixed $action;

    /**
     * @var null|RouteConfigInterface
     */
    protected ?RouteConfigInterface $routeMapGroupConfig = null;

    /**
     * @param RouteConfigInterface $routeConfig
     */
    public function __construct(protected RouteConfigInterface $routeConfig = new RouteConfig())
    {
    }

    /**
     * @inheritDoc
     */
    public function action(mixed $action): RouteInterface
    {
        if (empty($path)) {
            throw new RouteInvalidArgument("Invalid argument: action cannot be empty");
        }
        $this->action = $action;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAction(): mixed
    {
        return $this->action;
    }

    /**
     * @inheritDoc
     */
    public function routeConfig(RouteConfigInterface $routeConfig): void
    {
        $this->routeConfig = $routeConfig;
    }

    /**
     * @inheritDoc
     */
    public function config(
        array         $rules = null,
        array         $defaults = null,
        Closure|array $middlewares = null,
        string        $path = null,
        string        $name = null,
        Method|array  $methods = null,
    ): void
    {
        isset($rules) and $this->getConfig()->rules($rules);
        isset($defaults) and $this->getConfig()->defaults($defaults);
        isset($middlewares) and $this->getConfig()->middlewares($middlewares);
        isset($path) and $this->getConfig()->path($path);
        isset($name) and $this->getConfig()->name($name);
        isset($methods) and $this->getConfig()->methods($methods);

        if ($routeMapGroupConfig = $this->getRouteMapGroupConfig()) {
            $this->getConfig()->mergeConfig($routeMapGroupConfig);
        }
    }

    /**
     * @return RouteConfigInterface
     */
    public function getConfig(): RouteConfigInterface
    {
        return $this->routeConfig;
    }

    /**
     * @inheritDoc
     */
    public function routeMapGroupConfig(RouteConfigInterface $routeMapGroupConfig): void
    {
        $this->routeMapGroupConfig = $routeMapGroupConfig;
    }

    /**
     * @return RouteConfigInterface|null
     */
    protected function getRouteMapGroupConfig(): ?RouteConfigInterface
    {
        return $this->routeMapGroupConfig;
    }
}
