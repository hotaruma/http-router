<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\RouteUrlGenerator;

use Hotaruma\HttpRouter\Interface\Route\RouteInterface;
use Hotaruma\HttpRouter\Interface\RouteUrlBuilder\RouteUrlBuilderInterface;
use Hotaruma\HttpRouter\Exception\{PatternRegistryPatternNotFoundException,
    RouteUrlGeneratorInvalidArgumentException,
    RouteUrlGeneratorNotFoundException,
    RouteUrlBuilderWrongValuesException};

interface RouteUrlGeneratorInterface
{
    /**
     * @param array<RouteInterface>|null $routes Routes
     */
    public function config(array $routes = null): void;

    /**
     * Finds a route by name and fills in its path.
     *
     * @param string $routeName
     * @return RouteInterface
     *
     * @throws RouteUrlGeneratorInvalidArgumentException|RouteUrlGeneratorNotFoundException|RouteUrlBuilderWrongValuesException|PatternRegistryPatternNotFoundException
     */
    public function generateByName(string $routeName): RouteInterface;

    /**
     * Set route url builder.
     *
     * @param RouteUrlBuilderInterface $routeUrlBuilder
     * @return void
     */
    public function routeUrlBuilder(RouteUrlBuilderInterface $routeUrlBuilder): void;
}
