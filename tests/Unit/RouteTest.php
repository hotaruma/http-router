<?php

declare(strict_types=1);

namespace Hotaruma\Tests\Unit;

use Hotaruma\HttpRouter\Exception\RouteInvalidArgumentException;
use Hotaruma\HttpRouter\Factory\RouteFactory;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
    /**
     * @dataProvider \Hotaruma\Tests\DataProvider\RouteDataProvider::actionDataProvider
     */
    public function testAction(mixed $action): void
    {
        $route = RouteFactory::createRoute();
        $route->action($action);
        $this->assertEquals($action, $route->getAction());
    }

    /**
     * @dataProvider \Hotaruma\Tests\DataProvider\RouteDataProvider::invalidActionDataProvider
     */
    public function testInvalidAction(mixed $action): void
    {
        $this->expectException(RouteInvalidArgumentException::class);

        $route = RouteFactory::createRoute();
        $route->action($action);
    }


    /**
     * @dataProvider \Hotaruma\Tests\DataProvider\RouteDataProvider::attributesDataProvider
     */
    public function testAttributes(array $attributes): void
    {
        $route = RouteFactory::createRoute();
        $route->attributes($attributes);
        $this->assertEquals($attributes, $route->getAttributes());
    }

    /**
     * @dataProvider \Hotaruma\Tests\DataProvider\RouteDataProvider::invalidAttributesDataProvider
     */
    public function testInvalidAttributes(array $attributes): void
    {
        $this->expectException(RouteInvalidArgumentException::class);

        $route = RouteFactory::createRoute();
        $route->attributes($attributes);
    }


    /**
     * @dataProvider \Hotaruma\Tests\DataProvider\RouteDataProvider::urlDataProvider
     */
    public function testUrl(string $url, string $expectedUrl): void
    {
        $route = RouteFactory::createRoute();
        $route->url($url);
        $this->assertEquals($expectedUrl, $route->getUrl());
    }
}
