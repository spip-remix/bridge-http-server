<?php

namespace Spip\Component\Http\Test\Fixtures;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Spip\Component\Http\HttpMiddlewareInterface;
use Spip\Bridge\Pipeline\AbstractRule;

class HttpPreRouter extends AbstractRule implements HttpMiddlewareInterface
{
    private bool $changeExec = false;

    private bool $changePage = false;

    private bool $changeAction = false;

    private string $actionValue = '';

    private string $actionParams = '';

    public function when($request): bool
    {
        /** @var ServerRequestInterface $request */
        $path = rtrim(preg_replace(',(spip|index).php$,', '', $request->getUri()->getPath()), '/') . '/';
        if ((bool) \preg_match(',/ecrire/$,', $path))   {
            $this->changeExec = true;
        } elseif ((bool) preg_match(',^/(\w+)\.api/?(.*)/$,', $path, $matches)) {
            $this->changeAction = true;
            $this->actionValue = 'api_' . $matches[1];
            $this->actionParams = $matches[2];
        } else {
            $queryParams = $request->getQueryParams();
            if (!empty($queryParams['exec']) || ($queryParams['action'] ?? '') == 'exec') {
                $this->changeExec = true;
            }
            if (!empty($queryParams['page']) || ($queryParams['action'] ?? '') == 'page') {
                $this->changePage = true;
            }
        }

        return true;
    }

    public function then($request): mixed
    {
        /** @var ServerRequestInterface $request */
        $queryParams = $request->getQueryParams();
        if ($this->changeExec) {
            $queryParams['action'] = 'exec';
            $queryParams['exec'] = $queryParams['exec'] ?? 'accueil';
        }
        if ($this->changePage) {
            $queryParams['action'] = 'page';
            $queryParams['page'] = $queryParams['page'] ?? 'sommaire';
        }
        if ($this->changeAction) {
            $queryParams['action'] = $this->actionValue;
            $queryParams['args'] = $queryParams['args'] ?? $this->actionParams;
        }
        $this->changeExec = false;
        $this->changePage = false;
        $this->changeAction = false;

        return $request->withQueryParams($queryParams);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->when($request)) {
            $request = $this->then($request);
        }

        return $handler->handle($request);
    }
}