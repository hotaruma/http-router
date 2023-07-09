<?php

declare(strict_types=1);

namespace Hotaruma\Tests\Unit;

use Hotaruma\HttpRouter\ConfigStore\ConfigStore;
use Hotaruma\HttpRouter\Interface\Config\ConfigInterface;
use Hotaruma\HttpRouter\Interface\Enum\RequestMethodInterface;
use Hotaruma\HttpRouter\Interface\Route\RouteInterface;
use Hotaruma\HttpRouter\Route\Route;
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

        $resRoute = $routeMatcher->matchRouteByRegex([$route], $requestPath);

        $this->assertEquals($expected, $resRoute?->getAttributes());
    }

    /**
     * @throws Exception
     */
    protected function getMockRoute(
        string $path = '',
        array  $methods = [],
        array  $rules = [],
    ): RouteInterface {

        $config = $this->createMock(ConfigInterface::class);
        $config->method('getPath')->willReturn($path);
        $config->method('getMethods')->willReturn($methods);
        $config->method('getRules')->willReturn($rules);

        $routeConfig = $this->getMockBuilder(ConfigStore::class)
            ->addMethods(['getPath', 'getMethods', 'getRules'])
            ->onlyMethods(['getConfig'])
            ->getMock();

        $routeConfig->method('getConfig')->willReturn($config);
        $routeConfig->method('getPath')->willReturn($path);
        $routeConfig->method('getMethods')->willReturn($methods);
        $routeConfig->method('getRules')->willReturn($rules);

        $route = $this->getMockBuilder(Route::class)
            ->onlyMethods(['getConfigStore'])
            ->getMock();

        $route->method('getConfigStore')->willReturn($routeConfig);

        return $route;
    }
}
