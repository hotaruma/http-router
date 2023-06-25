<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\Iterator;

use Hotaruma\HttpRouter\Interface\Route\RouteInterface;
use Iterator;

/**
 * @template-covariant TKey
 * @template-covariant TItem of RouteInterface
 *
 * @extends Iterator<TKey, TItem>
 */
interface RouteIteratorInterface extends Iterator
{
    /**
     * @param Iterator<mixed, RouteInterface> $routes
     * @return void
     */
    public function routes(Iterator $routes): void;

    /**
     * @return RouteInterface|null
     */
    public function current(): ?RouteInterface;
}
