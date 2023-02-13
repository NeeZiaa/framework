<?php

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 1000');
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) header("Access-Control-Allow-Headers: Accept, Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token, Authorization, token");
    exit(0);
}

require dirname(__DIR__).'/vendor/autoload.php';

$app = (new NeeZiaa\App());

if($app->getSettings()->get('DEBUG'))
{
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    define('DEBUG_TIME', microtime(true));
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}

session_start();

$app->extractPost();
$app->setRoutes();

//! Code non exécuté | Unreachable code !