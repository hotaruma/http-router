<?php

declare(strict_types=1);

namespace Tests\Integration;

use Hotaruma\HttpRouter\Config\RouteConfig;
use Hotaruma\HttpRouter\Exception\ConfigInvalidArgumentException;
use Hotaruma\HttpRouter\Attribute\{Route, RouteGroup};
use Hotaruma\HttpRouter\Enum\HttpMethod;
use Hotaruma\HttpRouter\RouteScanner\RouteScanner;
use PHPUnit\Framework\TestCase;
use stdClass;

class RouteScannerTest extends TestCase
{
    public function testScanRoutesCount(): void
    {
        $routeScanner = new RouteScanner();

        $class = new class () {
            #[Route('/route1', methods: HttpMethod::POST)]
            public function route1(): void
            {
            }

            #[Route('/route2', methods: HttpMethod::POST)]
            public function route2(): void
            {
            }
        };
        $class2 = new #[RouteGroup(pathPrefix: '/home', methods: HttpMethod::POST, defaults: ['id' => '\d+'])] class () {
            public function route1(): void
            {
            }

            #[Route('/route2')]
            #[Route('/route2')]
            public function route2(): void
            {
            }
        };
        $class3 = new #[RouteGroup(pathPrefix: '/home', methods: HttpMethod::POST, defaults: ['id' => '\d+'])] class () {
            public function route1(): void
            {
            }

            public function route2(): void
            {
            }
        };

        $routeMap = $routeScanner->scanRoutes($class::class, $class2::class, $class3::class);

        $routes = $routeMap->getRoutes();
        $this->assertCount(4, $routes);
    }

    public function testInvalidRoute(): void
    {
        $routeScanner = new RouteScanner();

        $class = new class () {
            #[Route('/route1')]
            public function route1(): void
            {
            }
        };

        $this->expectException(ConfigInvalidArgumentException::class);
        $routeScanner->scanRoutes($class::class);
    }

    public function testScanRoutesRouteConfig(): void
    {
        $routeScanner = new RouteScanner();

        $routeConfig = new RouteConfig();
        $routeConfig->path('/route1');
        $routeConfig->methods(HttpMethod::GET);
        $routeConfig->rules(['id' => '\d+']);
        $routeConfig->defaults(['id' => '1']);
        $routeConfig->name('route');
        $routeConfig->middlewares([stdClass::class]);
        $class = new class () {
            #[Route(
                path: '/route1',
                methods: HttpMethod::GET,
                rules: ['id' => '\d+'],
                defaults: ['id' => '1'],
                name: 'route',
                middlewares: [stdClass::class]
            )]
            public function route1(): void
            {
            }
        };

        $routeMap = $routeScanner->scanRoutes($class::class);
        $routes = $routeMap->getRoutes();
        $route = $routes->getIterator()->current();

        $this->assertEquals($routeConfig, $route->getConfigStore()->getConfig());
    }

    public function testScanRoutesRouteGroupMergeConfig(): void
    {
        $routeScanner = new RouteScanner();

        $class = new #[RouteGroup(
            rules: ['group_rule1' => '\d+'],
            defaults: ['group_default1' => '1'],
            middlewares: ['group_middleware1'],
            pathPrefix: 'group_route1',
            namePrefix: 'group_route1',
            methods: HttpMethod::POST,
        )] class () {
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
            rules: ['group_rule2' => '\d+'],
            defaults: ['group_default2' => '1'],
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

        $routeMap = $routeScanner->scanRoutes($class::class, $class2::class);
        $routes = $routeMap->getRoutes();

        $iterator = $routes->getIterator();

        $routeConfig = $iterator->current()->getConfigStore()->getConfig();
        $this->assertSame(
            [
                '/group_route1/route1/',
                [HttpMethod::POST, HttpMethod::GET],
                ['rule1' => '\d+', 'group_rule1' => '\d+'],
                ['default1' => '1', 'group_default1' => '1'],
                'group_route1.route1',
                ['group_middleware1', 'middleware1'],
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
                ['rule2' => '\d+', 'group_rule2' => '\d+'],
                ['default2' => '1', 'group_default2' => '1'],
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
    }

    public function testRouteActionBuilder(): void
    {
        $routeScanner = new RouteScanner();
        $routeScanner->routeActionBuilder(function (string $className, string $methodName): array {
            return [$methodName, $className];
        });

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

        $routeMap = $routeScanner->scanRoutes($class::class);
        $routes = $routeMap->getRoutes();

        $iterator = $routes->getIterator();

        $this->assertSame(['route1', $class::class], $iterator->current()->getAction());
    }

    public function testScanRoutesFromDirectory(): void
    {
        $routeScanner = new RouteScanner();

        $roteMap = $routeScanner->scanRoutesFromDirectory(__DIR__);

        $routes = $roteMap->getRoutes();
        $this->assertCount(4, $routes);
    }
}
