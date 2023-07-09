<?php

declare(strict_types=1);

namespace Hotaruma\Tests\Unit;

use Closure;
use Hotaruma\HttpRouter\Factory\GroupConfigStoreFactory;
use PHPUnit\Framework\TestCase;

class GroupRouteConfigTest extends TestCase
{
    /**
     * @dataProvider \Hotaruma\Tests\DataProvider\GroupRouteConfigDataProvider::pathMergeDataProvider
     */
    public function testMergePath(string $groupPath, string $routePath, string $expectedPath): void
    {
        $groupConfig = GroupConfigStoreFactory::create();
        $groupConfig->path($groupPath);

        $routeConfig = GroupConfigStoreFactory::create();
        $routeConfig->path($routePath);

        $routeConfig->mergeConfig($groupConfig);

        $this->assertEquals($expectedPath, $routeConfig->getPath());
    }


    /**
     * @dataProvider \Hotaruma\Tests\DataProvider\GroupRouteConfigDataProvider::methodsMergeDataProvider
     */
    public function testMergeMethods(array $groupMethods, array $routeMethods, array $expectedMethods): void
    {
        $groupConfig = GroupConfigStoreFactory::create();
        $groupConfig->methods($groupMethods);

        $routeConfig = GroupConfigStoreFactory::create();
        $routeConfig->methods($routeMethods);

        $routeConfig->mergeConfig($groupConfig);

        $this->assertEquals($expectedMethods, $routeConfig->getMethods());
    }


    /**
     * @dataProvider \Hotaruma\Tests\DataProvider\GroupRouteConfigDataProvider::nameMergeDataProvider
     */
    public function testMergeName(string $groupName, string $routeName, string $expectedName): void
    {
        $groupConfig = GroupConfigStoreFactory::create();
        $groupConfig->name($groupName);

        $routeConfig = GroupConfigStoreFactory::create();
        $routeConfig->name($routeName);

        $routeConfig->mergeConfig($groupConfig);

        $this->assertEquals($expectedName, $routeConfig->getName());
    }


    /**
     * @dataProvider \Hotaruma\Tests\DataProvider\GroupRouteConfigDataProvider::rulesMergeDataProvider
     */
    public function testMergeRules(array $groupRules, array $routeRules, array $expectedRules): void
    {
        $groupConfig = GroupConfigStoreFactory::create();
        $groupConfig->rules($groupRules);

        $routeConfig = GroupConfigStoreFactory::create();
        $routeConfig->rules($routeRules);

        $routeConfig->mergeConfig($groupConfig);

        $this->assertEquals($expectedRules, $routeConfig->getRules());
    }


    /**
     * @dataProvider \Hotaruma\Tests\DataProvider\GroupRouteConfigDataProvider::defaultsMergeDataProvider
     */
    public function testMergeDefaults(array $groupDefaults, array $routeDefaults, array $expectedDefaults): void
    {
        $groupConfig = GroupConfigStoreFactory::create();
        $groupConfig->defaults($groupDefaults);

        $routeConfig = GroupConfigStoreFactory::create();
        $routeConfig->defaults($routeDefaults);

        $routeConfig->mergeConfig($groupConfig);

        $this->assertEquals($expectedDefaults, $routeConfig->getDefaults());
    }


    /**
     * @dataProvider \Hotaruma\Tests\DataProvider\GroupRouteConfigDataProvider::middlewaresMergeDataProvider
     */
    public function testMergeMiddlewares(mixed $groupMiddlewares, mixed $routeMiddlewares, array $expectedMiddlewares): void
    {
        $groupConfig = GroupConfigStoreFactory::create();
        $groupConfig->middlewares($groupMiddlewares);

        $routeConfig = GroupConfigStoreFactory::create();
        $routeConfig->middlewares($routeMiddlewares);

        $routeConfig->mergeConfig($groupConfig);

        $this->assertEquals($expectedMiddlewares, $routeConfig->getMiddlewares());
    }
}
