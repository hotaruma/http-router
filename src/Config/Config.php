<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Config;

use Closure;
use Hotaruma\HttpRouter\Interface\Config\{ConfigConfigureInterface, ConfigInterface};
use Hotaruma\HttpRouter\Interface\Enum\RequestMethodInterface;
use Hotaruma\HttpRouter\Utils\ConfigNormalizeUtils;

abstract class Config implements ConfigInterface
{
    use ConfigNormalizeUtils;

    /**
     * @param array<string,string> $rules
     * @param array<string,string> $defaults
     * @param array<mixed> $middlewares
     * @param string $path
     * @param string $name
     * @param array<RequestMethodInterface> $methods
     */
    public function __construct(
        protected array  $rules = [],
        protected array  $defaults = [],
        protected array  $middlewares = [],
        protected string $path = '/',
        protected string $name = '',
        protected array  $methods = [],
    ) {
    }

    /**
     * @inheritDoc
     */
    public function rules(array $rules): ConfigConfigureInterface
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * @inheritDoc
     */
    public function defaults(array $defaults): ConfigConfigureInterface
    {
        $this->defaults = $defaults;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getDefaults(): array
    {
        return $this->defaults;
    }

    /**
     * @inheritDoc
     */
    public function middlewares(array|Closure $middlewares): ConfigConfigureInterface
    {
        $this->middlewares = is_array($middlewares) ? $middlewares : [$middlewares];

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    /**
     * @inheritDoc
     */
    public function path(string $path): ConfigConfigureInterface
    {
        $this->path = $this->normalizePath($path);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @inheritDoc
     */
    public function name(string $name): ConfigConfigureInterface
    {
        $this->name = $this->normalizeName($name);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function methods(RequestMethodInterface|array $methods): ConfigConfigureInterface
    {
        $this->methods = is_array($methods) ? $methods : [$methods];

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getMethods(): array
    {
        return $this->methods;
    }
}
