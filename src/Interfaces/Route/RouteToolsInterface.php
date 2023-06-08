<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interfaces\Route;

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
     * @return array<string,string> Current regex rules for attributes in path
     */
    public function getRules(): array;

    /**
     * @return array<string,string> Current default values for attributes in path
     */
    public function getDefaults(): array;

    /**
     * @return array Current middlewares
     */
    public function getMiddlewares(): array;
}
