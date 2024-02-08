<?php

namespace Spip\Component\Http\Test\Fixtures;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Spip\Component\Http\HttpMiddlewareInterface;

class EspacePriveMiddleware implements HttpMiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (\array_key_exists('exec', $request->getQueryParams())) {
            $request = $request
                ->withAttribute('action', 'exec')
                ->withAttribute('exec', $request->getQueryParams()['exec'] ?? 'accueil')
                ->withAttribute('espace_prive', true)
                ->withoutAttribute('args')
            ;

            return (new SpipFrameworkHandler(new Psr17Factory))->handle($request);
        }

        return $handler->handle($request);
    }

    public function __invoke($payload, ?callable $next = null): mixed
    {
    }
}
