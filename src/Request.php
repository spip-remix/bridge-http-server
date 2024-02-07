<?php

namespace Spip\Component\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class Request extends Message implements RequestInterface
{
    public function getRequestTarget(): string { return '/'; }
    public function withRequestTarget(string $requestTarget): RequestInterface { return $this; }
    public function getMethod(): string { return 'GET'; }
    public function withMethod(string $method): RequestInterface { return $this; }
    public function getUri(): UriInterface {return new Uri; }
    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface { return $this; }
}
