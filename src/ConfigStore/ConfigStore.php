<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\ConfigStore;

use Hotaruma\HttpRouter\Interface\{Config\ConfigConfigureInterface,
    Config\ConfigInterface,
    ConfigStore\ConfigStoreInterface,
    ConfigStore\ConfigStoreToolsInterface
};
use Hotaruma\HttpRouter\Config\RouteConfig;
use Hotaruma\HttpRouter\Exception\RouteConfigInvalidArgumentException;
use Hotaruma\HttpRouter\Utils\ConfigNormalizeUtils;

/**
 * @mixin ConfigConfigureInterface
 */
class ConfigStore implements ConfigStoreInterface
{
    use ConfigNormalizeUtils;

    protected const REDUCE_KEY = 1;
    protected const REDUCE_MERGE = 2;
    protected const REDUCE_CONCATENATE = 3;
    protected const REDUCE_ENUM_MERGE = 4;

    protected const PATH_SEPARATOR = '/';
    protected const NAME_SEPARATOR = '.';

    /**
     * @param ConfigInterface $config
     */
    public function __construct(
        protected ConfigInterface $config = new RouteConfig()
    ) {
    }

    /**
     * @inheritDoc
     */
    public function config(ConfigInterface $config): ConfigStoreToolsInterface
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getConfig(): ConfigInterface
    {
        return $this->config;
    }

    /**
     * @inheritDoc
     */
    public function mergeConfig(ConfigStoreInterface $routeConfig): void
    {
        $this->path($this->reduceConfig(
            [$routeConfig->getPath(), $this->getPath()],
            self::REDUCE_CONCATENATE,
            self::PATH_SEPARATOR
        ));
        $this->methods($this->reduceConfig(
            [$routeConfig->getMethods(), $this->getMethods()],
            self::REDUCE_ENUM_MERGE
        ));
        $this->name($this->reduceConfig(
            [$routeConfig->getName(), $this->getName()],
            self::REDUCE_CONCATENATE,
            self::NAME_SEPARATOR
        ));
        $this->rules($this->reduceConfig(
            [$routeConfig->getRules(), $this->getRules()],
            self::REDUCE_KEY
        ));
        $this->defaults($this->reduceConfig(
            [$routeConfig->getDefaults(), $this->getDefaults()],
            self::REDUCE_KEY
        ));
        $this->middlewares($this->reduceConfig(
            [$routeConfig->getMiddlewares(), $this->getMiddlewares()],
            self::REDUCE_MERGE
        ));
    }

    /**
     * @param string $name
     * @param array<mixed> $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments): mixed
    {
        if (!method_exists($this->getConfig(), $name)) {
            throw new RouteConfigInvalidArgumentException(sprintf('Method %s does not exist', $name));
        }

        return $this->getConfig()->$name(...$arguments);
    }

    /**
     * Reduce config by type.
     *
     * @template T of array|string
     * @param array<T> $items
     * @param int $reduceType
     * @param string $separator
     * @return T
     *
     * @throws RouteConfigInvalidArgumentException
     *
     * @phpstan-param int-mask-of<static::REDUCE_*> $reduceType
     *
     */
    protected function reduceConfig(array $items, int $reduceType, string $separator = ''): array|string
    {
        $reduceConfig = static function (array $config, callable $fn, string $separator = '') {
            return array_reduce($config, function (array|string|null $carry, array|string $item) use ($fn, $separator) {
                if ($carry === null) {
                    return $item;
                }
                return $fn($carry, $item, $separator);
            });
        };

        return match ($reduceType) {
            self::REDUCE_KEY =>
            $reduceConfig($items, function (array $carry, array $item): array {
                return $item + $carry;
            }, $separator),
            self::REDUCE_MERGE =>
            array_unique($reduceConfig($items, function (array $carry, array $item): array {
                return array_merge($carry, $item);
            }, $separator)),
            self::REDUCE_ENUM_MERGE =>
            $reduceConfig($items, function (array $carry, array $item): array {
                return array_merge($carry, $item);
            }, $separator),
            self::REDUCE_CONCATENATE =>
            $reduceConfig($items, function (string $carry, string $item, string $separator): string {
                return $carry . $separator . $item;
            }, $separator),
            default =>
            throw new RouteConfigInvalidArgumentException(sprintf('Unsupported reduce type: %s', $reduceType)),
        };
    }
}
