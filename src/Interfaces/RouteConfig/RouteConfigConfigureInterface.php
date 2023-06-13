<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interfaces\RouteConfig;

use Closure;
use Hotaruma\HttpRouter\Exception\RouteConfigInvalidArgument;
use Hotaruma\HttpRouter\Interfaces\Method;

interface RouteConfigConfigureInterface
{
    /**
     * @param string $path New path
     * @return RouteConfigConfigureInterface
     *
     * @throws RouteConfigInvalidArgument
     */
    public function path(string $path): RouteConfigConfigureInterface;

    /**
     * @return string Current path
     */
    public function getPath(): string;

    /**
     * @param string $name New name
     * @return RouteConfigConfigureInterface
     *
     * @throws RouteConfigInvalidArgument
     */
    public function name(string $name): RouteConfigConfigureInterface;

    /**
     * @return string Current name
     */
    public function getName(): string;

    /**
     * @param Method|array<Method> $methods Http methods
     * @return RouteConfigConfigureInterface
     *
     * @throws RouteConfigInvalidArgument
     */
    public function methods(Method|array $methods): RouteConfigConfigureInterface;

    /**
     * @return array<Method> Current methods
     */
    public function getMethods(): array;

    /**
     * @param array<string,string> $rules Regex rules for attributes in path
     * @return RouteConfigConfigureInterface
     *
     * @throws RouteConfigInvalidArgument
     */
    public function rules(array $rules): RouteConfigConfigureInterface;

    /**
     * @return array<string,string> Current regex rules for attributes in path
     */
    public function getRules(): array;

    /**
     * @param array<string,string> $defaults Default values for attributes in path
     * @return RouteConfigConfigureInterface
     *
     * @throws RouteConfigInvalidArgument
     */
    public function defaults(array $defaults): RouteConfigConfigureInterface;

    /**
     * @return array<string,string> Current default values for attributes in path
     */
    public function getDefaults(): array;

    /**
     * @param Closure|array $middlewares Middlewares list
     * @return RouteConfigConfigureInterface
     *
     * @throws RouteConfigInvalidArgument
     */
    public function middlewares(Closure|array $middlewares): RouteConfigConfigureInterface;

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
