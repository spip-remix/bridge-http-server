<?php

namespace Spip\Component\Http\Test\Fixtures;

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
        $contents = 'C\'est la fin du chemin.';
        $attributes = 'Attributes:' . PHP_EOL;
        foreach ($request->getAttributes() as $key => $value) {
            $attributes .= sprintf('  Key:%s, Value:%s', \str_pad($key, 12), is_bool($value) ? ($value ? 'oui' : 'non') : $value) . PHP_EOL;
        }
        $attributes .= PHP_EOL;
        $extraParameters = \array_diff($request->getQueryParams(), $request->getAttributes());
        $parameters = '';
        foreach ($extraParameters as $key => $value) {
            $parameters .= '  Key:' . $key . ',Value:'. $value . PHP_EOL;
        }
        if ($parameters) {
            $parameters = PHP_EOL . 'Extra Parameters' . PHP_EOL . $parameters . \PHP_EOL;
        }
        $restricted = $request->getAttribute('espace_prive', false);
        $contents = ($restricted ? 'Espace privé de SPIP' . PHP_EOL : '') . $attributes . $parameters . $contents;

        $response = $this->factory->createResponse();
        $stream = $this->factory->createStream($contents);

        return $response->withBody($stream);
    }
}
