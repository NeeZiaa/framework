<?php

namespace NeeZiaa\Twig\Extensions;

use NeeZiaa\App;
use NeeZiaa\Router\RouterException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UrlExtension extends AbstractExtension
{

    public function getFunctions(): array
    {
        return array(
            new TwigFunction('url', array($this, 'url')),
            new TwigFunction('current_page', array($this, 'current_page'))
        );
    }

    /**
     * @throws RouterException
     */
    public function url($route, $params = [])
    {

        return App::getInstance()->getRouter()->url($route, $params);

    }

    public function current_page(): string
    {
        return App::getInstance()->getRouter()->current();
    }

}