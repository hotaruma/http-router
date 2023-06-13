<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interfaces\Route;

use Closure;
use Hotaruma\HttpRouter\Exception\RouteConfigInvalidArgument;
use Hotaruma\HttpRouter\Interfaces\Method;

interface RouteConfigureInterface
{
    /**
     * Set route config.
     *
     * @param array<string,string>|null $rules Regex rules for attributes in path
     * @param array<string,string>|null $defaults Default values for attributes in path
     * @param Closure|array|null $middlewares Middlewares list
     * @param string|null $path Url path
     * @param string|null $name Route name
     * @param Method|array<Method>|null $methods Http methods
     * @return void
     *
     * @throws RouteConfigInvalidArgument
     */
    public function config(
        array         $rules = null,
        array         $defaults = null,
        Closure|array $middlewares = null,
        string        $path = null,
        string        $name = null,
        Method|array  $methods = null
    ): void;
}
