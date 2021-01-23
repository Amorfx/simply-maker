post_type:
  <?= $postTypeSlug ?>:
    public: <?= $isPublic . PHP_EOL ?>
    labels:
      name: <?= $postTypeName . PHP_EOL ?>
<?php if (!empty($supports)): ?>
    supports:
<?php foreach ($supports as $name): ?>
      - <?= $name . PHP_EOL ?>
<?php endforeach; ?>
<?php endif; ?>
