<?php

require dirname(__DIR__). DIRECTORY_SEPARATOR .'vendor'. DIRECTORY_SEPARATOR .'autoload.php';

session_start();

$app = new NeeZiaa\App();

if($app->getSettings()->get('DEBUG'))
{
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    define('DEBUG_TIME', microtime(true));
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}

$app->registerController("Home");
$app->run();

//! Code non exécuté | Unreachable code !