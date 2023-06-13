<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\RouteConfig;

use Hotaruma\HttpRouter\Interface\Validator\RouteConfigValidatorInterface;

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
