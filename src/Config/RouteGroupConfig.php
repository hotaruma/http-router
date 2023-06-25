<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Config;

use Hotaruma\HttpRouter\Interface\Config\ConfigConfigureInterface;
use Hotaruma\HttpRouter\Interface\Enum\RequestMethodInterface;
use Hotaruma\HttpRouter\Utils\ConfigValidateUtils;

class RouteGroupConfig extends Config
{
    use ConfigValidateUtils;

    /**
     * @inheritDoc
     */
    public function rules(array $rules): ConfigConfigureInterface
    {
        $this->stringStructure($rules, 'Invalid format for route rule. Rules must be specified as strings.');

        return parent::rules($rules);
    }

    /**
     * @inheritDoc
     */
    public function defaults(array $defaults): ConfigConfigureInterface
    {
        $this->stringStructure(
            $defaults,
            'Invalid format for route defaults. Defaults must be specified as strings.'
        );

        return parent::defaults($defaults);
    }

    /**
     * @inheritDoc
     */
    public function methods(RequestMethodInterface|array $methods): ConfigConfigureInterface
    {
        $methods = is_array($methods) ? $methods : [$methods];

        $this->itemsImplement(
            $methods,
            RequestMethodInterface::class,
            'Invalid argument. Expected instance of Method.'
        );

        return parent::methods($methods);
    }
}
