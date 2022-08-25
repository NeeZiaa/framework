<?php

namespace NeeZiaa;

class Model {

    protected App $app;
    protected ?Utils\Config $settings;
    /**
     * @var mixed|null
     */
    protected mixed $bdd;
    protected ?Router\Router $router;

    public function __construct()
    {
        $this->app = App::getInstance();
        $this->router = $this->app->getRouter();
        $this->bdd = $this->app->getDb();
        $this->settings = $this->app->getSettings();
    }

}