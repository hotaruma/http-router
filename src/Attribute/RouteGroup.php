<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Attribute;

use Attribute;
use Hotaruma\HttpRouter\Config\RouteGroupConfig;
use Hotaruma\HttpRouter\Interface\Attribute\RouteGroupInterface;
use Hotaruma\HttpRouter\Interface\Enum\RequestMethodInterface;

#[Attribute(Attribute::TARGET_CLASS)]
class RouteGroup extends RouteGroupConfig implements RouteGroupInterface
{
    /**
     * @param array<string,string> $rules
     * @param array<string,string> $defaults
     * @param mixed $middlewares
     * @param string $pathPrefix
     * @param string $namePrefix
     * @param array<RequestMethodInterface>|RequestMethodInterface $methods
     */
    public function __construct(
        array                        $rules = [],
        array                        $defaults = [],
        mixed                        $middlewares = [],
        string                       $pathPrefix = '/',
        string                       $namePrefix = '',
        array|RequestMethodInterface $methods = [],
    ) {
        $this->rules($rules);
        $this->defaults($defaults);
        $this->middlewares($middlewares);
        $this->path($pathPrefix);
        $this->name($namePrefix);
        $this->methods($methods);
    }
}
