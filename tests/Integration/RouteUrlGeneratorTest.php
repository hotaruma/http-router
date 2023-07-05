<?php

declare(strict_types=1);

namespace Tests\Integration;

use Hotaruma\HttpRouter\Exception\RouteUrlGeneratorInvalidArgumentException;
use Hotaruma\HttpRouter\Exception\RouteUrlGeneratorNotFoundException;
use Hotaruma\HttpRouter\Interface\PatternRegistry\PatternRegistryInterface;
use Hotaruma\HttpRouter\PatternRegistry\PatternRegistry;
use Hotaruma\HttpRouter\RouteMap;
use Hotaruma\HttpRouter\RouteUrlGenerator;
use PHPUnit\Framework\TestCase;
use stdClass;

class RouteUrlGeneratorTest extends TestCase
{
    public function testGenerateByName(): void
    {
        $routeUrlGenerator = new RouteUrlGenerator();
        $routeMap = new RouteMap();

        $routeMap->get('/get/{id}/', stdClass::class)->config(
            name: 'get',
            defaults: ['id' => '1']
        );

        $routeUrlGenerator->config(
            routes: $routeMap->getRoutes(),
        );
        $route = $routeUrlGenerator->generateByName('get');
        $this->assertEquals('/get/1/', $route->getUrl());
    }

    public function testInvalidGenerateByName(): void
    {
        $routeUrlGenerator = new RouteUrlGenerator();
        $routeMap = new RouteMap();

        $routeMap->get('/get/', stdClass::class);

        $routeUrlGenerator->config(
            routes: $routeMap->getRoutes(),
        );
        $this->expectException(RouteUrlGeneratorNotFoundException::class);
        $routeUrlGenerator->generateByName('get');
    }

    public function testInvalidGenerateByEmptyName(): void
    {
        $routeUrlGenerator = new RouteUrlGenerator();
        $routeMap = new RouteMap();

        $routeMap->get('/get/', stdClass::class);

        $routeUrlGenerator->config(
            routes: $routeMap->getRoutes(),
        );

        $this->expectException(RouteUrlGeneratorInvalidArgumentException::class);
        $routeUrlGenerator->generateByName('');
    }

    public function testPatternRegistry(): void
    {
        $routeUrlGenerator = new RouteUrlGenerator();
        $patternRegistry = new PatternRegistry();
        $routeMap = new RouteMap();

        $routeMap->get('/get/{id:custom}/', stdClass::class)->config(
            name: 'get',
            defaults: ['id' => '1'],
        );

        $patternRegistry->addPattern('custom', static function (string $value, PatternRegistryInterface $patternRegistry): bool {
            /* @phpstan-ignore-next-line */
            return !!preg_match(sprintf('#^%s$#', $patternRegistry->getPattern('int')), $value);
        });

        $routeUrlGenerator->config(
            routes: $routeMap->getRoutes(),
            patternRegistry: $patternRegistry
        );
        $route = $routeUrlGenerator->generateByName('get');
        $this->assertEquals('/get/1/', $route->getUrl());
    }
}
