<?= "<?php\n"; ?>
/*
Plugin Name: <?= $pluginName . "\n" ?>
<?php if (! empty($pluginDescription)): ?>
Description: <?= $pluginDescription . "\n" ?>
<?php endif; ?>
Author: <?= $pluginAuthorName . "\n" ?>
Version: <?= $pluginVersion . "\n" ?>
*/

/*
 * Classes in src/ are registered as services and must be autoloadable.
 * Add this line to the "autoload.psr-4" section of the project composer.json, then run composer dump-autoload:
 * "<?= str_replace('\\', '\\\\', $pluginNamespace) ?>\\": "web/content/plugins/<?= $pluginDirectorySlug ?>/src/"
 */
Simply::registerPlugin(__DIR__, '<?= $pluginNamespace ?>');
