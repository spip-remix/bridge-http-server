<?php

namespace SpipRemix\Bridge\Http;

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @codeCoverageIgnore
 */
class Converter
{
    private PsrHttpFactory $psrHttpFactory;

    public function __construct(
        private Psr17Factory $psr17Factory,
        private HttpFoundationFactory $httpFoundationFactory,
    ) {
        $this->psrHttpFactory = new PsrHttpFactory(
            $psr17Factory,
            $psr17Factory,
            $psr17Factory,
            $psr17Factory,
        );
    }

    public function fromSymfonyRequest(Request $request): ServerRequestInterface
    {
        return $this->psrHttpFactory->createRequest($request);
    }

    public function fromSymfonyResponse(Response $response): ResponseInterface
    {
        return $this->psrHttpFactory->createResponse($response);
    }

    public function fromPsrServerRequest(ServerRequestInterface $request): Request
    {
        return $this->httpFoundationFactory->createRequest($request);
    }

    public function fromPsrResponse(ResponseInterface $response): Response
    {
        return $this->httpFoundationFactory->createResponse($response);
    }

    public static function fromGlobals(): ServerRequestInterface
    {
        $factory = new Psr17Factory();
        $creator = new ServerRequestCreator(
            $factory, // ServerRequestFactory
            $factory, // UriFactory
            $factory, // UploadedFileFactory
            $factory  // StreamFactory
        );

        return $creator->fromGlobals();
    }
}
