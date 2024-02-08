<?php

namespace Spip\Bridge\Http\Test\Fixtures;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Spip\Bridge\Http\AbstractMiddleware;

class ActionMiddleware extends AbstractMiddleware
{
    private string $actionName = '';

    private string $actionArgs = '';

    public function when($request): bool
    {
        $this->actionName = $request->getQueryParams()['action'] ?? '';
        $this->actionArgs = $request->getQueryParams()['args'] ?? '';
        $path = rtrim(preg_replace(',(spip|index).php$,', '', $request->getUri()->getPath()), '/') . '/';
        if ((bool) preg_match(',^/(\w+)\.api/?(.*)/$,', $path, $matches)) {
            $this->actionName = 'api_' . $matches[1];
            $this->actionArgs = $matches[2];
        }

        return !in_array($this->actionName, ['', 'page', 'exec']);
    }

    public function then($request): ServerRequestInterface|ResponseInterface
    {
        $request = $request
            ->withAttribute('action', $this->actionName)
            ->withAttribute('args', $this->actionArgs);

        return (new SpipFrameworkHandler(new Psr17Factory))->handle($request);
    }
}
