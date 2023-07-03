<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Config;

use Hotaruma\HttpRouter\Interface\Config\{ConfigConfigureInterface, ConfigInterface};
use Hotaruma\HttpRouter\Enum\AdditionalMethod;
use Hotaruma\HttpRouter\Interface\Enum\RequestMethodInterface;
use Hotaruma\HttpRouter\Utils\ConfigNormalizeUtils;

abstract class Config implements ConfigInterface
{
    use ConfigNormalizeUtils;

    /**
     * @var array<string,string>
     */
    protected array $rules = [];

    /**
     * @var array<string,string>
     */
    protected array $defaults = [];

    /**
     * @var array<mixed>
     */
    protected array $middlewares = [];

    /**
     * @var string
     */
    protected string $path = '/';

    /**
     * @var string
     */
    protected string $name = '';

    /**
     * @var array<RequestMethodInterface>
     */
    protected array $methods = [];

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
    public function middlewares(mixed $middlewares): ConfigConfigureInterface
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
        $methods = is_array($methods) ? $methods : [$methods];
        $this->methods = array_filter($methods, fn($method) => $method !== AdditionalMethod::NULL);

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
