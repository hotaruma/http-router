<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interfaces\RouteConfig;

use Closure;
use Hotaruma\HttpRouter\Interfaces\Method;

interface ConfigurableInterface
{
    /**
     * Set route/s config.
     *
     * @param array $rules Regex rules for attributes in path
     * @param array $defaults Default values for attributes in path
     * @param Closure|array $middlewares Middlewares list
     * @param string $path Path
     * @param string $name Name
     * @param Method|array $methods Http methods
     * @return void
     */
    public function config(
        array         $rules = [],
        array         $defaults = [],
        Closure|array $middlewares = [],
        string        $path = '',
        string        $name = '',
        Method|array  $methods = []
    ): void;

    /**
     * @return RouteConfigInterface
     */
    public function getConfig(): RouteConfigInterface;
}
