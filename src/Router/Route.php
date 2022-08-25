<?php
namespace NeeZiaa\Router;

class Route
{
    private string $path;
    private mixed $callable;
    private array $matches = [];
    private array $params = [];


    public function __construct($path, $callable)
    {
        $this->path = trim($path, '/');
        $this->callable = $callable;
    }

    public function with($param, $regex): self
    {
        $this->params[$param] = str_replace('(', '(?:', $regex);
        return $this;
    }

    public function match($url): bool
    {
        $url = trim($url, '/');
        $path = preg_replace_callback('#:([\w]+)#', [$this, 'paramMatch'], $this->path);
        $regex = "#^$path$#i";

        if (!preg_match($regex, $url, $matches)) {
            return false;
        }
        array_shift($matches);
        $this->matches = $matches;
        return true;
    }

    private function paramMatch($match): string
    {
        if (isset($this->params[$match[1]])) {
            return '(' . $this->params[$match[1]] . ')';
        }
        return '([^/]+)';
    }

    public function call()
    {
        $co = [];
        if (is_string($this->callable)) {
            $params = explode('@', $this->callable);
            $c = explode('/', $params[0]);
            foreach($c as $c) {
                $co[] = ucfirst($c);
            }
            $co = join('\\', $co);
            $controller = "App\\Controller\\" . $co . "Controller";
            $controller = new $controller($this->matches);
            return call_user_func_array([$controller, $params[1]], $this->matches);
        } else {
            return call_user_func_array($this->callable, $this->matches);
        }
    }

    public function getUrl($params): array|string
    {
        $path = $this->path;
        foreach($params as $key => $value) {
            $path = str_replace(":$key", $value, $path);
        }
        if(isset($_SERVER['HTTPS'])) $protocol = 'https'; else $protocol = 'http';
        return $protocol . '://' . $_SERVER['HTTP_HOST'] . '/' . $path;
    }
}