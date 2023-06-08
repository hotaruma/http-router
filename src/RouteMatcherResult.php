<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter;

use Hotaruma\HttpRouter\Interfaces\RouteMatcher\RouteMatcherResultInterface;

class RouteMatcherResult implements RouteMatcherResultInterface
{
    /**
     * @var array<string,string>
     */
    protected array $attributes;

    /**
     * @var mixed
     */
    protected mixed $action;

    /**
     * @var array
     */
    protected array $middlewares;

    /**
     * @inheritDoc
     */
    public function result(array $attributes, mixed $action, array $middlewares): void
    {
        $this->attributes = $attributes;
        $this->action = $action;
        $this->middlewares = $middlewares;
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
    public function getAction(): mixed
    {
        return $this->action;
    }

    /**
     * @inheritDoc
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}
