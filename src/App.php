<?php

namespace NeeZiaa;

use NeeZiaa\Attributes\Route;
use NeeZiaa\Components\ConfigLoader;
use NeeZiaa\Database\DatabaseException;
use NeeZiaa\Http\ServerRequest;
use NeeZiaa\Permissions\Job;
use NeeZiaa\Permissions\Permission;
use NeeZiaa\Permissions\User;
use NeeZiaa\Router\Router;
use NeeZiaa\Router\RouterException;
use NeeZiaa\Router\Routes;
use NeeZiaa\Twig\Twig;
use NeeZiaa\Utils\Ip;
use ReflectionException;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class App {

    private static ?App $_instance = null;

    private ?Config $settings;
    private ?Routes $route = null;
    private ?Twig $twig = null;
    private ?User $user = null;
    private ?Job $job = null;
    private ?Permission $permission = null;
    private ?Router $router = null;
    private ?ServerRequest $request = null;

    private mixed $db = null;

    /**
     * @return App
     */
    public static function getInstance(): App
    {
        if(is_null(self::$_instance)) self::$_instance = new App(Config::getInstance());
        return self::$_instance;
    }

    public function __construct(Config $config = null)
    {
        is_null($config) ? $this->settings = Config::getInstance() : $this->settings = $config;
    }

    /**
     * @return Routes
     * @throws RouterException
     */
    public function setRoutes(): Routes
    {
        if(is_null($this->route)){
            $this->route = new Routes();
            $this->route->routes();
        }
        return $this->route;
    }

    /**
     * @return Router
     */
    public function getRouter(): Router
    {
        if(is_null($this->router)){
            $this->router = new Router($_SERVER['REQUEST_URI']);
        }
        return $this->router;
    }

    /**
     * @throws DatabaseException
     */
    public function getDb()
    {
        if(is_null($this->db)) {
            $settings = $this->settings->get_all();
            $allDrivers = array('mysql');
            $driver = $this->settings->get('DB_DRIVER');
            if(in_array($driver, $allDrivers))
            {
                $driverName = 'NeeZiaa\Database\\'.ucfirst($driver) . '\\' . ucfirst($driver).'Database';
                return (new $drivername)->getDb($settings['DB_HOST'], $settings['DB_NAME'], $settings['DB_USER'], $settings['DB_PASSWORD']);
            }
            throw new DatabaseException('Undefined driver');
        }
        return $this->db;

    }

    /**
     * @return Permission
     */
    public function getPermissions(): Permission
    {
        if(is_null($this->permission)){
            $this->permission = new Permission($this->getUser());
        }
        return $this->permission;
    }

    /**
     * @return Config
     */
    public function getSettings(): Config
    {
        return $this->settings;
    }

    /**
     * @return Twig
     */

    public function getTwig(): Twig
    {
        $twigConfig = (new ConfigLoader("config" . DIRECTORY_SEPARATOR . "twig", "config" . ".yaml"))->get();
        $loader = new FilesystemLoader(dirname(__DIR__) . $twigConfig['views_path']);

        if($twigConfig['cache_path']) $options = ['cache' => $twigConfig['cache_path']]; else $options = [];

        $twig = new Environment($loader, $options);
        $extensions = $twigConfig['extensions'];

        if(!is_null($extensions)) {
            foreach ($extensions['functions'] as $fu){
                $name = '\NeeZiaa\Twig\Extensions\\'. ucfirst($fu) . 'Extension';
                foreach ((new $name())->getFunctions() as $v) {
                    $twig->addFunction($v);
                }
                $twig->addExtension(new $name());
            }
            foreach ($extensions['filters'] as $fi){
                $name = '\NeeZiaa\Twig\\'. ucfirst($fi) . 'Extension';
                $twig->addFunction(
                    (new $name())
                        ->getFilters()
                );
                $twig->addExtension(new $name());
            }
        }

        // if(is_null($this->twig)){
        //     $this->twig = new Twig($this->getExtensions('twig'));
        // }
        return $this->twig;
    }

    /**
     * @return ServerRequest
     * @throws Http\Exceptions\HttpRequestException
     */
    public function getRequest(): ServerRequest
    {
        if(is_null($this->request)) {
            $this->request = ServerRequest::fromGlobals();
        }
        return $this->request;
    }

    /**
     * @param string|NULL $type
     * @return array
     */

    public function getExtensions(string $type = NULL): array
    {
        $extensions = file_get_contents(dirname(__DIR__) . '/extensions.xml');
        $xml = simplexml_load_string($extensions, options: LIBXML_NOCDATA);
        $json = json_encode($xml);
        if(!is_null($type)) return json_decode($json,TRUE)[$type];
        return json_decode($json,TRUE);
    }

    /**
     * @return string
     */

    public function getIp(): string
    {
        return Ip::getIp();
    }

    /**
     * @param string $controller
     * @return $this
     * @throws ReflectionException
     */
    public function registerController(string $controller): self
    {
        $class = new \ReflectionClass('App\Controller\\' . $controller . 'Controller');
        $routeAttributes = $class->getAttributes(Route::class);

        !empty($routeAttributes) ? $prefix = $routeAttributes[0]->newInstance()->getPath() : $prefix = '';

        foreach ($class->getMethods() as $method) {
            $routeAttributes = $method->getAttributes(Route::class);
            if(empty($routeAttributes)) {
                continue;
            }
            foreach ($routeAttributes as $routeAttribute) {
                /** @var Route $route */
                $route = $routeAttribute->newInstance();
                $this->getRouter()->register($route->getMethod(), $prefix . $route->getPath(), $controller . '@' . $method->getName(), $route->getName());
            }
        }
        return $this;
    }

    public function run()
    {
        $this->getRouter()->run();
    }

}