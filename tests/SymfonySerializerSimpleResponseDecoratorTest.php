<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Http\Responder\Tests;

use DateTime;
use Legatus\Http\Responder\Responder;
use Legatus\Http\Responder\SymfonySerializerResponderDecorator;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class BasicResponderTest.
 */
final class SymfonySerializerSimpleResponseDecoratorTest extends TestCase
{
    public function testJson(): void
    {
        $serializerMock = $this->createMock(SerializerInterface::class);
        $simpleResponseMock = $this->createMock(Responder::class);
        $responseMock = $this->createMock(ResponseInterface::class);
        $streamMock = $this->createMock(StreamInterface::class);
        $dateTime = new DateTime();

        $serializerMock->expects($this->once())
            ->method('serialize')
            ->with($dateTime, 'json', [])
            ->willReturn('some-data');
        $simpleResponseMock->expects($this->once())
            ->method('response')
            ->with(200)
            ->willReturn($responseMock);
        $responseMock->expects($this->once())
            ->method('getBody')
            ->willReturn($streamMock);
        $streamMock->expects($this->once())
            ->method('write')
            ->with('some-data');
        $responseMock->expects($this->once())
            ->method('withHeader')
            ->with('Content-Type', 'application/json; charset=utf8')
            ->willReturn($responseMock);
        $simpleResponseMock->expects($this->never())
            ->method('json');

        $simpleResponse = new SymfonySerializerResponderDecorator($serializerMock, $simpleResponseMock);
        $response = $simpleResponse->json($dateTime);
        $this->assertSame($responseMock, $response);
    }

    public function testHtml(): void
    {
        $serializerMock = $this->createMock(SerializerInterface::class);
        $simpleResponseMock = $this->createMock(Responder::class);
        $responseMock = $this->createMock(ResponseInterface::class);

        $simpleResponseMock->expects($this->once())
            ->method('html')
            ->with('<h1>Hello</h1>')
            ->willReturn($responseMock);

        $simpleResponse = new SymfonySerializerResponderDecorator($serializerMock, $simpleResponseMock);
        $response = $simpleResponse->html('<h1>Hello</h1>');
        $this->assertSame($response, $responseMock);
    }

    public function testTemplate(): void
    {
        $serializerMock = $this->createMock(SerializerInterface::class);
        $simpleResponseMock = $this->createMock(Responder::class);
        $responseMock = $this->createMock(ResponseInterface::class);

        $simpleResponseMock->expects($this->once())
            ->method('template')
            ->with('template', ['key' => 'value'])
            ->willReturn($responseMock);

        $simpleResponse = new SymfonySerializerResponderDecorator($serializerMock, $simpleResponseMock);
        $response = $simpleResponse->template('template', ['key' => 'value']);
        $this->assertSame($response, $responseMock);
    }

    public function testRedirect(): void
    {
        $serializerMock = $this->createMock(SerializerInterface::class);
        $simpleResponseMock = $this->createMock(Responder::class);
        $responseMock = $this->createMock(ResponseInterface::class);

        $simpleResponseMock->expects($this->once())
            ->method('redirect')
            ->with('/uri')
            ->willReturn($responseMock);

        $simpleResponse = new SymfonySerializerResponderDecorator($serializerMock, $simpleResponseMock);
        $response = $simpleResponse->redirect('/uri');
        $this->assertSame($response, $responseMock);
    }

    public function testBlob(): void
    {
        $serializerMock = $this->createMock(SerializerInterface::class);
        $simpleResponseMock = $this->createMock(Responder::class);
        $responseMock = $this->createMock(ResponseInterface::class);

        $simpleResponseMock->expects($this->once())
            ->method('blob')
            ->with('/file.png')
            ->willReturn($responseMock);

        $simpleResponse = new SymfonySerializerResponderDecorator($serializerMock, $simpleResponseMock);
        $response = $simpleResponse->blob('/file.png');
        $this->assertSame($response, $responseMock);
    }

    public function testDownload(): void
    {
        $serializerMock = $this->createMock(SerializerInterface::class);
        $simpleResponseMock = $this->createMock(Responder::class);
        $responseMock = $this->createMock(ResponseInterface::class);

        $simpleResponseMock->expects($this->once())
            ->method('download')
            ->with('/file.png', 'download.png')
            ->willReturn($responseMock);

        $simpleResponse = new SymfonySerializerResponderDecorator($serializerMock, $simpleResponseMock);
        $response = $simpleResponse->download('/file.png', 'download.png');
        $this->assertSame($response, $responseMock);
    }

    public function testResponse(): void
    {
        $serializerMock = $this->createMock(SerializerInterface::class);
        $simpleResponseMock = $this->createMock(Responder::class);
        $responseMock = $this->createMock(ResponseInterface::class);

        $simpleResponseMock->expects($this->once())
            ->method('response')
            ->with(400)
            ->willReturn($responseMock);

        $simpleResponse = new SymfonySerializerResponderDecorator($serializerMock, $simpleResponseMock);
        $response = $simpleResponse->response(400);
        $this->assertSame($response, $responseMock);
    }
}
