<?php

namespace NeeZiaa\Utils;

use Dotenv\Dotenv;

class Config {

    private array $settings = [];
    private static ?Config $_instance = null;

    /**
     * @return Config
     */
    public static function getInstance(): Config
    {
        if(is_null(self::$_instance)) self::$_instance = new Config();
        return self::$_instance;
    }

    /**
     * Constructor
     */

    public function __construct()
    {
        $settings = Dotenv::createImmutable(dirname(__DIR__, 2))->load();
        $this->settings = $settings;
    }

    /**
     * @param $key
     * @return string|array|null $settings
     */

    public function get($key): string|array|null
    {
        if(!isset($this->settings[$key])) return null;
        return $this->settings[$key];
    }

    /**
     * @return array $settings
     */

    public function get_all(): array
    {
        return $this->settings;
    }

    /**
     * @param mixed $settings
     * @return bool
     */

    public function update(mixed $settings): bool
    {
        $parent = dirname(__DIR__, 2) . '/';

        try {
            $env = fopen($parent . '.env', 'w+');
            $backup = fopen($parent . '.env.backup', 'w+');
            // Create backup
            file_put_contents($parent . '.env.backup', file_get_contents($parent . '.env'));
            // Update settings
            file_put_contents($parent . '.env', $settings);
            return true;
        } catch(Exception $e) {
            // Some error
            return false;
        }

    }

}