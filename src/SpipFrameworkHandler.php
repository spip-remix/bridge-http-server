<?php

namespace Spip\Component\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SpipFrameworkHandler implements RequestHandlerInterface
{
    public function __construct(
        private ResponseFactory $factory,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $contents = 'C\'est la fin du chemin.';
        $extraParameters = \array_diff($request->getQueryParams(), $request->getAttributes());
        $parameters = '';
        foreach ($extraParameters as $key => $value) {
            $parameters .= '  Key:' . $key . ',Value:'. $value . PHP_EOL;
        }
        if ($parameters) {
            $parameters = PHP_EOL . 'Extra Parameters' . PHP_EOL . $parameters . \PHP_EOL;
        }
        $restricted = $request->getAttribute('espace_prive', false);
        $contents = ($restricted ? 'Espace privé de SPIP' . PHP_EOL : '') . $parameters . $contents;

        $response = $this->factory->createResponse();

        return $response->withBody(new Stream($contents));
    }
}
