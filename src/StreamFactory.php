<?php

namespace Spip\Component\Http;

use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

class StreamFactory implements StreamFactoryInterface
{
    public function createStream(string $content = ''): StreamInterface
    {
        return new Stream($content);
    }

    public function createStreamFromFile(string $filename, string $mode = 'r'): StreamInterface
    {
        return new Stream(\file_get_contents($filename));
    }

    public function createStreamFromResource($resource): StreamInterface
    {
        $content = '';
        while (!\feof($resource)) {
            $content .= \fread($resource, 4096);
        }
        \fclose($resource);

        return new Stream($content);
    }
}
