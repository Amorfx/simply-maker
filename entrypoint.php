<?php

use Simply\Maker\MakerPlugin;

$autoload = __DIR__ . '/vendor/autoload.php';

if (file_exists($autoload)) {
    require $autoload;
}


Simply::registerSimplyPlugin(new MakerPlugin());
