<?php

namespace Spip\Component\Http;

use Psr\Http\Message\ResponseInterface;

class Response extends Message implements ResponseInterface
{
    private int $statusCode = 0;
    private string $reasonPhrase = '';

    public function getStatusCode(): int { return $this->statusCode; }
    public function withStatus(int $code, string $reasonPhrase = ''): ResponseInterface
    {
        $this->statusCode = $code;
        $this->reasonPhrase = $reasonPhrase;
        
        return $this;
    }

    public function getReasonPhrase(): string { return $this->reasonPhrase; }
}
