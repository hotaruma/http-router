<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\Collection;

use Hotaruma\HttpRouter\Exception\RouteCollectionInvalidArgumentException;
use Hotaruma\HttpRouter\Interface\Iterator\RouteIteratorInterface;
use Hotaruma\HttpRouter\Interface\Route\RouteInterface;
use IteratorAggregate;

interface RouteCollectionInterface extends IteratorAggregate
{
    /**
     * Add route.
     *
     * @param RouteInterface $route
     * @return void
     */
    public function add(RouteInterface $route): void;

    /**
     * Get route.
     *
     * @param int|string $index
     * @return RouteInterface|null
     */
    public function get(int|string $index): ?RouteInterface;

    /**
     * Set routes iterator.
     *
     * @param string $class
     * @return void
     *
     * @throws RouteCollectionInvalidArgumentException
     */
    public function iterator(string $class): void;

    /**
     * @return RouteIteratorInterface
     */
    public function getIterator(): RouteIteratorInterface;
}
