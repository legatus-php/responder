<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Http\Responder\TemplateEngine;

use League\Plates\Engine;

/**
 * An adapter for the Plates Templating Engine.
 */
final class PlatesTemplateEngine implements TemplateEngineInterface
{
    /**
     * @var Engine
     */
    private $plates;

    /**
     * PlatesTemplateEngine constructor.
     *
     * @param Engine $plates
     */
    public function __construct(Engine $plates)
    {
        $this->plates = $plates;
    }

    /**
     * @param string $template
     * @param array  $data
     *
     * @return string
     */
    public function render(string $template, array $data = []): string
    {
        $extension = '.'.$this->plates->getFileExtension();
        if (strpos($template, $extension) === false) {
            $template .= $extension;
        }

        return $this->plates->render($template, $data);
    }
}
