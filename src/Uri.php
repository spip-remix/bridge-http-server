<?php

namespace Spip\Component\Http;

use Psr\Http\Message\UriInterface;

class Uri implements UriInterface
{
    private string $query;

    public function __construct(
        private string $uri = '',
    ) {
        $parsed = parse_url($uri);
        $this->query = $parsed['query'];
    }

    public function getScheme(): string { return ''; }
    public function getAuthority(): string { return ''; }
    public function getUserInfo(): string { return '';  }
    public function getHost(): string { return ''; }
    public function getPort(): ?int { return null; }
    public function getPath(): string { return ''; }
    public function getQuery(): string
    {
        return $this->query;
    }

    public function getFragment(): string { return ''; }
    public function withScheme(string $scheme): UriInterface { return $this; }
    public function withUserInfo(string $user, ?string $password = null): UriInterface { return $this; }
    public function withHost(string $host): UriInterface { return $this; }
    public function withPort(?int $port): UriInterface { return $this; }
    public function withPath(string $path): UriInterface { return $this; }
    public function withQuery(string $query): UriInterface
    {
        $this->query = $query;
    
        return $this;
    }

    public function withFragment(string $fragment): UriInterface { return $this; }
    public function __toString(): string
    {
        return $this->uri;
    }
}
