<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interfaces\RouteMatcher;

use Hotaruma\HttpRouter\Exception\RouteNotFoundException;
use Hotaruma\HttpRouter\Interfaces\RouteMap\RouteMapResultInterface;
use Psr\Http\Message\ServerRequestInterface;

interface RouteMatcherInterface
{
    /**
     * Sets the server request.
     *
     * @param ServerRequestInterface $serverRequest
     * @return RouteMatcherInterface
     */
    public function request(ServerRequestInterface $serverRequest): RouteMatcherInterface;

    /**
     * Sets the result object.
     *
     * @param RouteMatcherResultInterface $routeMatcherResult
     * @return RouteMatcherInterface
     */
    public function routeMatcherResult(RouteMatcherResultInterface $routeMatcherResult): RouteMatcherInterface;

    /**
     * Sets the routes collection result.
     *
     * @param RouteMapResultInterface $routeMapResult
     * @return RouteMatcherInterface
     */
    public function routeMapResult(RouteMapResultInterface $routeMapResult): RouteMatcherInterface;

    /**
     * Match routes by server request.
     *
     * @return RouteMatcherResultInterface
     *
     * @throws RouteNotFoundException
     */
    public function match(): RouteMatcherResultInterface;
}
