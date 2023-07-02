<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\RouteMap;

use Hotaruma\HttpRouter\Exception\RouteConfigInvalidArgumentException;
use Hotaruma\HttpRouter\Exception\RouteInvalidArgumentException;
use Hotaruma\HttpRouter\Interface\Route\RouteConfigureInterface;

interface RouteMapMethodsInterface
{
    /**
     * Add route with no http methods.
     *
     * @param string $path
     * @param mixed $action
     * @return RouteConfigureInterface
     *
     * @throws RouteConfigInvalidArgumentException|RouteInvalidArgumentException
     */
    public function add(string $path, mixed $action): RouteConfigureInterface;

    /**
     * Route will be accessed for any http method.
     *
     * @param string $path Route path
     * @param mixed $action Callable action
     * @return RouteConfigureInterface
     *
     * @throws RouteConfigInvalidArgumentException|RouteInvalidArgumentException
     */
    public function any(string $path, mixed $action): RouteConfigureInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @return RouteConfigureInterface
     *
     * @throws RouteConfigInvalidArgumentException|RouteInvalidArgumentException
     */
    public function get(string $path, mixed $action): RouteConfigureInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @return RouteConfigureInterface
     *
     * @throws RouteConfigInvalidArgumentException|RouteInvalidArgumentException
     */
    public function post(string $path, mixed $action): RouteConfigureInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @return RouteConfigureInterface
     *
     * @throws RouteConfigInvalidArgumentException|RouteInvalidArgumentException
     */
    public function put(string $path, mixed $action): RouteConfigureInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @return RouteConfigureInterface
     *
     * @throws RouteConfigInvalidArgumentException|RouteInvalidArgumentException
     */
    public function delete(string $path, mixed $action): RouteConfigureInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @return RouteConfigureInterface
     *
     * @throws RouteConfigInvalidArgumentException|RouteInvalidArgumentException
     */
    public function head(string $path, mixed $action): RouteConfigureInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @return RouteConfigureInterface
     *
     * @throws RouteConfigInvalidArgumentException|RouteInvalidArgumentException
     */
    public function options(string $path, mixed $action): RouteConfigureInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @return RouteConfigureInterface
     *
     * @throws RouteConfigInvalidArgumentException|RouteInvalidArgumentException
     */
    public function trace(string $path, mixed $action): RouteConfigureInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @return RouteConfigureInterface
     *
     * @throws RouteConfigInvalidArgumentException|RouteInvalidArgumentException
     */
    public function connect(string $path, mixed $action): RouteConfigureInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @return RouteConfigureInterface
     *
     * @throws RouteConfigInvalidArgumentException|RouteInvalidArgumentException
     */
    public function patch(string $path, mixed $action): RouteConfigureInterface;
}
