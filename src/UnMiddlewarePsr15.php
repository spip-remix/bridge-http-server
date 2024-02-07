<?php

namespace Spip\Component\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UnMiddlewarePsr15 implements MiddlewareInterface, RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->process($request, new RequestHandler);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        dump('processing HTTP Middleware 1');
        if (in_array('action', $request->getQueryParams())) {
            $request
                ->withAttribute('action', $request->getQueryParams()['action'])
                ->withAttribute('args', $request->getQueryParams()['args'] ?? '')
            ;
        }
        if (in_array('page', $request->getQueryParams())) {
            $request
                ->withAttribute('action', 'page')
                ->withAttribute('page', $request->getQueryParams()['page'])
                ->withAttribute('action', '')
                ->withoutAttribute('args')
            ;
        }

        return $handler->handle($request);
    }
}
