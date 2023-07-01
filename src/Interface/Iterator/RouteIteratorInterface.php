<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\Iterator;

use Hotaruma\HttpRouter\Exception\RouteIteratorOutOfRangeException;
use Hotaruma\HttpRouter\Interface\Route\RouteInterface;
use Iterator;

/**
 * @template TKey
 * @template TItem of RouteInterface
 *
 * @extends Iterator<TKey, TItem>
 */
interface RouteIteratorInterface extends Iterator
{
    /**
     * @param Iterator<TKey, TItem> $routes
     * @return void
     */
    public function routes(Iterator $routes): void;

    /**
     * @return TItem
     *
     * @throws RouteIteratorOutOfRangeException
     */
    public function current(): RouteInterface;
}
