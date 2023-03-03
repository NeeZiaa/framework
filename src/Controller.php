<?php
namespace NeeZiaa;

use NeeZiaa\Twig\Twig;

class Controller {

    protected ?App $app = null;
    protected ?Twig $twig = null;
    protected ?Router\Router $router;
    protected ?Permission $permissions;
    protected ?User $user;

    protected array $params = [];

    public function __construct($params = []) {
        $this->params = $params;
        $this->app = App::getInstance();
        $this->router = $this->app->getRouter();
    }

}