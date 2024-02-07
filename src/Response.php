<?php

namespace Spip\Component\Http;

use Psr\Http\Message\ResponseInterface;

class Response extends Message implements ResponseInterface
{
    private int $status = 0;
    public function getStatusCode(): int { return $this->status; }
    public function withStatus(int $code, string $reasonPhrase = ''): ResponseInterface { $this->status = $code; return $this; }
    public function getReasonPhrase(): string { return ''; }
}
