<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interfaces\Route;

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
}
