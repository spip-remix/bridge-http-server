<?php

namespace Spip\Component\Http;

use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;

class ServerRequestFactory extends RequestFactory implements ServerRequestFactoryInterface
{
    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        $request = $this->createRequest($method, $uri);
        return new ServerRequest($request->getMethod(), $request->getUri(), $serverParams);
    }
}
