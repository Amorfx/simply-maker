<?php

namespace Simply\Maker\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\String\Slugger\AsciiSlugger;

class MakeHook extends AbstractMakeCommand {
    protected static $defaultName = 'simply:make:hook';

    public function configure() {
        parent::configure();
        $this->setDescription('Create Hookable class.');
    }

    public function execute(InputInterface $input, OutputInterface $output) {
        $io = new SymfonyStyle($input, $output);
        $slugger = new AsciiSlugger();
        // the function set the root path for fileManager
        $this->askRootPath($io, $this->fileManager);
        $tplClass = $this->getSkeletonPath('/hook/Hook.tpl.php');
        $tplConfig = $this->getSkeletonPath('/hook/hook.tpl.yaml.php');

        $fullClassName = $io->askQuestion(new Question('Name of your Hook (with namespace) ?'));
        $classNameDetails = $this->generator->createClassNameDetails($fullClassName);

        $targetPathClass = $this->fileManager->getRootPath() . '/src/Hook/' . $classNameDetails->getClassName() . '.php';
        $targetPathConfig = $this->fileManager->getRootPath() . '/config/hook/' . strtolower($slugger->slug($classNameDetails->getClassName())) . '.yaml';

        $this->generator->generateClass($classNameDetails, $targetPathClass, $tplClass);
        $this->generator->generateFile($targetPathConfig, $tplConfig, ['hookName' => $classNameDetails->getFullName()]);
        $this->generator->writeChanges();

        $io->success('Hook class created');
        return Command::SUCCESS;
    }
}
