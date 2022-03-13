<?php
namespace NeeZiaa;

use NeeZiaa\Main;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;


class Init
{

    public static function twig_init()
    {
        $loader = new FilesystemLoader(Main::env()['PROJECT_PATH'] . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Views');
        if (Main::env()['DEBUG'] == true) {
            $twig = new Environment($loader);
        }
        else {
            $twig = new Environment($loader, [
                'cache' => Main::env()['CACHE_PATH'],
            ]);
        }
        return $twig;
    }

    public static function render(string $filename, ?array $array_loader = NULL, ?array $twig = null, )
    {

        if (is_null($twig)) {
            $twig = Init::twig_init();
        }

        $twig_array = [
            'NeeZiaa_name' => Main::env()['NeeZiaa_NAME'],
            'NeeZiaa_desc' => Main::env()['NeeZiaa_DESC'],
            'cfx_link' => Main::env()['CFX_LINK'],
            'logo' => Main::env()['LOGO_NAME'],
        ];

        if (!is_null($array_loader)) {
            $twig_array = array_merge($twig_array, $array_loader);
        }

        echo $twig->render($filename . '.html.twig', $twig_array);

    }

    public static function load_controller(string $controller, array $params)
    {

        if (str_contains($controller, '/')) {

            $c = explode('/', $controller);
            $controllerName = "NeeZiaa\Controller\\" . ucfirst($c[0]) . "\\" . ucfirst($c[1]) . "Controller";
            require_once Main::env()['PROJECT_PATH'] . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . ucfirst($controller) . 'Controller.php';
            return new $controllerName($params);

        }
        else {

            $controllerName = trim('NeeZiaa\Controller\ ') . ucfirst($controller) . 'Controller';
            require_once Main::env()['PROJECT_PATH'] . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . ucfirst($controller) . 'Controller.php';
            return new $controllerName($params);

        }
    }

    public static function load_model(string $model)
    {
        $modelName = trim('\NeeZiaa\Model\ ') . ucfirst($model) . 'Model';
        require_once Main::env()['PROJECT_PATH'] . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR . ucfirst($model) . 'Model.php';
        return (new $modelName);
    }

}