<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Http\Responder\Tests;

use JsonSerializable;
use Legatus\Http\Responder\BasicResponder;
use Mimey\MimeTypes;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

final class BasicResponderTest extends TestCase implements JsonSerializable
{
    public function testItRespondsWithJsonFromArray(): void
    {
        $streamStub = $this->createStub(StreamInterface::class);
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseFactoryMock = $this->createMock(ResponseFactoryInterface::class);
        $streamFactoryMock = $this->createMock(StreamFactoryInterface::class);
        $mimeTypesMock = $this->createMock(MimeTypes::class);

        $responseFactoryMock->expects($this->once())
            ->method('createResponse')
            ->with(200, null)
            ->willReturn($responseMock);
        $streamFactoryMock->expects($this->once())
            ->method('createStream')
            ->with('{"hello":"name"}')
            ->willReturn($streamStub);
        $responseMock->expects($this->once())
            ->method('withBody')
            ->with($streamStub)
            ->willReturn($responseMock);
        $responseMock->expects($this->once())
            ->method('withHeader')
            ->with('Content-Type', 'application/json; charset=utf8')
            ->willReturn($responseMock);

        $simpleResponse = new BasicResponder($responseFactoryMock, $streamFactoryMock, $mimeTypesMock);
        $simpleResponse->json(['hello' => 'name']);
    }

    public function jsonSerialize(): array
    {
        return ['hello' => 'name'];
    }

    public function testItRespondsWithJsonFromJsonSerializableObject(): void
    {
        $streamStub = $this->createStub(StreamInterface::class);
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseFactoryMock = $this->createMock(ResponseFactoryInterface::class);
        $streamFactoryMock = $this->createMock(StreamFactoryInterface::class);
        $mimeTypesMock = $this->createMock(MimeTypes::class);

        $responseFactoryMock->expects($this->once())
            ->method('createResponse')
            ->with(200, null)
            ->willReturn($responseMock);
        $streamFactoryMock->expects($this->once())
            ->method('createStream')
            ->with('{"hello":"name"}')
            ->willReturn($streamStub);
        $responseMock->expects($this->once())
            ->method('withBody')
            ->with($streamStub)
            ->willReturn($responseMock);
        $responseMock->expects($this->once())
            ->method('withHeader')
            ->with('Content-Type', 'application/json; charset=utf8')
            ->willReturn($responseMock);

        $simpleResponse = new BasicResponder($responseFactoryMock, $streamFactoryMock, $mimeTypesMock);
        $simpleResponse->json($this);
    }

    public function testItRespondsWithJsonFromStdClassInstance(): void
    {
        $responseFactoryMock = $this->createMock(ResponseFactoryInterface::class);
        $streamFactoryMock = $this->createMock(StreamFactoryInterface::class);
        $mimeTypesMock = $this->createMock(MimeTypes::class);

        $object = new class() {
        };
        $simpleResponse = new BasicResponder($responseFactoryMock, $streamFactoryMock, $mimeTypesMock);
        $this->expectException(RuntimeException::class);
        $simpleResponse->json($object);
    }

    public function testItRespondsWithHtml(): void
    {
        $streamStub = $this->createStub(StreamInterface::class);
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseFactoryMock = $this->createMock(ResponseFactoryInterface::class);
        $streamFactoryMock = $this->createMock(StreamFactoryInterface::class);
        $mimeTypesMock = $this->createMock(MimeTypes::class);

        $responseFactoryMock->expects($this->once())
            ->method('createResponse')
            ->with(200, null)
            ->willReturn($responseMock);
        $streamFactoryMock->expects($this->once())
            ->method('createStream')
            ->with('<h1>Hello world!</h1>')
            ->willReturn($streamStub);
        $responseMock->expects($this->once())
            ->method('withBody')
            ->with($streamStub)
            ->willReturn($responseMock);
        $responseMock->expects($this->once())
            ->method('withHeader')
            ->with('Content-Type', 'text/html; charset=utf8')
            ->willReturn($responseMock);
        $simpleResponse = new BasicResponder($responseFactoryMock, $streamFactoryMock, $mimeTypesMock);
        $simpleResponse->html('<h1>Hello world!</h1>');
    }

    public function testItThrowsErrorOnTemplate(): void
    {
        $responseFactoryMock = $this->createMock(ResponseFactoryInterface::class);
        $streamFactoryMock = $this->createMock(StreamFactoryInterface::class);
        $mimeTypesMock = $this->createMock(MimeTypes::class);

        $simpleResponse = new BasicResponder($responseFactoryMock, $streamFactoryMock, $mimeTypesMock);

        $this->expectException(RuntimeException::class);
        $simpleResponse->template('template', ['key' => 'value']);
    }

