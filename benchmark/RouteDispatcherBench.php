<?php

declare(strict_types=1);

namespace Hotaruma\Benchmark;

use PhpBench\Attributes\{Assert, BeforeMethods, Iterations, OutputTimeUnit, Revs, Skip, Timeout, Warmup};
use Hotaruma\HttpRouter\Enum\HttpMethod;
use Hotaruma\HttpRouter\Interface\RouteDispatcher\RouteDispatcherInterface;
use Hotaruma\HttpRouter\Interface\RouteMap\RouteMapInterface;
use Hotaruma\HttpRouter\RouteDispatcher;
use Hotaruma\HttpRouter\RouteMap;
use stdClass;

#[BeforeMethods(['setUp'])]
#[Warmup(2)]
#[Iterations(5)]
#[Revs(500)]
#[Timeout(10.0)]
#[OutputTimeUnit('microseconds')]
class RouteDispatcherBench
{
    protected RouteDispatcherInterface $routeDispatcher;
    protected RouteMapInterface $routeMap;

    public function setUp(): void
    {
        $this->routeDispatcher = new RouteDispatcher();
        $this->routeMap = new RouteMap();

        for ($i = 0; $i <= 500; $i++) {
            $this->routeMap->get('/test/{id}/{category}/', stdClass::class)->config(
                methods: [HttpMethod::PUT, HttpMethod::POST],
            );
        }
        $this->routeMap->put('/test/{id}/{category}/sfx/', stdClass::class)->config();

        $this->routeDispatcher->config(
            requestHttpMethod: HttpMethod::PUT,
            requestPath: '/test/123/qwe/sfx/',
            routes: $this->routeMap->getRoutes(),
        );
    }

    #[Assert("mode(variant.time.avg) < 1500 microseconds +/- 10%")]
    #[Assert("mode(variant.mem.peak) < 3mb")]
    public function benchMatch(): void
    {
        $this->routeDispatcher->match();
    }
}
