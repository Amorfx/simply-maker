<?= "<?php\n"; ?>

namespace <?= $namespace ?>;

use Simply\Core\Command\AbstractWordPressCommand;

class <?= $className ?> extends AbstractWordPressCommand
{
    public static string $commandName = '<?= $commandName ?>';

    /**
     * @param array<int, string> $args
     * @param array<string, string> $assoc_args
     */
    public function execute(array $args, array $assoc_args): void
    {
        // ...
    }
}
