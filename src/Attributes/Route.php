<?php
namespace NeeZiaa\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD|Attribute::TARGET_CLASS)]
class Route
{

    /**
     * @param string $method
     * @param string $path
     * @param string $name
     */
    public function __construct(private string $method, private string $path, private string $name) {}

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

}