<?php

namespace Spip\Component\Http;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

class Message implements MessageInterface
{
    private StreamInterface $body;
    private string $protocolVersion;

    public function getProtocolVersion(): string
    {
        return 'HTTP/'.$this->protocolVersion;
    }

    public function withProtocolVersion(string $version): MessageInterface
    {
        $this->protocolVersion = $version;

        return $this;
    }

    public function getHeaders(): array { return []; }
    public function hasHeader(string $name): bool { return false; }
    public function getHeader(string $name): array { return []; }
    public function getHeaderLine(string $name): string { return ''; }
    public function withHeader(string $name, $value): MessageInterface { return $this; }
    public function withAddedHeader(string $name, $value): MessageInterface { return $this; }
    public function withoutHeader(string $name): MessageInterface { return $this; }
    public function getBody(): StreamInterface { return $this->body; }
    public function withBody(StreamInterface $body): MessageInterface { $this->body = $body; return $this; }
}
