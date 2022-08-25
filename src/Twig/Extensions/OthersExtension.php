<?php

namespace NeeZiaa\Twig\Extensions;

use NeeZiaa\Utils\Alert;
use NeeZiaa\Utils\Exception;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class OthersExtension extends AbstractExtension {


    /**
     * @return TwigFunction
     */
    public function getFunctions(): array
    {
        return array(
            new TwigFunction('alert', array($this, 'alert')),
        );
    }

    /**
     * @throws Exception
     */
    public function alert(): void
    {
        echo Alert::show();
    }

}