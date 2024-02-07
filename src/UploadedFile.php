<?php

namespace Spip\Component\Http;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;

class UploadedFile implements UploadedFileInterface
{
    public function __construct(
        private Stream $stream,
        private ?int $size = null,
        private int $error,
        private ?string $clientFilename = null,
        private ?string $clientMediaType = null,
    ) {
    }

    public function getStream(): StreamInterface
    {
        return $this->stream;
    }

    public function moveTo(string $targetPath): void
    {
        
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function getError(): int
    {
        return $this->error;
    }

    public function getClientFilename(): ?string
    {
        return $this->clientFilename;
    }

    public function getClientMediaType(): ?string
    {
        return $this->clientMediaType;
    }
}
