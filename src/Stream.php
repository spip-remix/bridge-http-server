<?php

namespace Spip\Component\Http;

use Psr\Http\Message\StreamInterface;

class Stream implements StreamInterface
{
    public function __construct(
        private string $contents = '',
    ) {
    }

    public function __toString(): string { return ''; }
    public function close(): void {}
    public function detach() {}
    public function getSize(): ?int { return null; }
    public function tell(): int { return 0; }
    public function eof(): bool { return false; }
    public function isSeekable(): bool { return false; }
    public function seek(int $offset, int $whence = SEEK_SET): void {}
    public function rewind(): void {}
    public function isWritable(): bool { return false; }
    public function write(string $string): int { return 0; }
    public function isReadable(): bool { return false; }
    public function read(int $length): string { return ''; }
    public function getContents(): string { return $this->contents; }
    public function getMetadata(?string $key = null) { return null; }
}
