<?php

namespace Spip\Component\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Spip\Component\Pipeline\AbstractRule;

class ActionMiddleware extends AbstractRule implements MiddlewareInterface
{
    public function when($request): bool
    {
        $action = '';
        /** @var ServerRequestInterface $request */
        if (array_key_exists('action', $request->getQueryParams())) {
            $action = $request->getQueryParams()['action'];
        }

        return !in_array($action, ['', 'page', 'exec']);
    }
    
    public function then($request): mixed
    {
        /** @var ServerRequestInterface $request */
        $request
            ->withAttribute('action', $request->getQueryParams()['action'])
            ->withAttribute('args', $request->getQueryParams()['args'] ?? '')
        ;

        return (new SpipFrameworkHandler(new ResponseFactory))->handle($request);
    }
    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->when($request)) {
            return $this->then($request);
        }

        return $handler->handle($request);
    }
}
