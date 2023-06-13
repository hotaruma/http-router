<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\RouteDispatcher;

use Hotaruma\HttpRouter\Exception\RouteDispatcherNotFoundException;
use Hotaruma\HttpRouter\Interface\Collection\RouteCollectionInterface;
use Hotaruma\HttpRouter\Interface\Enum\Method;
use Hotaruma\HttpRouter\Interface\Route\RouteInterface;
use Hotaruma\HttpRouter\Interface\RouteMatcher\RouteMatcherInterface;

interface RouteDispatcherInterface
{
    /**
     * @param Method|null $requestHttpMethod Http method
     * @param string|null $requestPath Uri path
     * @param RouteCollectionInterface|null $routes Route collection for matching
     */
    public function config(
        Method                   $requestHttpMethod = null,
        string                   $requestPath = null,
        RouteCollectionInterface $routes = null
    ): void;

    /**
     * Set route matcher.
     *
     * @param RouteMatcherInterface $routeMatcher
     * @return void
     */
    public function routeMatcher(RouteMatcherInterface $routeMatcher): void;

    /**
     * Match routes by config.
     *
     * @return RouteInterface
     *
     * @throws RouteDispatcherNotFoundException
     */
    public function match(): RouteInterface;
}
