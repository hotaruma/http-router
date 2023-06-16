<?php

declare(strict_types=1);

namespace Tests\Integration;

use Hotaruma\HttpRouter\Exception\RouteUrlGeneratorNotFoundException;
use Hotaruma\HttpRouter\RouteMap;
use Hotaruma\HttpRouter\RouteUrlGenerator;
use PHPUnit\Framework\TestCase;
use stdClass;

class RouteUrlGeneratorTest extends TestCase
{
    public function testGenerateByName(): void
    {
        $routeUrlGenerator = new RouteUrlGenerator();
        $routeMap = new RouteMap();

        $routeMap->get('/get/{id}/', stdClass::class)->config(
            name: 'get', defaults: ['id' => '1']
        );

        $routeUrlGenerator->config(
            routes: $routeMap->getRoutes(),
        );
        $route = $routeUrlGenerator->generateByName('get');
        $this->assertEquals('/get/1/', $route->getUrl());
    }

    public function testInvalidGenerateByName(): void
    {
        $routeUrlGenerator = new RouteUrlGenerator();
        $routeMap = new RouteMap();

        $routeMap->get('/get/', stdClass::class);

        $routeUrlGenerator->config(
            routes: $routeMap->getRoutes(),
        );
        $this->expectException(RouteUrlGeneratorNotFoundException::class);
        $routeUrlGenerator->generateByName('get');

        $this->expectException(RouteUrlGeneratorNotFoundException::class);
        $routeUrlGenerator->generateByName('');
    }
}
