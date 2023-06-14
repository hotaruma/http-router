<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Iterator;

use Hotaruma\HttpRouter\Exception\RouteIteratorInvalidArgumentException;
use Hotaruma\HttpRouter\Interface\Iterator\RouteIteratorInterface;
use Hotaruma\HttpRouter\Interface\Route\RouteInterface;

class RouteIterator implements RouteIteratorInterface
{
    /**
     * @var int
     */
    protected int $position = 0;

    /**
     * @var array<RouteInterface>
     */
    protected array $routes = [];

    /**
     * @inheritDoc
     */
    public function routes(array $routes): void
    {
        foreach ($routes as $route) {
            if (!$route instanceof RouteInterface) {
                throw new RouteIteratorInvalidArgumentException('Invalid routes. All routes must implement the RouteInterface.');
            }
        }
        $this->routes = array_values($routes);
    }

    /**
     * @inheritDoc
     */
    public function current(): ?RouteInterface
    {
        return $this->routes[$this->position] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function next(): void
    {
        ++$this->position;
    }

    /**
     * @inheritDoc
     */
    public function key(): int
    {
        return $this->position;
    }

    /**
     * @inheritDoc
     */
    public function valid(): bool
    {
        return isset($this->routes[$this->position]);
    }

    /**
     * @inheritDoc
     */
    public function rewind(): void
    {
        $this->position = 0;
    }
}
