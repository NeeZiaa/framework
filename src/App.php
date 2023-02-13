<?php

namespace NeeZiaa;

use NeeZiaa\Attributes\Route;
use NeeZiaa\Database\DatabaseException;
use NeeZiaa\Http\ServerRequest;
use NeeZiaa\Permissions\Job;
use NeeZiaa\Permissions\Permission;
use NeeZiaa\Permissions\User;
use NeeZiaa\Router\Router;
use NeeZiaa\Router\RouterException;
use NeeZiaa\Router\Routes;
use NeeZiaa\Twig\Twig;
use NeeZiaa\Utils\Config;
use NeeZiaa\Utils\Ip;
use ReflectionException;

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
            $this->router = Router::getInstance();
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
     * @return User
     */
    public function getUser(): User
    {
        if(is_null($this->user)){
            $this->user = new User();
        }
        return $this->user;
    }

    /**
     * @return Job
     */

    public function getJob(): Job
    {
        if(is_null($this->job)){
            $this->job = new Job();
        }
        return $this->job;
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
        if(is_null($this->twig)){
            $this->twig = new Twig($this->getExtensions('twig'));
        }
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
     * @return array
     */
    public function extractPost(): array
    {
        if($_SERVER['REQUEST_METHOD'] === "POST")
        {
            $raw = file_get_contents('php://input');
            if($array = json_decode($raw, true)) {
                return $_POST = $array;
            }
        }
    }

    /**
     * @throws ReflectionException
     */
    public function registerController($controller)
    {
        $class = new \ReflectionClass($controller);
        $routeAttributes = $class->getAttributes(Route::class);
        $prefix = '';
        if(!empty($routeAttributes)) {
            $prefix = $routeAttributes[0]->newInstance()->getPath();
        }
        foreach ($class->getMethods() as $method) {
            $routeAttributes = $method->getAttributes(Route::class);
            if(empty($routeAttributes)) {
                continue;
            }
            foreach ($routeAttributes as $routeAttribute) {
                $route = $routeAttribute->newInstance();
                // $this->router->get($prefix . $route->getPath, []);
            }
        }
    }



}