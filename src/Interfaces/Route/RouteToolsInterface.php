<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interfaces\Route;

use Closure;
use Hotaruma\HttpRouter\Interfaces\Method;

interface RouteToolsInterface
{
    /**
     * @param string $path New path
     * @return RouteInterface
     */
    public function path(string $path): RouteInterface;

    /**
     * @return string Current path
     */
    public function getPath(): string;

    /**
     * @param string $name New name
     * @return RouteInterface
     */
    public function name(string $name): RouteInterface;

    /**
     * @return string Current name
     */
    public function getName(): string;

    /**
     * @param Method|array<Method> $methods Http methods
     * @return RouteInterface
     */
    public function methods(Method|array $methods): RouteInterface;

    /**
     * @return array<Method> Current methods
     */
    public function getMethods(): array;

    /**
     * @param mixed $action Route action
     * @return RouteInterface
     */
    public function action(mixed $action): RouteInterface;

    /**
     * @return mixed Callable action
     */
    public function getAction(): mixed;

    /**
     * @param array<string,string> $rules Regex rules for attributes in path
     * @return RouteConfigureInterface
     */
    public function rules(array $rules): RouteConfigureInterface;

    /**
     * @return array<string,string> Current regex rules for attributes in path
     */
    public function getRules(): array;

    /**
     * @param array<string,string> $defaults Default values for attributes in path
     * @return RouteConfigureInterface
     */
    public function defaults(array $defaults): RouteConfigureInterface;

    /**
     * @return array<string,string> Current default values for attributes in path
     */
    public function getDefaults(): array;

    /**
     * @param Closure|array $middlewares Middlewares list
     * @return RouteConfigureInterface
     */
    public function middlewares(Closure|array $middlewares): RouteConfigureInterface;

    /**
     * @return array Current middlewares
     */
    public function getMiddlewares(): array;

    /**
     * Set function for merge config with RouteMap group.
     *
     * @param Closure $fn
     * @return void
     */
    public function fnMergeConfigWithGroup(Closure $fn): void;
}
