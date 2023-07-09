<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\RouteMatcher;

use Closure;
use Hotaruma\HttpRouter\Enum\AdditionalMethod;
use Hotaruma\HttpRouter\Exception\{RouteMatcherInvalidArgumentException, RouteMatcherRuntimeException};
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
        $routeHttpMethods = $route->getConfigStore()->getConfig()->getMethods();
        return in_array($requestMethod, [...$routeHttpMethods, AdditionalMethod::ANY]) ||
            in_array(AdditionalMethod::ANY, $routeHttpMethods);
    }

    /**
     * @inheritDoc
     */
    public function matchRouteByRegex(array $routes, string $requestPath): ?RouteInterface
    {
        $requestPath = $this->normalizePath($requestPath);
        $simpleRoutes = $closureRoutes = [];

        foreach ($routes as $index => $route) {
            $attributesValidators = [];
            $regexp = $this->generatePattern($route, $attributesValidators);

            if (!empty($attributesValidators)) {
                $closureRoutes[$index] = [
                    'route' => $route,
                    'regexp' => $regexp,
                    'attributesValidators' => $attributesValidators
                ];
                continue;
            }
            $simpleRoutes[$index] = [
                'route' => $route,
                'regexp' => $regexp,
            ];
        }

        $variants = array_map(function (array $routesStore, int $key) {
            return "{$routesStore['regexp']}(*MARK:$key)";
        }, $simpleRoutes, array_keys($simpleRoutes));

        $pattern = sprintf('#^(?|%s)$#', implode(array: $variants, separator: '|'));

        if (preg_match($pattern, $requestPath, $matches)) {
            $index = (int)$matches['MARK'];
            unset($matches['MARK']);

            $route = $simpleRoutes[$index]['route'];
            $route->attributes(array_filter($matches, '\is_string', ARRAY_FILTER_USE_KEY));

            return $route;
        }

        foreach ($closureRoutes as $routeData) {
            if (!preg_match("#^{$routeData['regexp']}$#", $requestPath, $matches)) {
                continue;
            }
            $attributes = array_filter($matches, '\is_string', ARRAY_FILTER_USE_KEY);

            foreach ($routeData['attributesValidators'] as $placeholderName => $attributesValidator) {
                if (!isset($attributes[$placeholderName])) {
                    throw new RouteMatcherRuntimeException(
                        sprintf('Attribute %s not found', $placeholderName)
                    );
                }
                if ($attributesValidator($attributes[$placeholderName], $this->getPatternRegistry()) === true) {
                    $route = $routeData['route'];
                    $route->attributes(array_filter($matches, '\is_string', ARRAY_FILTER_USE_KEY));

                    return $route;
                }
            }
        }

        return null;
    }

    /**
     * @param RouteInterface $route
     * @param array<string, Closure> $attributesValidators
     * @return string Regex pattern for route matching.
     *
     * @throws RouteMatcherInvalidArgumentException
     */
    protected function generatePattern(RouteInterface $route, array &$attributesValidators): string
    {
        $routePath = $route->getConfigStore()->getConfig()->getPath();

        return (string)preg_replace_callback('#{([^}]*)}#', function ($subject) use ($route, &$attributesValidators) {
            $placeholderName = $subject[1];
            if (empty($placeholderName)) {
                throw new RouteMatcherInvalidArgumentException('Placeholder has no name');
            }
            [$placeholderName, $placeholderPattern] = explode(':', $placeholderName);

            $placeholderRules = $route->getConfigStore()->getConfig()->getRules()[$placeholderName] ?? null;
            $placeholderRules = match (true) {
                isset($placeholderRules) => $this->getPatternRegistry()->hasPattern($placeholderRules) ?
                    $this->getPatternRegistry()->getPattern($placeholderRules) :
                    $placeholderRules,
                !empty($placeholderPattern) => $this->getPatternRegistry()->hasPattern($placeholderPattern) ?
                    $this->getPatternRegistry()->getPattern($placeholderPattern) :
                    $placeholderPattern,
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
        }, $routePath);
    }
}
