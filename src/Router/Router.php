<?php

namespace NeeZiaa\Router;

use http\Header;
use NeeZiaa\Router\RouterException;

class Router
{
    private string $url;
    private array $routes = [];
    private array $namedRoutes = [];

    /**
     *  constructor
     * @param string $url
     * @return void
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * Get method
     *
     * @param string $path
     * @param mixed $callable
     * @param string|null $name
     *
     * @return self
     */
    public function get(string $path, mixed $callable, ?string $name = null): self
    {
        $this->register($path, $callable, $name, 'GET');
        return $this;
    }

    /**
     * Post method
     *
     * @param string $path
     * @param mixed $callable
     * @param string|null $name
     * @return self
     */
    public function post(string $path, mixed $callable, ?string $name = null): self
    {
        $this->register($path, $callable, $name, 'POST');
        return $this;
    }

    /**
     * Add - Register Route
     *
     * @param string $path
     * @param mixed $callable
     * @param string|null $name
     * @param string $method
     * @return Route
     */
    public function register(string $method, string $path, mixed $callable, ?string $name): Route
    {
        $route = new Route($path, $callable);
        $this->routes[$method][] = $route;
        if ($name) $this->namedRoutes[$name] = $route;
        return $route;
    }

    /**
     * @throws \NeeZiaa\Router\RouterException
     */
    public function run()
    {
        if (!isset($this->routes[$_SERVER['REQUEST_METHOD']])) throw new RouterException('REQUEST_METHOD does not exist');

        foreach($this->routes[$_SERVER['REQUEST_METHOD']] as $route) {
            if ($route->match($this->url)) return $route->call();
        }
        throw new RouterException('No matching routes');
    }

    /**
     * @return string
     */
    public function currentUrl(): string
    {
        if(isset($_SERVER['HTTPS'])) $protocol = 'https'; else $protocol = 'http';
        return $protocol . '://' . $_SERVER['HTTP_HOST'] . $this->url;
    }

    /**
     * Generate link
     * @throws \NeeZiaa\Router\RouterException
     */
    public function generateUrl(string $name, array $params = [])
    {
        if (!isset($this->namedRoutes[$name])) throw new RouterException('No route matches this name');
        return $this->namedRoutes[$name]->getUrl($params);
    }

    public function getParams()
    {

    }

    public function checkUrl()
    {

    }

}