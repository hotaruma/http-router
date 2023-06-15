<?php

declare(strict_types=1);

namespace Tests\Unit;

use Hotaruma\HttpRouter\Factory\RouteGroupConfigFactory;
use PHPUnit\Framework\TestCase;

class GroupRouteConfigTest extends TestCase
{
    /**
     * @dataProvider \Tests\DataProvider\GroupRouteConfigDataProvider::pathMergeDataProvider
     */
    public function testMergePath(string $groupPath, string $routePath, string $expectedPath): void
    {
        $groupConfig = RouteGroupConfigFactory::createRouteConfig();
        $groupConfig->path($groupPath);

        $routeConfig = RouteGroupConfigFactory::createRouteConfig();
        $routeConfig->path($routePath);

        $routeConfig->mergeConfig($groupConfig);

        $this->assertEquals($expectedPath, $routeConfig->getPath());
    }


    /**
     * @dataProvider \Tests\DataProvider\GroupRouteConfigDataProvider::methodsMergeDataProvider
     */
    public function testMergeMethods(array $groupMethods, array $routeMethods, array $expectedMethods): void
    {
        $groupConfig = RouteGroupConfigFactory::createRouteConfig();
        $groupConfig->methods($groupMethods);

        $routeConfig = RouteGroupConfigFactory::createRouteConfig();
        $routeConfig->methods($routeMethods);

        $routeConfig->mergeConfig($groupConfig);

        $this->assertEquals($expectedMethods, $routeConfig->getMethods());
    }


    /**
     * @dataProvider \Tests\DataProvider\GroupRouteConfigDataProvider::nameMergeDataProvider
     */
    public function testMergeName(string $groupName, string $routeName, string $expectedName): void
    {
        $groupConfig = RouteGroupConfigFactory::createRouteConfig();
        $groupConfig->name($groupName);

        $routeConfig = RouteGroupConfigFactory::createRouteConfig();
        $routeConfig->name($routeName);

        $routeConfig->mergeConfig($groupConfig);

        $this->assertEquals($expectedName, $routeConfig->getName());
    }


    /**
     * @dataProvider \Tests\DataProvider\GroupRouteConfigDataProvider::rulesMergeDataProvider
     */
    public function testMergeRules(array $groupRules, array $routeRules, array $expectedRules): void
    {
        $groupConfig = RouteGroupConfigFactory::createRouteConfig();
        $groupConfig->rules($groupRules);

        $routeConfig = RouteGroupConfigFactory::createRouteConfig();
        $routeConfig->rules($routeRules);

        $routeConfig->mergeConfig($groupConfig);

        $this->assertEquals($expectedRules, $routeConfig->getRules());
    }


    /**
     * @dataProvider \Tests\DataProvider\GroupRouteConfigDataProvider::defaultsMergeDataProvider
     */
    public function testMergeDefaults(array $groupDefaults, array $routeDefaults, array $expectedDefaults): void
    {
        $groupConfig = RouteGroupConfigFactory::createRouteConfig();
        $groupConfig->defaults($groupDefaults);

        $routeConfig = RouteGroupConfigFactory::createRouteConfig();
        $routeConfig->defaults($routeDefaults);

        $routeConfig->mergeConfig($groupConfig);

        $this->assertEquals($expectedDefaults, $routeConfig->getDefaults());
    }


    /**
     * @dataProvider \Tests\DataProvider\GroupRouteConfigDataProvider::middlewaresMergeDataProvider
     */
    public function testMergeMiddlewares(array $groupMiddlewares, array $routeMiddlewares, array $expectedMiddlewares): void
    {
        $groupConfig = RouteGroupConfigFactory::createRouteConfig();
        $groupConfig->middlewares($groupMiddlewares);

        $routeConfig = RouteGroupConfigFactory::createRouteConfig();
        $routeConfig->middlewares($routeMiddlewares);

        $routeConfig->mergeConfig($groupConfig);

        $this->assertEquals($expectedMiddlewares, $routeConfig->getMiddlewares());
    }
}
