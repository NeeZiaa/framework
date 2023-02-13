<?php
namespace NeeZiaa\Http;

class Response {

    private float $httpVersion = 1.1;
    private int $httpStatus = 200;
    private string $httpMessage = "";
    private string $body = "";
    private array $headers = [];

    /**
     * @param string $body
     * @return Response
     */
    public function setBody(string $body): self
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @param int $status
     * @return Response
     */
    public function setStatus(int $status): self
    {
        $this->httpStatus = $status;
        return $this;
    }

    /**
     * @param string $message
     * @return Response
     */
    public function setMessage(string $message): self
    {
        $this->httpMessage = $message;
        return $this;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return Response
     */
    public function addHeader(string $name, mixed $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * @param string $name
     * @return Response
     */
    public function removeHeader(string $name): self
    {
        unset($this->headers[$name]);
        return $this;
    }

    /**
     * @return Response
     */
    public function build(): self
    {
        http_response_code($this->status);
        header("HTTP/{$this->httpVersion} {$this->httpStatus} {$this->httpMessage}");
        foreach($this->headers as $k => $v) {
            header("{$k}: {$v}");
        }
        return $this;
    }

    public function send()
    {
        echo $this->body;
    }

}