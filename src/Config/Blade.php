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

class Blade
{
    /**
     * @var Factory
     */
    public static Factory $blade;

    /**
     * @param $dir
     * @param string|null $default_pagination
     * @return void
     */
    public static function makeFactory($dir,?string $default_pagination = null): void
    {
        $filesystem = new Filesystem();

        $compiler = new BladeCompiler($filesystem, $dir . '/resources/cache');

        $viewFinder = new FileViewFinder($filesystem, [$dir . '/resources/views']);

        $resolver = new EngineResolver();
        $resolver->register('php', function () use ($filesystem) {
            return new PhpEngine($filesystem);
        });
        $resolver->register('blade', function () use ($compiler) {
            return new CompilerEngine($compiler);
        });

        $dispatcher = new Dispatcher();

        $viewFactory = new Factory($resolver, $viewFinder, $dispatcher);
        AbstractPaginator::viewFactoryResolver(function () use ($viewFactory) {
            return $viewFactory;
        });
        if (!blank($default_pagination)) {
            AbstractPaginator::defaultView($default_pagination);
        }
        static::$blade = $viewFactory;
    }
}