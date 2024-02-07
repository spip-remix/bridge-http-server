<?php

namespace Spip\Component\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class EspacePriveMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (\array_key_exists('exec', $request->getQueryParams())) {
            $request
                ->withAttribute('action', 'exec')
                ->withAttribute('exec', $request->getQueryParams()['exec'] ?? 'accueil')
                ->withAttribute('espace_prive', true)
                ->withoutAttribute('args')
            ;

            return (new SpipFrameworkHandler(new ResponseFactory))->handle($request);
        }

        return $handler->handle($request);
    }

    public function __invoke($payload, ?callable $next = null): mixed
    {
    }
}
