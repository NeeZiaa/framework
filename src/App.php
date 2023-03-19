<?php

namespace NeeZiaa;

use NeeZiaa\Attributes\Route;
use NeeZiaa\Components\ConfigLoader;
use NeeZiaa\Components\Twig;
use NeeZiaa\Database\DatabaseException;
use NeeZiaa\Database\MysqlDatabase;
use NeeZiaa\Http\ServerRequest;
use NeeZiaa\Permissions\Job;
use NeeZiaa\Permissions\Permission;
use NeeZiaa\Permissions\User;
use NeeZiaa\Router\Router;
use NeeZiaa\Router\RouterException;
use NeeZiaa\Router\Routes;
use NeeZiaa\Utils\Ip;
use ReflectionException;
use Twig\Environment;

class App {

    private static ?App $_instance = null;

    private ?Config $settings;
    private ?Routes $route = null;
    private ?Environment $twig = null;
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

    public function getClassInstance(string $class)
    {
        if(is_null($this->$class)) {
            $this->$class = new $class();
        }
        return $this->$class;
    }

    /**
     * @return Router
     */
    public function getRouter(): Router
    {
        if(is_null($this->router)) $this->router = new Router($_SERVER['REQUEST_URI']);
        return $this->router;
    }

    /**
     * @throws DatabaseException
     */
    public function getDb()
    {
        $config = (new ConfigLoader('database/config'))->get();
        if(is_null($this->db)) {
//            $allDrivers = array('mysql');
            $driver = $config['driver'];
//            if(in_array($driver, $allDrivers))
//            {
//                $driverName = 'NeeZiaa\Database\\'.ucfirst($driver) . '\\' . ucfirst($driver).'Database';
//                return (new $driverName)->getDb($settings['DB_HOST'], $settings['DB_NAME'], $settings['DB_USER'], $settings['DB_PASSWORD']);
//            }
//            throw new DatabaseException('Undefined driver');
            return MysqlDatabase::getPDO($config['host'], $config['database'], $config['user'], $config['password']);
        }
        return $this->db;

    }

    /**
     * @return Permission
     */
    public function getPermissions(): Permission
    {
        if(is_null($this->permission)) $this->permission = new Permission($this->getUser());
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

    public function getTwig(): Environment
    {
        if(is_null($this->twig)) {
            $this->twig = Twig::getTwig();
        }
        return $this->twig;
    }

    /**
     * @return ServerRequest
     * @throws Http\Exceptions\HttpRequestException
     */
    public function getRequest(): ServerRequest
    {
        if(is_null($this->request)) $this->request = ServerRequest::fromGlobals();
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
     * @return void
     * @throws Stream\Exceptions\StreamException
     * @throws Stream\ParserException|ReflectionException
     */
    public function loadControllers()
    {
        $controllers = (new ConfigLoader('services'))->get()['controllers'];
        foreach($controllers as $k => $v) {
            $this->registerController($v);
        }
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