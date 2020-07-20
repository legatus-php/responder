<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Support;

use Closure;
use RuntimeException;

/**
 * Class LazyTemplateEngine.
 *
 * Internally cached, lazy template engine implementation.
 */
final class LazyTemplateEngine implements TemplateEngine
{
    private Closure $factory;
    private ?TemplateEngine $templateEngine;

    /**
     * LazyTemplateEngine constructor.
     *
     * @param Closure $factory
     */
    public function __construct(Closure $factory)
    {
        $this->factory = $factory;
        $this->templateEngine = null;
    }

    public function render(string $template, array $data = []): string
    {
        return $this->templateEngine()->render($template, $data);
    }

    /**
     * @return TemplateEngine
     */
    private function templateEngine(): TemplateEngine
    {
        if ($this->templateEngine === null) {
            $templateEngine = ($this->factory)();
            if (!$templateEngine instanceof TemplateEngine) {
                throw new RuntimeException(sprintf('The TemplateEngine callable must return an instance of %s', TemplateEngine::class));
            }
            $this->templateEngine = $templateEngine;
        }

        return $this->templateEngine;
    }
}
