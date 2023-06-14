<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\RouteUrlBuilder;

use Hotaruma\HttpRouter\Exception\RouteUrlBuilderWrongValuesException;
use Hotaruma\HttpRouter\Interface\Route\RouteInterface;

interface RouteUrlBuilderInterface
{
    /**
     * Builds and fills the url for the route.
     *
     * @param RouteInterface $route
     * @return RouteInterface
     *
     * @throws RouteUrlBuilderWrongValuesException
     */
    public function build(RouteInterface $route): RouteInterface;
}
