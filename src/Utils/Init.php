<?php
namespace NeeZiaa\Utils;

use NeeZiaa\App;
use NeeZiaa\Database\Mysql\QueryBuilder;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class Init
{

    public static function Twig(array $functions = [], array $filters = []): Environment
    {

        $config = Config::getInstance();

        $app = App::getInstance();

        $functions = [
            ['name'=>'url'],
        ];

        $loader = new FilesystemLoader(dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Views');

        if ($config->get('DEBUG')) $options = []; else $options = ['cache' => $config->get('CACHE_PATH')];

        $twig = new Environment($loader, $options);

//        foreach ($functions as $fu){
//            $fu['name'] = '\NeeZiaa\Twig\\'. ucfirst($fu['name']) . 'Extension';
//            $twig->addFunction(
//                (new $fu['name']())
//                    ->getFunctions()
//            );
//        }
//        foreach ($filters as $fi){
//            $fi['name'] = '\NeeZiaa\Twig\\'. ucfirst($fi['name']) . 'Extension';
//            $twig->addFunction(
//                (new $fi['name']())
//                    ->getFilters()
//            );
//        }
        $function = new TwigFunction('url', function ($route, $params = []) use ($app) {
            $app->getRoutes()->url($route, $params);
        });
        $twig->addFunction($function);
        return $twig;
    }

    public static function render(string $filename, ?array $array_loader = NULL, ?array $twig = null): Environment|array|null
    {
        if (is_null($twig)) {
            $twig = Init::Twig();
        }

        $twig_array = [];

        if (!is_null($array_loader)) {
            $twig_array = array_merge($twig_array, $array_loader);
        }

        echo $twig->render($filename . '.html.twig', $twig_array);

        return $twig;

    }

}