<?php

namespace SpipRemix\Bridge\Http\Test\Fixtures;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SpipRemix\Bridge\Http\AbstractMiddleware;

class EspacePublicMiddleware extends AbstractMiddleware
{
    public function when($request): bool
    {
        return true;
    }

    public function then($request): ServerRequestInterface|ResponseInterface
    {
        return $request
            ->withAttribute('action', 'page')
            ->withAttribute('page', $request->getQueryParams()['page'] ?? 'sommaire')
            ->withoutAttribute('exec')
            ->withoutAttribute('args');
    }
}
