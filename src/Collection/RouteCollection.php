<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Collection;

use Hotaruma\HttpRouter\Exception\RouteCollectionInvalidArgumentException;
use Hotaruma\HttpRouter\Interface\Collection\RouteCollectionInterface;
use Hotaruma\HttpRouter\Interface\Iterator\RouteIteratorInterface;
use Hotaruma\HttpRouter\Interface\Route\RouteInterface;
use Hotaruma\HttpRouter\Iterator\RouteIterator;
use SplObjectStorage;

class RouteCollection implements RouteCollectionInterface
{
    /**
     * @var string Iterator class
     */
    protected string $iterator = RouteIterator::class;

    public function __construct(
        protected SplObjectStorage $routes = new SplObjectStorage()
    ) {
    }

    /**
     * @inheritDoc
     */
    public function iterator(string $class): void
    {
        if (!is_subclass_of($class, RouteIteratorInterface::class)) {
            throw new RouteCollectionInvalidArgumentException(
                sprintf('Invalid iterator type. %s must be a subclass of RouteIteratorInterface.', $class)
            );
        }

        $this->iterator = $class;
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): RouteIteratorInterface
    {
        $iterator = $this->createIterator();
        $iterator->routes($this->getRoutes());

        return $iterator;
    }

    /**
     * @inheritDoc
     */
    public function add(RouteInterface $route): void
    {
        if ($this->getRoutes()->contains($route)) {
            throw new RouteCollectionInvalidArgumentException('Route already exists in the collection.');
        }
        $this->getRoutes()->attach($route);
    }

    /**
     * @inheritDoc
     */
    public function unset(RouteInterface $route): void
    {
        $this->getRoutes()->detach($route);
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return $this->getRoutes()->count();
    }

    /**
     * Create new iterator.
     *
     * @return RouteIteratorInterface
     */
    protected function createIterator(): RouteIteratorInterface
    {
        return new $this->iterator();
    }

    /**
     * @return SplObjectStorage
     */
    protected function getRoutes(): SplObjectStorage
    {
        return $this->routes;
    }
}
