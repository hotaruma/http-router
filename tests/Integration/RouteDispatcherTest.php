<?php

declare(strict_types=1);

namespace Tests\Integration;

use Hotaruma\HttpRouter\{Enum\AdditionalMethod,
    Enum\HttpMethod,
    Exception\RouteDispatcherNotFoundException,
    RouteDispatcher,
    RouteMap};
use PHPUnit\Framework\TestCase;
use stdClass;

class RouteDispatcherTest extends TestCase
{
    public function testMatch(): void
    {
        $routeDispatcher = new RouteDispatcher();
        $routeMap = new RouteMap();

        $routeMap->get('/get/{id}/', stdClass::class);
        $routeMap->post('/post/{id}/', stdClass::class);
        $routeMap->connect('/connect/{id}/', stdClass::class);
        $routeMap->delete('/delete/', stdClass::class);

        $routeDispatcher->config(
            requestHttpMethod: HttpMethod::GET,
            requestPath: '/get/1',
            routes: $routeMap->getRoutes(),
        );
        $route = $routeDispatcher->match();
        $this->assertEquals(['id' => '1'], $route->getAttributes());

        $routeDispatcher->config(
            requestHttpMethod: AdditionalMethod::ANY,
            requestPath: '/connect/1',
        );
        $route = $routeDispatcher->match();
        $this->assertEquals(['id' => '1'], $route->getAttributes());

        $routeDispatcher->config(
            requestHttpMethod: HttpMethod::DELETE,
            requestPath: '/delete/',
        );
        $route = $routeDispatcher->match();
        $this->assertEquals([], $route->getAttributes());
    }

    public function testInvalidMatch(): void
    {
        $routeDispatcher = new RouteDispatcher();
        $routeMap = new RouteMap();

        $routeMap->get('/get/{id}/', stdClass::class);

        $routeDispatcher->config(
            requestHttpMethod: HttpMethod::HEAD,
            requestPath: '/head/',
            routes: $routeMap->getRoutes(),
        );
        $this->expectException(RouteDispatcherNotFoundException::class);
        $routeDispatcher->match();
    }
}
