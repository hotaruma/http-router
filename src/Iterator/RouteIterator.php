<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Iterator;

use Hotaruma\HttpRouter\Exception\RouteIteratorInvalidArgumentException;
use Hotaruma\HttpRouter\Interface\Iterator\RouteIteratorInterface;
use Hotaruma\HttpRouter\Interface\Route\RouteInterface;
use Iterator;

/**
 * @template TKey of int
 * @template-covariant TItem of RouteInterface
 *
 * @implements RouteIteratorInterface<TKey, TItem>
 */
class RouteIterator implements RouteIteratorInterface
{
    /**
     * @var int
     * @phpstan-var TKey
     */
    protected int $position = 0;

    /**
     * @var array<TKey, RouteInterface>
     */
    protected array $routes = [];

    /**
     * @inheritDoc
     */
    public function routes(Iterator $routes): void
    {
        $routes->rewind();
        foreach ($routes as $route) {
            if (!$route instanceof RouteInterface) {
                throw new RouteIteratorInvalidArgumentException(
                    'Invalid routes. All routes must implement the RouteInterface.'
                );
            }
            $this->routes[] = $route;
        }
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
     * @param int $rewindPosition
     * @phpstan-param TKey $rewindPosition
     */
    public function rewind(int $rewindPosition = 0): void
    {
        $this->position = $rewindPosition;
    }
}
