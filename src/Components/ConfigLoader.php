<?php

namespace NeeZiaa\Components;
use NeeZiaa\Stream\Exceptions\StreamException;
use NeeZiaa\Stream\ParserException;
use NeeZiaa\Stream\Stream;

class ConfigLoader {

    private string $configDirectory = "config";
    private string $configFile;
    private Stream $stream;

    /**
     * @param string $configDirectory
     * @param string $configName
     */
    public function __construct(string $configDirectory, string $configName) 
    {
        $this->configDirectory = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . trim($this->configDirectory, "\/") . DIRECTORY_SEPARATOR . trim($configDirectory, "\/");
        $this->configFile = trim($configName, '\/');
        $this->stream = new Stream($this->configDirectory, $this->configFile);
    }

    /**
     * @return array
     * @throws StreamException
     * @throws ParserException
     */
    public function get() 
    {
        return (new Parser($this->stream->getData()))->getArray();
    }

    /**
     * @param string|array $data
     * @return void
     * @throws StreamException
     * @throws ParserException
     */
    public function write(string|array $data)
    {
        if(is_array($data)) $data = (new Parser($data))->getYaml();
        $this->stream->putData($data, true);
    }

}