<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Http\Responder\TemplateEngine;

use Legatus\Http\Responder\Responder;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Class TemplateEngineResponderDecorator.
 */
final class TemplateEngineResponderDecorator implements Responder
{
    private Responder $simpleResponse;
    private TemplateEngineInterface $templateEngine;

    /**
     * TemplateEngineResponderDecorator constructor.
     *
     * @param Responder               $simpleResponse
     * @param TemplateEngineInterface $templateEngine
     */
    public function __construct(Responder $simpleResponse, TemplateEngineInterface $templateEngine)
    {
        $this->simpleResponse = $simpleResponse;
        $this->templateEngine = $templateEngine;
    }

    /**
     * @return TemplateEngineInterface
     */
    public function getTemplateEngine(): TemplateEngineInterface
    {
        return $this->templateEngine;
    }

    /**
     * {@inheritdoc}
     */
    public function json($data, array $context = []): Response
    {
        return $this->simpleResponse->json($data, $context);
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
        $html = $this->templateEngine->render($template, $data);

        return $this->simpleResponse->html($html);
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
