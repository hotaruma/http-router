<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\Route;

use Hotaruma\HttpRouter\Exception\RouteConfigInvalidArgumentException;
use Hotaruma\HttpRouter\Interface\Enum\RequestMethodInterface;

interface RouteConfigureInterface
{
    /**
     * Set route config.
     *
     * @param array<string,string>|null $rules Regex rules for attributes in path
     * @param array<string,string>|null $defaults Default values for attributes in path
     * @param mixed $middlewares Middlewares list
     * @param string|null $path Url path
     * @param string|null $name Route name
     * @param RequestMethodInterface|array<RequestMethodInterface>|null $methods Http methods
     * @return void
     *
     * @throws RouteConfigInvalidArgumentException
     */
    public function config(
        array                        $rules = null,
        array                        $defaults = null,
        mixed                        $middlewares = null,
        string                       $path = null,
        string                       $name = null,
        RequestMethodInterface|array $methods = null
    ): void;
}
