<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Utils;

use Closure;
use Hotaruma\HttpRouter\Interfaces\Method;

readonly class GroupConfig
{
    /**
     * @param array $rules
     * @param array $defaults
     * @param Closure|array $middlewares
     * @param string $pathPrefix
     * @param string $namePrefix
     * @param Method|array $methods
     */
    public function __construct(
        protected array         $rules = [],
        protected array         $defaults = [],
        protected Closure|array $middlewares = [],
        protected string        $pathPrefix = '',
        protected string        $namePrefix = '',
        protected Method|array  $methods = []
    )
    {
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * @return array
     */
    public function getDefaults(): array
    {
        return $this->defaults;
    }

    /**
     * @return array
     */
    public function getMiddlewares(): array
    {
        return (array)$this->middlewares;
    }

    /**
     * @return string
     */
    public function getPathPrefix(): string
    {
        return $this->pathPrefix;
    }

    /**
     * @return string
     */
    public function getNamePrefix(): string
    {
        return $this->namePrefix;
    }

    /**
     * @return array
     */
    public function getMethods(): array
    {
        return (array)$this->methods;
    }
}
