<?php

namespace Simply\Maker\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\String\Slugger\AsciiSlugger;

/**
 * Command to create default plugin for Simply Framework
 */
class MakePlugin extends AbstractMakeCommand
{
    protected static $defaultName = 'simply:make:plugin';

    public function configure()
    {
        parent::configure();
        $this->setDescription('Create plugin with the boilerplate for Simply.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $slugger = new AsciiSlugger();
        $pluginName = $io->askQuestion(new Question('Name of your plugin ?'));
        $pluginNamespace = $io->askQuestion(new Question('Plugin namespace ?', 'MyPlugin'));

        $pluginDirectorySlug = strtolower($slugger->slug($pluginName));
        $pluginDirectorySlug = $io->askQuestion(new Question('Plugin directory slug (lowercase and seperate with -) ?', $pluginDirectorySlug));
        $pluginDirectorySlug = strtolower(str_replace(' ', '-', $pluginDirectorySlug));

        $pluginDescription = $io->askQuestion(new Question('Plugin description ?', ''));

        $pluginAuthorName = $io->askQuestion(new Question('Plugin author name ?', 'John Doe'));
        $pluginVersion = $io->askQuestion(new Question('Plugin Version ?', '1.0'));

        $tplEnterpoint = $this->getSkeletonPath('/plugin/enterpoint.tpl.php');
        // Create directory of plugin + enterpoint
        $pluginPath = $this->fileManager->getPluginDirectory() . '/' . $pluginDirectorySlug;
        $this->fileManager->dumpFile(
            $pluginPath . '/' . $pluginDirectorySlug . '.php',
            (string) $this->fileManager->parseTemplate($tplEnterpoint, [
                'pluginName' => $pluginName,
                'pluginDescription' => $pluginDescription,
                'pluginAuthorName' => $pluginAuthorName,
                'pluginVersion' => $pluginVersion,
                'pluginNamespace' => $pluginNamespace,
            ])
        );
        // Create plugin default directory and src
        $this->fileManager->mkdir($pluginPath . '/config');
        $this->fileManager->mkdir($pluginPath . '/src');

        $io->success('Plugin created');

        return Command::SUCCESS;
    }
}
