<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Attribute;

use Attribute;
use Hotaruma\HttpRouter\Config\RouteConfig;
use Hotaruma\HttpRouter\Enum\AdditionalMethod;
use Hotaruma\HttpRouter\Interface\Attribute\RouteInterface;
use Hotaruma\HttpRouter\Interface\Enum\RequestMethodInterface;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Route extends RouteConfig implements RouteInterface
{
    /**
     * @param string $path
     * @param RequestMethodInterface|array<RequestMethodInterface> $methods
     * @param array<string,string> $rules
     * @param array<string,string> $defaults
     * @param mixed $middlewares
     * @param string $name
     */
    public function __construct(
        string                       $path,
        RequestMethodInterface|array $methods = [AdditionalMethod::NULL],
        array                        $rules = [],
        array                        $defaults = [],
        mixed                        $middlewares = [],
        string                       $name = '',
    ) {
        $this->path($path);
        $this->methods($methods);
        $this->rules($rules);
        $this->defaults($defaults);
        $this->middlewares($middlewares);
        $this->name($name);
    }
}
