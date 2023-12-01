<?php

namespace Simply\Maker\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class MakeCommand extends AbstractMakeCommand
{
    protected static $defaultName = 'simply:make:wpclicommand';

    public function configure()
    {
        parent::configure();
        $this->setDescription('Create WCLI class command into a plugin.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        // the function set the root path for fileManager
        $this->askRootPath($io, $this->fileManager);
        $commandName = $io->askQuestion(new Question('Name of your command', 'app:mycommand'));
        $fullClassName = $io->askQuestion(new Question('Command class name', 'Namespace\MyCommand'));
        $classNameDetails = $this->generator->createClassNameDetails($fullClassName);
        $targetPath = $this->fileManager->getRootPath() . '/src/Command/' . $classNameDetails->getClassName() . '.php';
        $fileTemplate = $this->getSkeletonPath('/command/Command.tpl.php');
        $tplYaml = $this->getSkeletonPath('/command/command.tpl.yaml.php');
        $targetYaml = $this->fileManager->getRootPath() . '/config/command/' . strtolower($classNameDetails->getClassName()) . '.yaml';

        $this->generator->generateClass($classNameDetails, $targetPath, $fileTemplate, ['commandName' => $commandName]);
        $this->generator->generateFile($targetYaml, $tplYaml, ['fullClassName' => $classNameDetails->getFullName()]);

        $this->generator->writeChanges();
        $io->success('WP CLI command created');

        return Command::SUCCESS;
    }
}