    public function testItRespondsWithRedirect(): void
    {
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseFactoryMock = $this->createMock(ResponseFactoryInterface::class);
        $streamFactoryMock = $this->createMock(StreamFactoryInterface::class);
        $mimeTypesMock = $this->createMock(MimeTypes::class);

        $responseFactoryMock->expects($this->once())
            ->method('createResponse')
            ->with(302, null)
            ->willReturn($responseMock);
        $responseMock->expects($this->once())
            ->method('withHeader')
            ->with('Location', '/uri')
            ->willReturn($responseMock);

        $simpleResponse = new BasicResponder($responseFactoryMock, $streamFactoryMock, $mimeTypesMock);
        $simpleResponse->redirect('/uri');
    }

    public function testItRespondsWithDownload(): void
    {
        $streamMock = $this->createMock(StreamInterface::class);
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseFactoryMock = $this->createMock(ResponseFactoryInterface::class);
        $streamFactoryMock = $this->createMock(StreamFactoryInterface::class);
        $mimeTypesMock = $this->createMock(MimeTypes::class);

        $streamFactoryMock->expects($this->once())
            ->method('createStreamFromFile')
            ->with('/foo/bar.csv', 'rb')
            ->willReturn($streamMock);
        $mimeTypesMock->expects($this->once())
            ->method('getMimeType')
            ->with('csv')
            ->willReturn('text/csv');
        $responseFactoryMock->expects($this->once())
            ->method('createResponse')
            ->with(200, null)
            ->willReturn($responseMock);
        $streamMock->expects($this->once())
            ->method('getSize')
            ->willReturn(300);
        $responseMock->expects($this->exactly(3))
            ->method('WithHeader')
            ->withConsecutive(
                ['Content-Type', 'text/csv'],
                ['Content-Length', '300'],
                ['Content-Disposition', 'attachment; filename="bar.csv"']
            )
            ->willReturn($responseMock);
        $responseMock->expects($this->once())
            ->method('withBody')
            ->with($streamMock)
            ->willReturn($responseMock);

        $simpleResponse = new BasicResponder($responseFactoryMock, $streamFactoryMock, $mimeTypesMock);
        $simpleResponse->download('/foo/bar.csv');
    }

    public function testItRespondsWithDownloadOnNullMime(): void
    {
        $streamMock = $this->createMock(StreamInterface::class);
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseFactoryMock = $this->createMock(ResponseFactoryInterface::class);
        $streamFactoryMock = $this->createMock(StreamFactoryInterface::class);
        $mimeTypesMock = $this->createMock(MimeTypes::class);

        $streamFactoryMock->expects($this->once())
            ->method('createStreamFromFile')
            ->with('/foo/bar', 'rb')
            ->willReturn($streamMock);
        $mimeTypesMock->expects($this->once())
            ->method('getMimeType')
            ->with('')
            ->willReturn(null);
        $responseFactoryMock->expects($this->once())
            ->method('createResponse')
            ->with(200, null)
            ->willReturn($responseMock);
        $streamMock->expects($this->once())
            ->method('getSize')
            ->willReturn(300);
        $responseMock->expects($this->exactly(3))
            ->method('WithHeader')
            ->withConsecutive(
                ['Content-Type', 'application/octet-stream'],
                ['Content-Length', '300'],
                ['Content-Disposition', 'attachment; filename="bar"']
            )
            ->willReturn($responseMock);
        $responseMock->expects($this->once())
            ->method('withBody')
            ->with($streamMock)
            ->willReturn($responseMock);

        $simpleResponse = new BasicResponder($responseFactoryMock, $streamFactoryMock, $mimeTypesMock);
        $simpleResponse->download('/foo/bar');
    }

    public function testItRespondsWithCleanResponse(): void
    {
        $streamStub = $this->createStub(StreamInterface::class);
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseFactoryMock = $this->createMock(ResponseFactoryInterface::class);
        $streamFactoryMock = $this->createMock(StreamFactoryInterface::class);
        $mimeTypesMock = $this->createMock(MimeTypes::class);

        $responseFactoryMock->expects($this->once())
            ->method('createResponse')
            ->with(200, null)
            ->willReturn($responseMock);
        $streamFactoryMock->expects($this->once())
            ->method('createStream')
            ->with('')
            ->willReturn($streamStub);
        $responseMock->expects($this->once())
            ->method('withBody')
            ->with($streamStub)
            ->willReturn($responseMock);

        $simpleResponse = new BasicResponder($responseFactoryMock, $streamFactoryMock, $mimeTypesMock);
        $simpleResponse->response(200);
    }
}
