<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Http;

use Psr\Http\Message\ResponseInterface as Response;

/**
 * Interface Responder.
 */
interface Responder
{
    /**
     * Creates a Json response.
     *
     * @param mixed $data
     * @param array $context
     *
     * @return Response
     */
    public function json($data, array $context = []): Response;

    /**
     * Creates an Html response.
     *
     * @param string $html
     *
     * @return Response
     */
    public function html(string $html): Response;

    /**
     * Creates an Html template response.
     *
     * @param string $template
     * @param array  $data
     *
     * @return Response
     */
    public function template(string $template, array $data = []): Response;

    /**
     * Creates a redirect response.
     *
     * @param string $uri
     *
     * @return Response
     */
    public function redirect(string $uri): Response;

    /**
     * Creates a blob response.
     *
     * @param string $filename
     *
     * @return Response
     */
    public function blob(string $filename): Response;

    /**
     * Creates a download response.
     *
     * @param string      $filename
     * @param string|null $downloadName
     *
     * @return Response
     */
    public function download(string $filename, string $downloadName = null): Response;

    /**
     * Creates a raw response.
     *
     * @param int    $status
     * @param string $body
     *
     * @return Response
     */
    public function raw(int $status = 200, string $body = ''): Response;
}
