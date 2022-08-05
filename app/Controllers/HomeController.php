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
        (new Form())
            ->select('Example')
                ->option('Option 1 ', 1)
                ->option('Option 2 ', 2)
            ->endselect()

            ->input('Name')
            ->submit();
        echo $this->app->getRouter()->url('test', ['id'=>1]);
        return $this->twig->render('index');
    }

}