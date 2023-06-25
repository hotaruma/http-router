<?php

declare(strict_types=1);

namespace Tests\Unit;

use Closure;
use Hotaruma\HttpRouter\Exception\ConfigInvalidArgumentException;
use Hotaruma\HttpRouter\Factory\ConfigStoreFactory;
use PHPUnit\Framework\TestCase;

class RouteConfigTest extends TestCase
{
    /**
     * @dataProvider \Tests\DataProvider\RouteConfigDataProvider::rulesDataProvider
     */
    public function testRules(array $rules): void
    {
        $config = ConfigStoreFactory::create();
        $config->rules($rules);
        $this->assertEquals($rules, $config->getRules());
    }

    /**
     * @dataProvider \Tests\DataProvider\RouteConfigDataProvider::invalidRulesDataProvider
     */
    public function testInvalidRules(array $rules): void
    {
        $this->expectException(ConfigInvalidArgumentException::class);

        $config = ConfigStoreFactory::create();
        $config->rules($rules);
    }


    /**
     * @dataProvider \Tests\DataProvider\RouteConfigDataProvider::defaultsDataProvider
     */
    public function testDefaults(array $defaults): void
    {
        $config = ConfigStoreFactory::create();
        $config->defaults($defaults);
        $this->assertEquals($defaults, $config->getDefaults());
    }

    /**
     * @dataProvider \Tests\DataProvider\RouteConfigDataProvider::invalidDefaultsDataProvider
     */
    public function testInvalidDefaults(array $defaults): void
    {
        $this->expectException(ConfigInvalidArgumentException::class);

        $config = ConfigStoreFactory::create();
        $config->defaults($defaults);
    }


    /**
     * @dataProvider \Tests\DataProvider\RouteConfigDataProvider::middlewaresDataProvider
     */
    public function testMiddlewares(array|Closure $middlewares, mixed $expected = null): void
    {
        $config = ConfigStoreFactory::create();
        $config->middlewares($middlewares);
        $this->assertEquals($expected ?? $middlewares, $config->getMiddlewares());
    }


    /**
     * @dataProvider \Tests\DataProvider\RouteConfigDataProvider::pathDataProvider
     */
    public function testPath(string $path, string $expectedPath): void
    {
        $config = ConfigStoreFactory::create();
        $config->path($path);
        $this->assertEquals($expectedPath, $config->getPath());
    }

    /**
     * @dataProvider \Tests\DataProvider\RouteConfigDataProvider::invalidPathDataProvider
     */
    public function testInvalidPath(string $path): void
    {
        $this->expectException(ConfigInvalidArgumentException::class);

        $config = ConfigStoreFactory::create();
        $config->path($path);
    }


    /**
     * @dataProvider \Tests\DataProvider\RouteConfigDataProvider::nameDataProvider
     */
    public function testName(string $name, string $expectedName): void
    {
        $config = ConfigStoreFactory::create();
        $config->name($name);
        $this->assertEquals($expectedName, $config->getName());
    }


    /**
     * @dataProvider \Tests\DataProvider\RouteConfigDataProvider::methodsDataProvider
     */
    public function testMethods($methods, array $expectedMethods): void
    {
        $config = ConfigStoreFactory::create();
        $config->methods($methods);
        $this->assertEquals($expectedMethods, $config->getMethods());
    }

    /**
     * @dataProvider \Tests\DataProvider\RouteConfigDataProvider::invalidMethodsDataProvider
     */
    public function testInvalidMethods(array $methods): void
    {
        $this->expectException(ConfigInvalidArgumentException::class);

        $config = ConfigStoreFactory::create();
        $config->methods($methods);
    }


    /**
     * @dataProvider \Tests\DataProvider\RouteConfigDataProvider::pathMergeDataProvider
     */
    public function testMergePath(string $mergePath, string $initialPath, string $expectedPath): void
    {
        $routeConfig = ConfigStoreFactory::create();
        $routeConfig->path($mergePath);

        $baseConfig = ConfigStoreFactory::create();
        $baseConfig->path($initialPath);

        $baseConfig->mergeConfig($routeConfig);

        $this->assertEquals($expectedPath, $baseConfig->getPath());
    }


    /**
     * @dataProvider \Tests\DataProvider\RouteConfigDataProvider::methodsMergeDataProvider
     */
    public function testMergeMethods(array $mergeMethods, array $initialMethods, array $expectedMethods): void
    {
        $routeConfig = ConfigStoreFactory::create();
        $routeConfig->methods($mergeMethods);

        $baseConfig = ConfigStoreFactory::create();
        $baseConfig->methods($initialMethods);

        $baseConfig->mergeConfig($routeConfig);

        $this->assertEquals($expectedMethods, $baseConfig->getMethods());
    }


    /**
     * @dataProvider \Tests\DataProvider\RouteConfigDataProvider::nameMergeDataProvider
     */
    public function testMergeName(string $mergeName, string $initialName, string $expectedName): void
    {
        $routeConfig = ConfigStoreFactory::create();
        $routeConfig->name($mergeName);

        $baseConfig = ConfigStoreFactory::create();
        $baseConfig->name($initialName);

        $baseConfig->mergeConfig($routeConfig);

        $this->assertEquals($expectedName, $baseConfig->getName());
    }


    /**
     * @dataProvider \Tests\DataProvider\RouteConfigDataProvider::rulesMergeDataProvider
     */
    public function testMergeRules(array $mergeRules, array $initialRules, array $expectedRules): void
    {
        $routeConfig = ConfigStoreFactory::create();
        $routeConfig->rules($mergeRules);

        $baseConfig = ConfigStoreFactory::create();
        $baseConfig->rules($initialRules);

        $baseConfig->mergeConfig($routeConfig);

        $this->assertEquals($expectedRules, $baseConfig->getRules());
    }


    /**
     * @dataProvider \Tests\DataProvider\RouteConfigDataProvider::defaultsMergeDataProvider
     */
    public function testMergeDefaults(array $mergeDefaults, array $initialDefaults, array $expectedDefaults): void
    {
        $routeConfig = ConfigStoreFactory::create();
        $routeConfig->defaults($mergeDefaults);

        $baseConfig = ConfigStoreFactory::create();
        $baseConfig->defaults($initialDefaults);

        $baseConfig->mergeConfig($routeConfig);

        $this->assertEquals($expectedDefaults, $baseConfig->getDefaults());
    }


    /**
     * @dataProvider \Tests\DataProvider\RouteConfigDataProvider::middlewaresMergeDataProvider
     */
    public function testMergeMiddlewares(array $mergeMiddlewares, array $initialMiddlewares, array $expectedMiddlewares): void
    {
        $routeConfig = ConfigStoreFactory::create();
        $routeConfig->middlewares($mergeMiddlewares);

        $baseConfig = ConfigStoreFactory::create();
        $baseConfig->middlewares($initialMiddlewares);

        $baseConfig->mergeConfig($routeConfig);

        $this->assertEquals($expectedMiddlewares, $baseConfig->getMiddlewares());
    }
}
