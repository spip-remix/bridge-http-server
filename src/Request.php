<?php

namespace Spip\Component\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class Request extends Message implements RequestInterface
{
    private string $method;

    private UriInterface $uri;

    public function __construct(string $method, UriInterface $uri)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->withProtocolVersion('1.1');
    }

    public function getRequestTarget(): string { return '/'; }
    public function withRequestTarget(string $requestTarget): RequestInterface { return $this; }
    public function getMethod(): string
    {
        return $this->method;
    }

    public function withMethod(string $method): RequestInterface
    {
        $this->method = $method;
        
        return $this;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        $this->uri = $uri;
        
        return $this;
    }
}
