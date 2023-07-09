<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter;

use Hotaruma\HttpRouter\Enum\AdditionalMethod;
use Hotaruma\HttpRouter\Exception\RouteDispatcherNotFoundException;
use Hotaruma\HttpRouter\Interface\{
    Enum\RequestMethodInterface,
    PatternRegistry\PatternRegistryInterface,
    Route\RouteInterface,
    RouteDispatcher\RouteDispatcherInterface,
    RouteMatcher\RouteMatcherInterface
};
use Hotaruma\HttpRouter\PatternRegistry\PatternRegistryCase;
use Hotaruma\HttpRouter\RouteMatcher\RouteMatcher;
use Hotaruma\HttpRouter\Utils\ConfigNormalizeUtils;

class RouteDispatcher implements RouteDispatcherInterface
{
    use ConfigNormalizeUtils;
    use PatternRegistryCase;

    /**
     * @var RequestMethodInterface Http method
     */
    protected RequestMethodInterface $requestHttpMethod = AdditionalMethod::ANY;

    /**
     * @var string Uri path
     */
    protected string $requestPath = '';

    /**
     * @var array<RouteInterface> Routes
     */
    protected array $routes;

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
        array                    $routes = null,
        PatternRegistryInterface $patternRegistry = null
    ): void {
        isset($requestHttpMethod) and $this->requestHttpMethod($requestHttpMethod);
        isset($requestPath) and $this->requestPath($requestPath);
        isset($routes) and $this->routes($routes);
        isset($patternRegistry) and $this->patternRegistry($patternRegistry);
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
        $this->getRouteMatcher()->patternRegistry($this->getPatternRegistry());

        $routes = [];
        foreach ($this->getRoutes() as $route) {
            if ($this->getRouteMatcher()->matchRouteByHttpMethod($route, $this->getRequestHttpMethod())) {
                $routes[] = $route;
            }
        }

        if (!empty($routes)) {
            $route = $this->getRouteMatcher()->matchRouteByRegex($routes, $this->getRequestPath());
            if (!empty($route)) {
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
     * @param array<RouteInterface> $routes
     * @return void
     */
    protected function routes(array $routes): void
    {
        $this->routes = $routes;
    }

    /**
     * @return array<RouteInterface>
     */
    protected function getRoutes(): array
    {
        return $this->routes ??= [];
    }

    /**
     * @return RouteMatcherInterface
     */
    protected function getRouteMatcher(): RouteMatcherInterface
    {
        return $this->routeMatcher ??= new RouteMatcher();
    }
}
