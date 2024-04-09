<?php

namespace SpipRemix\Bridge\Http\Test\Fixtures;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use SpipRemix\Bridge\Http\AbstractMiddleware;

class SecurityScreenMiddleware extends AbstractMiddleware
{
    public function when($request): bool
    {
    }

    public function then($request): ServerRequestInterface|ResponseInterface
    {
    }
}
