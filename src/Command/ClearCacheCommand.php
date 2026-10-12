<?php

namespace Simply\Maker\Command;

use Simply\Core\Cache\CacheDirectoryManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'simply:cache:clear', description: 'Clear cache of simply framework.')]
class ClearCacheCommand extends Command
{
    public function configure()
    {
        parent::configure();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Start clearing Simply framework cache...');
        CacheDirectoryManager::deleteCache();
        $io->success('Cache clear.');

        return Command::SUCCESS;
    }
}
