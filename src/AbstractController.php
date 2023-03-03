<?php
namespace NeeZiaa;

use NeeZiaa\Router\Router;
use NeeZiaa\Router\RouterException;

abstract class AbstractController {

    protected App $app;
    protected Router $router;

    protected function __construct()
    {
        $this->app = App::getInstance();
        $this->router = $this->app->getRouter();
    }

    /**
     * @return string
     */
    protected function currentUrl()
    {
        return $this->router->currentUrl();
    }

    /**
     * @param string $route
     * @param array $params
     * @return mixed
     */
    protected function generateUrl(string $route, array $params = [])
    {
        return $this->generateUrl($route, $params);
    }

    /**
     * @param string $url
     * @return void
     */
    protected function redirectToUrl(string $url) {
        header('Location: ' . $url);
    }

    /**
     * @param string $route
     * @param array $params
     * @return void
     * @throws RouterException
     */
    protected function redirectToRoute(string $route, array $params = [])
    {
        $this->redirectToUrl(
            $this->router->generateUrl($route, $params)
        );
    }

}