<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Support;

use RuntimeException;

/**
 * Class PhpTemplateEngine.
 */
final class PhpTemplateEngine implements TemplateEngine
{
    private string $templatePath;
    private string $extension;

    /**
     * PhpTemplateEngine constructor.
     *
     * @param string $templatePath
     * @param string $extension
     */
    public function __construct(string $templatePath, string $extension = '.php')
    {
        $this->templatePath = $templatePath;
        $this->extension = '.'.trim($extension, '.');
    }

    /**
     * {@inheritdoc}
     */
    public function render(string $template, array $data = []): string
    {
        if (strpos($template, $this->extension) === false) {
            $template .= $this->extension;
        }
        $filename = sprintf('%s%s%s', $this->templatePath, \DIRECTORY_SEPARATOR, $template);
        if (!file_exists($filename)) {
            throw new RuntimeException(sprintf('Template file "%s" not found at "%s"', $template, $this->templatePath));
        }
        extract($data, EXTR_OVERWRITE);
        ob_start();
        include $filename;

        return ob_get_clean();
    }
}
