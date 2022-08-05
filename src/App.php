<?php

namespace NeeZiaa;

use NeeZiaa\Database\DatabaseException;
use NeeZiaa\Router\Router;
use NeeZiaa\Router\RouterException;
use NeeZiaa\Router\Routes;
use NeeZiaa\Twig\Twig;
use NeeZiaa\Utils\Config;
use Psr\Log\NullLogger;

class App {

    private static ?App $_instance = null;

    private ?Config $settings;
    private ?Routes $route = null;
    private ?Twig $twig = null;

    private mixed $db = null;
    private ?Router $router = null;

    /**
     * @return App
     */
    public static function getInstance(): App
    {
        if(is_null(self::$_instance)) self::$_instance = new App(Config::getInstance());
        return self::$_instance;
    }

    public function __construct(Config $config)
    {
        $this->settings = Config::getInstance();
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

    public function getRouter(): ?Router
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
            $all_drivers = array('mysql');
            $driver = $this->settings->get('DB_DRIVER');
            if(in_array($driver, $all_drivers))
            {
                $drivername = 'NeeZiaa\Database\\'.ucfirst($driver) . '\\' . ucfirst($driver).'Database';
                return (new $drivername)->getDb($settings['DB_HOST'], $settings['DB_NAME'], $settings['DB_USER'], $settings['DB_PASSWORD']);
            }
            throw new DatabaseException('Undefined driver');
        }
        return $this->db;

    }

    /**
     * @return Config|null
     */
    public function getSettings(): ?Config
    {
        return $this->settings;
    }

    /**
     * @return Twig|null
     */

    public function getTwig(): ?Twig
    {
        if(is_null($this->twig)){
            $this->twig = new Twig($this->getExtensions('twig'));
        }
        return $this->twig;
    }

    /**
     * @param string|NULL $type
     * @return array
     */

    public function getExtensions(string $type = NULL): array
    {
        $extensions = file_get_contents(dirname(__DIR__) . '/extensions.xml');
        $xml = simplexml_load_string($extensions, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        if(!is_null($type)) return json_decode($json,TRUE)[$type];
        return json_decode($json,TRUE);
    }

}