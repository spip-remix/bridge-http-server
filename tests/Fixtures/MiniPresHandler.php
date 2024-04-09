<?php

namespace SpipRemix\Bridge\Http\Test\Fixtures;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MiniPresHandler implements RequestHandlerInterface
{
    public function __construct(
        private Psr17Factory $factory,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $page = 'C\'est ça ! Fais le malin, toi ...';
        $infos = [
            'attributes' => $request->getAttributes(),
            'extras' => \array_diff($request->getQueryParams(), $request->getAttributes()),
            'page' => $page,
        ];

        $response = $this->factory->createResponse()->withStatus(403);
        $stream = $this->factory->createStream(\json_encode($infos));

        return $response->withBody($stream);
    }
}
