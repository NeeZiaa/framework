<?php

require dirname(__DIR__).'/vendor/autoload.php';

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
if(extension_loaded('yaml')) {
    dd("Extension chargée");
} else {
    dd('Extension non chargée');
}

$app->registerController("Home");
$app->run();

//! Code non exécuté | Unreachable code !