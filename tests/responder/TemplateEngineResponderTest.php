<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Http;

use Legatus\Support\TemplateEngine;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * Class TemplateEngineResponderTest.
 */
final class TemplateEngineResponderTest extends TestCase
{
    public function testItRespondsWithJson(): void
    {
        $simpleResponseMock = $this->createMock(Responder::class);
        $templateEngineMock = $this->createMock(TemplateEngine::class);
        $responseMock = $this->createMock(ResponseInterface::class);

        $simpleResponseMock->expects(self::once())
            ->method('json')
            ->with(['data' => 'value'])
            ->willReturn($responseMock)
        ;

        $simpleResponse = new TemplateEngineResponder($simpleResponseMock, $templateEngineMock);
        $response = $simpleResponse->json(['data' => 'value']);
        self::assertSame($responseMock, $response);
    }

    public function testItRespondsWithHtml(): void
    {
        $simpleResponseMock = $this->createMock(Responder::class);
        $templateEngineMock = $this->createMock(TemplateEngine::class);
        $responseMock = $this->createMock(ResponseInterface::class);

        $simpleResponseMock->expects(self::once())
            ->method('html')
            ->with('<h1>Text</h1>')
            ->willReturn($responseMock)
        ;

        $simpleResponse = new TemplateEngineResponder($simpleResponseMock, $templateEngineMock);
        $response = $simpleResponse->html('<h1>Text</h1>');
        self::assertSame($responseMock, $response);
    }

    public function testItRespondsWithTemplate(): void
    {
        $simpleResponseMock = $this->createMock(Responder::class);
        $templateEngineMock = $this->createMock(TemplateEngine::class);
        $responseMock = $this->createMock(ResponseInterface::class);

        $templateEngineMock->expects(self::once())
            ->method('render')
            ->with('template', ['data' => 'value'])
            ->willReturn('<h1>Text</h1>')
        ;
        $simpleResponseMock->expects(self::once())
            ->method('html')
            ->with('<h1>Text</h1>')
            ->willReturn($responseMock)
        ;

        $simpleResponse = new TemplateEngineResponder($simpleResponseMock, $templateEngineMock);
        $response = $simpleResponse->template('template', ['data' => 'value']);
        self::assertSame($responseMock, $response);
    }

    public function testItRespondsWithRedirect(): void
    {
        $simpleResponseMock = $this->createMock(Responder::class);
        $templateEngineMock = $this->createMock(TemplateEngine::class);
        $responseMock = $this->createMock(ResponseInterface::class);

        $simpleResponseMock->expects(self::once())
            ->method('redirect')
            ->with('/home')
            ->willReturn($responseMock)
        ;

        $simpleResponse = new TemplateEngineResponder($simpleResponseMock, $templateEngineMock);
        $response = $simpleResponse->redirect('/home');
        self::assertSame($responseMock, $response);
    }

    public function testItRespondsWithBlob(): void
    {
        $simpleResponseMock = $this->createMock(Responder::class);
        $templateEngineMock = $this->createMock(TemplateEngine::class);
        $responseMock = $this->createMock(ResponseInterface::class);

        $simpleResponseMock->expects(self::once())
            ->method('blob')
            ->with('hello.png')
            ->willReturn($responseMock)
        ;

        $simpleResponse = new TemplateEngineResponder($simpleResponseMock, $templateEngineMock);
        $response = $simpleResponse->blob('hello.png');
        self::assertSame($responseMock, $response);
    }

    public function testItRespondsWithDownload(): void
    {
        $simpleResponseMock = $this->createMock(Responder::class);
        $templateEngineMock = $this->createMock(TemplateEngine::class);
        $responseMock = $this->createMock(ResponseInterface::class);

        $simpleResponseMock->expects(self::once())
            ->method('download')
            ->with('hello.png')
            ->willReturn($responseMock)
        ;

        $simpleResponse = new TemplateEngineResponder($simpleResponseMock, $templateEngineMock);
        $response = $simpleResponse->download('hello.png');
        self::assertSame($responseMock, $response);
    }

    public function testItRespondsWithNormalResponse(): void
    {
        $simpleResponseMock = $this->createMock(Responder::class);
        $templateEngineMock = $this->createMock(TemplateEngine::class);
        $responseMock = $this->createMock(ResponseInterface::class);

        $simpleResponseMock->expects(self::once())
            ->method('raw')
            ->with(200)
            ->willReturn($responseMock)
        ;

        $simpleResponse = new TemplateEngineResponder($simpleResponseMock, $templateEngineMock);
        $response = $simpleResponse->raw();
        self::assertSame($responseMock, $response);
    }
}
