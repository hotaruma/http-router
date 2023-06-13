<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interfaces\RouteConfig;

use Hotaruma\HttpRouter\Interfaces\RouteConfigValidator\RouteConfigValidatorInterface;

interface RouteConfigToolsInterface
{
    /**
     * Set route config validator.
     *
     * @param RouteConfigValidatorInterface $configValidator
     * @return RouteConfigToolsInterface
     */
    public function validator(RouteConfigValidatorInterface $configValidator): RouteConfigToolsInterface;
}
