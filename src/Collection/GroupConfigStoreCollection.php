<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Collection;

use Hotaruma\HttpRouter\Exception\GroupConfigStoreCollectionLogicException;
use Hotaruma\HttpRouter\Interface\ConfigStore\ConfigStoreInterface;
use Hotaruma\HttpRouter\Interface\RouteMap\RouteMapInterface;
use WeakReference;

class GroupConfigStoreCollection
{
    /**
     * @var int Current group level
     */
    protected int $groupLevel = 0;

    /**
     * @var array<int, ConfigStoreInterface>
     */
    protected array $configStoreCollections = [];

    /**
     * @var WeakReference<RouteMapInterface>|null
     */
    protected ?WeakReference $routeMap;

    /**
     * @param RouteMapInterface $routeMap
     * @return void
     */
    public function routeMap(RouteMapInterface $routeMap): void
    {
        $this->routeMap = WeakReference::create($routeMap);
    }

    /**
     * @return ConfigStoreInterface
     *
     * @throws GroupConfigStoreCollectionLogicException
     */
    public function getConfigStore(): ConfigStoreInterface
    {
        $configStore = $this->getConfigStoreByLevel($this->getGroupLevel());
        if (!isset($configStore)) {
            $configStore = $this->getRouteMap()?->getGroupConfigStoreFactory()::create();
            $this->configStore($configStore ?? throw new GroupConfigStoreCollectionLogicException('Failed to create config store'));
        }
        return $configStore;
    }

    /**
     * Set current group config.
     *
     * @param ConfigStoreInterface $configStore
     * @return void
     */
    public function configStore(ConfigStoreInterface $configStore): void
    {
        $this->configStoreByLevel($this->getGroupLevel(), $configStore);
    }

    /**
     * @return void
     */
    public function resetConfigStore(): void
    {
        $this->resetConfigStoreByLevel($this->getGroupLevel());
    }


    /**
     * @param int $level
     * @param ConfigStoreInterface $configStore
     * @return void
     */
    public function configStoreByLevel(int $level, ConfigStoreInterface $configStore): void
    {
        $this->configStoreCollections[$level] = $configStore;
    }

    /**
     * @param int $level
     * @return ConfigStoreInterface|null
     */
    public function getConfigStoreByLevel(int $level): ?ConfigStoreInterface
    {
        return $this->configStoreCollections[$level] ?? null;
    }

    /**
     * @return ConfigStoreInterface|null
     */
    public function getPreviousConfigStore(): ?ConfigStoreInterface
    {
        return $this->getConfigStoreByLevel($this->getPreviousGroupLevel());
    }

    /**
     * @param int $level
     * @return void
     */
    public function resetConfigStoreByLevel(int $level): void
    {
        unset($this->configStoreCollections[$level]);
    }


    /**
     * @return void
     */
    public function raiseGroupLevel(): void
    {
        $this->groupLevel($this->getNextGroupLevel());
    }

    /**
     * @return void
     */
    public function lowerGroupLevel(): void
    {
        $this->groupLevel($this->getPreviousGroupLevel());
    }

    /**
     * @param int $level
     * @return void
     */
    public function groupLevel(int $level): void
    {
        $this->groupLevel = $level;
    }

    /**
     * @return int
     */
    public function getGroupLevel(): int
    {
        return $this->groupLevel;
    }

    /**
     * @return int
     */
    public function getPreviousGroupLevel(): int
    {
        return $this->getGroupLevel() - 1;
    }

    /**
     * @return int
     */
    public function getNextGroupLevel(): int
    {
        return $this->getGroupLevel() + 1;
    }


    /**
     * @return RouteMapInterface|null
     */
    protected function getRouteMap(): ?RouteMapInterface
    {
        return $this->routeMap?->get();
    }
}
