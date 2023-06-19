<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Route;

use Closure;
use Hotaruma\HttpRouter\Exception\RouteInvalidArgumentException;
use Hotaruma\HttpRouter\Interface\{Enum\RequestMethodInterface,
    Factory\RouteConfigFactoryInterface,
    Route\RouteInterface,
    RouteConfig\RouteConfigInterface
};
use Hotaruma\HttpRouter\Factory\RouteConfigFactory;
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
    protected array $attributes = [];

    /**
     * @var string
     */
    protected string $url = '';

    /**
     * @var null|RouteConfigInterface
     */
    protected ?RouteConfigInterface $routeMapGroupConfig = null;

    /**
     * @var RouteConfigInterface
     */
    protected RouteConfigInterface $routeConfig;

    /**
     * @param RouteConfigFactoryInterface $routeConfigFactory
     */
    public function __construct(
        protected RouteConfigFactoryInterface $routeConfigFactory = new RouteConfigFactory()
    ) {
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
    public function routeConfigFactory(RouteConfigFactoryInterface $routeConfigFactory): void
    {
        $this->routeConfigFactory = $routeConfigFactory;
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
    ): void {
        $routeConfig = $this->getRouteConfigFactory()::createRouteConfig();
        $routeConfig->config(
            rules: $rules,
            defaults: $defaults,
            middlewares: $middlewares,
            path: $path,
            name: $name,
            methods: $methods,
        );
        if ($routeMapGroupConfig = $this->getRouteMapGroupConfig()) {
            $routeConfig->mergeConfig($routeMapGroupConfig);
        }
        $this->getRouteConfig()->config(
            rules: isset($rules) ? $routeConfig->getRules() : null,
            defaults: isset($defaults) ? $routeConfig->getDefaults() : null,
            middlewares: isset($middlewares) ? $routeConfig->getMiddlewares() : null,
            path: isset($path) ? $routeConfig->getPath() : null,
            name: isset($name) ? $routeConfig->getName() : null,
            methods: isset($methods) ? $routeConfig->getMethods() : null,
        );
    }

    /**
     * @return RouteConfigInterface
     */
    public function getRouteConfig(): RouteConfigInterface
    {
        return $this->routeConfig ??= $this->getRouteConfigFactory()::createRouteConfig();
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

    /**
     * @return RouteConfigFactory
     */
    protected function getRouteConfigFactory(): RouteConfigFactory
    {
        return $this->routeConfigFactory;
    }
}
