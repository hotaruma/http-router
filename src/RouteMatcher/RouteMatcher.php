<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\RouteMatcher;

use Closure;
use Hotaruma\HttpRouter\Enum\AdditionalMethod;
use Hotaruma\HttpRouter\Exception\RouteMatcherInvalidArgumentException;
use Hotaruma\HttpRouter\Exception\RouteMatcherRuntimeException;
use Hotaruma\HttpRouter\Interface\Enum\RequestMethodInterface;
use Hotaruma\HttpRouter\Interface\Route\RouteInterface;
use Hotaruma\HttpRouter\Interface\RouteMatcher\RouteMatcherInterface;
use Hotaruma\HttpRouter\PatternRegistry\PatternRegistryCase;
use Hotaruma\HttpRouter\Utils\ConfigNormalizeUtils;

class RouteMatcher implements RouteMatcherInterface
{
    use ConfigNormalizeUtils;
    use PatternRegistryCase;

    /**
     * @inheritDoc
     */
    public function matchRouteByHttpMethod(RouteInterface $route, RequestMethodInterface $requestMethod): bool
    {
        $routeHttpMethods = $route->getConfigStore()->getMethods();
        return in_array($requestMethod, [...$routeHttpMethods, AdditionalMethod::ANY]) ||
            in_array(AdditionalMethod::ANY, $routeHttpMethods);
    }

    /**
     * @inheritDoc
     */
    public function matchRouteByRegex(RouteInterface $route, string $requestPath): ?array
    {
        [$pattern, $attributesValidators] = $this->generatePattern($route);

        if (!preg_match($pattern, $this->normalizePath($requestPath), $matches)) {
            return null;
        }
        $attributes = array_filter($matches, '\is_string', ARRAY_FILTER_USE_KEY);

        foreach ($attributesValidators as $placeholderName => $attributesValidator) {
            if (!isset($attributes[$placeholderName])) {
                throw new RouteMatcherRuntimeException(
                    sprintf('Attribute %s not found', $placeholderName)
                );
            }
            if ($attributesValidator($attributes[$placeholderName], $this->getPatternRegistry()) !== true) {
                return null;
            }
        }
        return $attributes;
    }

    /**
     * @param RouteInterface $route
     * @return array{string, array<string, Closure>} Regex pattern for route matching and attributes validators.
     *
     * @throws RouteMatcherInvalidArgumentException
     *
     * @phpstan-return array{0: string, 1: array<string, TA_PatternRegistryClosure>}
     */
    protected function generatePattern(RouteInterface $route): array
    {
        $routePath = $this->preparePathForRegExp($route->getConfigStore()->getPath());
        $attributesValidators = [];

        $pattern = preg_replace_callback(
            '#{(?P<placeholderName>[^}]+)}#',
            function ($subject) use ($route, &$attributesValidators) {
                $placeholderName = $subject['placeholderName'];

                if (empty($placeholderName)) {
                    throw new RouteMatcherInvalidArgumentException('Placeholder has no name');
                }
                [$placeholderName, $placeholderPattern] = explode(string: $placeholderName, separator: ':');

                $placeholderRules = $route->getConfigStore()->getRules()[$placeholderName] ?? null;
                $placeholderRules = match (true) {
                    isset($placeholderRules) => $this->getPatternRegistry()->hasPattern($placeholderRules) ?
                        $this->getPatternRegistry()->getPattern($placeholderRules) :
                        $placeholderRules,
                    !empty($placeholderPattern) => $this->getPatternRegistry()->hasPattern($placeholderPattern) ?
                        $this->getPatternRegistry()->getPattern($placeholderPattern) :
                        stripslashes($placeholderPattern),
                    default => null,
                };

                if ($placeholderRules instanceof Closure) {
                    $attributesValidators[$placeholderName] = $placeholderRules;
                    unset($placeholderRules);
                }
                return sprintf(
                    '(?P<%s>%s)',
                    $placeholderName,
                    $placeholderRules ?? '[^}/]+'
                );
            },
            $routePath
        );
        $pattern = sprintf("#^%s$#", $pattern);

        return [$pattern, $attributesValidators];
    }
}
