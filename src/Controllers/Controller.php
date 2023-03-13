<?php
/*
 * Created by (c)danidoble 2023.
 * @website https://github.com/danidoble
 * @website https://danidoble.com
 */

namespace Danidoble\SkeletonConfig\Controllers;

use Danidoble\SkeletonConfig\Config\Blade;
use Symfony\Component\HttpFoundation\Response;

class Controller
{
    /**
     * @param string $view - the name of view to render
     * @param array $data - array of data to send to view
     * @param int $status - status of http request
     * @param array $mergeData -- other array to merge with first
     * @return Response
     */
    public function view(string $view, array $data = [], int $status = 200, array $mergeData = []): Response
    {
        $data = array_merge($data, $mergeData);
        return new Response(Blade::$blade->make($view)->with($data)->render(), $status);
    }

    /**
     * View of error http
     * @param int $no number of error
     * @param array $data
     * @return Response
     */
    public function httpError(int $no = 404, array $data = []): Response
    {
        return $this->view('errors.' . $no, [], $no);
    }

}