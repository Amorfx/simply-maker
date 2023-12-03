<?php

namespace Simply\Maker;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class FileManager
{
    private string $rootPath;
    private Filesystem $filesystem;

    public function __construct()
    {
        $this->rootPath = '';
        $this->filesystem = new Filesystem();
    }

    public function getPluginDirectory(): string
    {
        return WP_PLUGIN_DIR; // @phpstan-ignore-line
    }

    public function getThemeDirectory(): string
    {
        return WP_CONTENT_DIR . '/themes'; // @phpstan-ignore-line
    }

    /**
     * Setting the root path to create different files or directories
     * @param string $rootType
     * @param string $directory
     */
    public function setRootPath(string $rootType, string $directory): void
    {
        switch ($rootType) {
            case 'plugin':
                $this->rootPath = $this->getPluginDirectory() . '/' . $directory;

                break;
            case 'theme':
                $this->rootPath = $this->getThemeDirectory() . '/' . $directory;

                break;
            default:
                throw new \RuntimeException('Can not have root path of type ' . $rootType);
        }
    }

    public function getRootPath(): string
    {
        return $this->rootPath;
    }

    public function parseTemplate(string $templatePath, array $parameters): string|false // @phpstan-ignore-line
    {
        ob_start();
        extract($parameters, EXTR_SKIP);
        include $templatePath;

        return ob_get_clean();
    }

    public function dumpFile(string $filename, string $content): void
    {
        $this->filesystem->dumpFile($filename, $content);
    }

    public function fileExists(string $fileName = ''): bool
    {
        return file_exists($this->getRootPath() . '/' . $fileName);
    }

    /**
     * @param string $directory
     * @return array<string>
     */
    public function getAvailableDirectories(string $directory): array
    {
        $finder = new Finder();
        $finder->in($directory)->depth('== 0')->directories();
        $returnDirectories = [];
        if ($finder->hasResults()) {
            foreach ($finder as $dir) {
                $returnDirectories[] = $dir->getRelativePathname();
            }
        }

        return $returnDirectories;
    }

    /**
     * @param string|iterable $dirs
     * @return void
     */
    public function mkdir(string|iterable $dirs): void //@phpstan-ignore-line
    {
        $this->filesystem->mkdir($dirs);
    }
}
