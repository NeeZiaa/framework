<?php

namespace NeeZiaa\Components;

class ConfigLoader {

    private string $config_directory = "config" . DIRECTORY_SEPARATOR . "lang" . DIRECTORY_SEPARATOR;

    public function __construct($configName) 
    {
        $this->config_file = $configName;
    }

    public function get() 
    {
        file_get_contents()
    }

    public function write(string $content) 
    {
        
    }

}