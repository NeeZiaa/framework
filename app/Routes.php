<?php

namespace NeeZiaa;

use NeeZiaa\Router;

$router = (new Router())
    ->get('/', 'home#index', 'home')
    ->run();