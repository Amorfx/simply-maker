<?php

namespace Simply\Maker;

use Symfony\Component\Filesystem\Filesystem;

class FileManager {
    private string $rootPath;
    private Filesystem $filesystem;

    public function __construct() {
        $this->rootPath = '';
        $this->filesystem = new Filesystem();
    }

    /**
     * Setting the root path to create different files or directories
     * @param string $rootType
     * @param string $directory
     */
    public function setRootPath(string $rootType, string $directory) {
        switch ($rootType) {
            case 'plugin':
                $this->rootPath = WP_PLUGIN_DIR . '/' . $directory;
                break;
            case 'theme':
                $this->rootPath = WP_CONTENT_DIR . '/themes/' . $directory;
                break;
            default:
                throw new \RuntimeException('Can not have root path of type ' . $rootType);
        }
    }

    public function getRootPath(): string {
        return $this->rootPath;
    }

    /**
     * @param string $templatePath
     * @param array $parameters
     *
     * @return string
     */
    public function parseTemplate(string $templatePath, array $parameters): string {
        ob_start();
        extract($parameters, EXTR_SKIP);
        include $templatePath;
        return ob_get_clean();
    }

    public function dumpFile(string $filename, string $content) {
        $this->filesystem->dumpFile($filename, $content);
    }

    public function fileExists($fileName = ''): bool {
        return file_exists($this->getRootPath() . '/' . $fileName);
    }
}
