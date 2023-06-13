<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\Iterator;

use Hotaruma\HttpRouter\Interface\Route\RouteInterface;
use Iterator;

interface RouteIteratorInterface extends Iterator
{
    /**
     * @param array<RouteInterface> $routes
     * @return void
     */
    public function routes(array $routes): void;

    /**
     * @return RouteInterface|null
     */
    public function current(): ?RouteInterface;
}
