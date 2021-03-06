<?php

declare(strict_types=1);

/*
 * This file is part of the Legatus project organization.
 * (c) Matías Navarro-Carter <contact@mnavarro.dev>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Legatus\Support;

use PHPUnit\Framework\TestCase;
use Vfs\FileSystem;
use Vfs\Node\Directory;
use Vfs\Node\File;

/**
 * @internal
 */
final class PhpTemplateEngineTest extends TestCase
{
    private FileSystem $fs;

    public function setUp(): void
    {
        // Setting up virtual FS
        $this->fs = FileSystem::factory('vfs://');
        $this->fs->mount();
    }

    public function tearDown(): void
    {
        $this->fs->unmount();
    }

    public function testItRendersTemplate(): void
    {
        $foo = new Directory(['template.php' => new File('Template string')]);
        $this->fs->get('/')->add('templates', $foo);

        $templating = new PhpTemplateEngine('vfs://templates', 'php');
        $string = $templating->render('template');

        $this->assertSame('Template string', $string);
    }

    public function testItThrowsErrorWhenTemplateIsNotFound(): void
    {
        $foo = new Directory([]);
        $this->fs->get('/')->add('templates', $foo);

        $templating = new PhpTemplateEngine('vfs://templates', 'php');
        $this->expectException(\RuntimeException::class);
        $templating->render('template');
    }
}
