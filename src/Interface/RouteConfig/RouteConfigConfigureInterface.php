<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\RouteConfig;

use Closure;
use Hotaruma\HttpRouter\Exception\RouteConfigInvalidArgumentException;
use Hotaruma\HttpRouter\Interface\Enum\RequestMethodInterface;

interface RouteConfigConfigureInterface
{
    /**
     * @param string $path New path
     * @return RouteConfigConfigureInterface
     *
     * @throws RouteConfigInvalidArgumentException
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
     * @throws RouteConfigInvalidArgumentException
     */
    public function name(string $name): RouteConfigConfigureInterface;

    /**
     * @return string Current name
     */
    public function getName(): string;

    /**
     * @param RequestMethodInterface|array<RequestMethodInterface> $methods Http methods
     * @return RouteConfigConfigureInterface
     *
     * @throws RouteConfigInvalidArgumentException
     */
    public function methods(RequestMethodInterface|array $methods): RouteConfigConfigureInterface;

    /**
     * @return array<RequestMethodInterface> Current methods
     */
    public function getMethods(): array;

    /**
     * @param array<string,string> $rules Regex rules for attributes in path
     * @return RouteConfigConfigureInterface
     *
     * @throws RouteConfigInvalidArgumentException
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
     * @throws RouteConfigInvalidArgumentException
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
     * @throws RouteConfigInvalidArgumentException
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
