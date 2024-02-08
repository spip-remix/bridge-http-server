<?php

namespace Spip\Bridge\Http\Test\Fixtures;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Spip\Bridge\Http\HttpMiddlewareInterface;

class EspacePublicMiddleware implements HttpMiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request = $request
            ->withAttribute('action', 'page')
            ->withAttribute('page', $request->getQueryParams()['page'] ?? 'sommaire')
            ->withoutAttribute('exec')
            ->withoutAttribute('args');

        return $handler->handle($request);
    }

    public function __invoke($payload, ?callable $next = null): mixed
    {
    }
}
