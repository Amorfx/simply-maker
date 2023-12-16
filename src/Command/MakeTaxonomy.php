<?php

namespace Simply\Maker\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\String\Slugger\AsciiSlugger;

class MakeTaxonomy extends AbstractMakeCommand
{
    protected static $defaultName = 'simply:make:taxonomy';

    public function configure()
    {
        parent::configure();
        $this->setDescription('Create a taxonomy in WordPress.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $slugger = new AsciiSlugger();

        $this->askRootPath($io, $this->fileManager);

        $taxonomyName = $io->askQuestion(new Question('Name of your Taxonomy ?', 'My Taxonomy'));
        $taxonomySlug = strtolower($slugger->slug($taxonomyName));

        // Object type to attach to
        $objectTypes = [];
        $objectTypes[] = $io->askQuestion(new Question('Object type slug to attach to ?', 'post'));
        do {
            $objectType = $io->askQuestion(new Question('Do you want to attach it to another object type ?', 'no'));
            if ($objectType !== 'no') {
                $objectTypes[] = $objectType;
            }
        } while ($objectType !== 'no');


        $isPublic = $io->askQuestion(new ConfirmationQuestion('Is your post type public ?', true));
        $isHierarchical = $io->askQuestion(new ConfirmationQuestion('Is it hierarchical ?', true));

        $tplConfig = $this->getSkeletonPath('/taxonomy/taxonomy.tpl.yaml.php');
        $targetPath = $this->fileManager->getRootPath() . '/config/taxonomy/' . $taxonomySlug . '.yaml';

        $tplVariables = [
            'taxonomySlug' => $taxonomySlug,
            'objectTypes' => $objectTypes,
            'isPublic' => $isPublic ? 'true' : 'false',
            'isHierarchical' => $isHierarchical ? 'true' : 'false',
            'taxonomyName' => $taxonomyName,
        ];

        $this->generator->generateFile($targetPath, $tplConfig, $tplVariables);
        $this->generator->writeChanges();

        $io->success('Taxonomy ' . $taxonomyName . ' has been created.');

        return Command::SUCCESS;
    }
}
