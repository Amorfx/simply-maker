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

    public function getPluginDirectory()
    {
        return WP_PLUGIN_DIR;
    }

    public function getThemeDirectory()
    {
        return WP_CONTENT_DIR . '/themes';
    }

    /**
     * Setting the root path to create different files or directories
     * @param string $rootType
     * @param string $directory
     */
    public function setRootPath(string $rootType, string $directory)
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

    /**
     * @param string $templatePath
     * @param array $parameters
     *
     * @return string
     */
    public function parseTemplate(string $templatePath, array $parameters): string
    {
        ob_start();
        extract($parameters, EXTR_SKIP);
        include $templatePath;

        return ob_get_clean();
    }

    public function dumpFile(string $filename, string $content)
    {
        $this->filesystem->dumpFile($filename, $content);
    }

    public function fileExists($fileName = ''): bool
    {
        return file_exists($this->getRootPath() . '/' . $fileName);
    }

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

    public function mkdir($dirs)
    {
        $this->filesystem->mkdir($dirs);
    }
}
