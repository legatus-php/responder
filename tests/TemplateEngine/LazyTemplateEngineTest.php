<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Http\Responder\Tests\TemplateEngine;

use Legatus\Http\Responder\TemplateEngine\LazyTemplateEngine;
use Legatus\Http\Responder\TemplateEngine\TemplateEngineInterface;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class LazyTemplateEngineTest extends TestCase
{
    public function testRender(): void
    {
        $templateEngineMock = $this->createMock(TemplateEngineInterface::class);
        $callable = static function () use ($templateEngineMock) {
            return $templateEngineMock;
        };

        $templateEngineMock->expects($this->once())
            ->method('render')
            ->with('template', [])
            ->willReturn('Template string')
        ;

        $templateEngine = new LazyTemplateEngine($callable);

        $string = $templateEngine->render('template');
        $this->assertSame('Template string', $string);
    }
}
