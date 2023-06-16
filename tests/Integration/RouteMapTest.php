<?php

declare(strict_types=1);

namespace Tests\Integration;

use Hotaruma\HttpRouter\Enum\HttpMethod;
use Hotaruma\HttpRouter\Interface\RouteMap\RouteMapInterface;
use Hotaruma\HttpRouter\RouteMap;
use PHPUnit\Framework\TestCase;
use stdClass;

class RouteMapTest extends TestCase
{
    public function testChangeGroupConfig(): void
    {
        $routeMap = new RouteMap();

        $routeMap->changeGroupConfig(
            defaults: ['page_f' => '1'],
            rules: ['page_f' => '\d+'],
            middlewares: ['Middleware1_f'],
            pathPrefix: 'group_f/path_f',
            namePrefix: 'group_f.name_f',
            methods: HttpMethod::GET,
        );

        $routeMap->group(
            defaults: ['page_c' => '1'],
            rules: ['page_c' => '\d+'],
            middlewares: ['Middleware1_c'],
            pathPrefix: 'group_c/path_c',
            namePrefix: 'group_c.name_c',
            methods: HttpMethod::POST,
            group: function (RouteMapInterface $routeMap) {

                $routeMap->changeGroupConfig(
                    defaults: ['page_t' => '1'],
                    rules: ['page_t' => '\d+'],
                    middlewares: ['Middleware1_t'],
                    pathPrefix: 'group_t/path_t',
                    namePrefix: 'group_t.name_t',
                    methods: HttpMethod::DELETE,
                );
            }
        );

        $groupConfig = $routeMap->getRouteGroupConfig();

        $this->assertEquals(['page_f' => '1', 'page_t' => '1'], $groupConfig->getDefaults());
        $this->assertEquals(['page_f' => '\d+', 'page_t' => '\d+'], $groupConfig->getRules());
        $this->assertEquals(['Middleware1_f', 'Middleware1_t'], $groupConfig->getMiddlewares());
        $this->assertEquals('/group_f/path_f/group_t/path_t/', $groupConfig->getPath());
        $this->assertEquals('group_f.name_f.group_t.name_t', $groupConfig->getName());
        $this->assertEquals([HttpMethod::GET, HttpMethod::DELETE], $groupConfig->getMethods());
    }

    public function testChangeGroupConfigNullMerge(): void
    {
        $routeMap = new RouteMap();

        $routeMap->changeGroupConfig(
            defaults: ['page_f' => '1'],
            rules: ['page_f' => '\d+'],
            middlewares: ['Middleware1_f'],
            pathPrefix: 'group_f/path_f',
            namePrefix: 'group_f.name_f',
            methods: HttpMethod::GET,
        );

        $routeMap->group(
            defaults: ['page_c' => '1'],
            rules: ['page_c' => '\d+'],
            middlewares: ['Middleware1_c'],
            pathPrefix: 'group_c/path_c',
            namePrefix: 'group_c.name_c',
            methods: HttpMethod::POST,
            group: function (RouteMapInterface $routeMap) {
                $routeMap->changeGroupConfig(
                    defaults: ['page_t' => '1'],
                );
            }
        );

        $groupConfig = $routeMap->getRouteGroupConfig();

        $this->assertEquals(['page_f' => '1', 'page_t' => '1'], $groupConfig->getDefaults());
        $this->assertEquals(['page_f' => '\d+', 'page_c' => '\d+'], $groupConfig->getRules());
        $this->assertEquals(['Middleware1_f', 'Middleware1_c'], $groupConfig->getMiddlewares());
        $this->assertEquals('/group_f/path_f/group_c/path_c/', $groupConfig->getPath());
        $this->assertEquals('group_f.name_f.group_c.name_c', $groupConfig->getName());
        $this->assertEquals([HttpMethod::GET, HttpMethod::POST], $groupConfig->getMethods());
    }

    public function testAddRoute(): void
    {
        $routeMap = new RouteMap();

        $routeMap->changeGroupConfig(
            defaults: ['page_f' => '1'],
            rules: ['page_f' => '\d+'],
            middlewares: ['Middleware1_f'],
            pathPrefix: 'group_f/path_f',
            namePrefix: 'group',
            methods: HttpMethod::GET,
        );
        $routeMap->post('route_f/path_f', StdClass::class)->config(
            defaults: ['page_c' => '1'],
            rules: ['page_c' => '\d+'],
            middlewares: ['Middleware1_c'],
            path: 'route_ff/path_ff',
            name: 'route_f',
            methods: HttpMethod::OPTIONS,
        );
        $routeMap->connect('route_t/path_t', StdClass::class)->config();


        $routesCollection = $routeMap->getRoutes();
        $routeIterator = $routesCollection->getIterator();

        $this->assertCount(2, $routesCollection);

        $routeConfig = $routeIterator->current()->getRouteConfig();
        $this->assertEquals(['page_f' => '1', 'page_c' => '1'], $routeConfig->getDefaults());
        $this->assertEquals(['page_f' => '\d+', 'page_c' => '\d+'], $routeConfig->getRules());
        $this->assertEquals(['Middleware1_f', 'Middleware1_c'], $routeConfig->getMiddlewares());
        $this->assertEquals('/group_f/path_f/route_ff/path_ff/', $routeConfig->getPath());
        $this->assertEquals('group.route_f', $routeConfig->getName());
        $this->assertEquals([HttpMethod::GET, HttpMethod::OPTIONS], $routeConfig->getMethods());

        $routeIterator->next();

        $routeConfig = $routeIterator->current()->getRouteConfig();
        $this->assertEquals(['page_f' => '1'], $routeConfig->getDefaults());
        $this->assertEquals(['page_f' => '\d+'], $routeConfig->getRules());
        $this->assertEquals(['Middleware1_f'], $routeConfig->getMiddlewares());
        $this->assertEquals('/group_f/path_f/route_t/path_t/', $routeConfig->getPath());
        $this->assertEquals('group', $routeConfig->getName());
        $this->assertEquals([HttpMethod::GET, HttpMethod::CONNECT], $routeConfig->getMethods());
    }
}
