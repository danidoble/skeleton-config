<?php

namespace Danidoble\SkeletonConfig\Config;

use Closure;
use Exception;
use Symfony\Component\Routing\Route as SymfonyRoute;

class Route
{
    /**
     * @var SymfonyRoute $route Symfony Route object
     */
    public SymfonyRoute $route;
    /**
     * @var string $path URI path
     */
    public string $path = '';
    /**
     * @var string $prefix URI prefix
     */
    private string $prefix = '';

    /**
     * @return void
     */
    public function construct(): void
    {
        $this->bindRoute();
    }

    private function bindRoute(): void
    {
        // check if route is initialized
        if (isset($this->route)) {
            return;
        }
        $this->route = new SymfonyRoute('');
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setPath(string $path): Route
    {
        $this->bindRoute();
        $this->path = $path;
        $this->route->setPath($this->prefix . $path);
        return $this;
    }

    /**
     * @param array $controller
     * @return $this
     */
    public function setController(array $controller): Route
    {
        $this->bindRoute();
        $this->route->setDefault('_controller', [$controller[0], $controller[1]]);
        return $this;
    }

    /**
     * @param string|array $methods
     * @return $this
     */
    public function setMethods(string|array $methods): Route
    {
        $this->bindRoute();
        $this->route->setMethods($methods);
        return $this;
    }

    /**
     * @param array $requirements
     * @return $this
     */
    public function setRequirements(array $requirements): Route
    {
        $this->bindRoute();
        $this->route->setRequirements($requirements);
        return $this;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options): Route
    {
        $this->bindRoute();
        $this->route->setOptions($options);
        return $this;
    }

    /**
     * @param string $host
     * @return $this
     */
    public function setHost(string $host): Route
    {
        $this->bindRoute();
        $this->route->setHost($host);
        return $this;
    }

    /**
     * @param string|array $schemes
     * @return $this
     */
    public function setSchemes(string|array $schemes): Route
    {
        $this->bindRoute();
        $this->route->setSchemes($schemes);
        return $this;
    }

    /**
     * @param string $condition
     * @return $this
     */
    public function setCondition(string $condition): Route
    {
        $this->bindRoute();
        $this->route->setCondition($condition);
        return $this;
    }

    /**
     * @param array $defaults
     * @return $this
     */
    public function setDefaults(array $defaults): Route
    {
        $this->bindRoute();
        $this->route->setDefaults($defaults);
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     * @throws Exception
     */
    public function addToCollection(string $name): Route
    {
        $this->bindRoute();
        if ($this->route->getDefault('_controller') === null) {
            throw new Exception('Route controller not set');
        }
        Router::$routes->add($name, $this->route);
        $this->route = new SymfonyRoute('');
        return $this;
    }

    /**
     * Prefix route path
     * @param string $prefix
     * @return $this
     */
    public function prefix(string $prefix): Route
    {
        $this->bindRoute();
        $this->prefix = $prefix;
        $this->route->setPath($prefix . $this->path);
        return $this;
    }

    /**
     * Add middleware to route
     * @param string|array|Closure $middleware
     * @return $this
     */
    public function middleware(string|array|Closure $middleware): Route
    {
        $this->bindRoute();
        $this->route->setDefault('_middleware', $middleware);
        return $this;
    }

}