<?php

namespace SpipRemix\Bridge\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Spip\Bridge\Pipeline\AbstractRule;

abstract class AbstractMiddleware extends AbstractRule implements HttpMiddlewareInterface
{
    /** @param ServerRequestInterface $request */
    abstract public function when($request): bool;

    /** @param ServerRequestInterface $request */
    abstract public function then($request): ServerRequestInterface|ResponseInterface;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->when($request)) {
            $then = $this->then($request);
            if ($then instanceof ResponseInterface) {
                return $then;
            }
            $request = $then;
        }

        return $handler->handle($request);
    }

    public function __invoke($request, ?callable $handler = null): ResponseInterface
    {
        /** @var ServerRequestInterface $request */
        /** @var RequestHandlerInterface $handler */
        return $this->process($request, $handler);
    }
}
