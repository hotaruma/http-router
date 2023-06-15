<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Route;

use Closure;
use Hotaruma\HttpRouter\Exception\RouteInvalidArgumentException;
use Hotaruma\HttpRouter\Interface\{Enum\RequestMethodInterface, Route\RouteInterface, RouteConfig\RouteConfigInterface};
use Hotaruma\HttpRouter\RouteConfig\RouteConfig;
use Hotaruma\HttpRouter\Utils\ConfigNormalizeUtils;

class Route implements RouteInterface
{
    use ConfigNormalizeUtils;

    /**
     * @var mixed
     */
    protected mixed $action;

    /**
     * @var array<string, string>
     */
    protected array $attributes;

    /**
     * @var string
     */
    protected string $url = '';

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
        if (empty($action)) {
            throw new RouteInvalidArgumentException('Invalid argument: action cannot be empty');
        }
        $this->action = $action;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAction(): mixed
    {
        return $this->action ?? null;
    }

    /**
     * @inheritDoc
     */
    public function attributes(array $attributes): RouteInterface
    {
        foreach ($attributes as $name => $attribute) {
            if (!is_string($name) || !is_string($attribute)) {
                throw new RouteInvalidArgumentException('Invalid format for route attribute. Attributes must be specified as strings.');
            }
        }
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @inheritDoc
     */
    public function url(string $url): RouteInterface
    {
        $this->url = $this->normalizePath($url);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getUrl(): string
    {
        return $this->url;
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
        array                        $rules = null,
        array                        $defaults = null,
        Closure|array                $middlewares = null,
        string                       $path = null,
        string                       $name = null,
        RequestMethodInterface|array $methods = null,
    ): void
    {
        isset($rules) and $this->getRouteConfig()->rules($rules);
        isset($defaults) and $this->getRouteConfig()->defaults($defaults);
        isset($middlewares) and $this->getRouteConfig()->middlewares($middlewares);
        isset($path) and $this->getRouteConfig()->path($path);
        isset($name) and $this->getRouteConfig()->name($name);
        isset($methods) and $this->getRouteConfig()->methods($methods);

        if ($routeMapGroupConfig = $this->getRouteMapGroupConfig()) {
            $this->getRouteConfig()->mergeConfig($routeMapGroupConfig);
        }
    }

    /**
     * @return RouteConfigInterface
     */
    public function getRouteConfig(): RouteConfigInterface
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
