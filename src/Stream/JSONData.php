<?php

namespace NeeZiaa\Utils;

class JSONData {

    /**
     * @var string
     */
    private string $file;

    /**
     * @var mixed|string
     */
    private mixed $folder;

    /**
     * @var string
     */
    private string $raw;

    /**
     * @var array
     */
    private array $array;


    /**
     * @return bool|string
     */
    public function get_raw(): bool|string
    {
        return $this->raw;
    }

    /**
     * @return array
     */
    public function get_array(): array
    {
        return $this->array;
    }

    /**
     * @param string $file
     * @param $folder
     */
    public function __construct(string $file, $folder = null)
    {
        if(is_null($folder)){
            $this->folder = dirname(__DIR__, 2) . '/storage/data' . DIRECTORY_SEPARATOR;
        } else {
            $this->folder = trim($folder, '/');
        }
        $this->file = $file;
        $this->raw = json_encode($this->get());
        $this->array = $this->get();
    }

    /**
     * @param null $key
     * @return array
     */
    public function get($key = null): array
    {
        if(is_null($key)){
            $return = json_decode(file_get_contents($this->folder . $this->file), true);
        } else {
            $return = json_decode(file_get_contents($this->folder . $this->file), true)[$key];
        }
        if(is_bool($return) or is_string($return)){
            // Invalid path or JSON content
            exit();
        }
        return $return;
    }

    /**
     * @param string|int $key
     * @param array|string|int|bool $content
     * @return array
     */
    public function add(string|int $key, array|string|int|bool $content): array
    {
        $array = $this->array;
        if(key_exists($key, $array)) {
            $this->remove($key);
        }
        $array[$key] = $content;

        $this->raw = json_encode($array);
        return $this->array = $array;
    }

    /**
     * @param string|int ...$key
     * @return array|mixed
     */
    public function remove(string|int ...$key): mixed
    {
        $array = $this->array;
        $i = 0;
        $len = count($key);
        foreach ($key as $k) {
            $array = $array[$k];
            if($i === $len) unset($array);
        }

        $this->raw = json_encode($array);
        return $this->array = $array;
    }

    /**
     * @return false|int
     */
    public function push(): bool|int
    {
        return file_put_contents($this->folder . DIRECTORY_SEPARATOR . $this->file, $this->raw);
    }

}