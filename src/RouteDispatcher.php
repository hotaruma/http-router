<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter;

use Hotaruma\HttpRouter\Collection\RouteCollection;
use Hotaruma\HttpRouter\Enum\AdditionalMethod;
use Hotaruma\HttpRouter\Exception\RouteDispatcherNotFoundException;
use Hotaruma\HttpRouter\Interface\{Collection\RouteCollectionInterface,
    Enum\RequestMethodInterface,
    Route\RouteInterface,
    RouteDispatcher\RouteDispatcherInterface,
    RouteMatcher\RouteMatcherInterface
};
use Hotaruma\HttpRouter\RouteMatcher\RouteMatcher;
use Hotaruma\HttpRouter\Utils\ConfigNormalizeUtils;

class RouteDispatcher implements RouteDispatcherInterface
{
    use ConfigNormalizeUtils;

    /**
     * @var RequestMethodInterface Http method
     */
    protected RequestMethodInterface $requestHttpMethod = AdditionalMethod::ANY;

    /**
     * @var string Uri path
     */
    protected string $requestPath = '';

    /**
     * @var RouteCollectionInterface Route collection for matching
     *
     * @phpstan-var TA_RouteCollection
     */
    protected RouteCollectionInterface $routesCollection;

    /**
     * @var RouteMatcherInterface
     */
    protected RouteMatcherInterface $routeMatcher;

    /**
     * @inheritDoc
     */
    public function config(
        RequestMethodInterface   $requestHttpMethod = null,
        string                   $requestPath = null,
        RouteCollectionInterface $routes = null
    ): void {
        isset($requestHttpMethod) and $this->requestHttpMethod($requestHttpMethod);
        isset($requestPath) and $this->requestPath($requestPath);
        isset($routes) and $this->routesCollection($routes);
    }

    /**
     * @inheritDoc
     */
    public function routeMatcher(RouteMatcherInterface $routeMatcher): void
    {
        $this->routeMatcher = $routeMatcher;
    }

    /**
     * @inheritDoc
     */
    public function match(): RouteInterface
    {
        foreach ($this->getRoutesCollection() as $route) {
            if (
                $this->getRouteMatcher()->matchRouteByHttpMethod($route, $this->getRequestHttpMethod()) &&
                ($attributes = $this->getRouteMatcher()->matchRouteByRegex($route, $this->getRequestPath())) !== null
            ) {
                $route->attributes($attributes);
                return $route;
            }
        }

        throw new RouteDispatcherNotFoundException('No matching route found for the current request.');
    }

    /**
     * @param RequestMethodInterface $requestHttpMethod
     * @return void
     */
    protected function requestHttpMethod(RequestMethodInterface $requestHttpMethod): void
    {
        $this->requestHttpMethod = $requestHttpMethod;
    }

    /**
     * @return RequestMethodInterface
     */
    protected function getRequestHttpMethod(): RequestMethodInterface
    {
        return $this->requestHttpMethod;
    }

    /**
     * @param string $path
     * @return void
     */
    protected function requestPath(string $path): void
    {
        $this->requestPath = $this->normalizePath($path);
    }

    /**
     * @return string
     */
    protected function getRequestPath(): string
    {
        return $this->requestPath;
    }

    /**
     * @param RouteCollectionInterface $routeCollection
     * @return void
     *
     * @phpstan-param TA_RouteCollection $routeCollection
     */
    protected function routesCollection(RouteCollectionInterface $routeCollection): void
    {
        $this->routesCollection = $routeCollection;
    }

    /**
     * @return RouteCollectionInterface
     *
     * @phpstan-return TA_RouteCollection
     */
    protected function getRoutesCollection(): RouteCollectionInterface
    {
        return $this->routesCollection ??= new RouteCollection();
    }

    /**
     * @return RouteMatcherInterface
     */
    protected function getRouteMatcher(): RouteMatcherInterface
    {
        return $this->routeMatcher ??= new RouteMatcher();
    }
}
