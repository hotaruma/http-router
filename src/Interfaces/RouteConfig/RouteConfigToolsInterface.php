<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interfaces\RouteConfig;

use Closure;
use Hotaruma\HttpRouter\Interfaces\Method;

interface RouteConfigToolsInterface
{
    /**
     * @param string $path New path
     * @return RouteConfigToolsInterface
     */
    public function path(string $path): RouteConfigToolsInterface;

    /**
     * @return string Current path
     */
    public function getPath(): string;

    /**
     * @param string $name New name
     * @return RouteConfigToolsInterface
     */
    public function name(string $name): RouteConfigToolsInterface;

    /**
     * @return string Current name
     */
    public function getName(): string;

    /**
     * @param Method|array<Method> $methods Http methods
     * @return RouteConfigToolsInterface
     */
    public function methods(Method|array $methods): RouteConfigToolsInterface;

    /**
     * @return array<Method> Current methods
     */
    public function getMethods(): array;

    /**
     * @param array<string,string> $rules Regex rules for attributes in path
     * @return RouteConfigToolsInterface
     */
    public function rules(array $rules): RouteConfigToolsInterface;

    /**
     * @return array<string,string> Current regex rules for attributes in path
     */
    public function getRules(): array;

    /**
     * @param array<string,string> $defaults Default values for attributes in path
     * @return RouteConfigToolsInterface
     */
    public function defaults(array $defaults): RouteConfigToolsInterface;

    /**
     * @return array<string,string> Current default values for attributes in path
     */
    public function getDefaults(): array;

    /**
     * @param Closure|array $middlewares Middlewares list
     * @return RouteConfigToolsInterface
     */
    public function middlewares(Closure|array $middlewares): RouteConfigToolsInterface;

    /**
     * @return array Current middlewares
     */
    public function getMiddlewares(): array;

    /**
     * Merge all config with current params
     *
     * @param RouteConfigInterface $routeConfig
     * @return void
     */
    public function mergeConfig(RouteConfigInterface $routeConfig): void;
}
