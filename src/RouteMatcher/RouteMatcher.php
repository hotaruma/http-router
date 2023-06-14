<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\RouteMatcher;

use Hotaruma\HttpRouter\Enum\AdditionalMethod;
use Hotaruma\HttpRouter\Interface\Enum\RequestMethodInterface;
use Hotaruma\HttpRouter\Interface\Route\RouteInterface;
use Hotaruma\HttpRouter\Interface\RouteMatcher\RouteMatcherInterface;
use Hotaruma\HttpRouter\Utils\ConfigNormalizeUtils;

class RouteMatcher implements RouteMatcherInterface
{
    use ConfigNormalizeUtils;

    /**
     * @inheritDoc
     */
    public function matchRouteByHttpMethod(RouteInterface $route, RequestMethodInterface $method): bool
    {
        $routeHttpMethods = $route->getRouteConfig()->getMethods();
        return in_array($method, $routeHttpMethods) || in_array(AdditionalMethod::ANY, $routeHttpMethods);
    }

    /**
     * @inheritDoc
     */
    public function matchRouteByRegex(RouteInterface $route, string $path): ?array
    {
        if (preg_match($this->generatePattern($route), $this->normalizePath($path), $matches)) {
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
        $routePath = preg_quote($route->getRouteConfig()->getPath(), '/');
        $routePath = str_replace(['\{', '\}'], ['{', '}'], $routePath);

        $pattern = preg_replace_callback(
            '/{([^{}]+)}/',
            function ($attributeName) use ($route) {
                $attributeName = $attributeName[1];
                return sprintf(
                    '(?P<%s>%s)',
                    $attributeName,
                    ($route->getRouteConfig()->getRules()[$attributeName] ?? '[^}]+')
                );
            },
            $routePath
        );

        return sprintf("/^%s$/", $pattern);
    }
}