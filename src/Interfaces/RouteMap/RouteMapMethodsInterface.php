<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interfaces\RouteMap;

use Hotaruma\HttpRouter\Interfaces\Route\RouteConfigureInterface;
use Hotaruma\HttpRouter\Interfaces\Method;

interface RouteMapMethodsInterface
{
    /**
     * Route factory.
     *
     * @param string $path Route path
     * @param mixed $action Callable action
     * @param Method|array<Method> $methods Http methods
     * @param string $name Route name
     * @return RouteConfigureInterface
     */
    public function create(string $path, mixed $action, Method|array $methods, string $name = ''): RouteConfigureInterface;

    /**
     * Route will be accessed for any http method.
     *
     * @param string $path Route path
     * @param mixed $action Callable action
     * @param string $name Route name
     * @return RouteConfigureInterface
     */
    public function any(string $path, mixed $action, string $name = ''): RouteConfigureInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @param string $name Route name
     * @return RouteConfigureInterface
     */
    public function get(string $path, mixed $action, string $name = ''): RouteConfigureInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @param string $name Route name
     * @return RouteConfigureInterface
     */
    public function post(string $path, mixed $action, string $name = ''): RouteConfigureInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @param string $name Route name
     * @return RouteConfigureInterface
     */
    public function put(string $path, mixed $action, string $name = ''): RouteConfigureInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @param string $name Route name
     * @return RouteConfigureInterface
     */
    public function delete(string $path, mixed $action, string $name = ''): RouteConfigureInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @param string $name Route name
     * @return RouteConfigureInterface
     */
    public function head(string $path, mixed $action, string $name = ''): RouteConfigureInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @param string $name Route name
     * @return RouteConfigureInterface
     */
    public function options(string $path, mixed $action, string $name = ''): RouteConfigureInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @param string $name Route name
     * @return RouteConfigureInterface
     */
    public function trace(string $path, mixed $action, string $name = ''): RouteConfigureInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @param string $name Route name
     * @return RouteConfigureInterface
     */
    public function connect(string $path, mixed $action, string $name = ''): RouteConfigureInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @param string $name Route name
     * @return RouteConfigureInterface
     */
    public function patch(string $path, mixed $action, string $name = ''): RouteConfigureInterface;
}
