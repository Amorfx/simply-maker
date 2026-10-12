<?php

namespace Simply\Maker\Test;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Simply\Maker\Command\ClearCacheCommand;
use Simply\Maker\Command\MakeCommand;
use Simply\Maker\Command\MakeHook;
use Simply\Maker\Command\MakePlugin;
use Simply\Maker\Command\MakePostType;
use Simply\Maker\Command\MakeTheme;
use Symfony\Component\Console\Command\Command;

class CommandNamesTest extends TestCase
{
    /**
     * @return array<string, array{class-string<Command>, string, string}>
     */
    public static function commands(): array
    {
        return [
            'cache:clear' => [ClearCacheCommand::class, 'simply:cache:clear', 'Clear cache of simply framework.'],
            'make:wpclicommand' => [MakeCommand::class, 'simply:make:wpclicommand', 'Create WCLI class command into a plugin.'],
            'make:hook' => [MakeHook::class, 'simply:make:hook', 'Create Hookable class.'],
            'make:plugin' => [MakePlugin::class, 'simply:make:plugin', 'Create plugin with the boilerplate for Simply.'],
            'make:post-type' => [MakePostType::class, 'simply:make:post-type', 'Create a post type in WordPress.'],
            'make:theme' => [MakeTheme::class, 'simply:make:theme', 'Create theme with the boilerplate for Simply.'],
        ];
    }

    /**
     * @param class-string<Command> $class
     */
    #[DataProvider('commands')]
    public function testCommandIsNamedAndDescribed(string $class, string $name, string $description): void
    {
        $command = new $class();

        $this->assertSame($name, $command->getName());
        $this->assertSame($description, $command->getDescription());
    }
}
