<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\RouteGenerator;

use Hotaruma\HttpRouter\Interface\RouteMap\RouteMapResultInterface;
use Hotaruma\HttpRouter\Exception\{RouteGenerateNotFoundException, RouteGenerateWrongValues};

interface RouteGeneratorInterface
{
    /**
     * Sets the route collection.
     *
     * @param RouteMapResultInterface $routeMapResult
     * @return RouteGeneratorInterface
     */
    public function routeMap(RouteMapResultInterface $routeMapResult): RouteGeneratorInterface;

    /**
     * Generate url by route name.
     *
     * @param string $routeName
     * @param array<string,string> $attributes Values for path attributes
     * @param bool $strict Check if values comply route attributes rules
     * @return string
     *
     * @throws RouteGenerateWrongValues|RouteGenerateNotFoundException
     */
    public function generate(string $routeName, array $attributes = [], bool $strict = false): string;
}
