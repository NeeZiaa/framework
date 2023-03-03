<?php

namespace NeeZiaa\Components;
use NeeZiaa\Stream\ParserException;
use SimpleXMLElement;

class Parser {

    private array $array;
    private string $yaml;
    private string $json;
    private string $xml;

    /**
     * @param string|array $input
     * @throws ParserException
     */
    public function __construct(string|array $input)
    {
        $type = self::detect($input);
        $this->$type = $input;
        $this->buildArray($input, $type);
    }

    /**
     * @param string|array $input
     * @return string
     * @throws ParserException
     */
    private static function detect(string|array $input): string
    {
        if(gettype($input) == "array") echo "array";

        if(self::getArrayFromYaml($input) !== false) {
            return 'yaml';
        } elseif (self::getArrayFromJson($input) !== null) {
            return 'json';
        } elseif (self::getArrayFromXml($input) !== null) {
            return 'xml';
        }
        throw new ParserException("Unknown type. Only Yaml, JSON, XML and Array are allowed");
    }

    private function buildArray(string $from, string|array $input): self
    {
        $this->array = match($from) {
            'array' => $input,
            'yaml' => self::getArrayFromYaml($input),
            'json' => self::getArrayFromJson($input),
            'xml' => self::getArrayFromXml($input),
        };
        return $this;
    }

    private static function getArrayFromYaml(string|array $input): array|bool
    {
        return yaml_parse($input);
    }

    private static function getArrayFromJson(string|array $input)
    {
        return json_decode($input, true);
    }

    private static function getArrayFromXml(string|array $input)
    {
        return self::getArrayFromJson(
            json_encode(simplexml_load_string($input))
        );
    }

    /**
     * @param string $value
     * @param string $key
     * @return $this
     */
    public function add(string $value, string $key = ""): self
    {
        $input =
        [
            [
                'key'=>'aa',
                'value'=>'bb',
            ],
            [
                ''
            ]
        ];
        if(empty($key))
        foreach ($input as $i) {
            $this->array[$i] = $value;
        }
        return $this;
    }

    /**
     * @param string ...$key
     * @return $this
     */
    public function remove(string ...$key): self
    {
        foreach ($key as $k) {
            unset($this->array[$k]);
        }
        return $this;
    }

    public function clear(): self
    {
        $this->array = [];
        return $this;
    }

    /**
     * @return array
     */
    public function getArray(): array
    {
        return $this->array;
    }

    /**
     * @return string
     */
    public function getYaml(): string
    {
        return $this->yaml = yaml_emit($this->array);
    }

    /**
     * @return string
     */
    public function getJson(): string
    {
        return $this->json = json_encode($this->array);
    }

    /**
     * @return string
     */
    public function getXML()
    {
        $xmlData = new SimpleXMLElement('<?xml version="1.0"?><data></data>');
        self::arrayToXML($this->array, $xmlData);
        $xmlData->asXML();
    }

    private static function arrayToXML($data, &$xmlData)
    {
        foreach($data as $key => $value ) {
            if( is_array($value) ) {
                if( is_numeric($key) ){
                    $key = 'item'.$key;
                }
                $subnode = $xmlData->addChild($key);
                self::arrayToXML($value, $subnode);
            } else {
                $xmlData->addChild("$key",htmlspecialchars("$value"));
            }
        }
    }

}