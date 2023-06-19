<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter;

use Hotaruma\HttpRouter\Collection\RouteCollection;
use Hotaruma\HttpRouter\RouteUrlBuilder\RouteUrlBuilder;
use Hotaruma\HttpRouter\Exception\{RouteUrlGeneratorInvalidArgumentException,
    RouteUrlGeneratorNotFoundException,
};
use Hotaruma\HttpRouter\Interface\{Collection\RouteCollectionInterface,
    Route\RouteInterface,
    RouteUrlBuilder\RouteUrlBuilderInterface,
    RouteUrlGenerator\RouteUrlGeneratorInterface
};

class RouteUrlGenerator implements RouteUrlGeneratorInterface
{
    /**
     * @var RouteCollectionInterface Route collection
     */
    protected RouteCollectionInterface $routesCollection;

    /**
     * @param RouteUrlBuilderInterface $routeUrlBuilder
     */
    public function __construct(
        protected RouteUrlBuilderInterface $routeUrlBuilder = new RouteUrlBuilder()
    ) {
        $this->routesCollection(new RouteCollection());
    }

    /**
     * @inheritDoc
     */
    public function config(
        RouteCollectionInterface $routes = null
    ): void {
        isset($routes) and $this->routesCollection($routes);
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
            if ($route->getRouteConfig()->getName() === $routeName) {
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
     */
    protected function routesCollection(RouteCollectionInterface $routeCollection): void
    {
        $this->routesCollection = $routeCollection;
    }

    /**
     * @return RouteCollectionInterface
     */
    protected function getRoutesCollection(): RouteCollectionInterface
    {
        return $this->routesCollection;
    }

    /**
     * @return RouteUrlBuilderInterface
     */
    protected function getRouteUrlBuilder(): RouteUrlBuilderInterface
    {
        return $this->routeUrlBuilder;
    }
}
