<?php
/*
 * Created by (c)danidoble 2023.
 * @website https://github.com/danidoble
 * @website https://danidoble.com
 */

namespace Danidoble\SkeletonConfig\Config;

use Danidoble\SkeletonConfig\Controllers\Controller;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class Router
{
    /**
     * @var RouteCollection
     */
    static RouteCollection $routes;
    /**
     * @var UrlMatcher
     */
    static UrlMatcher $matcher;
    /**
     * @var Request
     */
    static Request $request;
    /**
     * @var RequestContext
     */
    static RequestContext $context;

    /**
     * @return void
     */
    public static function Router(): void
    {
        try {
            // Add Route object(s) to RouteCollection object
            self::$routes = new RouteCollection();

            self::$request = Request::createFromGlobals();

            // Init RequestContext object
            self::$context = new RequestContext();
            self::$context->fromRequest(Request::createFromGlobals());

            // Init UrlMatcher object
            self::$matcher = new UrlMatcher(self::$routes, self::$context);
        } catch (ResourceNotFoundException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @param string $path
     * @param array $controller
     * @param string $name
     * @param string|array $methods
     * @return void
     */
    public static function add(string $path, array $controller, string $name, string|array $methods = ['GET']): void
    {
        Router::$routes->add($name, new Route($path, ['_controller' => [$controller[0], $controller[1]]], [], [], '', [], $methods));
    }

    /**
     * @return void
     * @throws Exception
     */
    public static function dispatch(): void
    {
        $matcher = new UrlMatcher(self::$routes, self::$context);
        $response = new Response('.', 200);
        try {
            $parameters = $matcher->match(self::$context->getPathInfo());
            foreach ($parameters as $name => $parameter) {
                self::$request->attributes->set($name, $parameter);
            }

            $r_mid = true;
            if (isset($parameters['_middleware'])) {
                if ($parameters['_middleware'] instanceof \Closure) {
                    $r_mid = $parameters['_middleware']();
                } else if (is_array($parameters['_middleware'])) {
                    if (!class_exists($parameters['_middleware'][0])) {
                        throw new Exception('Middleware class not found');
                    }
                    if (!method_exists($parameters['_middleware'][0], $parameters['_middleware'][1])) {
                        throw new Exception('Middleware method not found');
                    }
                    $mid = new $parameters['_middleware'][0]();
                    $r_mid = $mid->{$parameters['_middleware'][1]}(self::$request);
                } else if (is_string($parameters['_middleware'])) {
                    $r_mid = call_user_func($parameters['_middleware'], self::$request);
                } else {
                    throw new Exception('Middleware must be a Closure, array, or string');
                }
            }
            if ($r_mid) {
                $resolver = new ControllerResolver();
                $controller = $resolver->getController(self::$request);
                foreach ($parameters as $name => $parameter) {
                    self::$request->attributes->remove($name);
                }
                $response = call_user_func_array($controller, Router::$request->attributes->all());
            } else {
                $response = (new Controller())->httpError(403);
            }
        } catch (ResourceNotFoundException) {
            $response = (new Controller())->httpError(404);
        } catch (MethodNotAllowedException) {
            $response = (new Controller())->httpError(405);
        } catch (Exception $e) {
            if (!env('APP_DEBUG', false)) {
                $response = (new Controller())->httpError(500);
            } else {
                throw new Exception($e->getMessage(), $e->getCode(), $e->getPrevious());
            }
        }
        $response->send();
    }

    /**
     * @return array|string
     */
    public static function currentRoute(): array|string
    {
        try {
            return Router::$matcher->match(Router::$context->getPathInfo());
        } catch (\Exception) { // Evitamos que se muestre el error de la ruta no encontrada
            return ['_route' => 'not.found'];
        }
    }

    /**
     * @return string
     */
    public static function currentRouteName(): string
    {
        return self::currentRoute()['_route'];
    }

    /**
     * @param string $name
     * @param bool $full
     * @return Route|string
     */
    public static function routeNamed(string $name, bool $full = false): Route|string
    {
        $route = Router::$routes->get($name);
        if (empty($route)) {
            throw new RouteNotFoundException('La ruta "' . $name . '"no existe');
        }
        if ($full) {
            return $route;
        }
        return env('APP_URL') . $route->getPath();
    }

}