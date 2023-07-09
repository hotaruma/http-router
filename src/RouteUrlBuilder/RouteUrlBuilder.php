<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\RouteUrlBuilder;

use Closure;
use Hotaruma\HttpRouter\Exception\PatternRegistryPatternNotFoundException;
use Hotaruma\HttpRouter\Exception\RouteUrlBuilderWrongValuesException;
use Hotaruma\HttpRouter\Interface\Route\RouteInterface;
use Hotaruma\HttpRouter\Interface\RouteUrlBuilder\RouteUrlBuilderInterface;
use Hotaruma\HttpRouter\PatternRegistry\PatternRegistryCase;

class RouteUrlBuilder implements RouteUrlBuilderInterface
{
    use PatternRegistryCase;

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
            '#{(?P<placeholderName>[^}]+)}#',
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
     * @throws RouteUrlBuilderWrongValuesException|PatternRegistryPatternNotFoundException
     */
    protected function replacePathPlaceholders(string $placeholderName): string
    {
        if (empty($placeholderName)) {
            throw new RouteUrlBuilderWrongValuesException('Placeholder has no name');
        }
        [$placeholderName, $placeholderPattern] = explode(string: $placeholderName, separator: ':');

        $placeholderRules = $this->getRoute()->getConfigStore()->getConfig()->getRules()[$placeholderName] ?? null;
        $placeholderRules = match (true) {
            isset($placeholderRules) => $this->getPatternRegistry()->hasPattern($placeholderRules) ?
                $this->getPatternRegistry()->getPattern($placeholderRules) :
                $placeholderRules,
            !empty($placeholderPattern) => $this->getPatternRegistry()->hasPattern($placeholderPattern) ?
                $this->getPatternRegistry()->getPattern($placeholderPattern) :
                $placeholderPattern,
            default => null,
        };
        $placeholderValue =
            $this->getRoute()->getAttributes()[$placeholderName] ??
            $this->getRoute()->getConfigStore()->getConfig()->getDefaults()[$placeholderName] ??
            throw new RouteUrlBuilderWrongValuesException(
                sprintf('Route has no value for attribute %s', $placeholderName)
            );

        if (
            isset($placeholderRules) &&
            (
                ($placeholderRules instanceof Closure && $placeholderRules($placeholderValue, $this->getPatternRegistry()) !== true) ||
                (is_string($placeholderRules) && !preg_match(sprintf("#^%s$#", $placeholderRules), $placeholderValue))
            )
        ) {
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
