<?php

namespace Danidoble\SkeletonConfig\Test;
use Danidoble\SkeletonConfig\Middlewares\Middleware;

class MiddlewareTest extends Middleware
{

    /**
     * @return bool
     */
    public function logged(): bool
    {
        if (1 === 2) {
            return true;
        }
        $this->redirect('login');
    }
}