<?php

declare(strict_types=1);

namespace Hotaruma\Tests\Integration;

use Hotaruma\HttpRouter\Interface\ConfigStore\ConfigStoreInterface;
use Hotaruma\HttpRouter\Attribute\{Route, RouteGroup};
use Hotaruma\HttpRouter\Enum\HttpMethod;
use Hotaruma\HttpRouter\Exception\ConfigInvalidArgumentException;
use Hotaruma\HttpRouter\Interface\RouteMap\RouteMapInterface;
use Hotaruma\HttpRouter\RouteMap;
use PHPUnit\Framework\TestCase;
use stdClass;

class RouteMapTest extends TestCase
{
    public function testChangeGroupConfig(): void
    {
        $routeMap = new RouteMap();
        $config = null;

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
            group: function (RouteMapInterface $routeMap) use (&$config) {

                $routeMap->changeGroupConfig(
                    defaults: ['page_t' => '1'],
                    rules: ['page_t' => '\d+'],
                    middlewares: ['Middleware1_t'],
                    pathPrefix: 'group_t/path_t',
                    namePrefix: 'group_t.name_t',
                    methods: HttpMethod::DELETE,
                );

                $config = $routeMap->getConfigStore();
            }
        );
        assert($config instanceof ConfigStoreInterface);

