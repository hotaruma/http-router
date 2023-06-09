<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\RouteDispatcher;

use Hotaruma\HttpRouter\Exception\RouteDispatcherNotFoundException;
use Hotaruma\HttpRouter\Exception\RouteMatcherInvalidArgumentException;
use Hotaruma\HttpRouter\Exception\RouteMatcherRuntimeException;
use Hotaruma\HttpRouter\Interface\Enum\RequestMethodInterface;
use Hotaruma\HttpRouter\Interface\Route\RouteInterface;
use Hotaruma\HttpRouter\Interface\RouteMatcher\RouteMatcherInterface;

interface RouteDispatcherInterface
{
    /**
     * @param RequestMethodInterface|null $requestHttpMethod Http method
     * @param string|null $requestPath Uri path
     * @param array<RouteInterface>|null $routes Routes
     */
    public function config(
        RequestMethodInterface $requestHttpMethod = null,
        string                 $requestPath = null,
        array                  $routes = null
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
     * @throws RouteDispatcherNotFoundException|RouteMatcherRuntimeException|RouteMatcherInvalidArgumentException
     */
    public function match(): RouteInterface;
}
