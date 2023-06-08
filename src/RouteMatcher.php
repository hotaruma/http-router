<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter;

use Hotaruma\HttpRouter\Exception\RouteNotFoundException;
use Hotaruma\HttpRouter\Interfaces\{Method,
    Route\RouteInterface,
    RouteMap\RouteMapResultInterface,
    RouteMatcher\RouteMatcherInterface,
    RouteMatcher\RouteMatcherResultInterface};
use Hotaruma\HttpRouter\Utils\RouteTrait;
use Psr\Http\Message\ServerRequestInterface;

class RouteMatcher implements RouteMatcherInterface
{
    use RouteTrait;

    /**
     * @var RouteMapResultInterface
     */
    protected RouteMapResultInterface $routeMapResult;

    /**
     * @var RouteMatcherResultInterface
     */
    protected RouteMatcherResultInterface $routeMatcherResult;

    /**
     * @var ServerRequestInterface
     */
    protected ServerRequestInterface $serverRequest;

    /**
     * @param RouteMatcherResultInterface $routeMatcherResult
     * @param RouteMapResultInterface $routeMapResult
     * @param ServerRequestInterface $serverRequest
     */
    public function __construct(
        RouteMatcherResultInterface $routeMatcherResult,
        RouteMapResultInterface $routeMapResult,
        ServerRequestInterface $serverRequest
    ) {
        $this->routeMatcherResult($routeMatcherResult);
        $this->routeMapResult($routeMapResult);
        $this->request($serverRequest);
    }

    /**
     * @inheritDoc
     */
    public function request(ServerRequestInterface $serverRequest): RouteMatcherInterface
    {
        $this->serverRequest = $serverRequest;
        return $this;
    }


    /**
     * @inheritDoc
     */
    public function routeMatcherResult(RouteMatcherResultInterface $routeMatcherResult): RouteMatcherInterface
    {
        $this->routeMatcherResult = $routeMatcherResult;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function routeMapResult(RouteMapResultInterface $routeMapResult): RouteMatcherInterface
    {
        $this->routeMapResult = $routeMapResult;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function match(): RouteMatcherResultInterface
    {
        $requestHttpMethod = $this->serverRequest->getMethod();
        $requestPath = $this->serverRequest->getUri()->getPath();
        $routes = $this->routeMapResult->getRoutes();

        foreach ($routes as $route) {
            if (
                $this->mathRouteByHttpMethod($route, $requestHttpMethod) &&
                ($attributes = $this->mathRouteByRegex($route, $requestPath)) !== null
            ) {
                $this->routeMatcherResult->result($attributes, $route->getAction(), $route->getMiddlewares());
                return $this->routeMatcherResult;
            }
        }

        throw new RouteNotFoundException("Suitable route not found");
    }

    /**
     * Check is route fits the request http method.
     *
     * @param RouteInterface $route
     * @param string $requestHttpMethod
     * @return bool
     */
    protected function mathRouteByHttpMethod(RouteInterface $route, string $requestHttpMethod): bool
    {
        $routeHttpMethods = $route->getMethods();
        return in_array($requestHttpMethod, $routeHttpMethods) || in_array(Method::ANY, $routeHttpMethods);
    }

    /**
     * @param RouteInterface $route
     * @param string $requestPath
     * @return array<string, string>|null Route attributes
     */
    protected function mathRouteByRegex(RouteInterface $route, string $requestPath): ?array
    {
        if (preg_match($this->generatePattern($route), $this->normalizePath($requestPath), $matches)) {
            return array_filter($matches, '\is_string', ARRAY_FILTER_USE_KEY);
        }
        return null;
    }

    /**
     * @param RouteInterface $route
     * @return string Regex pattern for route matching
     */
    protected function generatePattern(RouteInterface $route): string
    {
        $routePath = preg_quote($route->getPath(), '/');
        $routePath = str_replace(['\{', '\}'], ['{', '}'], $routePath);

        $pattern = preg_replace_callback(
            '/{([^{}]+)}/',
            function ($attributeName) use ($route) {
                $attributeName = $attributeName[1];
                return sprintf(
                    '(?P<%s>%s)',
                    $attributeName,
                    ($route->getRules()[$attributeName] ?? '[^}]+')
                );
            },
            $routePath
        );

        return sprintf("/^%s$/", $pattern);
    }
}
