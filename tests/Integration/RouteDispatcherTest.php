<?php

declare(strict_types=1);

namespace Hotaruma\Tests\Integration;

use Hotaruma\HttpRouter\Enum\{AdditionalMethod, HttpMethod};
use Hotaruma\HttpRouter\Exception\RouteDispatcherNotFoundException;
use Hotaruma\HttpRouter\Interface\PatternRegistry\PatternRegistryInterface;
use Hotaruma\HttpRouter\PatternRegistry\PatternRegistry;
use Hotaruma\HttpRouter\RouteDispatcher;
use Hotaruma\HttpRouter\RouteMap;
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

    public function testPatternRegistry(): void
    {
        $routeDispatcher = new RouteDispatcher();
        $patternRegistry = new PatternRegistry();
        $routeMap = new RouteMap();

        $routeMap->get('/get/{id:custom}/', stdClass::class);

        $patternRegistry->addPattern('custom', static function (string $value, PatternRegistryInterface $patternRegistry): bool {
            /* @phpstan-ignore-next-line */
            return !!preg_match(sprintf('#^%s$#', $patternRegistry->getPattern('int')), $value);
        });

        $routeDispatcher->config(
            requestHttpMethod: HttpMethod::GET,
            requestPath: '/get/1/',
            routes: $routeMap->getRoutes(),
            patternRegistry: $patternRegistry
        );

        $route = $routeDispatcher->match();
        $this->assertSame(['id' => '1'], $route->getAttributes());
    }
}
