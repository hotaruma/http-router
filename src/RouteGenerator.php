<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter;

use Hotaruma\HttpRouter\Exception\{RouteGenerateNotFoundException, RouteGenerateWrongValues};
use Hotaruma\HttpRouter\Interface\{Route\RouteInterface,
    RouteGenerator\RouteGeneratorInterface,
    RouteMap\RouteMapResultInterface};

class RouteGenerator implements RouteGeneratorInterface
{
    /**
     * @var RouteMapResultInterface
     */
    protected RouteMapResultInterface $routeMapResult;

    /**
     * @param RouteMapResultInterface $routeMapResult
     * @return void
     */
    public function __construct(RouteMapResultInterface $routeMapResult)
    {
        $this->routeMap($routeMapResult);
    }

    /**
     * @inheritDoc
     */
    public function routeMap(RouteMapResultInterface $routeMapResult): RouteGeneratorInterface
    {
        $this->routeMapResult = $routeMapResult;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function generate(string $routeName, array $attributes = [], bool $strict = true): string
    {
        $routes = $this->routeMapResult->getRoutes();
        foreach ($routes as $route) {
            if ($route->getRouteConfig()->getName() === $routeName) {
                return $this->generateByRoute($route, $attributes, $strict);
            }
        }
        throw new RouteGenerateNotFoundException(sprintf('Not found route with name %s', $routeName));
    }

    /**
     * @param RouteInterface $route
     * @param array<string,string> $attributes
     * @param bool $strict
     * @return string
     *
     * @throws RouteGenerateWrongValues
     */
    protected function generateByRoute(RouteInterface $route, array $attributes = [], bool $strict = true): string
    {
        return preg_replace_callback(
            '/{([^}]+)}/',
            function ($attributeName) use ($route, $attributes, $strict) {
                $attributeName = $attributeName[1];
                $argumentValue = $attributes[$attributeName] ?? $route->getRouteConfig()->getDefaults()[$attributeName] ?? null;

                if (!isset($argumentValue)) {
                    throw new RouteGenerateWrongValues(
                        sprintf('Route %s has no value for attribute %s', $route->getRouteConfig()->getName(), $attributeName)
                    );
                }

                $argumentValue = (string)$argumentValue;
                if (
                    $strict && isset($route->getRouteConfig()->getRules()[$attributeName]) &&
                    !preg_match(sprintf("/^%s$/", $route->getRouteConfig()->getRules()[$attributeName]), $argumentValue)
                ) {
                    throw new RouteGenerateWrongValues(
                        sprintf('Route %s has wrong value for attribute %s', $route->getRouteConfig()->getName(), $attributeName)
                    );
                }

                return $argumentValue;
            },
            $route->getRouteConfig()->getPath()
        );
    }
}
