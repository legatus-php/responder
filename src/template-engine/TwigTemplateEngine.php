<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Support;

use Twig\Environment;
use Twig\Error as TwigError;

/**
 * An adapter for the Twig templating engine.
 */
final class TwigTemplateEngine implements TemplateEngine
{
    private const EXTENSION = '.html.twig';
    private Environment $twig;

    /**
     * TwigTemplateEngine constructor.
     *
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param string $template
     * @param array  $data
     *
     * @throws TwigError\LoaderError
     * @throws TwigError\RuntimeError
     * @throws TwigError\SyntaxError
     *
     * @return string
     */
    public function render(string $template, array $data = []): string
    {
        if (strpos($template, self::EXTENSION) === false) {
            $template .= self::EXTENSION;
        }

        return $this->twig->render($template, $data);
    }
}
