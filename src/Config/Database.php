<?php

namespace Danidoble\SkeletonConfig\Config;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

class Database
{
    /**
     * @var DB
     */
    public static DB $db;

    /**
     * @param array $params
     * @param string $name
     * @return void
     */
    public static function add(array $params, string $name): void
    {
        if (!self::$db) {
            self::$db = new DB();
        }
        self::$db->addConnection($params, $name);
    }

    /**
     * @return void
     */
    public static function boot(): void
    {
        self::$db->setEventDispatcher(new Dispatcher(new Container));
        self::$db->setAsGlobal();
        self::$db->bootEloquent();
    }

    /**
     * @return void
     */
    public static function default(): void
    {
        self::$db = new DB;

        self::$db->addConnection([
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'database' => env('DB_NAME', 'skeleton'),
            'username' => env('DB_USER', 'root'),
            'password' => env('DB_PASS', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
            'prefix' => '',
        ]);
    }
}