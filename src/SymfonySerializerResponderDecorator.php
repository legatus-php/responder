<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Http\Responder;

use Psr\Http\Message\ResponseInterface as Response;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class SymfonySerializerResponderDecorator.
 *
 * This decorates simple response to use Symfony Serializer to turn data structures
 * into json.
 */
final class SymfonySerializerResponderDecorator implements Responder
{
    private SerializerInterface $serializer;
    private Responder $simpleResponse;

    /**
     * SymfonySerializerResponderDecorator constructor.
     *
     * @param SerializerInterface $serializer
     * @param Responder           $simpleResponse
     */
    public function __construct(SerializerInterface $serializer, Responder $simpleResponse)
    {
        $this->serializer = $serializer;
        $this->simpleResponse = $simpleResponse;
    }

    /**
     * {@inheritdoc}
     */
    public function json($data, array $context = []): Response
    {
        $string = $this->serializer->serialize($data, 'json', $context);
        $response = $this->response(200);
        $response->getBody()->write($string);

        return $response->withHeader('Content-Type', 'application/json; charset=utf8');
    }

    /**
     * {@inheritdoc}
     */
    public function html(string $html): Response
    {
        return $this->simpleResponse->html($html);
    }

    /**
     * {@inheritdoc}
     */
    public function template(string $template, array $data = []): Response
    {
        return $this->simpleResponse->template($template, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function redirect(string $uri): Response
    {
        return $this->simpleResponse->redirect($uri);
    }

    /**
     * {@inheritdoc}
     */
    public function blob(string $filename): Response
    {
        return $this->simpleResponse->blob($filename);
    }

    /**
     * {@inheritdoc}
     */
    public function download(string $filename, string $downloadName = null): Response
    {
        return $this->simpleResponse->download($filename, $downloadName);
    }

    /**
     * {@inheritdoc}
     */
    public function response(int $status = 200, string $body = ''): Response
    {
        return $this->simpleResponse->response($status);
    }
}
