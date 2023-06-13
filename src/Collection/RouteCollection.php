<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Collection;

use Hotaruma\HttpRouter\Exception\RouteCollectionInvalidArgument;
use Hotaruma\HttpRouter\Interface\Collection\RouteCollectionInterface;
use Hotaruma\HttpRouter\Interface\Iterator\RouteIteratorInterface;
use Hotaruma\HttpRouter\Interface\Route\RouteInterface;
use Hotaruma\HttpRouter\Iterator\RouteIterator;

class RouteCollection implements RouteCollectionInterface
{
    /**
     * @var array<RouteInterface>
     */
    protected array $routes;

    /**
     * @var string Iterator class
     */
    protected string $iterator = RouteIterator::class;

    /**
     * @inheritDoc
     */
    public function iterator(string $class): void
    {
        if (!is_subclass_of($this->iterator, RouteIteratorInterface::class)) {
            throw new RouteCollectionInvalidArgument('Invalid iterator type. Must be a subclass of RouteIteratorInterface.');
        }

        $this->iterator = $class;
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): RouteIteratorInterface
    {
        $iterator = $this->createIterator();
        $iterator->routes($this->routes);

        return $iterator;
    }

    /**
     * @inheritDoc
     */
    public function add(RouteInterface $route): void
    {
        $name = $route->getRouteConfig()->getName();
        $this->routes[$name ?: count($this->routes)] = $route;
    }

    /**
     * @inheritDoc
     */
    public function get(int|string $index): ?RouteInterface
    {
        return $this->routes[$index] ?? null;
    }

    /**
     * Create new iterator.
     *
     * @return RouteIteratorInterface
     */
    protected function createIterator(): RouteIteratorInterface
    {
        return new $this->iterator;
    }
}
