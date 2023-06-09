<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interfaces\Route;

use Closure;

interface RouteConfigureInterface
{
    /**
     * Set route config.
     *
     * @param array $rules Regex rules for attributes in path
     * @param array $defaults Default values for attributes in path
     * @param Closure|array $middlewares Middlewares list
     * @return void
     */
    public function config(array $rules = [], array $defaults = [], Closure|array $middlewares = []): void;
}
