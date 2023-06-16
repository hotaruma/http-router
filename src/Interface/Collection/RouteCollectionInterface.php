<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\Collection;

use Countable;
use Hotaruma\HttpRouter\Exception\RouteCollectionInvalidArgumentException;
use Hotaruma\HttpRouter\Interface\Iterator\RouteIteratorInterface;
use Hotaruma\HttpRouter\Interface\Route\RouteInterface;
use IteratorAggregate;

interface RouteCollectionInterface extends IteratorAggregate, Countable
{
    /**
     * Add route.
     *
     * @param RouteInterface $route
     * @return void
     */
    public function add(RouteInterface $route): void;

    /**
     * Remove route.
     *
     * @param RouteInterface $route
     * @return void
     */
    public function unset(RouteInterface $route): void;

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
