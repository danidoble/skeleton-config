<?php
/*
 * Created by (c)danidoble 2023.
 * @website https://github.com/danidoble
 * @website https://danidoble.com
 */

/*
 * Created by (c)danidoble 2023.
 * @website https://github.com/danidoble
 * @website https://danidoble.com
 */

namespace Danidoble\SkeletonConfig\Config;

use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use Illuminate\Pagination\AbstractPaginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;

class Blade
{
    /**
     * @var Factory $blade
     */
    public static Factory $blade;
    /**
     * @var string $page_name
     */
    public static string $page_name = 'page';

    /**
     * @param $dir
     * @param string|null $default_pagination
     * @return void
     */
    public static function makeFactory($dir, ?string $default_pagination = null): void
    {
        // Crear una instancia de Filesystem para que pueda ser usada por Blade
        $filesystem = new Filesystem();

        // Nueva instancia de BladeCompiler, y la ruta donde se guardaran los archivos compilados
        $compiler = new BladeCompiler($filesystem, $dir . '/resources/cache');

        // Instancia de FileViewFinder, y la ruta donde se encuentran las vistas Blade
        $viewFinder = new FileViewFinder($filesystem, [$dir . '/resources/views']);

        // Instancia de EngineResolver, y el motor que se usara para compilar las vistas
        $resolver = new EngineResolver();
        // Se registra el motor PHP
        $resolver->register('php', function () use ($filesystem) {
            return new PhpEngine($filesystem);
        });
        // Se registra el motor Blade
        $resolver->register('blade', function () use ($compiler) {
            return new CompilerEngine($compiler);
        });

        // Dispatcher para los eventos de Blade
        $dispatcher = new Dispatcher();

        $viewFactory = new Factory($resolver, $viewFinder, $dispatcher);
        AbstractPaginator::viewFactoryResolver(function () use ($viewFactory) {
            return $viewFactory;
        });
        if (!blank($default_pagination)) {
            AbstractPaginator::defaultView($default_pagination);
        }
        // Resolver de la página actual
        AbstractPaginator::currentPageResolver(fn() => self::currentPageResolver());
        // Resolver de la ruta actual
        AbstractPaginator::currentPathResolver(fn() => self::currentPathResolver());

        static::$blade = $viewFactory;
    }

    /**
     * Change name page resolver
     * @param Request|null $request
     * @return int
     */
    public static function currentPageResolver(?Request $request = null): int
    {
        if ($request === null) {
            $request = Router::$request;
        }

        $page = $request->query->get(self::$page_name, 1);

        if (filter_var($page, FILTER_VALIDATE_INT) !== false && (int)$page >= 1) {
            return (int)$page;
        }

        return 1;
    }

    public static function currentPathResolver(): string
    {
        // Crear un generador de URL
        $urlGenerator = new UrlGenerator(Router::$routes, Router::$context);

        // Obtenemos el array a partir de los parámetros de la URL
        $arr = Router::$request->query->all();
        if (isset($arr[self::$page_name])) {
            unset($arr[self::$page_name]); // eliminamos el parámetro de la página a usar paginación
        }

        // return Router::$request->getBasePath() . Router::$request->getPathInfo() . $urlGenerator->getContext()->getQueryString();
        return $urlGenerator->generate('test', $arr); // Generamos la URL y la regresamos
    }
}