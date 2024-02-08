<?php

namespace Spip\Bridge\Http\Test;

use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Spip\Bridge\Http\HttpPipeline;
use Spip\Bridge\Http\Test\Fixtures\ActionMiddleware;
use Spip\Bridge\Http\Test\Fixtures\EspacePriveMiddleware;
use Spip\Bridge\Http\Test\Fixtures\EspacePublicMiddleware;
use Spip\Bridge\Http\Test\Fixtures\HttpPreRouter;
use Spip\Bridge\Http\Test\Fixtures\SpipFrameworkHandler;

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

    public static function dataProcess(): array
    {
        return [
            'site_public_page_default' => [
                'expected' => [200, 'page', 'sommaire', false, []],
                'uri' => '/',
            ],
            'espace_prive_page_default' => [
                'expected' => [200, 'exec', 'accueil', true, []],
                'uri' => '/ecrire',
            ],
            'espace_prive_page_upgrade_legacy' => [
                'expected' => [200, 'exec', 'upgrade', true, ['reinstall' => 'oui']],
                'uri' => '/ecrire/?exec=upgrade&reinstall=oui',
            ],
            'stats_spip' => [
                'expected' => [200, 'action', 'api_stats', false, []],
                'uri' => '/stats.api/spip/4.2',
            ],
        ];
    }

    #[DataProvider('dataProcess')]
    public function testProcess($expected, $uri): void
    {
        // Given
        $request = $this->factory->createServerRequest('GET', $uri);

        // When
        $actual = $this->pipeline->process($request, $this->final);
        $actualContent = $actual->getBody()->getContents();

        // Then
        $this->assertEquals($expected[0], $actual->getStatusCode());
        $this->assertStringContainsString(
            'Key:action,Value:' . $expected[1],
            $actualContent
        );
        $key = $expected[3] ? 'exec' : 'page';
        $this->assertStringContainsString(
            'Key:' . $key . ',Value:' . $expected[2],
            $actualContent
        );
        $this->assertEquals($expected[3], \str_contains($actualContent, 'Espace privé de SPIP'));
        foreach ($expected[4] as $extra => $value) {
            $this->assertStringContainsString(
                'Extra:' . $extra . ',Value:' . $value,
                $actualContent
            );
        }
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
