<?= "<?php\n"; ?>

namespace <?= $namespace ?>;

use Simply\Core\Contract\HookableInterface;

class <?= $className ?> implements HookableInterface {
    public function register() {
        // add_action(...);
    }
}
