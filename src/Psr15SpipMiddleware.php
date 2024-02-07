<?php

namespace Spip\Component\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Psr15SpipMiddleware implements MiddlewareInterface, RequestHandlerInterface
{
    public function __construct(
        private ResponseFactory $factory,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->process($request, new RequestHandler($this->factory));
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        dump('processing HTTP Middleware '.__CLASS__);
        if (in_array('action', array_keys($request->getQueryParams()))) {
            $request
                ->withAttribute('action', $request->getQueryParams()['action'])
                ->withAttribute('args', $request->getQueryParams()['args'] ?? '')
            ;
        }
        if (in_array('exec', array_keys($request->getQueryParams()))) {
            dump('Attention! On est dans l\'espace privé!');
            $request
                ->withAttribute('action', 'exec')
                ->withAttribute('exec', $request->getQueryParams()['exec'])
                ->withAttribute('espace_prive', true)
                ->withoutAttribute('args')
                ;
            }
            if (in_array('page', array_keys($request->getQueryParams()))) {
                $request
                ->withAttribute('action', 'page')
                ->withAttribute('page', $request->getQueryParams()['page'])
                ->withoutAttribute('exec')
                ->withoutAttribute('args')
            ;
        }

        return $handler->handle($request);
    }
}
