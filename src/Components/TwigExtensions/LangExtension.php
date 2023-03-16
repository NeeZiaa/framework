<?php
namespace NeeZiaa\Components\TwigExtensions;

use Twig\TwigFunction;

class LangExtension {

    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return array(
            new TwigFunction('translate', array($this, 'translate')),
        );
    }

    public function translate($word): array
    {

    }

}