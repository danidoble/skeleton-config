<?php

namespace Danidoble\SkeletonConfig\Config;

use Closure;
use Exception;

class SRoute
{
    /**
     * @var Route|null $route
     */
    public static ?Route $route;

    /**
     * Create a new route instance.
     * @param bool $force
     * @return void
     */
    private static function createRoute(bool $force = false): void
    {
        if (!isset(self::$route) || $force) {
            self::$route = new Route();
        }
    }

    /**
     * add middleware to route
     * @param string|array|Closure $middleware
     * @return static
     */
    public static function middleware(string|array|Closure $middleware): static
    {
        self::createRoute(true);
        self::$route->middleware($middleware);
        return new static;
    }

    /**
     * add prefix to route
     * @param string $prefix
     * @return static
     */
    public static function prefix(string $prefix): static
    {
        self::createRoute();
        self::$route->prefix($prefix);
        return new static;
    }

    /**
     * add path and controller to route
     * @param string $path
     * @param array $controller
     * @return void
     * @throws Exception
     */
    private static function setPathController(string $path, array $controller): void
    {
        self::$route->setPath($path);
        if (count($controller) < 2) {
            throw new Exception('The controller must have a method and a class, Example: [Controller::class, "method"]');
        }
        self::$route->setController($controller);
    }

    /**
     * @param array|string $methods
     * @param string $path
     * @param array $controller
     * @return static
     * @throws Exception
     */
    public static function match(array|string $methods, string $path, array $controller): static
    {
        self::createRoute();
        self::$route->setMethods($methods);
        self::setPathController($path, $controller);
        return new SRoute();
    }

    /**
     * add get route
     * @param string $path
     * @param array $controller
     * @return static
     * @throws Exception
     */
    public static function get(string $path, array $controller): static
    {
        self::createRoute();
        self::$route->setMethods(['GET']);
        self::setPathController($path, $controller);
        return new SRoute();
    }

    /**
     * @param $path
     * @param $controller
     * @return static
     * @throws Exception
     */
    public static function post($path, $controller): static
    {
        self::createRoute();
        self::$route->setMethods(['POST']);
        self::setPathController($path, $controller);
        return new SRoute();
    }

    /**
     * @param string $path
     * @param array $controller
     * @return static
     * @throws Exception
     */
    public static function put(string $path, array $controller): static
    {
        self::createRoute();
        self::$route->setMethods(['PUT']);
        self::setPathController($path, $controller);
        return new SRoute();
    }

    /**
     * @param string $path
     * @param array $controller
     * @return static
     * @throws Exception
     */
    public static function options(string $path, array $controller): static
    {
        self::createRoute();
        self::$route->setMethods(['OPTIONS']);
        self::setPathController($path, $controller);
        return new SRoute();
    }

    /**
     * @param string $path
     * @param array $controller
     * @return static
     * @throws Exception
     */
    public static function patch(string $path, array $controller): static
    {
        self::createRoute();
        self::$route->setMethods(['PATCH']);
        self::setPathController($path, $controller);
        return new SRoute();
    }

    /**
     * @param string $path
     * @param array $controller
     * @return static
     * @throws Exception
     */
    public static function head(string $path, array $controller): static
    {
        self::createRoute();
        self::$route->setMethods(['HEAD']);
        self::setPathController($path, $controller);
        return new SRoute();
    }

    /**
     * @param string $name
     * @return void
     * @throws Exception
     */
    public function name(string $name): void
    {
        self::createRoute();
        self::$route->addToCollection($name);
        $this->clear();
    }

    /**
     * clear SRoute
     * @return void
     */
    private function clear(): void
    {
        SRoute::$route = null;
    }


}