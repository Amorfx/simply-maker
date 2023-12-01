<?php

namespace Simply\Maker\Command;

use Simply\Maker\FileManager;
use Simply\Maker\Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

abstract class AbstractMakeCommand extends Command
{
    protected Generator $generator;
    protected FileManager $fileManager;

    public function configure()
    {
        parent::configure();
        $this->fileManager = new FileManager();
        $this->generator = new Generator($this->fileManager);
    }

    public function getSkeletonPath($path)
    {
        return __DIR__ . '/../Resources/skeleton' . $path;
    }

    public function askRootPath(SymfonyStyle $io, FileManager $fileManager): array
    {
        $rootType = $io->choice('Do you want to create it in plugin or theme ?', [
            'plugin' => 'plugin',
            'theme' => 'theme',
        ], 'plugin');
        do {
            if (isset($directoryExist) && ! $directoryExist) {
                $io->error('The ' . $rootType . ' ' . $directoryToAdd . " doesn't exist.");
            }

            if (! isset($allChoices)) {
                $directoryChoice = $rootType === 'plugin' ?
                    $this->fileManager->getPluginDirectory() :
                    $this->fileManager->getThemeDirectory();
                $allChoices = $this->fileManager->getAvailableDirectories($directoryChoice);
            }

            $directoryPathQuestion = new Question('In which ' . $rootType . ' do you want to generate this command ?', '');
            $directoryPathQuestion->setAutocompleterValues($allChoices);
            $directoryToAdd = $io->askQuestion($directoryPathQuestion);
            $fileManager->setRootPath($rootType, $directoryToAdd);
            $directoryExist = $fileManager->fileExists();
        } while (empty($directoryToAdd) || ! $directoryExist);

        return ['rootType' => $rootType, 'directory' => $directoryToAdd];
    }
}
