<?php
namespace NeeZiaa\Http;

class Request {

    private string $method;
    private array $headers = [];
    private array $cookies = [];
    private string $body = "";
    private string $uri = "";
    private string $protocol = "";

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     * @return $this
     */
    public function setUri(string $uri): self
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @return $this
     * @throws Exceptions\HttpRequestException
     */
    public function setMethod(string $method): self
    {
        if(in_array($method, ['GET', 'POST'])) {
            $this->method = $method;
        } else {
            throw new Exceptions\HttpRequestException("Method not allowed");
        }
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
    public function getParsedBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return $this
     */
    public function withParsedBody(string $body): self
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

    public function withCookies(array $cookies): self
    {
        $this->cookies = $cookies;
        return $this;
    }
    /**
     * @return string
     */
    public function getProtocol(): string
    {
        return $this->protocol;
    }

    public function withProtocol(string $protocol): self
    {
        $this->protocol = $protocol;
        return $this;
    }

    /**
     * @return bool|string
     * @throws Exceptions\HttpRequestException
     */
    public function send(): bool|string
    {
        $cookies = [];
        foreach($this->cookies as $k => $v) {
            $cookies[] = "$k=$v";
        }
        $cookies = join("&", $cookies);

        try {
            $request = curl_init($this->uri);
            curl_setopt($request, CURLOPT_URL, $this->uri);
            curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
            if($_SERVER['REQUEST_METHOD'] == "POST") {
                curl_setopt($request, CURLOPT_POST, true);
                curl_setopt($request, CURLOPT_POSTFIELDS, $this->body);
            }
            curl_setopt($request, CURLOPT_HTTPHEADER, $this->headers);
            curl_setopt($request, CURLOPT_COOKIE, $cookies);

            $response = curl_exec($request);
            curl_close($request);

            return $response;

        } catch(Exceptions\HttpRequestException) {
            throw new Exceptions\HttpRequestException("An error occurred while submitting the request");
        }

    }

}