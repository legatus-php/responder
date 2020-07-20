<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Support;

/**
 * Interface TemplateEngine.
 */
interface TemplateEngine
{
    /**
     * @param string $template
     * @param array  $context
     *
     * @return string
     */
    public function render(string $template, array $context = []): string;
}
