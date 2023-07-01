<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\Collection;

use Countable;
use Hotaruma\HttpRouter\Exception\RouteCollectionInvalidArgumentException;
use Hotaruma\HttpRouter\Interface\Iterator\RouteIteratorInterface;
use Hotaruma\HttpRouter\Interface\Route\RouteInterface;
use IteratorAggregate;

/**
 * @template TItems of RouteInterface
 * @template TIterator of RouteIteratorInterface<mixed, TItems>
 *
 * @extends IteratorAggregate<mixed, TItems>
 */
interface RouteCollectionInterface extends IteratorAggregate, Countable
{
    /**
     * Add route.
     *
     * @param RouteInterface $route
     * @return void
     *
     * @phpstan-param TItems $route
     */
    public function add(RouteInterface $route): void;

    /**
     * Remove route.
     *
     * @param RouteInterface $route
     * @return void
     *
     * @phpstan-param TItems $route
     */
    public function unset(RouteInterface $route): void;

    /**
     * Set routes iterator.
     *
     * @param class-string<TIterator> $class
     * @return void
     *
     * @throws RouteCollectionInvalidArgumentException
     */
    public function iterator(string $class): void;

    /**
     * @return RouteIteratorInterface
     *
     * @phpstan-return TIterator
     */
    public function getIterator(): RouteIteratorInterface;
}
