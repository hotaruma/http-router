<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interfaces\RouteMap;

use Hotaruma\HttpRouter\Exception\RouteConfigInvalidArgument;
use Hotaruma\HttpRouter\Exception\RouteInvalidArgument;
use Hotaruma\HttpRouter\Interfaces\Route\RouteConfigureInterface;

interface RouteMapMethodsInterface
{
    /**
     * Route will be accessed for any http method.
     *
     * @param string $path Route path
     * @param mixed $action Callable action
     * @return RouteConfigureInterface
     *
     * @throws RouteConfigInvalidArgument|RouteInvalidArgument
     */
    public function any(string $path, mixed $action): RouteConfigureInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @return RouteConfigureInterface
     *
     * @throws RouteConfigInvalidArgument|RouteInvalidArgument
     */
    public function get(string $path, mixed $action): RouteConfigureInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @return RouteConfigureInterface
     *
     * @throws RouteConfigInvalidArgument|RouteInvalidArgument
     */
    public function post(string $path, mixed $action): RouteConfigureInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @return RouteConfigureInterface
     *
     * @throws RouteConfigInvalidArgument|RouteInvalidArgument
     */
    public function put(string $path, mixed $action): RouteConfigureInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @return RouteConfigureInterface
     *
     * @throws RouteConfigInvalidArgument|RouteInvalidArgument
     */
    public function delete(string $path, mixed $action): RouteConfigureInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @return RouteConfigureInterface
     *
     * @throws RouteConfigInvalidArgument|RouteInvalidArgument
     */
    public function head(string $path, mixed $action): RouteConfigureInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @return RouteConfigureInterface
     *
     * @throws RouteConfigInvalidArgument|RouteInvalidArgument
     */
    public function options(string $path, mixed $action): RouteConfigureInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @return RouteConfigureInterface
     *
     * @throws RouteConfigInvalidArgument|RouteInvalidArgument
     */
    public function trace(string $path, mixed $action): RouteConfigureInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @return RouteConfigureInterface
     *
     * @throws RouteConfigInvalidArgument|RouteInvalidArgument
     */
    public function connect(string $path, mixed $action): RouteConfigureInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @return RouteConfigureInterface
     *
     * @throws RouteConfigInvalidArgument|RouteInvalidArgument
     */
    public function patch(string $path, mixed $action): RouteConfigureInterface;
}
