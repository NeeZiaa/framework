<?php

namespace NeeZiaa\Twig\Extensions;

use NeeZiaa\App;
use NeeZiaa\Router\RouterException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UrlExtension extends AbstractExtension
{

    public function getFunctions(): TwigFunction
    {
        return new TwigFunction('url', array($this, 'url'));
    }

    /**
     * @throws RouterException
     */
    public function url($route, $params = [])
    {

        return App::getInstance()->getRouter()->url($route, $params);

    }

}