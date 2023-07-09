<?php

declare(strict_types=1);

namespace Hotaruma\Benchmark;

use Hotaruma\HttpRouter\Enum\HttpMethod;
use Hotaruma\HttpRouter\Interface\RouteMap\RouteMapInterface;
use Hotaruma\HttpRouter\Interface\RouteUrlGenerator\RouteUrlGeneratorInterface;
use Hotaruma\HttpRouter\RouteMap;
use Hotaruma\HttpRouter\RouteUrlGenerator;
use PhpBench\Attributes\{Assert, BeforeMethods, Iterations, OutputTimeUnit, Revs, Skip, Timeout, Warmup};
use stdClass;

#[BeforeMethods(['setUp'])]
#[Warmup(2)]
#[Iterations(5)]
#[Revs(500)]
#[Timeout(10.0)]
#[OutputTimeUnit('microseconds')]
class RouteUrlGeneratorBench
{
    protected RouteUrlGeneratorInterface $routeUrlGenerator;
    protected RouteMapInterface $routeMap;

    public function setUp(): void
    {
        $this->routeUrlGenerator = new RouteUrlGenerator();
        $this->routeMap = new RouteMap();

        for ($i = 0; $i <= 500; $i++) {
            $this->routeMap->get('/non-exist/{id:int}/{category:slug}/', stdClass::class)->config(
                name: 'non-exist'
            );
        }
        $this->routeMap->put('/last/{id:int}/{category:slug}/', stdClass::class)->config(
            defaults: ['id' => '1', 'category' => 'qwe'],
            name: 'last'
        );

        $this->routeUrlGenerator->config(
            routes: $this->routeMap->getRoutes(),
        );
    }

    #[Assert("mode(variant.time.avg) < 100 microseconds +/- 10%")]
    #[Assert("mode(variant.mem.peak) < 3mb")]
    public function benchGenerateByName(): void
    {
        $this->routeUrlGenerator->generateByName('last');
    }
}
