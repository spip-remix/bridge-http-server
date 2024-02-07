<?php

namespace Spip\Component\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestHandler implements RequestHandlerInterface
{
    public function __construct(
        private ResponseFactory $factory,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        dump('handling HTTP Handler '.__CLASS__);
        $contents = 'C\'est la fin du chemin.';
        $complement = $request->getAttribute('espace_prive', false);
        $contents = ($complement ? 'Espace privé de SPIP' . PHP_EOL : '') . $contents;

        $response = $this->factory->createResponse();
        return $response->withBody(new Stream($contents));
    }
}
