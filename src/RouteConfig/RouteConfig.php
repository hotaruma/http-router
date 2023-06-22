<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\RouteConfig;

use Closure;
use Hotaruma\HttpRouter\Interface\{Enum\RequestMethodInterface,
    RouteConfig\RouteConfigConfigureInterface,
    RouteConfig\RouteConfigInterface,
    RouteConfig\RouteConfigToolsInterface,
    Validator\RouteConfigValidatorInterface
};
use Hotaruma\HttpRouter\Exception\RouteConfigInvalidArgumentException;
use Hotaruma\HttpRouter\Utils\ConfigNormalizeUtils;
use Hotaruma\HttpRouter\Validator\RouteConfigValidator;

class RouteConfig implements RouteConfigInterface
{
    use ConfigNormalizeUtils;

    protected const KEY_REDUCE = 1;
    protected const MERGE_REDUCE = 2;
    protected const CONCATENATE_REDUCE = 3;
    protected const ENUM_MERGE_REDUCE = 4;

    protected const PATH_SEPARATOR = '/';
    protected const NAME_SEPARATOR = '.';

    /**
     * @param array<string,string> $rules
     * @param array<string,string> $defaults
     * @param Closure|array $middlewares
     * @param string $path
     * @param string $name
     * @param RequestMethodInterface|array<RequestMethodInterface> $methods
     * @param RouteConfigValidatorInterface $configValidator
     */
    public function __construct(
        protected array                         $rules = [],
        protected array                         $defaults = [],
        protected Closure|array                 $middlewares = [],
        protected string                        $path = '/',
        protected string                        $name = '',
        protected RequestMethodInterface|array  $methods = [],
        protected RouteConfigValidatorInterface $configValidator = new RouteConfigValidator()
    ) {
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
        $middlewares = is_array($middlewares) ? $middlewares : [$middlewares];
        $this->getConfigValidator()->validateMiddlewares($middlewares);
        $this->middlewares = $middlewares;
        return $this;
    }

    /**
     * @return array
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
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
    public function methods(RequestMethodInterface|array $methods): RouteConfigConfigureInterface
    {
        $methods = is_array($methods) ? $methods : [$methods];
        $this->getConfigValidator()->validateMethods($methods);
        $this->methods = $methods;
        return $this;
    }

    /**
     * @return array
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @inheritDoc
     */
    public function config(
        array                        $rules = null,
        array                        $defaults = null,
        array|Closure                $middlewares = null,
        string                       $path = null,
        string                       $name = null,
        array|RequestMethodInterface $methods = null
    ): void {
        isset($rules) and $this->rules($rules);
        isset($defaults) and $this->defaults($defaults);
        isset($middlewares) and $this->middlewares($middlewares);
        isset($path) and $this->path($path);
        isset($name) and $this->name($name);
        isset($methods) and $this->methods($methods);
    }

    /**
     * @inheritDoc
     */
    public function mergeConfig(RouteConfigInterface $routeConfig): void
    {
        $this->path($this->reduceConfig(
            [$routeConfig->getPath(), $this->getPath()],
            self::CONCATENATE_REDUCE,
            self::PATH_SEPARATOR
        ));
        $this->methods($this->reduceConfig(
            [$routeConfig->getMethods(), $this->getMethods()],
            self::ENUM_MERGE_REDUCE
        ));
        $this->name($this->reduceConfig(
            [$routeConfig->getName(), $this->getName()],
            self::CONCATENATE_REDUCE,
            self::NAME_SEPARATOR
        ));
        $this->rules($this->reduceConfig(
            [$routeConfig->getRules(), $this->getRules()],
            self::KEY_REDUCE
        ));
        $this->defaults($this->reduceConfig(
            [$routeConfig->getDefaults(), $this->getDefaults()],
            self::KEY_REDUCE
        ));
        $this->middlewares($this->reduceConfig(
            [$routeConfig->getMiddlewares(), $this->getMiddlewares()],
            self::MERGE_REDUCE
        ));
    }

    /**
     * Reduce config by type.
     *
     * @param array $items
     * @param int $reduceType
     * @param string $separator
     * @return array|string
     *
     * @throws RouteConfigInvalidArgumentException
     */
    protected function reduceConfig(array $items, int $reduceType, string $separator = ''): array|string
    {
        $reduceConfig = static function (array $config, callable $fn, string $separator = ''): array|string {
            return array_reduce($config, function (array|string|null $carry, array|string $item) use ($fn, $separator) {
                if ($carry === null) {
                    return $item;
                }
                return $fn($carry, $item, $separator);
            });
        };

        return match ($reduceType) {
            self::KEY_REDUCE =>
            $reduceConfig($items, function (array $carry, array $item): array {
                return $item + $carry;
            }, $separator),
            self::MERGE_REDUCE =>
            array_unique($reduceConfig($items, function (array $carry, array $item): array {
                return array_merge($carry, $item);
            }, $separator)),
            self::ENUM_MERGE_REDUCE =>
            $reduceConfig($items, function (array $carry, array $item): array {
                return array_merge($carry, $item);
            }, $separator),
            self::CONCATENATE_REDUCE =>
            $reduceConfig($items, function (string $carry, string $item, string $separator): string {
                return $carry . $separator . $item;
            }, $separator),
            default =>
            throw new RouteConfigInvalidArgumentException(sprintf('Unsupported reduce type: %s', $reduceType)),
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
