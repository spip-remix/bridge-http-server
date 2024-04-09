<?php

namespace SpipRemix\Bridge\Http;

use Psr\Http\Server\MiddlewareInterface as PsrMiddlewareInterface;
use Spip\Bridge\Pipeline\MiddlewareInterface as PipelineMiddlewareInterface;

interface HttpMiddlewareInterface extends PipelineMiddlewareInterface, PsrMiddlewareInterface
{
}
