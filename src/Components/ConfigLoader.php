<?php

namespace NeeZiaa\Components;

class ConfigLoader {

    private string $configDirectory = "config" . DIRECTORY_SEPARATOR . "lang" . DIRECTORY_SEPARATOR;
    private string $configFile;

    public function __construct($configName) 
    {
        $this->configFile = trim($configName, '\/');
        $stream = new Stream($this->configDirectory . $this->configFile);
        $stream = new Stream($this->configDirectory)
        $stream->upload($file)
    }

    public function get() 
    {
        
    }

    public function write(string $content) 
    {
        
    }

    public function openFile(string $file) 
    {
        return fopen($this->configDirectory . $file);
    }

}