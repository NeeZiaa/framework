<?php

namespace NeeZiaa\Controller;

use NeeZiaa\Init;

class _name_1Controller
{

    public function __construct($params){
        $this->params = $params;
    }

    public function index()
    {
        Init::render('_name_2');

    }
}