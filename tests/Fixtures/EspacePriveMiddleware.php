<?php

namespace SpipRemix\Bridge\Http\Test\Fixtures;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SpipRemix\Bridge\Http\AbstractMiddleware;

class EspacePriveMiddleware extends AbstractMiddleware
{
    public function when($request): bool
    {
        $path = rtrim(preg_replace(',(spip|index).php$,', '', $request->getUri()->getPath()), '/') . '/';
        return ((bool) \preg_match(',/ecrire/$,', $path)) ||
            \array_key_exists('exec', $request->getQueryParams());
    }

    public function then($request): ServerRequestInterface|ResponseInterface
    {
        $request = $request
            ->withAttribute('action', 'exec')
            ->withAttribute('exec', $request->getQueryParams()['exec'] ?? 'accueil')
            ->withAttribute('espace_prive', true)
            ->withoutAttribute('args');

        return (new SpipFrameworkHandler(new Psr17Factory))->handle($request);
    }
}
