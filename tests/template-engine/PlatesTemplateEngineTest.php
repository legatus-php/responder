<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Support;

use League\Plates\Engine;
use PHPUnit\Framework\TestCase;

/**
 * Class PlatesTemplateEngineTest.
 */
final class PlatesTemplateEngineTest extends TestCase
{
    public function testItRendersTemplate(): void
    {
        $engine = $this->createMock(Engine::class);

        $engine->expects(self::once())
            ->method('getFileExtension')
            ->willReturn('php')
        ;
        $engine->expects(self::once())
            ->method('render')
            ->with('some/template.php', [])
            ->willReturn('template string')
        ;

        $templating = new PlatesTemplateEngine($engine);
        $string = $templating->render('some/template');

        self::assertSame('template string', $string);
    }
}
