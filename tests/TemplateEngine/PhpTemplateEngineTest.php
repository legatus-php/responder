<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Http\Responder\Tests\TemplateEngine;

use Legatus\Http\Responder\TemplateEngine\PhpTemplateEngine;
use PHPUnit\Framework\TestCase;
use Vfs\FileSystem;
use Vfs\Node\Directory;
use Vfs\Node\File;

/**
 * @internal
 */
final class PhpTemplateEngineTest extends TestCase
{
    public function testItRendersTemplate(): void
    {
        // Setting up virtual FS
        $fs = FileSystem::factory('vfs://');
        $fs->mount();
        $foo = new Directory(['template.php' => new File('Template string')]);
        $fs->get('/')->add('templates', $foo);

        $templating = new PhpTemplateEngine('vfs://templates', 'php');
        $string = $templating->render('template');

        $this->assertSame('Template string', $string);
        $fs->unmount();
    }

    public function testItThrowsErrorWhenTemplateIsNotFound(): void
    {
        // Setting up virtual FS
        $fs = FileSystem::factory('vfs://');
        $fs->mount();
        $foo = new Directory([]);
        $fs->get('/')->add('templates', $foo);

        $templating = new PhpTemplateEngine('vfs://templates', 'php');
        $this->expectException(\RuntimeException::class);
        $templating->render('template');

        $fs->unmount();
    }
}
