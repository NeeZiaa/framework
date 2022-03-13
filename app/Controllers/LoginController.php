<?php

namespace NeeZiaa\Controller;

use NeeZiaa\Init;
use \NeeZiaa\Login;

class LoginController
{

    public function __construct($params){
        $this->params = $params;
    }

    public function index()
    {
        
        Init::render('login');

    }

    public function post() 
    {
        $user = Login::login($_POST['email'], $_POST['password']);

    }
}