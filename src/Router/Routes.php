<?php

namespace NeeZiaa\Router;

class Routes {

    /**
     * @throws RouterException
     */
    public function routes(): void
    {
        $router = Router::getInstance();

        $router->get('/test', 'home@test', 'test');

        $router->get('/', 'home@index', 'home');

        $router->run();
    }

}