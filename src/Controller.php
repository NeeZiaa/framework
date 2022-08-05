<?php
namespace NeeZiaa;

use NeeZiaa\Twig\Twig;

class Controller {

    protected ?App $app = null;
    protected ?Twig $twig = null;
    protected array $params = [];

    public function __construct($params = []) {
        $this->params = $params;
        $this->app = App::getInstance();
        $this->twig = App::getInstance()->getTwig();
    }

}