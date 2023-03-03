<?php

namespace NeeZiaa\Twig\Extensions;

use NeeZiaa\App;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PermissionExtension extends AbstractExtension {

    public function getFunctions(): array
    {
        return array(
            new TwigFunction('can', array($this, 'can')),

        );
    }

    public function can($permission): bool
    {
        return App::getInstance()->getPermissions()->can($permission);
    }

}