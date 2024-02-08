<?php

namespace Spip\Component\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class HttpPipeline implements RequestHandlerInterface, HttpMiddlewareInterface
{
    private RequestHandlerInterface $final;

    /** @var HttpMiddlewareInterface[] */
    private array $middlewares;

    public function __construct(
        HttpMiddlewareInterface ...$middlewares,
    ) {
        $this->middlewares = $middlewares;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (empty($this->middlewares)) {
            if (!isset($this->final)) {
                throw new \RuntimeException('No final handler available');
            }
            return $this->final->handle($request);
        }
        $middleware = array_shift($this->middlewares);

        return $middleware->process($request, $this);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->final = $handler;
        return $this->handle($request);
    }

    /**
     * @codeCoverageIgnore
     */
    public function __invoke($payload, ?callable $next = null): mixed
    {
    }
}
