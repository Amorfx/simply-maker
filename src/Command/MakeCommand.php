<?php

namespace Simply\Maker;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class MakeCommand extends Command {
    protected static $defaultName = 'simply:make:wpclicommand';

    public function configure() {
        $this->setDescription('Create WCLI class command into a plugin.');
    }

    public function execute(InputInterface $input, OutputInterface $output) {
        $io = new SymfonyStyle($input, $output);
        $pluginChoosedPath = '';
        do {
            if (isset($pluginExist) && !$pluginExist) {
                $io->error('The plugin ' . $pluginToAdd . "doesn't exist.");
            }

            $pluginToAdd = $io->askQuestion(new Question('In which plugin do you want to generate this command ?', ''));
            $pluginChoosedPath = WP_PLUGIN_DIR . '/' . $pluginToAdd;
            $pluginExist = is_dir($pluginChoosedPath);
        } while(empty($pluginToAdd) || !$pluginExist);


        // generate name command
        $commandName = $io->askQuestion(new Question('Name of your command', 'app:mycommand'));
        $namespace = $io->askQuestion(new Question('Command class name', 'Namespace\MyCommand'));
        $classNameExploded = explode('\\', $namespace);
        $className = $classNameExploded[array_key_last($classNameExploded)];
        $namespace = str_replace('\\' . $className, '', $namespace);

        // generate file
        $fs = new Filesystem();
        $fileTemplate = __DIR__ . '/../../resources/skeleton/command/Command.tpl.php';
        $tplYaml = __DIR__ . '/../../resources/skeleton/command/command.tpl.yaml.php';
        ob_start();
        include $fileTemplate;
        $content = ob_get_clean();
        ob_start();
        $fullClassName = $namespace . '\\' . $className;
        include $tplYaml;
        $contentYaml = ob_get_clean();
        $fs->dumpFile($pluginChoosedPath . '/src/Command/' . $className . '.php', $content);
        $fs->dumpFile($pluginChoosedPath . '/config/command/' . strtolower($className) . '.yaml', $contentYaml);

        return Command::SUCCESS;
    }
}
