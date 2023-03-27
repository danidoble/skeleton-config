<?php
include '../vendor/autoload.php';

use Danidoble\SkeletonConfig\Config\Blade;
use Danidoble\SkeletonConfig\Config\Router;
use Danidoble\SkeletonConfig\Config\SRoute;
use Danidoble\SkeletonConfig\Config\StaticVar;
use Danidoble\SkeletonConfig\Exceptions\NotFoundFileConfigException;
use Danidoble\SkeletonConfig\Test\ClassTest;
use Danidoble\SkeletonConfig\Test\MiddlewareTest;
use Spatie\Ignition\Ignition;
use Symfony\Component\Routing\Route;

const BASE_PATH = __DIR__;
StaticVar::$BASE_PATH = BASE_PATH;


Ignition::make()->applicationPath(__DIR__)->register();

throw_if(!existEnvFile(), NotFoundFileConfigException::class, 'The configuration file was not found'); // if .env not exist

// configuration
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

Router::Router();
Blade::makeFactory(__DIR__);

SRoute::get('/login', [ClassTest::class, 'login'])->name('login');
SRoute::middleware([MiddlewareTest::class, 'logged'])->get('/', [ClassTest::class, 'test'])->name('test');
Router::dispatch();
dd(Router::$routes);


//Blade::$page_name = 'page';