<?php

declare(strict_types=1);

namespace Hotaruma\Tests\Unit;

use Hotaruma\HttpRouter\ConfigStore\ConfigStore;
use Hotaruma\HttpRouter\Interface\Enum\RequestMethodInterface;
use Hotaruma\HttpRouter\Interface\Route\RouteInterface;
use Hotaruma\HttpRouter\RouteMatcher\RouteMatcher;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class RouteMatcherTest extends TestCase
{
    /**
     * @dataProvider \Hotaruma\Tests\DataProvider\RouteMatcherDataProvider::matchRouteByHttpMethodDataProvider
     * @throws Exception
     */
    public function testMatchRouteByHttpMethod(
        array                  $routeMethods,
        RequestMethodInterface $requestMethod,
        bool                   $expected,
    ): void {
        $routeMatcher = new RouteMatcher();

        $route = $this->getMockRoute(methods: $routeMethods);
        $this->assertEquals($expected, $routeMatcher->matchRouteByHttpMethod($route, $requestMethod));
    }

    /**
     * @dataProvider \Hotaruma\Tests\DataProvider\RouteMatcherDataProvider::matchRouteByRegexDataProvider
     * @throws Exception
     */
    public function testMatchRouteByRegex(
        string $routePath,
        array  $routeRules,
        string $requestPath,
        ?array $expected,
    ): void {
        $routeMatcher = new RouteMatcher();

        $route = $this->getMockRoute(path: $routePath, rules: $routeRules);
        $this->assertEquals($expected, $routeMatcher->matchRouteByRegex($route, $requestPath));
    }

    /**
     * @throws Exception
     */
    protected function getMockRoute(
        string $path = '',
        array  $methods = [],
        array  $rules = [],
    ): RouteInterface {
        $routeConfig = $this->getMockBuilder(ConfigStore::class)
            ->addMethods(['getPath', 'getMethods', 'getRules'])
            ->getMock();

        $routeConfig->method('getPath')->willReturn($path);
        $routeConfig->method('getMethods')->willReturn($methods);
        $routeConfig->method('getRules')->willReturn($rules);

        $route = $this->createMock(RouteInterface::class);
        $route->method('getConfigStore')->willReturn($routeConfig);

        return $route;
    }
}
