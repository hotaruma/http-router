<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interfaces\RouteMap;

use Hotaruma\HttpRouter\Interfaces\Method;
use Hotaruma\HttpRouter\Interfaces\RouteConfig\ConfigurableInterface;

interface RouteMapMethodsInterface
{
    /**
     * Route factory.
     *
     * @param string $path Route path
     * @param mixed $action Callable action
     * @param Method|array<Method> $methods Http methods
     * @param string $name Route name
     * @return ConfigurableInterface
     */
    public function create(string $path, mixed $action, Method|array $methods, string $name = ''): ConfigurableInterface;

    /**
     * Route will be accessed for any http method.
     *
     * @param string $path Route path
     * @param mixed $action Callable action
     * @param string $name Route name
     * @return ConfigurableInterface
     */
    public function any(string $path, mixed $action, string $name = ''): ConfigurableInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @param string $name Route name
     * @return ConfigurableInterface
     */
    public function get(string $path, mixed $action, string $name = ''): ConfigurableInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @param string $name Route name
     * @return ConfigurableInterface
     */
    public function post(string $path, mixed $action, string $name = ''): ConfigurableInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @param string $name Route name
     * @return ConfigurableInterface
     */
    public function put(string $path, mixed $action, string $name = ''): ConfigurableInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @param string $name Route name
     * @return ConfigurableInterface
     */
    public function delete(string $path, mixed $action, string $name = ''): ConfigurableInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @param string $name Route name
     * @return ConfigurableInterface
     */
    public function head(string $path, mixed $action, string $name = ''): ConfigurableInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @param string $name Route name
     * @return ConfigurableInterface
     */
    public function options(string $path, mixed $action, string $name = ''): ConfigurableInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @param string $name Route name
     * @return ConfigurableInterface
     */
    public function trace(string $path, mixed $action, string $name = ''): ConfigurableInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @param string $name Route name
     * @return ConfigurableInterface
     */
    public function connect(string $path, mixed $action, string $name = ''): ConfigurableInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @param string $name Route name
     * @return ConfigurableInterface
     */
    public function patch(string $path, mixed $action, string $name = ''): ConfigurableInterface;
}
