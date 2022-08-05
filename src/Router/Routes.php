<?php

namespace NeeZiaa\Router;

class Routes {

    /**
     * @throws RouterException
     */
    public function routes(): void
    {
        $router = Router::getInstance();

        // Public

        $router->get('/', 'home@index', 'home', 'home');

        // Test

        $router->get('/test/:id', 'test@index', 'test', 'test');

        $router->run();
    }

}

