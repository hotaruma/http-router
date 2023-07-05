<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter;

use Hotaruma\HttpRouter\Collection\RouteCollection;
use Hotaruma\HttpRouter\PatternRegistry\PatternRegistryCase;
use Hotaruma\HttpRouter\RouteUrlBuilder\RouteUrlBuilder;
use Hotaruma\HttpRouter\Exception\{RouteUrlGeneratorInvalidArgumentException,
    RouteUrlGeneratorNotFoundException,
};
use Hotaruma\HttpRouter\Interface\{Collection\RouteCollectionInterface,
    PatternRegistry\PatternRegistryInterface,
    Route\RouteInterface,
    RouteUrlBuilder\RouteUrlBuilderInterface,
    RouteUrlGenerator\RouteUrlGeneratorInterface
};

class RouteUrlGenerator implements RouteUrlGeneratorInterface
{
    use PatternRegistryCase;

    /**
     * @var RouteCollectionInterface Route collection
     *
     * @phpstan-var TA_RouteCollection
     */
    protected RouteCollectionInterface $routesCollection;

    /**
     * @var RouteUrlBuilderInterface
     */
    protected RouteUrlBuilderInterface $routeUrlBuilder;

    /**
     * @inheritDoc
     */
    public function config(
        RouteCollectionInterface $routes = null,
        PatternRegistryInterface $patternRegistry = null
    ): void {
        isset($routes) and $this->routesCollection($routes);
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

        foreach ($this->getRoutesCollection() as $route) {
            if ($route->getConfigStore()->getName() === $routeName) {
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
     * @return RouteUrlBuilderInterface
     */
    protected function getRouteUrlBuilder(): RouteUrlBuilderInterface
    {
        return $this->routeUrlBuilder ??= new RouteUrlBuilder();
    }
}
