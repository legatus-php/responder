<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Http\Responder\Tests\TemplateEngine;

use Legatus\Http\Responder\TemplateEngine\TwigTemplateEngine;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

/**
 * @internal
 */
final class TwigTemplateEngineTest extends TestCase
{
    public function testItRendersTemplate(): void
    {
        $twig = $this->createMock(Environment::class);

        $twig->expects($this->once())
            ->method('render')
            ->with('some/template.html.twig', [])
            ->willReturn('template string')
        ;

        $templating = new TwigTemplateEngine($twig);
        $string = $templating->render('some/template');

        $this->assertSame('template string', $string);
    }
}