        $this->assertEquals(['page_f' => '1', 'page_t' => '1'], $config->getDefaults());
        $this->assertEquals(['page_f' => '\d+', 'page_t' => '\d+'], $config->getRules());
        $this->assertEquals(['Middleware1_f', 'Middleware1_t'], $config->getMiddlewares());
        $this->assertEquals('/group_f/path_f/group_t/path_t/', $config->getPath());
        $this->assertEquals('group_f.name_f.group_t.name_t', $config->getName());
        $this->assertEquals([HttpMethod::GET, HttpMethod::DELETE], $config->getMethods());
    }

    public function testChangeGroupConfigNullMerge(): void
    {
        $routeMap = new RouteMap();
        $config = null;

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
            group: function (RouteMapInterface $routeMap) use (&$config) {
                $routeMap->changeGroupConfig(
                    defaults: ['page_t' => '1'],
                );

                $config = $routeMap->getConfigStore();
            }
        );
        assert($config instanceof ConfigStoreInterface);

        $this->assertEquals(['page_f' => '1', 'page_t' => '1'], $config->getDefaults());
        $this->assertEquals(['page_f' => '\d+', 'page_c' => '\d+'], $config->getRules());
        $this->assertEquals(['Middleware1_f', 'Middleware1_c'], $config->getMiddlewares());
        $this->assertEquals('/group_f/path_f/group_c/path_c/', $config->getPath());
        $this->assertEquals('group_f.name_f.group_c.name_c', $config->getName());
        $this->assertEquals([HttpMethod::GET, HttpMethod::POST], $config->getMethods());
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

        $routeConfig = $routeIterator->current()->getConfigStore();
        $this->assertEquals(['page_f' => '1', 'page_c' => '1'], $routeConfig->getDefaults());
        $this->assertEquals(['page_f' => '\d+', 'page_c' => '\d+'], $routeConfig->getRules());
        $this->assertEquals(['Middleware1_f', 'Middleware1_c'], $routeConfig->getMiddlewares());
        $this->assertEquals('/group_f/path_f/route_ff/path_ff/', $routeConfig->getPath());
        $this->assertEquals('group.route_f', $routeConfig->getName());
        $this->assertEquals([HttpMethod::GET, HttpMethod::OPTIONS], $routeConfig->getMethods());

        $routeIterator->next();

        $routeConfig = $routeIterator->current()->getConfigStore();
        $this->assertEquals(['page_f' => '1'], $routeConfig->getDefaults());
        $this->assertEquals(['page_f' => '\d+'], $routeConfig->getRules());
        $this->assertEquals(['Middleware1_f'], $routeConfig->getMiddlewares());
        $this->assertEquals('/group_f/path_f/route_t/path_t/', $routeConfig->getPath());
        $this->assertEquals('group', $routeConfig->getName());
        $this->assertEquals([HttpMethod::GET, HttpMethod::CONNECT], $routeConfig->getMethods());
    }

    public function testAddSimpleRoute(): void
    {
        $routeMap = new RouteMap();

        $routeMap->changeGroupConfig(
            methods: [HttpMethod::GET],
        );

        $routeMap->add('route/path', StdClass::class);

        $routeMap->group(
            methods: [HttpMethod::POST],
            group: function (RouteMapInterface $routeMap) {
                $routeMap->add('route/path', StdClass::class);
            }
        );

        $routesCollection = $routeMap->getRoutes();
        $this->assertCount(2, $routesCollection);

        $iterator = $routesCollection->getIterator();
        $this->assertEquals([HttpMethod::GET], $iterator->current()->getConfigStore()->getMethods());
        $iterator->next();
        $this->assertEquals([HttpMethod::GET, HttpMethod::POST], $iterator->current()->getConfigStore()->getMethods());
    }

    public function testInvalidSimpleRoute(): void
    {
        $routeMap = new RouteMap();

        $this->expectException(ConfigInvalidArgumentException::class);
        $routeMap->add('route/path', StdClass::class);
    }

    public function testGroupWithRouteScanner(): void
    {
        $routeMap = new RouteMap();

        $routeMap->changeGroupConfig(
            defaults: ['page_f' => '1'],
            rules: ['page_f' => '\d+'],
            middlewares: ['Middleware1_f'],
            pathPrefix: 'group_f/path_f',
            namePrefix: 'group_f',
        );
        $baseRouteMapGroupConfigStore = $routeMap->getGroupConfigStoreFactory()::create();
        $baseRouteMapGroupConfigStore->mergeConfig($routeMap->getConfigStore());

        $class = new class () {
            #[Route(
                path: '/route1',
                methods: HttpMethod::GET,
                rules: ['rule1' => '\d+'],
                defaults: ['default1' => '1'],
                name: 'route1',
                middlewares: ['middleware1'],
            )]
            public function route1(): void
            {
            }
        };

        $class2 = new #[RouteGroup(
            middlewares: ['group_middleware2'],
            pathPrefix: 'group_route2',
            namePrefix: 'group_route2',
            methods: HttpMethod::OPTIONS,
        )] class () {
            #[Route(
                path: '/route2',
                methods: HttpMethod::DELETE,
                rules: ['rule2' => '\d+'],
                defaults: ['default2' => '1'],
                name: 'route2',
                middlewares: ['middleware2'],
            )]
            public function route1(): void
            {
            }
        };

        $routeMap->scanRoutes($class::class, $class2::class);
        $routes = $routeMap->getRoutes();

        $iterator = $routes->getIterator();

        $routeConfig = $iterator->current()->getConfigStore()->getConfig();
        $this->assertSame(
            [
                '/group_f/path_f/route1/',
                [HttpMethod::GET],
                ['rule1' => '\d+', 'page_f' => '\d+'],
                ['default1' => '1', 'page_f' => '1'],
                'group_f.route1',
                ['Middleware1_f', 'middleware1'],
            ],
            [
                $routeConfig->getPath(),
                $routeConfig->getMethods(),
                $routeConfig->getRules(),
                $routeConfig->getDefaults(),
                $routeConfig->getName(),
                $routeConfig->getMiddlewares()
            ]
        );

        $iterator->next();

        $routeConfig = $iterator->current()->getConfigStore()->getConfig();
        $this->assertSame(
            [
                '/group_route2/route2/',
                [HttpMethod::OPTIONS, HttpMethod::DELETE],
                ['rule2' => '\d+'],
                ['default2' => '1'],
                'group_route2.route2',
                ['group_middleware2', 'middleware2'],
            ],
            [
                $routeConfig->getPath(),
                $routeConfig->getMethods(),
                $routeConfig->getRules(),
                $routeConfig->getDefaults(),
                $routeConfig->getName(),
                $routeConfig->getMiddlewares()
            ]
        );

        $this->assertEquals($baseRouteMapGroupConfigStore->getConfig(), $routeMap->getConfigStore()->getConfig());
    }

    public function testSequentialGroups(): void
    {
        $routeMap = new RouteMap();
        $config = null;

        $routeMap->group(
            defaults: ['page_cc' => '1'],
            rules: ['page_cc' => '\d+'],
            middlewares: ['Middleware1_cc'],
            pathPrefix: 'group_cc/path_cc',
            namePrefix: 'group_cc.name_cc',
            methods: HttpMethod::GET,
            group: function (RouteMapInterface $routeMap) {
            }
        );

        $routeMap->group(
            defaults: ['page_c' => '1'],
            rules: ['page_c' => '\d+'],
            middlewares: ['Middleware1_c'],
            pathPrefix: 'group_c/path_c',
            namePrefix: 'group_c.name_c',
            methods: HttpMethod::POST,
            group: function (RouteMapInterface $routeMap) use (&$config) {
                $config = $routeMap->getConfigStore();
            }
        );
        assert($config instanceof ConfigStoreInterface);

        $this->assertEquals(['page_c' => '1'], $config->getDefaults());
        $this->assertEquals(['page_c' => '\d+'], $config->getRules());
        $this->assertEquals(['Middleware1_c'], $config->getMiddlewares());
        $this->assertEquals('/group_c/path_c/', $config->getPath());
        $this->assertEquals('group_c.name_c', $config->getName());
        $this->assertEquals([HttpMethod::POST], $config->getMethods());
    }

    public function testGroupReturnConfig(): void
    {
        $routeMap = new RouteMap();
        $config = null;

        $routeMap->group(
            defaults: ['page_cc' => '1'],
            rules: ['page_cc' => '\d+'],
            middlewares: ['Middleware1_cc'],
            pathPrefix: 'group_cc/path_cc',
            namePrefix: 'group_cc.name_cc',
            methods: HttpMethod::GET,
            group: function (RouteMapInterface $routeMap) use (&$config) {

                $routeMap->group(
                    defaults: ['page_c' => '1'],
                    rules: ['page_c' => '\d+'],
                    middlewares: ['Middleware1_c'],
                    pathPrefix: 'group_c/path_c',
                    namePrefix: 'group_c.name_c',
                    methods: HttpMethod::POST,
                    group: function (RouteMapInterface $routeMap) {

                        $routeMap->group(
                            defaults: ['page_ff' => '1'],
                            rules: ['page_ff' => '\d+'],
                            middlewares: ['Middleware1_ff'],
                            pathPrefix: 'group_ff/path_ff',
                            namePrefix: 'group_ff.name_ff',
                            methods: HttpMethod::CONNECT,
                            group: function (RouteMapInterface $routeMap) {
                            }
                        );
                    }
                );

                $routeMap->group(
                    defaults: ['page_f' => '1'],
                    rules: ['page_f' => '\d+'],
                    middlewares: ['Middleware1_f'],
                    pathPrefix: 'group_f/path_f',
                    namePrefix: 'group_f.name_f',
                    methods: HttpMethod::PUT,
                    group: function (RouteMapInterface $routeMap) use (&$config) {
                        $config = $routeMap->getConfigStore();
                    }
                );
            }
        );
        assert($config instanceof ConfigStoreInterface);

        $this->assertEquals(['page_f' => '1', 'page_cc' => '1'], $config->getDefaults());
        $this->assertEquals(['page_f' => '\d+', 'page_cc' => '\d+'], $config->getRules());
        $this->assertEquals(['Middleware1_cc', 'Middleware1_f'], $config->getMiddlewares());
        $this->assertEquals('/group_cc/path_cc/group_f/path_f/', $config->getPath());
        $this->assertEquals('group_cc.name_cc.group_f.name_f', $config->getName());
        $this->assertEquals([HttpMethod::GET, HttpMethod::PUT], $config->getMethods());
    }
}
