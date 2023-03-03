<?php
namespace NeeZiaa\Http;

use NeeZiaa\Http\Exceptions\HttpRequestException;

class ServerRequest {

    private array $headers;
    private array $cookies;
    private string $body;
    private mixed $files;
    private array $serverParams;

    /**
     * @return ServerRequest
     * @throws HttpRequestException
     */
    public static function fromGlobals(): ServerRequest
    {
        return (new ServerRequest())
            ->withServersParams($_SERVER)
            ->withBody(file_get_contents("php://input"))
            ->withCookies($_COOKIE)
            ->withHeaders(getallheaders())
            ->withUploadedFiles($_FILES);
    }

    public function getUri(): string
    {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     * @return $this
     */
    public function withHeaders(array $headers): self
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return $this
     */
    public function withBody(string $body): self
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return array
     */
    public function getCookies(): array
    {
        return $this->cookies;
    }

    /**
     * @param array $cookies
     * @return $this
     */
    public function withCookies(array $cookies): self
    {
        $this->cookies = $cookies;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFiles(): mixed
    {
        return $this->files;
    }

    /**
     * @param mixed $files
     * @return $this
     */
    public function withUploadedFiles(mixed $files): self
    {
        $this->files = $files;
        return $this;
    }

    /**
     * @return array
     */
    public function getServerParams(): array
    {
        return $this->serverParams;
    }

    /**
     * @param array $serverParams
     * @return $this
     */
    private function withServersParams(array $serverParams): self
    {
        $this->serverParams = $serverParams;
        return $this;
    }

}