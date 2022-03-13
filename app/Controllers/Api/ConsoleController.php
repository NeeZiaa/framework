<?php

namespace NeeZiaa\Controller\Api;

use NeeZiaa\Init;

use NeeZiaa\Models\TokenModel;

class ConsoleModel
{

    public function __construct($params){
        $this->params = $params;
    }

    public function index()
    {

        if(isset($_POST['api_key']))
        {
            if($token = TokenModel::verify($_GET['api_key']) == true)
            {

                

            } else {
                return "403 Access denied : Incorrect Api Key";
            }

        } else {
            return "403 Access denied : Api key no specified";
        }

    }

    
}