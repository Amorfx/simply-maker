taxonomy:
  <?= $taxonomySlug ?>:
    object_type:
<?php foreach ($objectTypes as $name): ?>
      - <?= $name . PHP_EOL ?>
<?php endforeach; ?>
    public: <?= $isPublic . PHP_EOL ?>
    hierarchical: <?= $isHierarchical . PHP_EOL ?>
    labels:
      name: <?= $taxonomyName . PHP_EOL ?>
<?php endif; ?>
