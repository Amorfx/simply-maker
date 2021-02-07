<?php

namespace Simply\Maker\Command;

use Simply\Core\Cache\CacheDirectoryManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ClearCacheCommand extends Command {
    protected static $defaultName = 'simply:cache:clear';

    public function configure() {
        parent::configure();
        $this->setDescription('Clear cache of simply framework.');
    }

    public function execute(InputInterface $input, OutputInterface $output) {
        $io = new SymfonyStyle($input, $output);
        $io->title('Start clearing Simply framework cache...');
        CacheDirectoryManager::deleteCache();
        $io->success('Cache clear.');
        return Command::SUCCESS;
    }
}
