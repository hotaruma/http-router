<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\RouteMap;

use Hotaruma\HttpRouter\Exception\{RouteConfigInvalidArgumentException,
    RouteInvalidArgumentException,
    RouteMapLogicException};
use Hotaruma\HttpRouter\Interface\Enum\RequestMethodInterface;
use Hotaruma\HttpRouter\Interface\Route\RouteConfigureInterface;

interface RouteMapMethodsInterface
{
    /**
     * Add route with no http methods.
     *
     * @param string $path
     * @param mixed $action
     * @param RequestMethodInterface $methods
     * @return RouteConfigureInterface
     *
     * @throws RouteConfigInvalidArgumentException|RouteInvalidArgumentException|RouteMapLogicException
     */
    public function add(string $path, mixed $action, ...$methods): RouteConfigureInterface;

    /**
     * Route will be accessed for any http method.
     *
     * @param string $path Route path
     * @param mixed $action Callable action
     * @return RouteConfigureInterface
     *
     * @throws RouteConfigInvalidArgumentException|RouteInvalidArgumentException|RouteMapLogicException
     */
    public function any(string $path, mixed $action): RouteConfigureInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @return RouteConfigureInterface
     *
     * @throws RouteConfigInvalidArgumentException|RouteInvalidArgumentException|RouteMapLogicException
     */
    public function get(string $path, mixed $action): RouteConfigureInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @return RouteConfigureInterface
     *
     * @throws RouteConfigInvalidArgumentException|RouteInvalidArgumentException|RouteMapLogicException
     */
    public function post(string $path, mixed $action): RouteConfigureInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @return RouteConfigureInterface
     *
     * @throws RouteConfigInvalidArgumentException|RouteInvalidArgumentException|RouteMapLogicException
     */
    public function put(string $path, mixed $action): RouteConfigureInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @return RouteConfigureInterface
     *
     * @throws RouteConfigInvalidArgumentException|RouteInvalidArgumentException|RouteMapLogicException
     */
    public function delete(string $path, mixed $action): RouteConfigureInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @return RouteConfigureInterface
     *
     * @throws RouteConfigInvalidArgumentException|RouteInvalidArgumentException|RouteMapLogicException
     */
    public function head(string $path, mixed $action): RouteConfigureInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @return RouteConfigureInterface
     *
     * @throws RouteConfigInvalidArgumentException|RouteInvalidArgumentException|RouteMapLogicException
     */
    public function options(string $path, mixed $action): RouteConfigureInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @return RouteConfigureInterface
     *
     * @throws RouteConfigInvalidArgumentException|RouteInvalidArgumentException|RouteMapLogicException
     */
    public function trace(string $path, mixed $action): RouteConfigureInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @return RouteConfigureInterface
     *
     * @throws RouteConfigInvalidArgumentException|RouteInvalidArgumentException|RouteMapLogicException
     */
    public function connect(string $path, mixed $action): RouteConfigureInterface;

    /**
     * @param string $path Route path
     * @param mixed $action Callable action
     * @return RouteConfigureInterface
     *
     * @throws RouteConfigInvalidArgumentException|RouteInvalidArgumentException|RouteMapLogicException
     */
    public function patch(string $path, mixed $action): RouteConfigureInterface;
}
