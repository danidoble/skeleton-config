<?php
/*
 * Created by (c)danidoble 2023.
 * @website https://github.com/danidoble
 * @website https://danidoble.com
 */

namespace Danidoble\SkeletonConfig\Solutions;

use Danidoble\SkeletonConfig\Config\StaticVar;
use Spatie\Ignition\Contracts\RunnableSolution;

class CopyEnvFileSolution implements RunnableSolution
{

    /**
     * @return string
     */
    public function getSolutionActionDescription(): string
    {
        return 'Copy configuration file to run Application successfully';
    }

    /**
     * @return string
     */
    public function getRunButtonText(): string
    {
        return 'Make configuration file';
    }

    /**
     * @param array $parameters
     * @return void
     */
    public function run(array $parameters = []): void
    {
        if (file_exists(StaticVar::$BASE_PATH . '/.env.example')) {
            copy(StaticVar::$BASE_PATH . '/.env.example', StaticVar::$BASE_PATH . '/.env');
        }
    }

    /**
     * @return array
     */
    public function getRunParameters(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public function getSolutionTitle(): string
    {
        return 'Please Make a configuration file';
    }

    /**
     * @return string
     */
    public function getSolutionDescription(): string
    {
        return 'The file of configuration `.env` not exist, copy from `.env.example` to `.env`';
    }

    /**
     * @return string[]
     */
    public function getDocumentationLinks(): array
    {
        return [];
    }
}