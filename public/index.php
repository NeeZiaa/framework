<?php

// Affichage des erreurs

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require dirname(__DIR__).'/vendor/autoload.php';
require dirname(__DIR__).'/src/Router/Router.php';
require dirname(__DIR__).'/src/Utils/Main.php';
require dirname(__DIR__).'/src/Init.php';

ini_set('session.gc_maxlifetime', \NeeZiaa\Main::env()['SESSION_DURATION']);

session_start();
// dd($_SERVER);

if(NeeZiaa\Main::env()['DEBUG'] == true)
{
    define('DEBUG_TIME', microtime(true));
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}

require dirname(__DIR__).'/app/Routes.php';

//! Code non exécuté !