<?php

namespace App\Controller;

use App\Models\ExampleModel;
use NeeZiaa\Controller;
use NeeZiaa\Form\Form;

class HomeController extends Controller
{

    private ExampleModel $model;

    public function index(): array|null|\Twig\Environment
    {
        echo $this->app->getRouter()->url('test', ['id'=>1]);
        return $this->twig->render('index');
    }

}