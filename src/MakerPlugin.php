<?php

namespace Simply\Maker;

use Simply\Core\Contract\PluginInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class MakerPlugin implements PluginInterface {
    public function build(ContainerBuilder $container): void {
        $fileLocator = new FileLocator(dirname(__DIR__) . '/config');
        $loaderYaml = new YamlFileLoader($container, $fileLocator);
        $loaderYaml->load('maker.yml');

        $container->registerForAutoconfiguration(Command::class)
            ->addTag('simply.commands');
    }
}
