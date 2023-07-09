<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter;

use Hotaruma\HttpRouter\PatternRegistry\PatternRegistryCase;
use Hotaruma\HttpRouter\RouteUrlBuilder\RouteUrlBuilder;
use Hotaruma\HttpRouter\Exception\{RouteUrlGeneratorInvalidArgumentException,
    RouteUrlGeneratorNotFoundException,
};
use Hotaruma\HttpRouter\Interface\{PatternRegistry\PatternRegistryInterface,
    Route\RouteInterface,
    RouteUrlBuilder\RouteUrlBuilderInterface,
    RouteUrlGenerator\RouteUrlGeneratorInterface
};

class RouteUrlGenerator implements RouteUrlGeneratorInterface
{
    use PatternRegistryCase;

    /**
     * @var array<RouteInterface> Routes
     */
    protected array $routesCollection;

    /**
     * @var RouteUrlBuilderInterface
     */
    protected RouteUrlBuilderInterface $routeUrlBuilder;

    /**
     * @inheritDoc
     */
    public function config(
        array                    $routes = null,
        PatternRegistryInterface $patternRegistry = null
    ): void {
        isset($routes) and $this->routes($routes);
        isset($patternRegistry) and $this->patternRegistry($patternRegistry);
    }

    /**
     * @inheritDoc
     */
    public function generateByName(string $routeName): RouteInterface
    {
        if (empty($routeName)) {
            throw new RouteUrlGeneratorInvalidArgumentException('Route name must not be empty');
        }

        foreach ($this->getRoutes() as $route) {
            if ($route->getConfigStore()->getConfig()->getName() === $routeName) {
                $this->getRouteUrlBuilder()->patternRegistry($this->getPatternRegistry());
                return $this->getRouteUrlBuilder()->build($route);
            }
        }

        throw new RouteUrlGeneratorNotFoundException(sprintf('Route with name "%s" not found', $routeName));
    }

    /**
     * @inheritDoc
     */
    public function routeUrlBuilder(RouteUrlBuilderInterface $routeUrlBuilder): void
    {
        $this->routeUrlBuilder = $routeUrlBuilder;
    }

    /**
     * @param array<RouteInterface> $routeCollection
     * @return void
     */
    protected function routes(array $routeCollection): void
    {
        $this->routesCollection = $routeCollection;
    }

    /**
     * @return array<RouteInterface>
     */
    protected function getRoutes(): array
    {
        return $this->routesCollection ??= [];
    }

    /**
     * @return RouteUrlBuilderInterface
     */
    protected function getRouteUrlBuilder(): RouteUrlBuilderInterface
    {
        return $this->routeUrlBuilder ??= new RouteUrlBuilder();
    }
}
