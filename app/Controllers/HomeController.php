<?php

namespace NeeZiaa\Controller;

use NeeZiaa\Init;
use NeeZiaa\Main;

class HomeController
{

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function index()
    {
        $array_loader = [
            'logfile' => Main::env()['SERVER_LOGFILE']
        ];
        Init::render('index');
    }

    public function console()
    {

        $array_loader = [
            'logfile' => Main::env()['SERVER_LOGFILE']
        ];

        Init::render('index', $array_loader);
        
    }

// public function articles(){

//     echo $this->twig->render('index.html.twig');
// }

}