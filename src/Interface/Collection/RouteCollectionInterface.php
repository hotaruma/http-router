<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\Collection;

use Countable;
use Hotaruma\HttpRouter\Exception\RouteCollectionInvalidArgumentException;
use Hotaruma\HttpRouter\Interface\Iterator\RouteIteratorInterface;
use Hotaruma\HttpRouter\Interface\Route\RouteInterface;
use IteratorAggregate;

/**
 * @template-covariant TItems of RouteInterface
 * @template-covariant TIterator of RouteIteratorInterface<int, TItems>
 *
 * @extends IteratorAggregate<int, TItems>
 */
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
     * @param class-string<TIterator<int, RouteInterface>> $class
     * @return void
     *
     * @throws RouteCollectionInvalidArgumentException
     */
    public function iterator(string $class): void;

    /**
     * @return RouteIteratorInterface
     *
     * @phpstan-return TIterator<int, RouteInterface>
     */
    public function getIterator(): RouteIteratorInterface;
}
