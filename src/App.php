<?php

namespace NeeZiaa;

use NeeZiaa\Database\DatabaseException;
use NeeZiaa\Router\Router;
use NeeZiaa\Router\RouterException;
use NeeZiaa\Router\Routes;
use NeeZiaa\Twig\Twig;
use NeeZiaa\Utils\Config;
use NeeZiaa\Utils\Ip;
use Psr\Log\NullLogger;

class App {

    // Singleton instance
    private static ?App $_instance = null;

    // Application settings
    private ?Config $settings;

    // Application routes
    private ?Routes $route = null;

    // Twig template engine instance
    private ?Twig $twig = null;

    // Database connection instance
    private mixed $db = null;

    // Router instance
    private ?Router $router = null;

    /**
     * Returns the singleton instance of App.
     *
     * @return App
     */
    public static function getInstance(): App
    {
        if(is_null(self::$_instance)) {
            // Creates a new instance of App
            self::$_instance = new App(Config::getInstance());
        }
        // Returns the singleton instance of App
        return self::$_instance;
    }

    /**
     * Initializes the App with the given configuration.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        // Sets the application settings
        $this->settings = Config::getInstance();
    }

    /**
     * Returns the application routes.
     *
     * @return Routes
     * @throws RouterException
     */
    public function setRoutes(): Routes
    {
        if(is_null($this->route)){
            // Creates a new instance of Routes
            $this->route = new Routes();
            // Registers the application routes
            $this->route->routes();
        }
        // Returns the application routes
        return $this->route;
    }

    /**
     * Returns the router instance.
     *
     * @return Router|null
     */
    public function getRouter(): ?Router
    {
        if(is_null($this->router)){
            // Creates a new instance of Router
            $this->router = Router::getInstance();
        }
        // Returns the router instance
        return $this->router;
    }

    /**
     * Returns the database connection instance.
     *
     * @return mixed
     * @throws DatabaseException
     */
    public function getDb()
    {
        if(is_null($this->db)) {
            // Gets the database settings
            $settings = $this->settings->get_all();
            // Supported drivers
            $all_drivers = array('mysql');
            // Gets the database driver
            $driver = $this->settings->get('DB_DRIVER');
            // If the driver is supported
            if(in_array($driver, $all_drivers))
            {
                // Gets the driver class name
                $drivername = 'NeeZiaa\Database\\'.ucfirst($driver) . '\\' . ucfirst($driver).'Database';
                // Creates a new instance of the driver class
                return (new $drivername)->getDb($settings['DB_HOST'], $settings['DB_NAME'], $settings['DB_USER'], $settings['DB_PASSWORD']);
            }
            // If the driver is not supported
            throw new DatabaseException('Undefined driver');
        }
        // Returns the database connection instance
        return $this
