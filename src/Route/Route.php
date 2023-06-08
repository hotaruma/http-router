<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Route;

use Hotaruma\HttpRouter\Exception\RouteInvalidArgument;
use Hotaruma\HttpRouter\Interfaces\{Route\RouteConfigureInterface, Route\RouteInterface, Method};
use Hotaruma\HttpRouter\Utils\RouteTrait;

class Route implements RouteInterface
{
    use RouteTrait;

    /**
     * @var array<Method>
     */
    protected array $methods;

    /**
     * @var string
     */
    protected string $path;

    /**
     * @var string
     */
    protected string $name = '';

    /**
     * @var array<string,string>
     */
    protected array $rules = [];

    /**
     * @var array<string,string>
     */
    protected array $defaults = [];

    /**
     * @var array
     */
    protected array $middlewares = [];

    /**
     * @var mixed
     */
    protected mixed $action;

    /**
     * @inheritDoc
     */
    public function rules(array $rules): RouteConfigureInterface
    {
        $this->rules = $rules;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function defaults(array $defaults): RouteConfigureInterface
    {
        $this->defaults = $defaults;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function middlewares(callable|array $middlewares): RouteConfigureInterface
    {
        $this->middlewares = (array)$middlewares;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function path(string $path): RouteInterface
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
    public function name(string $name): RouteInterface
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
    public function methods(Method|array $methods): RouteInterface
    {
        $methods = (array)$methods;

        foreach ($methods as $method) {
            if (!$method instanceof Method) {
                throw new RouteInvalidArgument("Invalid argument. Expected instance of Method.");
            }
        }
        $this->methods = $methods;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @inheritDoc
     */
    public function action(mixed $action): RouteInterface
    {
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
    public function getRules(): array
    {
        return $this->rules;
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
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}
