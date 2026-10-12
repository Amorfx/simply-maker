<?php

namespace Simply\Maker\Test;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Simply\Core\Command\AbstractWordPressCommand;
use Simply\Core\Contract\HookableInterface;
use Simply\Maker\FileManager;

/**
 * The generated code must load against the current simply-framework
 */
class SkeletonTest extends TestCase
{
    /**
     * @return array<string, array{string, array<string, string>, class-string}>
     */
    public static function skeletons(): array
    {
        return [
            'wp-cli command' => ['/command/Command.tpl.php', ['commandName' => 'app:generated'], AbstractWordPressCommand::class],
            'hook' => ['/hook/Hook.tpl.php', [], HookableInterface::class],
        ];
    }

    /**
     * @param array<string, string> $variables
     * @param class-string $parent
     */
    #[DataProvider('skeletons')]
    public function testGeneratedClassLoads(string $template, array $variables, string $parent): void
    {
        $className = 'Generated' . md5($template);
        $code = (new FileManager())->parseTemplate(
            dirname(__DIR__) . '/src/Resources/skeleton' . $template,
            $variables + ['namespace' => 'Simply\Maker\Test\Generated', 'className' => $className]
        );
        $file = sys_get_temp_dir() . '/' . $className . '.php';
        file_put_contents($file, $code);

        require $file;

        $this->assertTrue(is_subclass_of('Simply\Maker\Test\Generated\\' . $className, $parent));
    }
}
