<?php
/*
 * Created by (c)danidoble 2023.
 * @website https://github.com/danidoble
 * @website https://danidoble.com
 */

use Danidoble\SkeletonConfig\Component;
use Danidoble\SkeletonConfig\Config\Blade;
use Danidoble\SkeletonConfig\Config\StaticVar;
use Danidoble\SkeletonConfig\View;
use Spatie\Ignition\Ignition;
use Symfony\Component\HttpFoundation\Response;

if (!function_exists('view')) {
    /**
     * @param string $view - the name of view to render
     * @param array $data - array of data to send to view
     * @param int $status - status of http request
     * @param array $mergeData -- other array to merge with first
     * @return Response
     */
    function view(string $view, array $data = [], int $status = 200, array $mergeData = []): Response
    {
        return (new View($view, $data, $status, $mergeData))->toResponse();
    }
}

if (!function_exists('component')) {
    /**
     * @param string $view - the name of view to render
     * @param array $data - array of data to send to view
     * @param string $dir - the name of the directory to look for the component
     * @return string
     */
    function component(string $view, array $data = [], string $dir = 'components'): string
    {
        $component = new Component($view);
        $component->set('data', $data);
        $component->setDir($dir);
        return $component->render();
    }
}

if (!function_exists('existEnvFile')) {
    /**
     * @param string $file
     * @return bool
     */
    function existEnvFile(string $file = '.env'): bool
    {
        return file_exists(StaticVar::$BASE_PATH . DIRECTORY_SEPARATOR . $file);
    }
}
if (!function_exists('DisplayErrors')) {
    /**
     * @param bool $errors
     * @param string $path
     * @param string $theme
     * @return void
     */
    function DisplayErrors(bool $errors, string $path, string $theme = 'light'): void
    {
        Ignition::make()
            ->applicationPath($path)
            ->shouldDisplayException($errors)
            ->setTheme($theme)->register(); // errors
    }
}