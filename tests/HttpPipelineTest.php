<?php

namespace Spip\Bridge\Http\Test;

use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Spip\Bridge\Http\AbstractMiddleware;
use Spip\Bridge\Http\HttpPipeline;
use Spip\Bridge\Http\Test\Fixtures\ActionMiddleware;
use Spip\Bridge\Http\Test\Fixtures\EspacePriveMiddleware;
use Spip\Bridge\Http\Test\Fixtures\EspacePublicMiddleware;
use Spip\Bridge\Http\Test\Fixtures\SpipFrameworkHandler;

#[CoversClass(HttpPipeline::class)]
#[CoversClass(AbstractMiddleware::class)]
class HttpPipelineTest extends TestCase
{
    private Psr17Factory $factory;
    private SpipFrameworkHandler $final;
    private HttpPipeline $pipeline;

    protected function setUp(): void
    {
        $middlewares = [
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
                'expected' => [200, ['action' => 'page', 'page' => 'sommaire'], false, []],
                'uri' => '/',
            ],
            'espace_prive_page_default' => [
                'expected' => [200, ['action' => 'exec', 'exec' => 'accueil', 'espace_prive' => true], true, []],
                'uri' => '/ecrire',
            ],
            'espace_prive_page_upgrade_legacy' => [
                'expected' => [200, ['action' => 'exec', 'exec' => 'upgrade', 'espace_prive' => true], true, ['reinstall' => 'oui']],
                'uri' => '/ecrire/?exec=upgrade&reinstall=oui',
            ],
            'stats_spip' => [
                'expected' => [200, ['action' => 'api_stats', 'args' => 'spip/4.2'], false, []],
                'uri' => '/?action=api_stats&args=spip/4.2',
            ],
            'stats_spip_rewrite' => [
                'expected' => [200, ['action' => 'api_stats', 'args' => 'plugin/prefix/1.0.0'], false, []],
                'uri' => '/stats.api/plugin/prefix/1.0.0',
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
        $actualContent = \json_decode($actual->getBody()->getContents(), \true);
        // dump('actualContent', $actualContent);

        // Then
        $this->assertEquals($expected[0], $actual->getStatusCode());
        $this->assertEquals($expected[1], $actualContent['attributes']);
        $this->assertEquals($expected[2], \str_contains($actualContent['page'], 'Espace privé de SPIP'));
        $this->assertEquals($expected[3], $actualContent['extras']);
    }

    public function testHandle(): void
    {
        // Given
        $request = $this->factory->createServerRequest('GET', '/');
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No final handler available');

        // When
        (new HttpPipeline)->handle($request);
    }
}
