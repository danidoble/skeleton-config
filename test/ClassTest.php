<?php

namespace Danidoble\SkeletonConfig\Test;
use Danidoble\SkeletonConfig\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class ClassTest extends Controller
{
    /**
     * @return Response
     */
    public function test(): Response
    {
        return new Response(json_encode(['test'=>'test view']));
    }

    public function login(): Response
    {
        return new Response('login view');
    }
}