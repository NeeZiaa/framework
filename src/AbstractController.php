<?php
namespace NeeZiaa;

use NeeZiaa\Http\Response;
use NeeZiaa\Router\Router;
use NeeZiaa\Router\RouterException;

abstract class AbstractController {

    protected App $app;
    protected Router $router;

    public function __construct()
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

    /**
     * @param string $filename
     * @param array $arrayLoader
     * @param array|null $twig
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    protected function render(string $filename, array $arrayLoader = [], ?array $twig = null)
    {
        $filename = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $filename);
        $twig = $this->app->getTwig();
        $twigArray = [];
        $twigArray = array_merge($twigArray, $arrayLoader);
        (new Response())
            ->setBody($twig->render($filename . '.html.twig', $twigArray))
            ->setStatus(201)
            ->send();
    }
}