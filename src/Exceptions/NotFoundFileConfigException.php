<?php
/*
 * Created by (c)danidoble 2023.
 * @website https://github.com/danidoble
 * @website https://danidoble.com
 */

namespace Danidoble\SkeletonConfig\Exceptions;

use Danidoble\SkeletonConfig\Solutions\CopyEnvFileSolution;
use Exception;
use Spatie\Ignition\Contracts\ProvidesSolution;
use Spatie\Ignition\Contracts\Solution;

class NotFoundFileConfigException extends Exception implements ProvidesSolution
{
    /**
     * @return Solution
     */
    public function getSolution(): Solution
    {
        return new CopyEnvFileSolution();
    }
}