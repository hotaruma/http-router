<?php

declare(strict_types=1);

namespace Hotaruma\Benchmark;

use Hotaruma\HttpRouter\Enum\HttpMethod;
use Hotaruma\HttpRouter\RouteMap;
use Hotaruma\HttpRouter\Interface\RouteMap\RouteMapInterface;
use PhpBench\Attributes\{Assert, BeforeMethods, Iterations, OutputTimeUnit, Revs, Skip, Timeout, Warmup};
use stdClass;

#[BeforeMethods(['setUp'])]
#[Warmup(2)]
#[Iterations(5)]
#[Revs(500)]
#[Timeout(10.0)]
#[OutputTimeUnit('microseconds')]
class RouteMapBench
{
    protected RouteMapInterface $routeMap;

    public function setUp(): void
    {
        $this->routeMap = new RouteMap();
    }

    #[Assert("mode(variant.time.avg) < 100 microseconds +/- 10%")]
    #[Assert("mode(variant.mem.peak) < 3mb")]
    public function benchAddMethod(): void
    {
        $this->routeMap->get('/users', stdClass::class)->config(
            defaults: ['page' => '1'],
            rules: ['page' => '\d+'],
            middlewares: [stdClass::class],
            path: 'path',
            name: 'name',
            methods: HttpMethod::OPTIONS,
        );
    }

    #[Assert("mode(variant.time.avg) < 100 microseconds +/- 10%")]
    #[Assert("mode(variant.mem.peak) < 3mb")]
    public function benchMergeConfig(): void
    {
        $this->routeMap->group(
            defaults: ['page_c' => '1'],
            rules: ['page_c' => '\d+'],
            middlewares: [stdClass::class],
            pathPrefix: 'group_path',
            namePrefix: 'group_name',
            methods: HttpMethod::POST,
            group: static function (RouteMapInterface $routeMap) {

                $routeMap->get('/users', stdClass::class)->config(
                    defaults: ['page' => '1'],
                    rules: ['page' => '\d+'],
                    middlewares: [stdClass::class],
                    path: 'path',
                    name: 'name',
                    methods: HttpMethod::OPTIONS,
                );
            }
        );
    }
}
