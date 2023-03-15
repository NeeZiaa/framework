<?php

namespace NeeZiaa\Components;
use NeeZiaa\Stream\Stream;

class ConfigLoader {

    private string $configDirectory = "config";
    private string $configFile;
    private Stream $stream;

    public function __construct(string $configDirectory, string $configName) 
    {
        $this->configDirectory = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . trim($this->configDirectory, "\/") . DIRECTORY_SEPARATOR . trim($configDirectory, "\/");
        $this->configFile = trim($configName, '\/');
        $this->stream = new Stream($this->configDirectory, $this->configFile);
    }

    public function get() 
    {
        return (new Parser($this->stream->getData()))->getArray();
    }

    public function write(string|array $data) 
    {
        if(is_array($data)) $data = (new Parser($data))->getYaml();
        $this->stream->putData($data, true);
    }

}