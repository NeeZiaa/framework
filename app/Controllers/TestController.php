<?php

namespace NeeZiaa\Controller;

use NeeZiaa\Init;

class TestController
{

    public function __construct($params){
        $this->params = $params;
    }

    public function index()
    {
        Init::render('test');

    }
}