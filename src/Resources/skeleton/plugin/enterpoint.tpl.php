<?= "<?php\n"; ?>
/*
Plugin Name: <?= $pluginName . "\n" ?>
<?php if (!empty($pluginDescription)): ?>
Description: <?= $pluginDescription . "\n" ?>
<?php endif; ?>
Author: <?= $pluginAuthorName . "\n" ?>
Version: <?= $pluginVersion . "\n" ?>
*/

Simply::registerPlugin(__DIR__, '<?= $pluginNamespace ?>');
