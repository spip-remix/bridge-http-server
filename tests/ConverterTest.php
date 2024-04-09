<?php

namespace SpipRemix\Bridge\Http\Test;

use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use SpipRemix\Bridge\Http\Converter;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Component\HttpFoundation\Request;

#[CoversClass(Converter::class)]
class ConverterTest extends TestCase
{
    private Converter $converter;

    protected function setUp(): void
    {
        $this->converter = new Converter(
            new Psr17Factory,
            new HttpFoundationFactory()
        );
    }

    public function testFromSymfonyRequest(): void
    {
        // Given
        $request = Request::create('/test?param=1');
        $expectedValue = $request->get('param');

        // When
        $actual = $this->converter->fromSymfonyRequest($request);

        // Then
        if (!isset($actual->getQueryParams()['param'])) {
            $this->fail('param not converted');
        }
        $this->assertInstanceOf(ServerRequestInterface::class, $actual);
        $this->assertEquals($expectedValue, $actual->getQueryParams()['param']);
    }
}
