<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Route;

use Closure;
use Hotaruma\HttpRouter\Exception\ConfigInvalidArgumentException;
use Hotaruma\HttpRouter\Exception\RouteInvalidArgumentException;
use Hotaruma\HttpRouter\Interface\{Enum\RequestMethodInterface,
    Factory\ConfigStoreFactoryInterface,
    Route\RouteInterface,
    ConfigStore\ConfigStoreInterface
};
use Hotaruma\HttpRouter\Factory\ConfigStoreFactory;
use Hotaruma\HttpRouter\Utils\{ConfigNormalizeUtils, ConfigValidateUtils};

class Route implements RouteInterface
{
    use ConfigNormalizeUtils;
    use ConfigValidateUtils;

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
     * @var null|ConfigStoreInterface
     */
    protected ?ConfigStoreInterface $routeMapConfigStore = null;

    /**
     * @var ConfigStoreInterface
     */
    protected ConfigStoreInterface $configStore;

    /**
     * @param ConfigStoreFactoryInterface $configStoreFactory
     */
    public function __construct(
        protected ConfigStoreFactoryInterface $configStoreFactory = new ConfigStoreFactory()
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
        try {
            $this->stringStructure(
                $attributes,
                'Invalid format for route attribute. Attributes must be specified as strings.',
            );
        } catch (ConfigInvalidArgumentException $exception) {
            throw new RouteInvalidArgumentException($exception->getMessage(), $exception->getCode(), $exception);
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
    public function configStoreFactory(ConfigStoreFactoryInterface $configStoreFactory): void
    {
        $this->configStoreFactory = $configStoreFactory;
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
        $configStore = $this->getConfigStoreFactory()::create();

        isset($rules) and $configStore->rules($rules);
        isset($defaults) and $configStore->defaults($defaults);
        isset($middlewares) and $configStore->middlewares($middlewares);
        isset($path) and $configStore->path($path);
        isset($name) and $configStore->name($name);
        isset($methods) and $configStore->methods($methods);

        if ($routeMapConfigStore = $this->getRouteMapConfigStore()) {
            $configStore->mergeConfig($routeMapConfigStore);
        }

        isset($rules) and $this->getConfigStore()->rules($configStore->getRules());
        isset($defaults) and $this->getConfigStore()->defaults($configStore->getDefaults());
        isset($middlewares) and $this->getConfigStore()->middlewares($configStore->getMiddlewares());
        isset($path) and $this->getConfigStore()->path($configStore->getPath());
        isset($name) and $this->getConfigStore()->name($configStore->getName());
        isset($methods) and $this->getConfigStore()->methods($configStore->getMethods());
    }

    /**
     * @return ConfigStoreInterface
     */
    public function getConfigStore(): ConfigStoreInterface
    {
        return $this->configStore ??= $this->getConfigStoreFactory()::create();
    }

    /**
     * @inheritDoc
     */
    public function routeMapConfigStore(ConfigStoreInterface $routeMapConfigStore): void
    {
        $this->routeMapConfigStore = $routeMapConfigStore;
    }

    /**
     * @return ConfigStoreInterface|null
     */
    protected function getRouteMapConfigStore(): ?ConfigStoreInterface
    {
        return $this->routeMapConfigStore;
    }

    /**
     * @return ConfigStoreFactoryInterface
     */
    protected function getConfigStoreFactory(): ConfigStoreFactoryInterface
    {
        return $this->configStoreFactory;
    }
}
