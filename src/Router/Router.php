<?php
namespace NeeZiaa;

use AltoRouter;
use NeeZiaa\Main;
use NeeZiaa\Controller;
use NeeZiaa\Init;

class Router
{

    /** 
     * @var string
     */
    private $controllerPath;


    /** 
     * @var AltoRouter
     */
    private $router;

    public function __construct()
    {
        $this->controllerPath = Main::env()['PROJECT_PATH'] . DIRECTORY_SEPARATOR  .'app' . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR;

        $this->router = new AltoRouter();
    }

    public function get(string $url, string $controller, string $name): self
    {
   
        $controller = explode('#', $controller);
        $function = $controller[1];
        $controller = $controller[0];

        $name = $name . "#get";

        $this->router->map('GET', $url, $controller, $name);
        $this->function[$name] = $function;
        return $this;
    }

    public function post(string $url, string $controller, string $name): self
    {

        $controller = explode('#', $controller);
        //dd($controller);
        $function = $controller[1];
        $controller = $controller[0];
        $name = $name . "#post";

        $this->router->map('POST', $url, $controller, $name);
        $this->function[$name] = $function;
        return $this;
    }


    public function run()
    {

        $match = $this->router->match(); 

        // dd($match);

        if(!is_array($match)){
            throw new \Exception('No matching routes');
        }

        $function = $this->function[$match['name']];

        $c = Init::load_controller($match['target'], $match['params']);
        $c->$function();

    }

}