<?php

namespace Spip\Component\Http;

use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;

class RequestFactory implements RequestFactoryInterface
{
    public function createRequest(string $method, $uri): RequestInterface
    {
        $uri = (new UriFactory)->createUri($uri);
        return new Request($method, $uri);
    }
}
