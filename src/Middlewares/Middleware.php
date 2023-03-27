<?php

namespace Danidoble\SkeletonConfig\Middlewares;

use Danidoble\SkeletonConfig\Config\Router;
use JetBrains\PhpStorm\NoReturn;

class Middleware implements InterfaceMiddleware
{
    /**
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        throw new \BadMethodCallException(sprintf(
            'Method %s::%s does not exist.', static::class, $method
        ));
    }

    /**
     * @param string $route_name
     * @return void
     */
    #[NoReturn] public function redirect(string $route_name): void
    {
        header('Location: ' . Router::routeNamed($route_name));
        exit(1);
    }

    public function saveCurrentRoute(): void
    {
        $_SESSION['current_route'] = Router::$request->getRequestUri();
    }
}
