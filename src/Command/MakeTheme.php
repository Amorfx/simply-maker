<?php

namespace Simply\Maker\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\String\Slugger\AsciiSlugger;

/**
 * Command to create default theme for Simply Framework
 */
class MakeTheme extends AbstractMakeCommand
{
    protected static $defaultName = 'simply:make:theme';

    public function configure()
    {
        parent::configure();
        $this->setDescription('Create theme with the boilerplate for Simply.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $slugger = new AsciiSlugger();
        $themeName = $io->askQuestion(new Question('Name of your theme ?'));
        $themeNamespace = $io->askQuestion(new Question('Theme namespace ?', 'MyTheme'));

        $themeDirectorySlug = strtolower($slugger->slug($themeName));
        $themeDirectorySlug = $io->askQuestion(new Question('Theme directory slug (lowercase and seperate with -) ?', $themeDirectorySlug));
        $themeDirectorySlug = strtolower(str_replace(' ', '-', $themeDirectorySlug));

        $themeDescription = $io->askQuestion(new Question('Theme description ?', ''));

        $themeAuthorName = $io->askQuestion(new Question('Theme author name ?', 'John Doe'));
        $themeVersion = $io->askQuestion(new Question('Theme Version ?', '1.0'));

        $tplFunctions = $this->getSkeletonPath('/theme/functions.tpl.php');
        $tplIndex = $this->getSkeletonPath('/theme/index.tpl.php');
        $tplStyle = $this->getSkeletonPath('/theme/style.tpl.css');
        // Create directory of theme + index + style
        $themePath = $this->fileManager->getThemeDirectory() . '/' . $themeDirectorySlug;
        $this->fileManager->dumpFile($themePath . '/index.php', $this->fileManager->parseTemplate($tplIndex, []));
        $this->fileManager->dumpFile($themePath . '/functions.php', $this->fileManager->parseTemplate($tplFunctions, [
            'themeNamespace' => $themeNamespace,
        ]));
        $this->fileManager->dumpFile($themePath . '/style.css', $this->fileManager->parseTemplate($tplStyle, [
            'themeName' => $themeName,
            'themeAuthorName' => $themeAuthorName,
            'themeDescription' => $themeDescription,
            'themeVersion' => $themeVersion,
        ]));
        // Create theme default directories
        $this->fileManager->mkdir($themePath . '/config');
        $this->fileManager->mkdir($themePath . '/src');
        $this->fileManager->mkdir($themePath . '/views');

        $io->success('Theme created');

        return Command::SUCCESS;
    }
}
