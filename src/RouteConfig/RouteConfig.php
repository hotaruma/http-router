<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\RouteConfig;

use Closure;
use Hotaruma\HttpRouter\RouteConfigValidators\RouteConfigValidator;
use Hotaruma\HttpRouter\Interfaces\{Method,
    RouteConfig\RouteConfigInterface,
    RouteConfig\RouteConfigConfigureInterface,
    RouteConfig\RouteConfigToolsInterface,
    RouteConfigValidator\RouteConfigValidatorInterface};
use Hotaruma\HttpRouter\Utils\ConfigNormalizeUtils;

class RouteConfig implements RouteConfigInterface
{
    use ConfigNormalizeUtils;

    protected const KEY_REDUCE = 1;
    protected const MERGE_REDUCE = 2;
    protected const CONCATENATE_REDUCE = 3;

    protected const PATH_SEPARATOR = '/';
    protected const NAME_SEPARATOR = '.';

    /**
     * @param array<string,string> $rules
     * @param array<string,string> $defaults
     * @param Closure|array $middlewares
     * @param string $path
     * @param string $name
     * @param Method|array<Method> $methods
     * @param RouteConfigValidatorInterface $configValidator
     */
    public function __construct(
        protected array         $rules = [],
        protected array         $defaults = [],
        protected Closure|array $middlewares = [],
        protected string        $path = '',
        protected string        $name = '',
        protected Method|array  $methods = [],
        protected RouteConfigValidatorInterface $configValidator = new RouteConfigValidator()
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function validator(RouteConfigValidatorInterface $configValidator): RouteConfigToolsInterface
    {
        $this->configValidator = $configValidator;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function rules(array $rules): RouteConfigConfigureInterface
    {
        $this->getConfigValidator()->validateRules($rules);
        $this->rules = $rules;
        return $this;
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * @inheritDoc
     */
    public function defaults(array $defaults): RouteConfigConfigureInterface
    {
        $this->getConfigValidator()->validateDefaults($defaults);
        $this->defaults = $defaults;
        return $this;
    }

    /**
     * @return array
     */
    public function getDefaults(): array
    {
        return $this->defaults;
    }

    /**
     * @inheritDoc
     */
    public function middlewares(array|Closure $middlewares): RouteConfigConfigureInterface
    {
        $this->middlewares = (array)$middlewares;
        $this->getConfigValidator()->validateMiddlewares($middlewares);
        return $this;
    }

    /**
     * @return array
     */
    public function getMiddlewares(): array
    {
        return (array)$this->middlewares;
    }

    /**
     * @inheritDoc
     */
    public function path(string $path): RouteConfigConfigureInterface
    {
        $this->getConfigValidator()->validatePath($path);
        $this->path = $this->normalizePath($path);
        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @inheritDoc
     */
    public function name(string $name): RouteConfigConfigureInterface
    {
        $this->getConfigValidator()->validateName($name);
        $this->name = $this->normalizeName($name);
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function methods(Method|array $methods): RouteConfigConfigureInterface
    {
        $methods = (array)$methods;
        $this->getConfigValidator()->validateMethods($methods);
        $this->methods = $methods;
        return $this;
    }

    /**
     * @return array
     */
    public function getMethods(): array
    {
        return (array)$this->methods;
    }

    /**
     * @inheritDoc
     */
    public function mergeConfig(RouteConfigInterface $routeConfig): void
    {
        $this->path($this->reduceConfig([$routeConfig->getPath(), $this->getPath()], self::CONCATENATE_REDUCE, self::PATH_SEPARATOR));
        $this->methods($this->reduceConfig([$routeConfig->getMethods(), $this->getMethods()], self::MERGE_REDUCE));
        $this->name($this->reduceConfig([$routeConfig->getName(), $this->getName()], self::CONCATENATE_REDUCE, self::NAME_SEPARATOR));
        $this->rules($this->reduceConfig([$routeConfig->getRules(), $this->getRules()], self::KEY_REDUCE));
        $this->defaults($this->reduceConfig([$routeConfig->getDefaults(), $this->getDefaults()], self::KEY_REDUCE));
        $this->middlewares($this->reduceConfig([$routeConfig->getMiddlewares(), $this->getMiddlewares()], self::MERGE_REDUCE));
    }

    /**
     * Reduce array by type.
     *
     * @param array $items
     * @param int $reduceType
     * @param string $separator
     * @return mixed
     */
    protected function reduceConfig(array $items, int $reduceType, string $separator = ''): mixed
    {
        $reduceConfig = static function (array $config, callable $fn, $separator = ''): mixed {
            return array_reduce($config, function ($carry, $item) use ($fn, $separator) {
                if ($carry === null) {
                    return $item;
                }
                return $fn($carry, $item, $separator);
            });
        };

        return match ($reduceType) {
            self::KEY_REDUCE => $reduceConfig($items, function ($carry, $item): array {
                return (array)$item + (array)$carry;
            }, $separator),
            self::MERGE_REDUCE => $reduceConfig($items, function ($carry, $item): array {
                return array_merge((array)$carry, (array)$item);
            }, $separator),
            self::CONCATENATE_REDUCE => $reduceConfig($items, function ($carry, $item, $separator): string {
                return $carry . $separator . $item;
            }, $separator),
            default => $items,
        };
    }

    /**
     * @return RouteConfigValidatorInterface
     */
    private function getConfigValidator(): RouteConfigValidatorInterface
    {
        return $this->configValidator;
    }
}
