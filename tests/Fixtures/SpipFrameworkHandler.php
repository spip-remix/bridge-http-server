<?php

namespace Spip\Bridge\Http\Test\Fixtures;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SpipFrameworkHandler implements RequestHandlerInterface
{
    public function __construct(
        private Psr17Factory $factory,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $page = 'C\'est la fin du chemin.';
        $restricted = $request->getAttribute('espace_prive', false);
        $page = ($restricted ? 'Espace privé de SPIP: ' : '') . $page;

        $infos = [
            'attributes' => $request->getAttributes(),
            'extras' => \array_diff($request->getQueryParams(), $request->getAttributes()),
            'page' => $page,
        ];

        $response = $this->factory->createResponse();
        $stream = $this->factory->createStream(\json_encode($infos));

        return $response->withBody($stream);
    }
}
