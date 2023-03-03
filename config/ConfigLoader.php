<?php

namespace NeeZiaa;

class ConfigLoader {

    private array $array;
    private string $yaml;

    public function __construct(string $config)
    {

    }

    public function load(string $config)
    {

    }

    /**
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function add(string $key, string $value): self
    {
        $this->array[$key] = $value;
        return $this;
    }

    /**
     * @param string $key
     * @return $this
     */
    public function remove(string $key): self
    {
        unset($this->array[$key]);
        return $this;
    }

    /**
     * @return array
     */
    public function getArray(): array
    {
        return $this->array = yaml_parse($this->yaml);
    }

    /**
     * @return string
     */
    public function getYaml(): string
    {
        return $this->yaml = yaml_emit($this->array);
    }

    /**
     * @param ConfigLoader $config
     * @return void
     */
    public static function write(ConfigLoader $config)
    {

    }



}