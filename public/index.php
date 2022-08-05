<?php

require dirname(__DIR__).'/vendor/autoload.php';

use NeeZiaa\Router\Routes;

$config = NeeZiaa\Utils\Config::getInstance();

if($config->get('DEBUG'))
{
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    define('DEBUG_TIME', microtime(true));
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}

session_start();

$app = (new NeeZiaa\App($config));
$app->setRoutes();

//! Code non exécuté !