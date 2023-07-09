<?php

declare(strict_types=1);

namespace Hotaruma\Tests\Unit;

use Hotaruma\HttpRouter\ConfigStore\ConfigStore;
use Hotaruma\HttpRouter\Exception\RouteUrlBuilderWrongValuesException;
use Hotaruma\HttpRouter\Interface\Config\ConfigInterface;
use Hotaruma\HttpRouter\Interface\Route\RouteInterface;
use Hotaruma\HttpRouter\RouteUrlBuilder\RouteUrlBuilder;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RouteUrlBuilderTest extends TestCase
{
    /**
     * @dataProvider \Hotaruma\Tests\DataProvider\RouteUrlBuilderDataProvider::buildDataProvider
     * @throws Exception
     */
    public function testBuild(
        string $path,
        array  $rules,
        array  $defaults,
        array  $attributes,
        string $expectedUrl
    ): void {
        $routeUrlBuilder = new RouteUrlBuilder();

        $route = $this->getMockRoute(
            path: $path,
            rules: $rules,
            defaults: $defaults,
            attributes: $attributes,
        );
        $route->expects($this->once())->method('url')->with($expectedUrl);
        $routeUrlBuilder->build($route);
    }

    /**
     * @dataProvider \Hotaruma\Tests\DataProvider\RouteUrlBuilderDataProvider::invalidBuildDataProvider
     * @throws Exception
     */
    public function testInvalidBuild(
        string $path,
        array  $rules,
        array  $defaults,
        array  $attributes,
    ): void {
        $routeUrlBuilder = new RouteUrlBuilder();

        $route = $this->getMockRoute(
            path: $path,
            rules: $rules,
            defaults: $defaults,
            attributes: $attributes,
        );
        $this->expectException(RouteUrlBuilderWrongValuesException::class);
        $routeUrlBuilder->build($route);
    }

    /**
     * @throws Exception
     */
    protected function getMockRoute(
        string $path = '',
        array  $rules = [],
        array  $defaults = [],
        array  $attributes = [],
    ): RouteInterface|MockObject {

        $config = $this->createMock(ConfigInterface::class);
        $config->method('getPath')->willReturn($path);
        $config->method('getRules')->willReturn($rules);
        $config->method('getDefaults')->willReturn($defaults);

        $routeConfig = $this->getMockBuilder(ConfigStore::class)
            ->addMethods(['getPath', 'getRules', 'getDefaults'])
            ->onlyMethods(['getConfig'])
            ->getMock();

        $routeConfig->method('getConfig')->willReturn($config);
        $routeConfig->method('getPath')->willReturn($path);
        $routeConfig->method('getRules')->willReturn($rules);
        $routeConfig->method('getDefaults')->willReturn($defaults);

        $route = $this->createMock(RouteInterface::class);
        $route->method('getConfigStore')->willReturn($routeConfig);
        $route->method('getAttributes')->willReturn($attributes);

        return $route;
    }
}
