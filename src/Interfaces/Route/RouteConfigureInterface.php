<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interfaces\Route;

interface RouteConfigureInterface
{
    /**
     * @param array<string,string> $rules Regex rules for attributes in path
     * @return RouteConfigureInterface
     */
    public function rules(array $rules): RouteConfigureInterface;

    /**
     * @param array<string,string> $defaults Default values for attributes in path
     * @return RouteConfigureInterface
     */
    public function defaults(array $defaults): RouteConfigureInterface;

    /**
     * @param callable|array $middlewares Middlewares list
     * @return RouteConfigureInterface
     */
    public function middlewares(callable|array $middlewares): RouteConfigureInterface;
}
