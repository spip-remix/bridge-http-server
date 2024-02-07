<?php

namespace Spip\Component\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

class ServerRequest extends Request implements ServerRequestInterface
{
    private array $serverParams = [];

    private array $attributes = [];

    private array $queryParams = [];

    public function __construct(string $method, UriInterface $uri, array $serverParams = [])
    {
        $this->serverParams = $serverParams;
        parent::__construct($method, $uri);
        parse_str($this->getUri()->getQuery(), $result);
        $this->queryParams = $result;
    }

    public function getServerParams(): array { return $this->serverParams; }
    public function getCookieParams(): array { return []; }
    public function withCookieParams(array $cookies): ServerRequestInterface { return $this; }
    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function withQueryParams(array $query): ServerRequestInterface
    {
        $this->queryParams = $query;

        return $this;
    }

    public function getUploadedFiles(): array { return []; }
    public function withUploadedFiles(array $uploadedFiles): ServerRequestInterface { return $this; }
    public function getParsedBody() {}
    public function withParsedBody($data): ServerRequestInterface { return $this; }
    public function getAttributes(): array
    { 
        return $this->attributes;
    }

    public function getAttribute(string $name, $default = null)
    {
        if (\array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }

        return $default;
    }

    public function withAttribute(string $name, $value): ServerRequestInterface
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    public function withoutAttribute(string $name): ServerRequestInterface
    {
        if (\array_key_exists($name, $this->attributes)) {
            unset($this->attributes[$name]);
        }

        return $this;
    }
}
