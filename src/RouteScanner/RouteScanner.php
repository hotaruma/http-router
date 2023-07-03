<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\RouteScanner;

use Closure;
use Hotaruma\HttpRouter\Exception\RouteScannerReflectionException;
use Hotaruma\HttpRouter\Interface\Config\ConfigInterface;
use Hotaruma\HttpRouter\Interface\RouteScanner\RouteScannerInterface;
use Hotaruma\HttpRouter\Interface\Attribute\{RouteGroupInterface, RouteInterface};
use Hotaruma\HttpRouter\Interface\RouteMap\RouteMapInterface;
use Hotaruma\HttpRouter\RouteMap;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;

class RouteScanner implements RouteScannerInterface
{
    /**
     * @var RouteMapInterface
     */
    protected RouteMapInterface $routeMap;

    /**
     * @var Closure(string $className, string $methodName): mixed
     */
    protected Closure $routeActionBuilder;

    /**
     * @inheritDoc
     */
    public function scanRoutes(...$classes): RouteMapInterface
    {
        $baseRouteMapGroupConfigStore = $this->getRouteMap()->getGroupConfigStoreFactory()::create();
        $baseRouteMapGroupConfigStore->mergeConfig($this->getRouteMap()->getConfigStore());

        foreach ($classes as $class) {
            try {
                $reflectionController = new ReflectionClass($class);

                $routeGroupReflectionAttributes = $reflectionController->getAttributes(
                    RouteGroupInterface::class,
                    ReflectionAttribute::IS_INSTANCEOF
                );

                $routeGroupReflectionAttribute = array_shift($routeGroupReflectionAttributes);

                if ($routeGroupReflectionAttribute) {
                    $this->mergeRouteMapConfig($routeGroupReflectionAttribute->newInstance());
                }

                foreach ($reflectionController->getMethods() as $method) {
                    $reflectionAttributes = $method->getAttributes(
                        RouteInterface::class,
                        ReflectionAttribute::IS_INSTANCEOF
                    );
                    foreach ($reflectionAttributes as $reflectionAttribute) {
                        $this->addRoute(
                            $reflectionAttribute->newInstance(),
                            $this->getRouteActionBuilder()($reflectionController->getName(), $method->getName())
                        );
                    }
                }
            } catch (ReflectionException $e) {
                throw new RouteScannerReflectionException($e->getMessage(), $e->getCode(), $e);
            }

            $this->getRouteMap()->getConfigStore()->config($baseRouteMapGroupConfigStore->getConfig());
        }

        return $this->getRouteMap();
    }

    /**
     * @inheritDoc
     */
    public function routeMap(RouteMapInterface $routeMap): void
    {
        $this->routeMap = $routeMap;
    }

    /**
     * @inheritDoc
     */
    public function routeActionBuilder(Closure $routeActionBuilder): void
    {
        $this->routeActionBuilder = $routeActionBuilder;
    }

    /**
     * @return RouteMapInterface
     */
    protected function getRouteMap(): RouteMapInterface
    {
        return $this->routeMap ??= new RouteMap();
    }

    /**
     * @return Closure(string $className, string $methodName): mixed
     */
    protected function getRouteActionBuilder(): Closure
    {
        return $this->routeActionBuilder ??= function (string $className, string $methodName): array {
            return [$className, $methodName];
        };
    }

    /**
     * @param ConfigInterface $routeGroupConfig
     * @return void
     */
    protected function mergeRouteMapConfig(ConfigInterface $routeGroupConfig): void
    {
        $this->getRouteMap()->getConfigStore()->config($routeGroupConfig);
    }

    /**
     * @param RouteInterface $routeConfig
     * @param mixed $action
     * @return void
     */
    protected function addRoute(ConfigInterface $routeConfig, mixed $action): void
    {
        $route = $this->getRouteMap()->add(
            $routeConfig->getPath(),
            $action,
            ...$routeConfig->getMethods()
        );
        $route->config(
            rules: $routeConfig->getRules(),
            defaults: $routeConfig->getDefaults(),
            middlewares: $routeConfig->getMiddlewares(),
            name: $routeConfig->getName(),
        );
    }
}
