<?php

declare(strict_types=1);

namespace Hotaruma\HttpRouter\Interface\Config;

use Closure;
use Hotaruma\HttpRouter\Exception\ConfigInvalidArgumentException;
use Hotaruma\HttpRouter\Interface\Enum\RequestMethodInterface;

interface ConfigConfigureInterface
{
    /**
     * @param string $path New path
     * @return ConfigConfigureInterface
     *
     * @throws ConfigInvalidArgumentException
     */
    public function path(string $path): ConfigConfigureInterface;

    /**
     * @return string Current path
     */
    public function getPath(): string;

    /**
     * @param string $name New name
     * @return ConfigConfigureInterface
     *
     * @throws ConfigInvalidArgumentException
     */
    public function name(string $name): ConfigConfigureInterface;

    /**
     * @return string Current name
     */
    public function getName(): string;

    /**
     * @param RequestMethodInterface|array<RequestMethodInterface> $methods Http methods
     * @return ConfigConfigureInterface
     *
     * @throws ConfigInvalidArgumentException
     */
    public function methods(RequestMethodInterface|array $methods): ConfigConfigureInterface;

    /**
     * @return array<RequestMethodInterface> Current methods
     */
    public function getMethods(): array;

    /**
     * @param array<string,string> $rules Regex rules for attributes in path
     * @return ConfigConfigureInterface
     *
     * @throws ConfigInvalidArgumentException
     */
    public function rules(array $rules): ConfigConfigureInterface;

    /**
     * @return array<string,string> Current regex rules for attributes in path
     */
    public function getRules(): array;

    /**
     * @param array<string,string> $defaults Default values for attributes in path
     * @return ConfigConfigureInterface
     *
     * @throws ConfigInvalidArgumentException
     */
    public function defaults(array $defaults): ConfigConfigureInterface;

    /**
     * @return array<string,string> Current default values for attributes in path
     */
    public function getDefaults(): array;

    /**
     * @param Closure|array $middlewares Middlewares list
     * @return ConfigConfigureInterface
     *
     * @throws ConfigInvalidArgumentException
     */
    public function middlewares(Closure|array $middlewares): ConfigConfigureInterface;

    /**
     * @return array Current middlewares
     */
    public function getMiddlewares(): array;
}
