<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Http\Responder;

use JsonSerializable;
use Mimey\MimeTypes;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\StreamFactoryInterface;
use RuntimeException;
use stdClass;

/**
 * Class BasicResponder.
 */
final class BasicResponder implements Responder
{
    /**
     * @var ResponseFactoryInterface
     */
    private $response;
    /**
     * @var StreamFactoryInterface
     */
    private $stream;
    /**
     * @var MimeTypes
     */
    private $mimeTypes;

    /**
     * HttpFactoryResponder constructor.
     *
     * @param ResponseFactoryInterface $response
     * @param StreamFactoryInterface   $stream
     * @param MimeTypes                $mimeTypes
     */
    public function __construct(ResponseFactoryInterface $response, StreamFactoryInterface $stream, MimeTypes $mimeTypes)
    {
        $this->response = $response;
        $this->stream = $stream;
        $this->mimeTypes = $mimeTypes;
    }

    /**
     * {@inheritdoc}
     */
    public function json($data, array $context = []): Response
    {
        if (is_array($data) || $data instanceof JsonSerializable || $data instanceof stdClass) {
            return $this->response->createResponse(200)
                ->withBody($this->stream->createStream(json_encode($data)))
                ->withHeader('Content-Type', 'application/json; charset=utf8');
        }
        throw new RuntimeException('$data must be either an array or an instance of JsonSerializable');
    }

    /**
     * {@inheritdoc}
     */
    public function html(string $html): Response
    {
        return $this->response->createResponse(200)
            ->withBody($this->stream->createStream($html))
            ->withHeader('Content-Type', 'text/html; charset=utf8');
    }

    /**
     * {@inheritdoc}
     */
    public function template(string $template, array $data = []): Response
    {
        throw new RuntimeException(sprintf('TemplatingEngine is not implemented in %s', __CLASS__));
    }

    /**
     * {@inheritdoc}
     */
    public function redirect(string $uri): Response
    {
        return $this->response->createResponse(302)
            ->withHeader('Location', $uri);
    }

    /**
     * {@inheritdoc}
     */
    public function blob(string $filename): Response
    {
        $stream = $this->stream->createStreamFromFile($filename, 'rb');
        $mime = $this->mimeTypes->getMimeType(pathinfo($filename, PATHINFO_EXTENSION));
        if ($mime === null) {
            $mime = 'application/octet-stream';
        }

        return $this->response->createResponse(200)
            ->withHeader('Content-Type', $mime)
            ->withHeader('Content-Length', (string) $stream->getSize())
            ->withBody($stream);
    }

    /**
     * {@inheritdoc}
     */
    public function download(string $filename, string $downloadName = null): Response
    {
        $downloadName = $downloadName ?? (string) pathinfo($filename, PATHINFO_BASENAME);

        return $this->blob($filename)
            ->withHeader('Content-Disposition', sprintf('attachment; filename="%s"', $downloadName));
    }

    /**
     * {@inheritdoc}
     */
    public function response(int $status = 200, string $body = ''): Response
    {
        return $this->response->createResponse($status)
            ->withBody($this->stream->createStream($body));
    }
}
