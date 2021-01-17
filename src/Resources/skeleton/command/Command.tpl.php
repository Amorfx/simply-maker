<?= "<?php\n"; ?>

namespace <?= $namespace ?>;

use Simply\Core\Command\AbstractWordPressCommand;

class <?= $className ?> extends AbstractWordPressCommand {
    static $commandName = '<?= $commandName ?>';
    public function execute($args, $assoc_args) {
        // ...
    }
}
