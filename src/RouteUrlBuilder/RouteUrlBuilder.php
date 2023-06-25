<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\RouteUrlBuilder;

use Hotaruma\HttpRouter\Exception\RouteUrlBuilderWrongValuesException;
use Hotaruma\HttpRouter\Interface\Route\RouteInterface;
use Hotaruma\HttpRouter\Interface\RouteUrlBuilder\RouteUrlBuilderInterface;
use Hotaruma\HttpRouter\Utils\ConfigNormalizeUtils;

class RouteUrlBuilder implements RouteUrlBuilderInterface
{
    use ConfigNormalizeUtils;

    /**
     * @var RouteInterface
     */
    protected RouteInterface $route;

    /**
     * @inheritDoc
     */
    public function build(RouteInterface $route): RouteInterface
    {
        $this->route($route);

        $url = (string)preg_replace_callback(
            '/{(?P<placeholderName>[^}]+)}/',
            function (array $subject) {
                return $this->replacePathPlaceholders($subject['placeholderName']);
            },
            $route->getConfigStore()->getPath()
        );

        $this->getRoute()->url($url);
        return $this->getRoute();
    }

    /**
     * @param string $placeholderName
     * @return string
     *
     * @throws RouteUrlBuilderWrongValuesException
     */
    protected function replacePathPlaceholders(string $placeholderName): string
    {
        if (empty($placeholderName)) {
            throw new RouteUrlBuilderWrongValuesException('Placeholder has no name');
        }

        $placeholderRules = $this->getRoute()->getConfigStore()->getRules()[$placeholderName] ?? null;
        $placeholderValue =
            $this->getRoute()->getAttributes()[$placeholderName] ??
            $this->getRoute()->getConfigStore()->getDefaults()[$placeholderName] ??
            throw new RouteUrlBuilderWrongValuesException(
                sprintf('Route has no value for attribute %s', $placeholderName)
            );

        if (isset($placeholderRules) && !preg_match(sprintf("/^%s$/", $placeholderRules), $placeholderValue)) {
            throw new RouteUrlBuilderWrongValuesException(
                sprintf('Route has wrong value for attribute %s', $placeholderName)
            );
        }

        return $placeholderValue;
    }

    /**
     * @param RouteInterface $route
     * @return void
     */
    protected function route(RouteInterface $route): void
    {
        $this->route = $route;
    }

    /**
     * @return RouteInterface
     */
    protected function getRoute(): RouteInterface
    {
        return $this->route;
    }
}
