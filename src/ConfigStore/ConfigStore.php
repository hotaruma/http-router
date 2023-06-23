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

    protected const KEY_REDUCE = 1;
    protected const MERGE_REDUCE = 2;
    protected const CONCATENATE_REDUCE = 3;
    protected const ENUM_MERGE_REDUCE = 4;

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
}
