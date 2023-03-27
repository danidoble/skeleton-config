<?php
/*
 * Created by (c)danidoble 2023.
 * @website https://github.com/danidoble
 * @website https://danidoble.com
 */

namespace Danidoble\SkeletonConfig\Controllers;

use Danidoble\SkeletonConfig\Config\Blade;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\InvalidArgumentException;

class Controller
{
    /**
     * @param string $view - the name of view to render
     * @param array $data - array of data to send to view
     * @param int $status - status of http request
     * @param array $mergeData -- other array to merge with first
     * @return Response
     * @throws Exception
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
     * @throws Exception
     */
    public function httpError(int $no = 404, array $data = []): Response
    {
        if(!Blade::$blade->exists('errors.' . $no)){
            return new Response('Error ' . $no.", And view to render this error, not found as well", $no);
        }
        return $this->view('errors.' . $no, [], $no);
    }

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

}