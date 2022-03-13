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
        Init::render('index');
    }

}