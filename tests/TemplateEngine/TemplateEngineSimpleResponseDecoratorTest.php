<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Http\Responder\Tests\TemplateEngine;

use Legatus\Http\Responder\Responder;
use Legatus\Http\Responder\TemplateEngine\TemplateEngineInterface;
use Legatus\Http\Responder\TemplateEngine\TemplateEngineResponderDecorator;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

/**
 * @internal
 */
final class TemplateEngineSimpleResponseDecoratorTest extends TestCase
{
    public function testItRespondsWithJson(): void
    {
        $simpleResponseMock = $this->createMock(Responder::class);
        $templateEngineMock = $this->createMock(TemplateEngineInterface::class);
        $responseMock = $this->createMock(ResponseInterface::class);

        $simpleResponseMock->expects($this->once())
            ->method('json')
            ->with(['data' => 'value'])
            ->willReturn($responseMock)
        ;

        $simpleResponse = new TemplateEngineResponderDecorator($simpleResponseMock, $templateEngineMock);
        $response = $simpleResponse->json(['data' => 'value']);
        $this->assertSame($responseMock, $response);
    }

    public function testItRespondsWithHtml(): void
    {
        $simpleResponseMock = $this->createMock(Responder::class);
        $templateEngineMock = $this->createMock(TemplateEngineInterface::class);
        $responseMock = $this->createMock(ResponseInterface::class);

        $simpleResponseMock->expects($this->once())
            ->method('html')
            ->with('<h1>Text</h1>')
            ->willReturn($responseMock)
        ;

        $simpleResponse = new TemplateEngineResponderDecorator($simpleResponseMock, $templateEngineMock);
        $response = $simpleResponse->html('<h1>Text</h1>');
        $this->assertSame($responseMock, $response);
    }

    public function testItRespondsWithTemplate(): void
    {
        $simpleResponseMock = $this->createMock(Responder::class);
        $templateEngineMock = $this->createMock(TemplateEngineInterface::class);
        $responseMock = $this->createMock(ResponseInterface::class);

        $templateEngineMock->expects($this->once())
            ->method('render')
            ->with('template', ['data' => 'value'])
            ->willReturn('<h1>Text</h1>')
        ;
        $simpleResponseMock->expects($this->once())
            ->method('html')
            ->with('<h1>Text</h1>')
            ->willReturn($responseMock)
        ;

        $simpleResponse = new TemplateEngineResponderDecorator($simpleResponseMock, $templateEngineMock);
        $response = $simpleResponse->template('template', ['data' => 'value']);
        $this->assertSame($responseMock, $response);
    }

    public function testItRespondsWithRedirect(): void
    {
        $simpleResponseMock = $this->createMock(Responder::class);
        $templateEngineMock = $this->createMock(TemplateEngineInterface::class);
        $responseMock = $this->createMock(ResponseInterface::class);

        $simpleResponseMock->expects($this->once())
            ->method('redirect')
            ->with('/home')
            ->willReturn($responseMock)
        ;

        $simpleResponse = new TemplateEngineResponderDecorator($simpleResponseMock, $templateEngineMock);
        $response = $simpleResponse->redirect('/home');
        $this->assertSame($responseMock, $response);
    }

    public function testItRespondsWithBlob(): void
    {
        $simpleResponseMock = $this->createMock(Responder::class);
        $templateEngineMock = $this->createMock(TemplateEngineInterface::class);
        $responseMock = $this->createMock(ResponseInterface::class);

        $simpleResponseMock->expects($this->once())
            ->method('blob')
            ->with('hello.png')
            ->willReturn($responseMock)
        ;

        $simpleResponse = new TemplateEngineResponderDecorator($simpleResponseMock, $templateEngineMock);
        $response = $simpleResponse->blob('hello.png');
        $this->assertSame($responseMock, $response);
    }

    public function testItRespondsWithDownload(): void
    {
        $simpleResponseMock = $this->createMock(Responder::class);
        $templateEngineMock = $this->createMock(TemplateEngineInterface::class);
        $responseMock = $this->createMock(ResponseInterface::class);

        $simpleResponseMock->expects($this->once())
            ->method('download')
            ->with('hello.png')
            ->willReturn($responseMock)
        ;

        $simpleResponse = new TemplateEngineResponderDecorator($simpleResponseMock, $templateEngineMock);
        $response = $simpleResponse->download('hello.png');
        $this->assertSame($responseMock, $response);
    }

    public function testItRespondsWithNormalResponse(): void
    {
        $simpleResponseMock = $this->createMock(Responder::class);
        $templateEngineMock = $this->createMock(TemplateEngineInterface::class);
        $responseMock = $this->createMock(ResponseInterface::class);

        $simpleResponseMock->expects($this->once())
            ->method('response')
            ->with(200)
            ->willReturn($responseMock)
        ;

        $simpleResponse = new TemplateEngineResponderDecorator($simpleResponseMock, $templateEngineMock);
        $response = $simpleResponse->response();
        $this->assertSame($responseMock, $response);
    }
}
