<?php

namespace NeeZiaa;

use NeeZiaa\Router;

$router = (new Router())

    // Pages
    ->get('/', 'home#index', 'home')   
    ->get('/console', 'home#console', 'console')
    ->get('/login', 'login#index', 'login')
    ->post('/auth', 'login#post', 'login')
    
    // Api
 
    ->get('/api/console', 'api/console#show_log', 'show_log')
    ->run();