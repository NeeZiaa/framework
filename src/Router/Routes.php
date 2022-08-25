<?php

namespace NeeZiaa\Router;

class Routes {

    /**
     * @throws RouterException
     */
    public function routes(): void
    {
        $router = Router::getInstance();

        // Public

        $router->get('/', 'home@index', 'home');

        // News

        $router->get('/news', 'news@index', 'news');
        $router->get('/news/:id', 'news@article', 'news_article');

        // Autres

        $router->get('/rules', 'home@rules', 'rules');
        $router->get('/contact', 'home@contact', 'contact');
        $router->get('/settings', 'home@settings', 'settings');
        $router->get('/about', 'home@about', 'about');;

        // Team

        $router->get('/team', 'home@team', 'team');

        // Others

        $router->get('/discord', 'home@discord', 'discord');

        // Admin

        $router->get('/admin', 'admin/home@index', 'admin_home');

        // Login

        $router->get('/admin/login', 'admin/login@index', 'admin_login');
        $router->post('/admin/login', 'admin/login@auth', 'admin_login_auth');

        // Settings

        $router->get('/admin/settings', 'admin/settings@index', 'admin_settings');
        $router->post('/admin/settings/update', 'admin/settings@update', 'admin_settings_update');
        $router->post('/admin/rules/update', 'admin/settings@update_rules', 'admin_settings_update_rules');
        $router->post('/admin/about/update', 'admin/settings@update_about', 'admin_settings_update_about');

        // Slides

        $router->get('/admin/slides', 'admin/settings@slides', 'admin_slides');
        $router->get('/admin/slides/create', 'admin/settings@create_slide', 'admin_slide_create');
        $router->get('/admin/slides/update/:id', 'admin/settings@update_slide', 'admin_slide_update');

        $router->post('/admin/slides/create', 'admin/settings@create_slide', 'admin_slide_create');
        $router->post('/admin/slides/update/:id', 'admin/settings@update_slide', 'admin_slide_update');
        $router->post('/admin/slides/delete/:id', 'admin/settings@delete_slide', 'admin_slide_delete');

        // Team

        $router->get('/admin/team', 'admin/settings@team', 'admin_team');
        $router->get('/admin/team/create', 'admin/settings@create_profile', 'admin_team_create');
        $router->get('/admin/team/update/:id', 'admin/settings@update_profile', 'admin_team_update');

        $router->post('/admin/team/create', 'admin/settings@create_profile', 'admin_team_create');
        $router->post('/admin/team/update/:id', 'admin/settings@update_profile', 'admin_team_update');
        $router->post('/admin/team/delete/:id', 'admin/settings@delete_profile', 'admin_team_delete');

        // Admin - News

        $router->get('/admin/news', 'admin/news@index', 'admin_news');
        $router->get('/admin/news/create', 'admin/news@create', 'admin_news_create');
        $router->get('/admin/news/update/:id', 'admin/news@update', 'admin_news_update');

        $router->post('/admin/news/create', 'admin/news@create', 'admin_news_create');
        $router->post('/admin/news/update/:id', 'admin/news@update', 'admin_news_update');
        $router->post('/admin/news/delete/:id', 'admin/news@delete', 'admin_news_delete');

        $router->run();
    }

}

