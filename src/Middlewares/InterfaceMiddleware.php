<?php

namespace Danidoble\SkeletonConfig\Middlewares;

use JetBrains\PhpStorm\NoReturn;

interface InterfaceMiddleware
{
    public function __call($method, $parameters);

    #[NoReturn] public function redirect(string $route_name): void;
}