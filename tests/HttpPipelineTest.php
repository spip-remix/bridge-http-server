<?php

namespace Spip\Component\Http\Test;

use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Spip\Component\Http\HttpPipeline;
use Spip\Component\Http\Test\Fixtures\ActionMiddleware;
use Spip\Component\Http\Test\Fixtures\EspacePriveMiddleware;
use Spip\Component\Http\Test\Fixtures\EspacePublicMiddleware;
use Spip\Component\Http\Test\Fixtures\HttpPreRouter;
use Spip\Component\Http\Test\Fixtures\SpipFrameworkHandler;

#[CoversClass(HttpPipeline::class)]
class HttpPipelineTest extends TestCase
{
    private Psr17Factory $factory;
    private SpipFrameworkHandler $final;
    private HttpPipeline $pipeline;

    protected function setUp(): void
    {
        $middlewares = [
            new HttpPreRouter,
            new ActionMiddleware,
            new EspacePriveMiddleware,
            new EspacePublicMiddleware,
        ];
        $this->factory = new Psr17Factory;
        $this->final = new SpipFrameworkHandler($this->factory);
        
        $this->pipeline = new HttpPipeline(...$middlewares);
    }

    public function testProcess(): void
    {
        // Given
        $request = $this->factory->createServerRequest('GET', '/');

        // When
        $actual = $this->pipeline->process($request, $this->final);

        // Then
        $this->assertEquals(200, $actual->getStatusCode());
    }

    public function testHandle(): void
    {
        // Given
        $request = $this->factory->createServerRequest('GET', '/');
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No final handler available');
        
        // When
        $this->pipeline->handle($request);
    }
}
