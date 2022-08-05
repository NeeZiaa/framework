<?php

require '..' . DIRECTORY_SEPARATOR . 'Utils' . DIRECTORY_SEPARATOR . 'Main.php';
require '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$name = $argv[1];

// if(str_contains($name, '/')){
//     $name_explode = explode('/', $name);
//     if(!is_dir(\NeeZiaa\Main::env()['PROJECT_PATH']) . ){
//         mkdir()
//     }
// }

if(is_null($name)){
    echo "Veuillez entrer le nom du controller";
    exit;
}

$newController = fopen(\NeeZiaa\Router\Router\Utils\Main::env()['PROJECT_PATH'] . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR . ucfirst($name) . 'Controller.php', 'w+');
$routesBackup = fopen(\NeeZiaa\Router\Router\Utils\Main::env()['PROJECT_PATH'] . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Routes.php', 'r');

// Backup
$file = \NeeZiaa\Router\Router\Utils\Main::env()['PROJECT_PATH'] . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Routes.php';
$newfile = \NeeZiaa\Router\Router\Utils\Main::env()['PROJECT_PATH'] . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Routes.php.bak';
copy($file, $newfile);

try {

    if(in_array('--post', $argv)){

        $routes = file_get_contents($file);
        $routes = str_replace('    ->run();','',$routes,);
        $routes = $routes . "    ->get('/$name', '$name', 'index', '$name')
        ->run();";
        $editRoutes = fopen($file, 'w+');
        fwrite($editRoutes, $routes);

        echo "Route ajouté !";       
         
    } else {

        $routes = file_get_contents($file);
        $routes = str_replace('    ->run();','',$routes,);
        $routes = $routes . "    ->get('/$name', '$name', 'index', '$name')
        ->run();";
        $editRoutes = fopen($file, 'w+');
        fwrite($editRoutes, $routes);

        echo "Route ajouté !";        

    }

} catch(Exception $e) {

    $routes = file_get_contents($newfile);
    $editRoutes = fopen($file, 'w+');
    fwrite($editRoutes, $routes);
    echo "Echec de l'ajout de la Route, restauration de la backup";
    exit;
}


$controller = file_get_contents(\NeeZiaa\Router\Router\Utils\Main::env()['PROJECT_PATH'] . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'Controller' . DIRECTORY_SEPARATOR . 'ExampleController.php');

$controller = str_replace('_name_1', ucfirst($name), $controller);
$controller = str_replace('_name_2', $name, $controller);

if(in_array('--view', $argv)){

    $newView = fopen(\NeeZiaa\Router\Router\Utils\Main::env()['PROJECT_PATH'] . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . $name . '.html.twig', 'w+');


    $view = file_get_contents(\NeeZiaa\Router\Router\Utils\Main::env()['PROJECT_PATH'] . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR . 'ExampleView.html.twig');

    $view = str_replace('_name_', ucfirst($name), $view);

    fwrite($newView, $view);    

}

fwrite($newController, $controller);

echo "\nYour controller is ready !\n";