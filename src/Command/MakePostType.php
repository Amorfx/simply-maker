<?php

namespace Simply\Maker\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\String\Slugger\AsciiSlugger;

class MakePostType extends AbstractMakeCommand
{
    protected static $defaultName = 'simply:make:post-type';

    public function configure()
    {
        parent::configure();
        $this->setDescription('Create a post type in WordPress.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $slugger = new AsciiSlugger();

        $this->askRootPath($io, $this->fileManager);

        $postTypeName = $io->askQuestion(new Question('Name of your post type ?', 'Post Type'));
        $postTypeSlug = strtolower($slugger->slug($postTypeName));

        $isPublic = $io->askQuestion(new ConfirmationQuestion('Is your post type public ?', true));
        $supportsTitle = $io->askQuestion(new ConfirmationQuestion('Is your post type support title ?', true));
        $supportsThumbnail = $io->askQuestion(new ConfirmationQuestion('Is your post type support thumbnail ?', true));
        $supportsEditor = $io->askQuestion(new ConfirmationQuestion('Is your post type support editor ?', false));

        $tplConfig = $this->getSkeletonPath('/posttype/posttype.tpl.yaml.php');
        $targetPath = $this->fileManager->getRootPath() . '/config/post-type/' . $postTypeSlug . '.yaml';
        $supports = [];
        if ($supportsTitle) {
            $supports[] = 'title';
        }
        if ($supportsThumbnail) {
            $supports[] = 'thumbnail';
        }
        if ($supportsEditor) {
            $supports[] = 'editor';
        }

        $tplVariables = [
            'postTypeSlug' => $postTypeSlug,
            'isPublic' => $isPublic ? 'true' : 'false',
            'postTypeName' => $postTypeName,
            'supports' => $supports,
        ];

        $this->generator->generateFile($targetPath, $tplConfig, $tplVariables);
        $this->generator->writeChanges();

        $io->success('Post type ' . $postTypeName . ' has been created.');

        return Command::SUCCESS;
    }
}
