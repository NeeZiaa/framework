<?php
namespace NeeZiaa;

use NeeZiaa\Twig\Twig;

class Controller {

    protected ?App $app = null;
    protected ?Twig $twig = null;
    protected ?Router\Router $router;
    protected array $params = [];

    public function __construct($params = []) {
        $this->params = $params;
        $this->app = App::getInstance();
        $this->twig = $this->app->getTwig();
        $this->router = $this->app->getRouter();
    }

}