<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Route;

use ArrayIterator;
use Hotaruma\HttpRouter\Interfaces\Route\RouteCollectionInterface;
use Hotaruma\HttpRouter\Interfaces\Route\RouteInterface;
use Traversable;

class RouteCollection implements RouteCollectionInterface
{
    /**
     * @var array<RouteInterface>
     */
    protected array $routes;

    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->routes);
    }

    /**
     * @inheritDoc
     */
    public function add(RouteInterface $route): void
    {
        $this->routes[] = $route;
    }
}
