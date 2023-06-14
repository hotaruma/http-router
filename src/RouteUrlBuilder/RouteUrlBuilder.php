<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\RouteUrlBuilder;

use Hotaruma\HttpRouter\Exception\RouteUrlBuilderWrongValuesException;
use Hotaruma\HttpRouter\Interface\Route\RouteInterface;
use Hotaruma\HttpRouter\Interface\RouteUrlBuilder\RouteUrlBuilderInterface;

class RouteUrlBuilder implements RouteUrlBuilderInterface
{
    /**
     * @var RouteInterface
     */
    protected RouteInterface $route;

    /**
     * @inheritDoc
     */
    public function build(RouteInterface $route): RouteInterface
    {
        $url = preg_replace_callback(
            '/{(?P<placeholderName>[^}]+)}/',
            function (array $subject) {
                $this->replacePathPlaceholders($subject['placeholderName']);
            },
            $route->getRouteConfig()->getPath()
        );

        $route->url($url);
        return $route;
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

        $placeholderRules = $this->route->getRouteConfig()->getRules()[$placeholderName] ?? null;
        $placeholderValue =
            $this->route->getAttributes()[$placeholderName] ??
            $this->route->getRouteConfig()->getDefaults()[$placeholderName] ??
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
}
